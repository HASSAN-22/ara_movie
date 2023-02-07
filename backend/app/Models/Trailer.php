<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trailer extends Model
{
    use HasFactory;

    protected $fillable = ['movie_link_id','trailer','caption'];

    public function movieLink(){
        return $this->belongsTo(MovieLink::class);
    }
}
