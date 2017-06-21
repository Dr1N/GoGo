<?php

namespace src\models;

use src\Application;
use src\base\Model;

class Ad extends Model
{
    const FEMALE = 0;
    const MALE = 1;

    static public $tableName = 'ads';

    public $id;

    public $city_id;

    public $url;

    public $title;

    public $date;

    public $gender;

    public $age;

    public $weight;

    public $height;

    public $text;

    public $parsed;

    static public function findByUrl($url)
    {
        Application::$db->where('url', $url);
        $ad = Application::$db->getOne(self::$tableName);

        return self::createObjectFromArray($ad);
    }

    static public function findLastAdInCity(City $city)
    {
        Application::$db->where('city_id', $city->id);
        $maxDate = Application::$db->rawQueryValue('SELECT MAX(`date`) FROM `ads`');

        Application::$db->where('city_id', $city->id);
        Application::$db->where('date', $maxDate[0]);
        $lastAd = Application::$db->getOne(self::$tableName);

        return self::createObjectFromArray($lastAd);
    }
    
    static public function findUnparsedAd($cityId)
    {
        $result = [];
        Application::$db->where('city_id', $cityId);
        Application::$db->where('parsed', null, 'IS');
        $ads = Application::$db->get(self::$tableName);
        foreach ($ads as $ad) {
            $result[] = self::createObjectFromArray($ad);
        }

        return $result;
    }
}
