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

    static public function findByName($name)
    {
        Application::$db->where('name', $name);
        $result = Application::$db->getOne(self::$tableName);

        return self::createObjectFromArray($result);
    }

    public function getCities($offset = null, $limit = null)
    {
        if (empty($this->id)) {
            return null;
        }
        $result = [];
        Application::$db->where('country_id', $this->id);
        $queryResult = City::findAll($offset = null, $limit = null);
        foreach ($queryResult as $item) {
            $result[] = City::createObjectFromArray($item);
        }

        return $result;
    }
}
