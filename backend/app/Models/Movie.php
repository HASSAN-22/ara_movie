<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'short_link',
        'title',
        'fa_title',
        'type',
        'image',
        'quality',
        'genre',
        'product',
        'lang',
        'published_at',
        'time',
        'director',
        'actor',
        'imdb',
        'imdb_number',
        'critics',
        'rank',
        'pg',
        'play_status',
        'broadcast_day',
        'network',
        'subtitle',
        'dubbed',
        'status',
        'status_comment',
        'soon',
        'awards',
        'imdbId',
        'description',
        'moreDescription',
        'slug',
        'keyword',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function movieLinks(){
        return $this->hasMany(MovieLink::class);
    }
    public function latestMovieLink() {
        return $this->hasOne(MovieLink::class)->orderBy('updated_at','desc');
    }

    public function movieTags(){
        return $this->hasMany(MovieTag::class);
    }

    public function reportLinks(){
        return $this->hasMany(ReportLink::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes(){
        return $this->morphMany(Like::class, 'likeable');
    }

    public function watchLists(){
        return $this->hasMany(WatchList::class);
    }
}
