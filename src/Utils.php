<?php

namespace src;

use GuzzleHttp\Client;
use src\models\Ad;
use src\models\Image;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;
use DiDom\Document;

class Utils
{
    static $res = [];

    static public function removeTags()
    {
        $ads = Ad::findAll();
        foreach ($ads as $ad) {
            echo '.';
            /* @var $ad Ad*/
            $ad->text = strip_tags($ad->text);
            $ad->save();
        }
        echo PHP_EOL . 'Done' . PHP_EOL;
    }

    static public function removeEmptyImages()
    {
        $cnt = 0;
        $images = Image::findAll();
        foreach ($images as $image) {
            /* @var $image Image */
            if (filesize('images/' . $image->filename) == 0) {
                if (unlink('images/' . $image->filename)) {
                    if ($image->delete()) {
                        $cnt++;
                    }
                }
            }

        }
        echo PHP_EOL . 'Done. Deleted: ' . $cnt . PHP_EOL;
    }

    static public function run()
    {
        $url = 'http://bucha.ukrgo.com/';
        $adsCount = Parser::getAdsCount($url);
        echo 'ADS: ' . $adsCount . PHP_EOL;
        $pages = (int)ceil($adsCount / Parser::ADS_PER_PAGE);
        echo 'FIND: ' . $pages . ' Pages' . PHP_EOL;

        $result = [];
        $requests = function ($total) use ($url, $result) {
            for ($i = 1; $i <= $total; $i++) {
                $currentUrl = $url . Parser::CATEGORY;
                yield new Request('GET', ($i > 1 ? $currentUrl . '?' . Parser::SEARCH_PARAMS . $i : $currentUrl));
            }
        };
        $client = new Client();
        $pool = new Pool($client, $requests($pages), [
            'concurrency' => 5,
            'fulfilled' => function (Response $response, $index)  {
                $document = new Document($response->getBody()->getContents());
                list($urls, $isContinue) = Parser::getUrlsFromPage($document, null);
                $result = array_merge(self::$res, $urls);
                self::$res = array_merge(self::$res, $urls);
                echo count($result) . PHP_EOL;
            },
            'rejected' => function ($reason, $index) {
                echo $index . ' Fail!'  . $reason . PHP_EOL;
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();

        echo 'DONE!' . PHP_EOL;
        echo count(array_unique(self::$res)) . PHP_EOL;

        return self::$res;
    }
}
