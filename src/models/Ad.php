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

    static public function findCountByCity(City $city)
    {
        $cnt = Application::$db->rawQueryValue("SELECT COUNT(*) FROM " . self::$tableName . " WHERE `city_id`=" . $city->id);

        return $cnt[0];
    }

    static public function findUnparsedCountByCity(City $city)
    {
        $cnt = Application::$db->rawQueryValue("SELECT COUNT(*) FROM " . self::$tableName . " WHERE `city_id`=" . $city->id . " AND `parsed` IS NULL");

        return $cnt[0];
    }

    public function getImages($offset = null, $limit = null)
    {
        $result = [];
        Application::$db->where('ad_id', $this->id);
        $images = Image::findAll($offset, $limit);
        foreach ($images as $image) {
            $result[] = Image::createObjectFromArray($image);
        }

        return $result;
    }

    public function getCity()
    {
        if (empty($this->city_id)) {
            return null;
        }
        Application::$db->where('id', $this->city_id);
        $result = Application::$db->getOne(City::$tableName);

        return City::createObjectFromArray($result);
    }
}
