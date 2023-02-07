<?php


namespace App\Auxiliary\Uploader;


class Upload implements UploadInterface
{
    private static $image;
    private static $folder;
    private static $useName;
    private static $instance = null;
    private  function __construct()
    {

    }
    public static function setOptions($image,$folder,$useName=true){
        self::$image = $image;
        self::$folder = $folder;
        self::$useName = $useName;
        if(self::$instance == null){
           return self::$instance = new Upload();
        }
        return self::$instance;

    }
    private static function getName(){
        return self::microTime() . '.' . last(explode('/',self::$image['type']));
    }
    public function upload(){
        $name = self::$useName ? self::$image['name'] : self::getName();
        self::checkFolderExist();
        $file = move_uploaded_file(self::$image['tmp_name'],public_path(self::$folder).$name);
        if($file){
            return ['error'=>0,'data'=>self::$folder.'/'.$name];
        }
        else
            return ['error'=>-1,'data'=>null];
    }

    private static function checkFolderExist(){
        if(!file_exists(public_path('/uploader'))){
            mkdir(public_path('/uploader'));
        }
        if(!file_exists(public_path(self::$folder))){
            mkdir(public_path(self::$folder));
        }
    }
    private static function microTime(){
        return explode('.',explode(' ',microtime())[0])[1];
    }
}
