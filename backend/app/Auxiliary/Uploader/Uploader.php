<?php

namespace App\Auxiliary\Uploader;

class Uploader
{
    public static function upload(UploadInterface $upload){
        return $upload->upload();
    }
}
