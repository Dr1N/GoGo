<?php

namespace src\models;

use src\base\Model;

class Image extends Model
{
    static public $tableName = 'images';

    /**
     * @var integer
     */
    public $id;

    /**
     * @var integer
     */
    public $ad_id;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $filename;
}