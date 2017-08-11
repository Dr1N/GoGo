<?php

set_time_limit(0);

require_once 'config/config.php';
require_once 'vendor/autoload.php';

use src\Application;

$app = new Application();

echo 'Hello!' . PHP_EOL;
$ua = \src\models\Country::findByName('Украина');
$cities = $ua->getCities();
foreach ($cities as $city) {
    /* @var $city \src\models\City */
    echo $city->id . "\t" . $city->name . PHP_EOL;
}