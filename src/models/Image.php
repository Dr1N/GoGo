<?php

namespace src\models;

use src\base\Model;
use src\Application;

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

    public function getAd()
    {
        if (empty($this->ad_id)) {
            return null;
        }
        $result = Ad::findOne($this->ad_id);

        return Ad::createObjectFromArray($result);
    }
}