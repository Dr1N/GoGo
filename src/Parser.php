<?php

namespace src;

use DiDom\Document;
use DiDom\Element;
use src\models\Country;

class Parser
{
    const CATEGORY = 'view_subsection.php?id_subsection=146';
    const SEARCH_PARAMS = 'search=&page=';
    const ADS_PER_PAGE = 50;

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

    static public function getAdUrls($url, $lastUrl = null)
    {
        $result = [];
        $adsCount = self::getAdsCount($url);
        $pages = ceil($adsCount / self::ADS_PER_PAGE);
        for ($page = 1; $page <= $pages; $page++) {
            echo 'Page: ' . $page;
            $currentUrl = $url;
            if ($page > 1) {
                $currentUrl = $url . self::CATEGORY . '?' . self::SEARCH_PARAMS . $page;
            }
            echo "\tUrl: " . $currentUrl . PHP_EOL;
            $urls = self::getUrlsFromPage($currentUrl, $lastUrl);
            $result = array_merge($result, $urls);
        }

        return $result;
    }

    static public function getAdsCount($url)
    {
        try {
            $document = new Document($url . self::CATEGORY, true);
            $spans = $document->find('span[style=font-size: 14px;]');
            if (count($spans) != 0) {
                return (int)$spans[0]->text();
            }
        } catch (\Exception $ex) {
            self::logError('Can\'t find ads counter');
            self::logError($ex->getMessage());
        }
        return 0;
    }

    static public function getUrlsFromPage($url, $lastUrl)
    {
        $result = [];
        try {
            $document = new Document($url, true);
            $contentDiv = $document->find('div.main-content');
            if (count($contentDiv) != 0) {
                $links = $contentDiv[0]->find('a.link_post');
                foreach ($links as $link) {
                    $adUrl = $link->getAttribute('href');
                    if (!empty($lastUrl) && $lastUrl == $adUrl) {
                        break;
                    }
                    $result[] = $adUrl;
                }
            }
        } catch (\Exception $ex) {
            self::logError('Can\'t find ad urls on page');
            self::logError($ex->getMessage());
        }

        return $result;
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
        file_put_contents("logs/$filename", date('d.m.Y H:i:s') . "\t" . $error . PHP_EOL, FILE_APPEND);
    }
}
