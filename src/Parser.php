<?php

namespace src;

use DiDom\Document;
use DiDom\Element;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use src\models\Ad;
use src\models\Country;

class Parser
{
    const CATEGORY = 'view_subsection.php?id_subsection=146';
    const SEARCH_PARAMS = 'search=&page=';
    const ADS_PER_PAGE = 50;
    const ATTEMPT_DOWNLOAD = 3;
    const ATTEMPT_PAUSE = 1;

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
            self::logParsing($ex->getMessage());
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
            self::logParsing($ex->getMessage());
        }

        return $cities;
    }

    static public function getAdUrls($url, $lastUrl = null)
    {
        $result = [];
        $adsCount = self::getAdsCount($url);
        $pages = ceil($adsCount / self::ADS_PER_PAGE);
        echo 'FIND: ' . $pages . ' Pages' . PHP_EOL;
        for ($page = 1; $page <= $pages; $page++) {
            echo 'Page: ' . $page;
            $currentUrl = $url . self::CATEGORY;
            if ($page > 1) {
                $currentUrl = $currentUrl . '?' . self::SEARCH_PARAMS . $page;
            }
            echo "\tUrl: " . $currentUrl . PHP_EOL;
            list($urls, $isContinue) = self::getUrlsFromPage($currentUrl, $lastUrl);
            $result = array_merge($result, $urls);
            if (!$isContinue) break;
        }
        echo 'FIND: ' . count($result) . ' Urls' . PHP_EOL;

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
            self::logParsing('Can\'t find ads counter');
            self::logParsing($ex->getMessage());
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
                        return [$result, false];
                    }
                    if ($link->has('b')) {
                        $result[] = $adUrl;
                    }
                }
            }
        } catch (\Exception $ex) {
            self::logParsing('Can\'t find ad urls on page');
            self::logParsing($ex->getMessage());
        }
        echo 'URLs on Page: ' . count($result) . PHP_EOL;

        return [$result, true];
    }

    static public function getAdDataByUrl($url)
    {
        $result = [];
        try {
            echo 'AD URL: ' . $url . PHP_EOL;
            $document = self::getDocument($url);
            if ($document === null) return $result;
            //Url
            $result['url'] = $url;
            //Title
            $result['title'] = $document->find('h1[style^=display: inline]')[0]->text();
            $divInfo = $document->find('div[style=color: #242424; font-size: 12px; margin-top: 5px;]');
            if (count($divInfo) != 0) {
                $infoHtml = $divInfo[0]->innerHtml();
                //Date
                if (preg_match('/<b>Дата подачи объявления:<\/b>\s*(.*)<br>/iU', $infoHtml, $matches)) {
                    if (count($matches) == 2) {
                        $date = \DateTime::createFromFormat('d.m.Y H:i', trim($matches[1]));
                        $result['date'] = $date->getTimestamp();
                    }
                }
                //Gender
                if (preg_match('/<b>Пол:<\/b>\s*(.*)<br>/iU', $infoHtml, $matches)) {
                    if (count($matches) == 2) {
                        $result['gender'] = ((trim($matches[1])) == 'женщина') ? Ad::FEMALE : Ad::MALE;
                    }
                }
                //Age
                if (preg_match('/<b>Возраст:<\/b>\s*(.*)<br>/iU', $infoHtml, $matches)) {
                    if (count($matches) == 2) {
                        $result['age'] = intval(trim($matches[1]));
                    }
                }
                //Weight
                if (preg_match('/<b>Вес:<\/b>\s*(.*)<br>/iU', $infoHtml, $matches)) {
                    if (count($matches) == 2) {
                        $result['weight'] = intval(trim($matches[1]));
                    }
                }
                //Height
                if (preg_match('/<b>Рост:<\/b>\s*(.*)<br>/iU', $infoHtml, $matches)) {
                    if (count($matches) == 2) {
                        $result['height'] = intval(trim($matches[1]));
                    }
                }
                //Text
                $textDiv = $document->find('div[style=margin-top: 15px; text-align: left; width: 100%; color: #2a2a2a; font-size: 14px;]');
                if (count($textDiv) != 0) {
                    if (preg_match('/(.*)\s*<div/i', $textDiv[0]->innerHtml(), $matches)) {
                        $result['text'] = trim($matches[1]);
                    }
                    //Phones
                    $phones = $textDiv[0]->find('span');
                    if (count($phones) != 0) {
                        $result['phones'] = array_map('trim', explode(';', $phones[0]->text()));
                    }
                }
                //Images
                $result['images'] = self::getImagesForAd($document);
            }

        } catch (\Exception $ex) {
            self::logParsing('Can\'t parse ad');
            self::logParsing($ex->getMessage());
            self::logParsing($ex->getTraceAsString());
        }

        return $result;
    }

    static public function getImagesForAd(Document $document)
    {
        $result = [];
        $images = $document->find('img[style^=max-width: 120px; max-height: 120px;]');
        foreach ($images as $image) {
            $result[] = $image->getAttribute('src');
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
            self::logParsing($ex->getMessage());
        }

        return $result;
    }

    /**
     * Logging parser errors
     * @param $error
     * @param string $filename
     */
    static private function logParsing($error, $filename = 'parser.log')
    {
        echo $error . PHP_EOL;
        file_put_contents("logs/$filename", date('d.m.Y H:i:s') . "\t" . $error . PHP_EOL, FILE_APPEND);
    }

    /**
     * @param $url
     * @return array
     */
    private static function getDocument($url)
    {
        $document = null;
        //DiDom
        for ($i = 0; $i < self::ATTEMPT_DOWNLOAD; $i++) {
            try {
                $document = new Document($url, true);
                return $document;
            } catch (\Exception $ex) {
                sleep(self::ATTEMPT_PAUSE);
            }
        }
        //Guzzle
        echo 'GUZZLE' . PHP_EOL;
        try {
            $client = new Client();
            $response = $client->request('GET', $url);
            $body = $response->getBody()->getContents();
            $document = new Document($body);
        } catch (ClientException $cex) {
            self::logParsing($cex->getMessage());
            self::logParsing($cex->getCode());
            self::logParsing($cex->getRequest()->getUri());
            self::logParsing($cex->getResponse()->getHeaders());
        } catch (\Exception $ex) {
            self::logParsing($ex->getMessage());
        }

        return $document;
    }
}
