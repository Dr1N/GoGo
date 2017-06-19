<?php

namespace src;

use DiDom\Document;
use DiDom\Element;
use src\models\Country;

class Parser
{
    /**
     * Get Cities from Country
     * @param Country $country
     * @return array
     */
    static public function getCities(Country $country)
    {
        $result = [];
        try {
            $document = new Document($country->url, true);
            $citiesTable = $document->find('table[align="right"]');
            if (count($citiesTable) != 0) {
                $cities = self::getCitiesLinks($citiesTable[0], 'a.link');
                foreach ($cities as $name => $url) {
                    $result = array_merge($result, self::getCitiesFromRegion($url));
                }
            }
        } catch (\Exception $ex) {
            self::logError($ex->getMessage());
        }

        return $result;
    }

    /**
     * Get Cities From Region
     * @param $url string
     * @return array
     */
    static public function getCitiesFromRegion($url)
    {
        $cities = [];
        try {
            $document = new Document($url, true);
            $citiesTable = $document->find('table[align="right"]');
            if (count($citiesTable) != 0) {
                $cities = self::getCitiesLinks($citiesTable[0], 'li>a.link');
            }
        } catch (\Exception $ex) {
            self::logError($ex->getMessage());
        }

        return $cities;
    }

    /**
     * Get Cities data from element
     * @param Element $table
     * @param $selector
     * @return array
     */
    static private function getCitiesLinks(Element $table, $selector)
    {
        $result = [];
        try {
            $links = $table->find($selector);
            if (count($links) != 0) {
                foreach ($links as $link) {
                    if ($link->text() == "Все") continue;
                    $result[$link->text()] = $link->getAttribute('href');
                }
            }
        } catch (\Exception $ex) {
            self::logError($ex->getMessage());
        }

        return $result;
    }

    /**
     * Logging parser errors
     * @param $error
     * @param string $filename
     */
    static private function logError($error, $filename = 'parser.log')
    {
        echo $error . PHP_EOL;
        file_put_contents("logs/$filename", date('d.m.Y H:i:s') . $error . PHP_EOL, FILE_APPEND);
    }
}
