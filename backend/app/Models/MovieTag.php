<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieTag extends Model
{
    use HasFactory;

    protected $table = 'movie_tags';
    protected $fillable = ['movie_id','title','link'];

    public function movie(){
        return $this->belongsTo(Movie::class);
    }
}
