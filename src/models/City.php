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

    /**
     * Find all cities
     * @param null $limit
     * @return City[]
     */
    public static function findAll($limit = null)
    {
        $cities = [];
        $table = self::getTableRecords($limit);
        foreach ($table as $item) {
            $cities[] = self::createCityFromResult($item);
        }
        return $cities;
    }

    static public function findOne($id)
    {
        Application::$db->where('id', $id);
        $result = Application::$db->getOne(static::$tableName);

        return self::createCityFromResult($result);
    }

    public function getCountry()
    {
        if (empty($this->country_id)) {
            return null;
        }
        Application::$db->where('id', $this->country_id);
        $result = Application::$db->getOne(Country::$tableName);

        return Country::createCountryFromResult($result);
    }

    /**
     * Insert city
     * @return bool|null
     */
    public function insert()
    {
        if (!$this->validate()) {
            return null;
        }
        $data = [
            'country_id' => $this->country_id,
            'name' => $this->name,
            'url' => $this->url,
        ];
        $id = Application::$db->insert(self::$tableName, $data);

        return $id;
    }

    public function validate()
    {
        return true;
    }

    static public function createCityFromResult($result)
    {
        if (empty($result)) {
            return null;
        }
        $city = new City();
        $city->id = $result['id'];
        $city->country_id = $result['country_id'];
        $city->name = $result['name'];
        $city->url = $result['url'];

        return $city;
    }
}
