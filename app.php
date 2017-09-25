<?php

set_time_limit(0);

require_once 'config/config.php';
require_once 'vendor/autoload.php';

use src\Application;

$app = new Application();

file_put_contents('test.txt', 'Hello from Cron' . PHP_EOL, FILE_APPEND);
for ($i = 0; $i < $argc; $i++) {
    file_put_contents('test.txt', $argv[$i] . PHP_EOL, FILE_APPEND);
}
exit();

if (DB_CLEAR) {
    $app->clear();
}
if ($argc == 2) {
    if ($argv[1] == 'cities') {
        $app->parseCities();
    } else if (preg_match('/^-city=(.+)$/i', $argv[1], $matches)) {
        $city = trim($matches[1]);
        $app->run(null, $city);
    } else if (preg_match('/^-country=(.+)$/i', $argv[1], $matches)) {
        $country = trim($matches[1]);
        $app->run($country, null);
    }
    exit(0);
}

echo '### Command Line ###' . PHP_EOL;
echo '-city=cityname - parsing city' . PHP_EOL;
echo '-country=countryname - parsing country' . PHP_EOL;
