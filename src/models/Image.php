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
        Application::$db->where('id', $this->ad_id);
        $result = Application::$db->getOne(Ad::$tableName);

        return Ad::createObjectFromArray($result);
    }
}