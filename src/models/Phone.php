<?php

namespace src\models;

use src\base\Model;

class Phone extends Model
{
    static public $tableName = 'phones';

    /**
     * @var integer
     */
    public $phone;
}
