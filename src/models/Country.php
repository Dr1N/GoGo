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

    /**
     * @var bool
     */
    public $is_enabled;

    /**
     * @var integer | null
     */
    public $start_city_id;

    static public function findById($id)
    {
        Application::$db->where('id', $id);
        $result = Application::$db->getOne(self::$tableName);

        return self::createObjectFromArray($result);
    }

    static public function findByName($name)
    {
        Application::$db->where('name', $name);
        $result = Application::$db->getOne(self::$tableName);

        return self::createObjectFromArray($result);
    }

    public function getCities($offset = null, $limit = null)
    {
        if (empty($this->id)) {
            return [];
        }
        Application::$db->where('country_id', $this->id);
        Application::$db->orderBy('id', 'ASC');
        $result = City::findAll($offset, $limit);

        return $result;
    }
}
