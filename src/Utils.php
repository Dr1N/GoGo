<?php

namespace src;

use League\CLImate\CLImate;
use src\models\Ad;
use src\models\City;
use src\models\Image;

class Utils
{
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

    static public function moveImages()
    {
        $client = new CLImate();
        $cities = City::findAll();
        $cityProgress = $client->progress()->total(count($cities));
        foreach ($cities as $city) {
            /* @var City $city */
            echo $city->name . PHP_EOL;
            $cityProgress->advance();
            $dirName = 'images' . DIRECTORY_SEPARATOR . 'c' . $city->id;
            if (!is_dir($dirName)) {
                if (!mkdir($dirName)) {
                    echo 'Can\'t create directory' . PHP_EOL;
                    continue;
                }
            }
            $offset = 0;
            $limit = 1000;
            while (true) {
                $ads = $city->getAds($offset, $limit);
                if (empty($ads)) break;
                echo '.';
                foreach ($ads as $ad) {
                    $images = $ad->getImages();
                    foreach ($images as $image) {
                        $fullName = 'images' . DIRECTORY_SEPARATOR . $image->filename;
                        if (file_exists($fullName)) {
                            $oldName = realpath($fullName);
                            $newName = realpath($dirName . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $image->filename;
                            if (!rename($oldName, $newName)) {
                                echo 'Rename Error!' . PHP_EOL;
                                die();
                            }
                        }
                    }
                }
                $offset += $limit;
                echo PHP_EOL;
            }
        }
        
        echo 'DONE!' . PHP_EOL;
    }
}
