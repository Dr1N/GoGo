<?php

namespace src\models;

use src\base\Model;

class AdPhoneRelation extends Model
{
    static public $tableName = 'ad_phone_relation';

    /**
     * @var integer
     */
    public $ad_id;

    /**
     * @var integer
     */
    public $phone_id;
}