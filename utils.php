<?php

set_time_limit(0);

require_once 'config/config.php';
require_once 'vendor/autoload.php';

use src\Application;

$app = new Application();
file_put_contents('test.txt', 'Hello from Cron');
