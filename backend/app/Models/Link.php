<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = ['movie_link_id','title','link','caption','subtitle'];

    public function movieLink(){
        return $this->belongsTo(MovieLink::class);
    }

}
