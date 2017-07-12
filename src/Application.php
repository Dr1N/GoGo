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
            echo 'ERROR: ' . $ex->getMessage() . PHP_EOL;
        }
    }

    static public function log($message, $category = 'app')
    {
        echo $message . PHP_EOL;
        @file_put_contents("logs/$category.log", date('d.m.Y H:i:s') . "\t" . $message . PHP_EOL, FILE_APPEND);
    }

    public function run($country = null, $city = null)
    {
        $os = php_uname();
        if (!empty($country)) {
            if (stripos($os, 'windows') !== false) {
                $countryModel = Country::findByName(iconv('CP1251', 'UTF-8', $country));
            } else {
                $countryModel = Country::findByName($country);
            }
            if ($countryModel != null) {
                self::parseAdsFromCountry($countryModel);
            } else {
                echo 'INCORRECT COUNTRY: ' . $country . PHP_EOL;
            }
        }
        if (!empty($city)) {
            if (stripos($os, 'windows') !== false) {
                $cityModel = City::findByName(iconv('CP1251', 'UTF-8', $city));
            } else {
                $cityModel = City::findByName($city);
            }
            if ($cityModel != null) {
                self::parseAdsFromCity($cityModel);
            } else {
                echo 'INCORRECT CITY: ' . $city . PHP_EOL;
            }
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
        echo '### Cities Parsing ###' . PHP_EOL;
        City::truncate();
        $countries = Country::findAll();
        foreach ($countries as $country) {
            /* @var $country Country*/
            echo "\t{$country->name}:" . PHP_EOL;
            $cities = Parser::getCities($country);
            if (!empty($cities)) {
                foreach ($cities as $name => $url) {
                    $city = new City();
                    $city->country_id = $country->id;
                    $city->name = $name;
                    $city->url = $url;
                    $city->insert();
                    echo "\t\t{$city->name}" . PHP_EOL;
                }
            }
        }
        echo 'Parsing Done!' . PHP_EOL;
    }

    static private function parseAdsFromCountry(Country $country)
    {
        Application::log("### Begin Country ({$country->name}) ###", 'process');
        $cities = $country->getCities();
        foreach ($cities as $city) {
            self::parseAdsFromCity($city);
        }
        Application::log("### End Country ({$country->name}) ###", 'process');
    }

    static private function parseAdsFromCity(City $city)
    {
        Application::log("### Begin City ({$city->name}) ###", 'process');

        //Urls
        if (PARSE_URL) {
            $urls = Parser::getAdUrls($city);
            self::saveAdUrls($urls, $city);
        }

        //Ads
        $offset = 0;
        $limit = DB_READ_PACKET;

        while (true) {
            $query = "SELECT * FROM " . Ad::$tableName . " WHERE `parsed` IS NULL LIMIT $offset, $limit";
            $unparsedAds = Ad::rawQuery($query);
            if (empty($unparsedAds)) {
                break;
            }
            echo 'UNPARSED URLS: ' . count($unparsedAds) . PHP_EOL;
            $progress = (new CLImate())->progress()->total(count($unparsedAds));
            //Request
            $requests = function ($total) use ($unparsedAds, $progress) {
                for ($i = 0; $i < $total; $i++) {
                    $progress->advance();
                    yield new Request('GET', $unparsedAds[$i]->url);
                }
            };
            //Pool
            $client = new Client(['http_errors' => false]); //TODO
            $pool = new Pool($client, $requests(count($unparsedAds)), [
                'concurrency' => GZ_CONCURRENT,
                'fulfilled' => function (Response $response, $index) use ($unparsedAds, $city) {
                    try {
                        echo $response->getStatusCode() . PHP_EOL;
                        $document = new Document($response->getBody()->getContents());
                        print_r($document); die(); //TODO
                        $parsedData = Parser::getAdDataFromDocument($document, $unparsedAds[$index]->url);
                        if (!self::save($parsedData, $unparsedAds[$index], $city)) {
                            Application::log('SAVE ERROR: ' . $unparsedAds[$index]->url, 'app');
                        }
                    } catch (\Exception $ex) {
                        Application::log($ex->getMessage(), 'app');
                    }
                },
                'rejected' => function ($reason, $index) {
                    Application::log($index . ' Fail!'  . $reason, 'app');
                    echo $index . ' Fail!'  . $reason . PHP_EOL;
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
        echo 'CLEANED' . PHP_EOL;
        echo PHP_EOL . 'DONE' . PHP_EOL;

        Application::log("### End City ({$city->name}) ###", 'process');
    }

    static private function save($parsedData, Ad $ad, City $city)
    {
        if (empty($parsedData)) {
            return false;
        }

        //AD Model
        $adId = self::saveAdModel($ad, $parsedData);
        if ($adId === false) {
            echo 'AD SAVE ERROR!' . PHP_EOL;
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
                        echo 'AD PHONE RELATION SAVE ERROR!' . PHP_EOL;
                    }
                } else {
                    echo 'PHONE SAVE ERROR!' . PHP_EOL;
                }
            } else {
                //Add Relation
                $adPhoneRelation = new AdPhoneRelation();
                $adPhoneRelation->ad_id = $adId;
                $adPhoneRelation->phone_id = $existsPhone->id;
                if ($adPhoneRelation->insert() === false) {
                    echo 'AD PHONE RELATION SAVE ERROR!' . PHP_EOL;
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
        if (!is_dir('images' . DIRECTORY_SEPARATOR . $dirName)) {
            if (!mkdir('images' . DIRECTORY_SEPARATOR . $dirName)) {
                echo "Can't create directory [$dirName]" . PHP_EOL;
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
            if (SAVE_IMAGE) {
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
                    Application::log($ex->getMessage(), 'images');
                }
            }
            if ($imageModel->insert() === false) {
                echo 'IMAGE SAVE ERROR!' . PHP_EOL;
            }
        }
    }

    static private function saveAdUrls($urls, City $city)
    {
        echo 'SAVE URLS' . PHP_EOL;

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

        echo 'URL FOR SAVE: ' . count($urlsForSave) . PHP_EOL;

        $cityAdCnt = Ad::findCountByCity($city);
        if ($cityAdCnt != 0 && count($urls) === count($urlsForSave)) {
            Application::log('NEED MORE DEPTH (!)', 'parser');
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

        echo 'TOTAL SAVED: ' . $cnt . PHP_EOL;
    }
}
