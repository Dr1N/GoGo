<?php

namespace src;

use src\models\Ad;
use src\models\City;
use src\models\Image;

class Utils
{
    public function removeTags()
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
    
    public function removeEmptyImages()
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

    public function moveImages()
    {
        $cities = City::findAll();
        foreach ($cities as $city) {
            /* @var $city City */
            $ads = $city->getAds();
            foreach ($ads as $ad) {
                /* @var $ad Ad */
                $images = $ad->getImages();
                foreach ($images as $image) {
                    //TODO
                }
            }
        }
        
        echo 'DONE!' . PHP_EOL;
    }
}
