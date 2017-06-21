<?php

namespace src;

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

    public function run($country = null, $city = null)
    {
        if (!empty($country)) {
            $country = Country::findByName(iconv('CP1251', 'UTF-8', $country));
            if ($country != null) {
                $this->parseAdsFromCountry($country);
            } else {
                echo 'INCORRECT COUNTRY: ' . $country . PHP_EOL;
            }
        }
        if (!empty($city)) {
            $city = City::findByName(iconv('CP1251', 'UTF-8', $city));
            if ($city != null) {
                $this->parseAdsFromCity($city);
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
        $url = $city->url;
        $lastAd = Ad::findLastAdInCity($city);
        $urlList = Parser::getAdUrls($url, $lastAd->url);
        foreach ($urlList as $url) {
            $parsedData = Parser::getAdDataByUrl($url);
            if (empty($parsedData)) continue;
            //AD Model
            $adId = $this->saveAdModel($city->id, $parsedData);
            if ($adId === false) {
                echo 'AD SAVE ERROR!' . PHP_EOL;
                continue;
            }
            //Phone Models
            if (!empty($parsedData['phones'])) {
                $this->saveAdPhones($parsedData['phones'], $adId);
            }
            
            //Image Models
            if (!empty($parsedData['images'])) {
                $this->saveAdImages($parsedData['images'], $adId, $city->url);
            }
            echo 'SAVED' . PHP_EOL;
        }
    }

    /**
     * @param $city_id
     * @param $parsedData
     * @return false|integer
     */
    private function saveAdModel($city_id, $parsedData)
    {
        $adModel = new Ad();
        $adModel->city_id = $city_id;
        $adModel->url = $parsedData['url'];
        $adModel->title = isset($parsedData['title']) ? $parsedData['title'] : null;
        $adModel->date = isset($parsedData['date']) ? $parsedData['date'] : null;
        $adModel->gender = isset($parsedData['gender']) ? $parsedData['gender'] : null;
        $adModel->age = isset($parsedData['age']) ? $parsedData['age'] : null;
        $adModel->weight = isset($parsedData['weight']) ? $parsedData['weight'] : null;
        $adModel->height = isset($parsedData['height']) ? $parsedData['height'] : null;
        $adModel->text = isset($parsedData['text']) ? $parsedData['text'] : null;
        $adModel->parsed = time();

        return $adModel->insert();
    }

    /**
     * @param $phones array
     * @param $adId integer
     */
    private function saveAdPhones($phones, $adId)
    {
        foreach ($phones as $phone) {
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
        }
    }

    /**
     * @param $images array
     * @param $adId integer
     * @param $cityUrl string
     */
    private function saveAdImages($images, $adId, $cityUrl)
    {
        foreach ($images as $image) {
            $imageModel = new Image();
            $imageModel->ad_id = $adId;
            $imageModel->url = $cityUrl . substr($image, 2);
            try {
                $fileName = array_pop(explode('/', $image));
                $fullName = 'images' . DIRECTORY_SEPARATOR . $fileName;
                file_put_contents($fullName, file_get_contents($imageModel->url));
                $imageModel->filename = $fileName;
            } catch (\Exception $ex) {
                $imageModel->filename = null;
                echo $ex->getMessage() . PHP_EOL;
            }
            if ($imageModel->insert() === false) {
                echo 'IMAGE SAVE ERROR!' . PHP_EOL;
            }
        }
    }
}
