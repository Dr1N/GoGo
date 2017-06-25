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
        Application::$db->where('id', $this->country_id);
        $result = Application::$db->getOne(Country::$tableName);

        return Country::createObjectFromArray($result);
    }

    public function getAds()
    {
        $result = [];
        Application::$db->where('city_id', $this->id);
        $ads = Application::$db->get(Ad::$tableName);
        foreach ($ads as $ad) {
            $result[] = Ad::createObjectFromArray($ad);
        }

        return $result;
    }
}
