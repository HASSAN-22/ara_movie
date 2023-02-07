<?php


namespace App\Auxiliary\Services;


use App\Models\Movie;

class FrontMovieService
{
    public static function movies(){
        return Movie::where('status','yes');
    }
}
