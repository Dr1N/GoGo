<?php

namespace src;

use DiDom\Document;
use DiDom\Element;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use League\CLImate\CLImate;
use src\models\Ad;
use src\models\City;
use src\models\Country;

class Parser
{
    const CATEGORY = 'view_subsection.php?id_subsection=146';
    const SEARCH_PARAMS = 'search=&page=';
    const ADS_PER_PAGE = 50;
    const ATTEMPT_DOWNLOAD = 2;
    const ATTEMPT_PAUSE = 1;

    static public $result = [];

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

    static public function getAdUrls(City $city)
    {
        $url = $city->url;
        $adsCount = self::getAdsCount($url);
        echo 'ADS: ' . $adsCount . PHP_EOL;
        $pages = (int)ceil($adsCount / self::ADS_PER_PAGE);
        echo 'FIND: ' . $pages . ' Pages' . PHP_EOL;

        $progress = (new CLImate())->progress()->total($pages);
        //Request
        $requests = function ($total) use ($url, $progress) {
            for ($i = 1; $i <= $total; $i++) {
                $currentUrl = $url . Parser::CATEGORY;
                $progress->advance();
                yield new Request('GET', ($i > 1 ? $currentUrl . '?' . Parser::SEARCH_PARAMS . $i : $currentUrl));
            }
        };

        //Pool
        self::$result = [];
        $client = new Client();
        $pool = new Pool($client, $requests($pages), [
            'concurrency' => GZ_CONCURRENT,
            'fulfilled' => function (Response $response, $index) {
                $document = new Document($response->getBody()->getContents());
                $urls = Parser::getUrlsFromPage($document);
                self::$result = array_merge(self::$result, $urls);
            },
            'rejected' => function ($reason, $index) {
                echo $index . ' Fail!'  . $reason . PHP_EOL;
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
        
        self::$result = array_unique(self::$result);

        return self::$result;
    }

    static public function getUrlsFromPage(Document $document)
    {
        $result = [];
        if ($document == null) {
            return $result;
        }
        try {
            $contentDiv = $document->find('div.main-content');
            if (count($contentDiv) != 0) {
                $links = $contentDiv[0]->find('a.link_post');
                foreach ($links as $link) {
                    $adUrl = $link->getAttribute('href');
                    if ($link->has('b')) {
                        $result[] = $adUrl;
                    }
                }
            }
        } catch (\Exception $ex) {
            self::logParsing('Can\'t find ad urls on page');
            self::logParsing($ex->getMessage());
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
            self::logParsing('Can\'t find ads counter');
            self::logParsing($ex->getMessage());
        }
        return 0;
    }

    static public function getAdDataFromDocument(Document $document, $url)
    {
        $result = [];
        try {
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
                        $result['text'] = strip_tags(trim($matches[1]));
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
    
    static private function logParsing($error)
    {
        Application::log($error, 'parser');
    }

    /**
     * @param $url
     * @return Document|null
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
