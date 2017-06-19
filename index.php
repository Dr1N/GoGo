<?php

require_once 'vendor/autoload.php';
require_once 'config/config.php';

use src\Application;

$app = new Application();
$argument = $argv[1];
switch ($argument)
{
    case 'cities':
        $app->parseCities();
        break;
    default:
        $app->run();
        break;

}
