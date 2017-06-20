<?php

namespace src;

use MysqliDb;
use src\models\Ad;
use src\models\City;
use src\models\Country;

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

    public function run(Country $country = null, City $city = null)
    {
        echo 'Hello, World!' . PHP_EOL;
        $zp = City::findOne(45);
        $this->parseAdsFromCity($zp);
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
    
    public function parseAdsFromCountry(Country $country)
    {
        $cities = $country->getCities();
        foreach ($cities as $city) {
            $this->parseAdsFromCity($city);
        }
    }
    
    public function parseAdsFromCity(City $city)
    {
        $url = $city->url;
        $lastAd = Ad::findLastAdInCity($city);
        $urlList = Parser::getAdUrls($url, $lastAd->url);
        print_r($urlList);
        /*
        foreach ($urlList as $url) {
            $ad = Parser::getAd($url);
        }
        */
    }
}
