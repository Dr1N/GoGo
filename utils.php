<?php

set_time_limit(0);

require_once 'config/config.php';
require_once 'vendor/autoload.php';

use src\Application;
use src\models\Ad;

$app = new Application();

$offset = 0;
$limit = 100;
for ($i = 1; $i <= 50; $i++)
{
    echo "$offset $limit => ";
    Application::$db->where('city_id', 1);
    Application::$db->where('parsed', null, 'IS');
    //$tmp = Application::$db->get(Ad::$tableName, [$offset, $limit]);
    $tmp = Ad::findAll($offset, $limit);
    $offset += $limit;
    //echo $tmp[count($tmp) - 1]->id;
    echo count($tmp) . PHP_EOL;
}



