<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportLink extends Model
{
    use HasFactory;

    protected $table = 'report_links';

    protected $fillable = ['movie_id','movie_link_id','link_id','description'];

    public function movie(){
        return $this->belongsTo(Movie::class);
    }

    public function movieLink(){
        return $this->belongsTo(MovieLink::class);
    }

    public function link(){
        return $this->belongsTo(Link::class);
    }
}
