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
        $this->parseUrlsForCity($city);

        //Ads
        $unparsedAds = Ad::findUnparsedAd($city->id);
        foreach ($unparsedAds as $unparsedAd) {
            /* @var $unparsedAd Ad*/
            $parsedData = Parser::getAdDataByUrl($unparsedAd->url);
            if (empty($parsedData)) continue;
            //AD Model
            $adId = $this->saveAdModel($unparsedAd, $parsedData);
            if ($adId === false) {
                echo 'AD SAVE ERROR!' . PHP_EOL;
                continue;
            }
            //Phone Models
            if (!empty($parsedData['phones'])) {
                $this->saveAdPhones($parsedData['phones'], $unparsedAd->id);
            }
            
            //Image Models
            if (!empty($parsedData['images'])) {
                $this->saveAdImages($parsedData['images'], $unparsedAd->id, $city->url);
            }
            echo 'SAVED' . PHP_EOL;
        }
        echo 'DONE' . PHP_EOL;
    }

    private function saveAdModel(Ad $model, $parsedData)
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
    private function saveAdPhones($phones, $adId)
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
     * @param $images array
     * @param $adId integer
     * @param $cityUrl string
     */
    private function saveAdImages($images, $adId, $cityUrl)
    {
        foreach ($images as $image) {
            //Model
            $imageModel = new Image();
            $imageModel->ad_id = $adId;
            $imageModel->url = $cityUrl . substr($image, 2);
            $imageModel->filename = null;
            //File
            if (SAVE_IMAGE) {
                try {
                    $fileName = array_pop(explode('/', $image));
                    $fullName = 'images' . DIRECTORY_SEPARATOR . $fileName;
                    if (!file_exists($fullName)) {
                        file_put_contents($fullName, file_get_contents($imageModel->url));
                    }
                    $imageModel->filename = $fileName;
                } catch (\Exception $ex) {
                    echo $ex->getMessage() . PHP_EOL;
                }
            }
            if ($imageModel->insert() === false) {
                echo 'IMAGE SAVE ERROR!' . PHP_EOL;
            }
        }
    }

    /**
     * @param City $city
     */
    private function parseUrlsForCity(City $city)
    {
        $url = $city->url;
        $lastAd = Ad::findLastAdInCity($city);
        if ($lastAd != null) {
            echo 'LAST AD: ' . $lastAd->url . PHP_EOL;
        }
        $urlList = Parser::getAdUrls($url, $lastAd->url);
        $this->saveUrls($urlList, $city->id);
    }

    private function saveUrls($urls, $cityId)
    {
        foreach ($urls as $url) {
            try {
                $tmp = Ad::findByUrl($url);
                if (!empty($tmp)) {
                    echo 'URL: ' . $url . ' EXISTS' . PHP_EOL;
                    continue;
                }
                $ad = new Ad();
                $ad->city_id = $cityId;
                $ad->url = $url;
                if ($ad->insert() === false) {
                    echo 'URL SAVE ERROR' . PHP_EOL;
                }
            } catch (\Exception $ex) {
                continue;
            }
        }
    }
}
