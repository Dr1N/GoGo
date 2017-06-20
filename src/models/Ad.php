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

    /**
     * Find all ads
     * @param null $limit
     * @return Ad[]
     */
    static public function findAll($limit = null)
    {
        $ads = [];
        $table = self::getTableRecords($limit);
        foreach ($table as $item) {
            $ads[] = self::createAdFromResult($item);
        }
        return $ads;
    }

    static public function findLastAdInCity(City $city)
    {
        Application::$db->where('city_id', $city->id);
        Application::$db->orderBy('id', 'DESC');
        $lastAd = Application::$db->get(static::$tableName, 1);

        return static::createAdFromResult($lastAd);
    }

    static public function createAdFromResult($result)
    {
        if (empty($result)) {
            return null;
        }
        $ad = new Ad();
        foreach ($result as $field => $value) {
            $ad->$field = $value;
        }

        return $ad;
    }
}
