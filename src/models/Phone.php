<?php

namespace src\models;

use src\Application;
use src\base\Model;

class Phone extends Model
{
    static public $tableName = 'phones';

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $phone;
    
    static public function findByPhone($phone)
    {
        Application::$db->where('phone', $phone);
        $phone = Application::$db->getOne(static::$tableName);
        
        return self::createObjectFromArray($phone);
    }
}
