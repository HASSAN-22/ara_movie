<?php

namespace App\Auxiliary\Captcha;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use function App\Auxiliary\removeFile;

class Captcha
{
    private static $ip;
    public static function create(){
        self::$ip = request()->ip();
        $key = 'captcha_'.self::$ip;
        $text = self::generateRandomString(6);
        // Cache::forget($key);
        Cache::put($key,$text,now()->addMinutes(10));
        $im = imagecreate(70, 20);
        // White background and blue text
        $bg = imagecolorallocate($im, 255, 255, 255);
        $textcolor = imagecolorallocate($im, 0, 0, 255);
        // Write the string at the top left
        imagestring($im, 5, 15, 2, $text, $textcolor);

        return self::save($im);

    }

    private static function save($im){
        removeFile(Cache::get('image_'.self::$ip));
        // Cache::forget('image_'.self::$ip);
        $fileName = "/uploader/captcha_image/".self::$ip.'_'.rand(0000000,99999999).".png";
        Cache::put('image_'.self::$ip,$fileName,now()->addMinutes(10));
        imagepng($im, public_path($fileName));
        return $fileName;
    }


    private static function generateRandomString($length = 10) {
        $characters = '123456789bcdefghijkmnprstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
