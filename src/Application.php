<?php

namespace src;

use DiDom\Document;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use League\CLImate\CLImate;
use MysqliDb;
use src\base\Model;
use src\models\Ad;
use src\models\AdPhoneRelation;
use src\models\City;
use src\models\Country;
use src\models\Image;
use src\models\Phone;

class Application
{
    /**
     * @var MysqliDb
     */
    public static $db;

    public function __construct()
    {
        try {
            self::$db = new MysqliDb ([
                'host' => 'localhost',
                'username' => DB_USER,
                'password' => DB_PASSWORD,
                'db'=> DB_NAME,
                'port' => 3306,
                'charset' => 'utf8'
            ]);
        } catch (\Exception $ex) {
            Application::log('ERROR: ' . $ex->getMessage(), 'app', true);
        }
    }

    static public function log($message, $category = 'app', $console = false)
    {
        if ($console) {
            echo $message . PHP_EOL;
        }
        //TODO
        $fullLogPath = getcwd() . DIRECTORY_SEPARATOR . "parser" . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . "$category.log";
        file_put_contents($fullLogPath, date('d.m.Y H:i:s') . "\t" . $message . PHP_EOL, FILE_APPEND);
    }

    public function run($country = null, $city = null)
    {
        if ($country == null && $city == null) {
            self::parseAllCountries();
            return;
        }

        if (!empty($country)) {
            $countryModel = Country::findByName($country);
            if ($countryModel != null) {
                self::parseAdsFromCountry($countryModel);
            } else {
                echo 'INCORRECT COUNTRY: ' . $country . PHP_EOL;
            }
            return;
        }
        if (!empty($city)) {
            $cityModel = City::findByName($city);
            if ($cityModel != null) {
                self::parseAdsFromCity($cityModel);
            } else {
                echo 'INCORRECT CITY: ' . $city . PHP_EOL;
            }
            return;
        }
    }

    public function clear()
    {
        Ad::truncate();
        Phone::truncate();
        Image::truncate();
        AdPhoneRelation::truncate();
    }

    public function parseCities()
    {
        Application::log('### Cities Parsing ###', 'app', true);
        City::truncate();
        $countries = Country::findAll();
        foreach ($countries as $country) {
            /* @var $country Country*/
            Application::log("\t{$country->name}:", 'app', true);
            $cities = Parser::getCities($country);
            if (!empty($cities)) {
                foreach ($cities as $name => $url) {
                    $city = new City();
                    $city->country_id = $country->id;
                    $city->name = $name;
                    $city->url = $url;
                    $city->insert();
                    Application::log("\t\t{$city->name}", 'app', true);
                }
            }
        }
        Application::log('### Parsing Done! ###', 'app', true);
    }

    static private function parseAllCountries()
    {
        $countries = Country::findAll();
        foreach ($countries as $country) {
            self::parseAdsFromCountry($country);
        }
    }

    static private function parseAdsFromCountry(Country $country)
    {
        Application::log("###### Begin Country ({$country->name}) ######", 'process', true);
        if ($country->is_enabled == 0) {
            Application::log("{$country->id} {$country->name} disabled", 'app', true);
            return;
        }
        $cities = $country->getCities();
        foreach ($cities as $city) {
            /* @var $city City */
            if ($country->start_city_id !== null && $city->id < $country->start_city_id) {
                Application::log("{$city->id} {$city->name} missed", 'app', true);
                continue;
            }
            self::parseAdsFromCity($city);
        }
        Application::log("###### End Country ({$country->name}) ######", 'process', true);
    }

    static private function parseAdsFromCity(City $city)
    {
        Application::log("### Begin City ({$city->name}) ###", 'process', true);

        if ($city->is_enabled == 0) {
            Application::log("{$city->id} {$city->name} disabled", 'app', true);
            return;
        }

        //Urls
        $unparsedAdsCnt = Ad::findUnparsedCountByCity($city);
        Application::log('ALL UNPARSED: ' . $unparsedAdsCnt, 'app', true);
        if ($unparsedAdsCnt == 0 && $city->parse_urls == 1) {
            $urls = Parser::getAdUrls($city);
            self::saveAdUrls($urls, $city);
        }

        //Ads
        $offset = 0;
        $limit = DB_READ_PACKET;

        while (true) {
            $query = "SELECT * FROM " . Ad::$tableName . " WHERE `city_id`={$city->id} AND `parsed` IS NULL LIMIT $offset, $limit";
            /* @var $unparsedAds Ad[] */
            $unparsedAds = Ad::rawQuery($query);
            if (empty($unparsedAds)) {
                break;
            }
            Application::log('UNPARSED PART URLS: ' . count($unparsedAds), 'app', true);
            $progress = (new CLImate())->progress()->total(count($unparsedAds));
            //Request
            $requests = function ($total) use ($unparsedAds, $progress) {
                for ($i = 0; $i < $total; $i++) {
                    $progress->advance();
                    yield new Request('GET', $unparsedAds[$i]->url);
                }
            };
            //Pool
            $client = new Client(['http_errors' => false]);
            $pool = new Pool($client, $requests(count($unparsedAds)), [
                'concurrency' => GZ_CONCURRENT,
                'fulfilled' => function (Response $response, $index) use ($unparsedAds, $city) {
                    try {
                        if ($response->getStatusCode() == 200) {
                            $document = new Document($response->getBody()->getContents());
                            $parsedData = Parser::getAdDataFromDocument($document, $unparsedAds[$index]->url);
                            if (!self::save($parsedData, $unparsedAds[$index], $city)) {
                                Application::log('SAVE ERROR: ' . $unparsedAds[$index]->url, 'app');
                            }
                        } else {
                            $unparsedAds[$index]->delete();
                            Application::log('DELETE: ' . $unparsedAds[$index]->url, 'app');
                        }
                    } catch (\Exception $ex) {
                        Application::log($ex->getMessage(), 'app');
                    }
                },
                'rejected' => function ($reason, $index) use ($unparsedAds) {
                    $unparsedAds[$index]->delete();
                    Application::log('DELETE: ' . $unparsedAds[$index]->url, 'app');
                    Application::log('Rejected: ' . $index . ' ' . $reason, 'app');
                },
            ]);
            try {
                $promise = $pool->promise();
                $promise->wait();
            } catch (\Exception $ex) {
                Application::log($ex->getMessage(), 'app');
            }
        }

        //Clean empty
        Application::$db->rawQuery('DELETE FROM `ads` WHERE `parsed` IS NULL AND `city_id`=' . $city->id);
        Application::log('CLEANED', 'app', true);
        Application::log('DONE', 'app', true);
        Application::log("### End City ({$city->name}) ###", 'process', true);
    }

    static private function save($parsedData, Ad $ad, City $city)
    {
        if (empty($parsedData)) {
            return false;
        }

        //AD Model
        $adId = self::saveAdModel($ad, $parsedData);
        if ($adId === false) {
            Application::log('AD SAVE ERROR!', 'app', true);
            return false;
        }

        //Phone Models
        if (!empty($parsedData['phones'])) {
            self::saveAdPhones($parsedData['phones'], $ad->id);
        }

        //Image Models
        if (!empty($parsedData['images'])) {
            self::saveAdImages($parsedData['images'], $ad->id, $city);
        }

        return true;
    }

    static private function saveAdModel(Ad $model, $parsedData)
    {
        $model->title = isset($parsedData['title']) ? $parsedData['title'] : null;
        $model->date = isset($parsedData['date']) ? $parsedData['date'] : null;
        $model->gender = isset($parsedData['gender']) ? $parsedData['gender'] : null;
        $model->age = isset($parsedData['age']) ? $parsedData['age'] : null;
        $model->weight = isset($parsedData['weight']) ? $parsedData['weight'] : null;
        $model->height = isset($parsedData['height']) ? $parsedData['height'] : null;
        $model->text = isset($parsedData['text']) ? $parsedData['text'] : null;
        $model->parsed = time();

        return $model->save();
    }

    /**
     * @param $phones array
     * @param $adId integer
     */
    static private function saveAdPhones($phones, $adId)
    {
        foreach ($phones as $phone) {
            $existsPhone = Phone::findByPhone($phone);
            if ($existsPhone == null) {
                //Create Phone And Relation
                $phoneModel = new Phone();
                $phoneModel->phone = trim($phone);
                $phoneId = $phoneModel->insert();
                if ($phoneId !== false) {
                    $adPhoneRelation = new AdPhoneRelation();
                    $adPhoneRelation->ad_id = $adId;
                    $adPhoneRelation->phone_id = $phoneId;
                    if ($adPhoneRelation->insert() === false) {
                        Application::log('AD PHONE RELATION SAVE ERROR!', 'app', true);
                    }
                } else {
                    Application::log('PHONE SAVE ERROR!', 'app', true);
                }
            } else {
                //Add Relation
                $adPhoneRelation = new AdPhoneRelation();
                $adPhoneRelation->ad_id = $adId;
                $adPhoneRelation->phone_id = $existsPhone->id;
                if ($adPhoneRelation->insert() === false) {
                    Application::log('AD PHONE RELATION SAVE ERROR!', 'app', true);
                }
            }
        }
    }

    /**
     * @param $images
     * @param $adId
     * @param $city City
     */
    static private function saveAdImages($images, $adId, $city)
    {
        $dirName = 'c' . $city->id;
        if ($city->save_image && !is_dir('images' . DIRECTORY_SEPARATOR . $dirName)) {
            if (!mkdir('images' . DIRECTORY_SEPARATOR . $dirName)) {
                Application::log("Can't create directory [$dirName]", 'app', true);
                return;
            }
        }
        foreach ($images as $image) {
            //Model
            $imageModel = new Image();
            $imageModel->ad_id = $adId;
            $imageModel->url = $city->url . substr($image, 2);
            $imageModel->filename = null;
            //File
            if ($city->save_image) {
                try {
                    $tmp = explode('/', $image);
                    $fileName = array_pop($tmp);
                    $fullName = 'images' . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $fileName;
                    if (!file_exists($fullName)) {
                        @$fileContent = file_get_contents($imageModel->url);
                        if ($fileContent != false) {
                            file_put_contents($fullName, $fileContent);
                            $imageModel->filename = $fileName;
                        }
                    }
                } catch (\Exception $ex) {
                    Application::log($ex->getMessage(), 'app');
                }
            }
            if ($imageModel->insert() === false) {
                Application::log('IMAGE SAVE ERROR!', 'app');
            }
        }
    }

    static private function saveAdUrls($urls, City $city)
    {
        Application::log('SAVE URLS', 'app', true);

        $climate = new CLImate();
        if (count($urls) == 0) {
            $climate->progress(100)->current(100);
            return;
        }

        //TODO optimization
        //New Urls
        $allUrls = Model::rawQueryValue('SELECT `url` FROM `ads` WHERE `city_id`=' . $city->id);
        if (!empty($allUrls)) {
            $urlsForSave = array_diff($urls, $allUrls);
        } else {
            $urlsForSave = $urls;
        }

        Application::log('URL FOR SAVE: ' . count($urlsForSave), 'app', true);

        $cityAdCnt = Ad::findCountAdByCity($city);
        if ($cityAdCnt != 0 && count($urls) === count($urlsForSave)) {
            Application::log('NEED MORE DEPTH (!)', 'app', true);
        }

        //Save
        $progress = $climate->progress(count($urlsForSave));
        $cnt = 0;
        foreach ($urlsForSave as $url) {
            $progress->advance();
            $ad = new Ad();
            $ad->city_id = $city->id;
            $ad->url = $url;
            if ($ad->insert()) {
                $cnt++;
            }
        }

        Application::log('TOTAL SAVED: ' . $cnt, 'app', true);
    }
}
