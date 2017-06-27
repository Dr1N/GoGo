<?php

namespace src;

use League\CLImate\CLImate;
use MysqliDb;
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

    public static $climate;

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
            self::$climate = new CLImate();
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
        if (!empty($country)) {
            $countryModel = Country::findByName(iconv('CP1251', 'UTF-8', $country));
            if ($countryModel != null) {
                $this->parseAdsFromCountry($countryModel);
            } else {
                echo 'INCORRECT COUNTRY: ' . $country . PHP_EOL;
            }
        }
        if (!empty($city)) {
            $cityModel = City::findByName(iconv('CP1251', 'UTF-8', $city));
            if ($cityModel != null) {
                $this->parseAdsFromCity($cityModel);
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
    
    private function parseAdsFromCountry(Country $country)
    {
        $cities = $country->getCities();
        foreach ($cities as $city) {
            $this->parseAdsFromCity($city);
        }
    }

    private function parseAdsFromCity(City $city)
    {
        //Urls
        $urls = $this->parseAdUrlsForCity($city);
        $this->saveAdUrls($urls, $city->id);

        //Ads
        $offset = 0;
        $limit = DB_READ_PACKET;
        $cnt = 0;
        while (true) {
            $query = "SELECT * FROM " . Ad::$tableName . " WHERE `parsed` IS NULL LIMIT $offset, $limit";
            Application::log($query);
            $unparsedAds = Ad::rawQuery($query);
            Application::log(count($unparsedAds));
            Application::log(empty($unparsedAds) ? 'Empty' : 'Not Empty');
            if (empty($unparsedAds)) {
                Application::log('Exit!');
                break;
            }
            foreach ($unparsedAds as $unparsedAd) {
                if (self::save($unparsedAd, $city)) {
                    echo 'SAVED' . PHP_EOL;
                    $cnt++;
                }
            }
            $offset += $limit;
        }

        echo PHP_EOL . 'DONE ( ' . $cnt . ' )' . PHP_EOL;
    }

    static private function save(Ad $ad, City $city)
    {
        $parsedData = Parser::getAdDataByUrl($ad->url);
        if (empty($parsedData)) return false;
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
                    $fileName = array_pop(explode('/', $image));
                    $fullName = 'images' . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $fileName;
                    if (!file_exists($fullName)) {
                        @$fileContent = file_get_contents($imageModel->url);
                        if ($fileContent != false) {
                            file_put_contents($fullName, $fileContent);
                            $imageModel->filename = $fileName;
                        }
                    }
                } catch (\Exception $ex) {
                    echo $ex->getMessage() . PHP_EOL;
                }
            }
            if ($imageModel->insert() === false) {
                echo 'IMAGE SAVE ERROR!' . PHP_EOL;
            }
        }
    }

    private function parseAdUrlsForCity(City $city)
    {
        $url = $city->url;
        $lastAd = Ad::findLastAdInCity($city);
        if ($lastAd != null) {
            echo 'LAST AD: ' . $lastAd->url . PHP_EOL;
        }
        $urlList = Parser::getAdUrls($url, $lastAd->url);

        return $urlList;
    }

    private function saveAdUrls($urls, $cityId)
    {
        $climate = new CLImate();
        $climate->clear();
        if (count($urls) == 0) {
            $progress = $climate->progress(100);
            $progress->current(100);
            return;
        }

        $progress = $climate->progress(count($urls));
        $cnt = 0;
        $keys = ['city_id', 'url'];
        $data = [];
        foreach ($urls as $url) {
            $cnt++;
            $data[] = [$cityId, $url];
            if ($cnt % DB_WRITE_PACKET == 0) {
                if (!Ad::multiInsert($data, $keys)) {
                    echo 'insert failed: ' . Application::$db->getLastError() . PHP_EOL;
                }
                $data = [];
            }
            $progress->advance();
        }
        if (!empty($data)) {
            if (!Ad::multiInsert($data, $keys)) {
                echo 'insert failed: ' . Application::$db->getLastError() . PHP_EOL;
            }
        }
        $progress->current(count($urls));
    }
}
