<?php

namespace src\models;

use src\Application;
use src\base\Model;

class Country extends Model
{
    static public  $tableName = 'countries';

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $url;

    static public function findAll($limit = null)
    {
        $countries = [];
        $table = self::getTableRecords($limit);
        foreach ($table as $item) {
            $countries[] = self::createCountryFromResult($item);
        }
        return $countries;
    }

    static public function findOne($id)
    {
        Application::$db->where('id', $id);
        $result = Application::$db->getOne(static::$tableName);

        return self::createCountryFromResult($result);
    }

    static public function findByName($name)
    {
        Application::$db->where('name', $name);
        $result = Application::$db->getOne(static::$tableName);

        return self::createCountryFromResult($result);
    }

    public function getCities()
    {
        if (empty($this->id)) {
            return null;
        }
        $result = [];
        Application::$db->where('country_id', $this->id);
        $queryResult = Application::$db->get(City::$tableName);
        foreach ($queryResult as $item) {
            $result[] = City::createCityFromResult($item);
        }

        return $result;
    }

    static public function createCountryFromResult($result)
    {
        if (empty($result)) {
            return null;
        }
        $country = new Country();
        $country->id = $result['id'];
        $country->name = $result['name'];
        $country->url = $result['url'];

        return $country;
    }
}
