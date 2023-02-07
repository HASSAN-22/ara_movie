<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieLink extends Model
{
    use HasFactory;

    protected $table = 'movie_links';
    protected $fillable = ['movie_id','title','caption_movie','vip'];

    public function movie(){
        return $this->belongsTo(Movie::class);
    }

    public function screenShot(){
        return $this->hasOne(ScreenShot::class);
    }

    public function trailers(){
        return $this->hasMany(Trailer::class);
    }

    public function links(){
        return $this->hasMany(Link::class);
    }
}
