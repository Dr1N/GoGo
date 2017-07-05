<?php

namespace src\models;

use src\Application;
use src\base\Model;

class City extends Model
{
    static public $tableName = 'cities';

    /**
     * @var integer
     */
    public $id;

    /**
     * @var integer
     */
    public $country_id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $url;

    static public function findByName($name)
    {
        Application::$db->where('name', $name);
        $result = Application::$db->getOne(self::$tableName);

        return self::createObjectFromArray($result);
    }

    public function getCountry()
    {
        if (empty($this->country_id)) {
            return null;
        }
        $result = Country::findOne($this->country_id);

        return $result;
    }

    public function getAds($offset = null, $limit = null)
    {
        $result = [];
        Application::$db->where('city_id', $this->id);
        $ads = Ad::findAll($offset, $limit);
        foreach ($ads as $ad) {
            $result[] = Ad::createObjectFromArray($ad);
        }

        return $result;
    }

    public function getLastAd()
    {
        Application::$db->where('city_id', $this->id);
        $maxDate = Application::$db->rawQueryValue('SELECT MAX(`date`) FROM `ads`');
        Application::$db->where('city_id', $this->id);
        Application::$db->where('date', $maxDate[0]);
        $lastAd = Application::$db->getOne(Ad::$tableName);

        return Ad::createObjectFromArray($lastAd);
    }
}
