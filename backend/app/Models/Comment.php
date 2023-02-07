<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['name','email','comment','status','commentable_id','commentable_type'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function commentAnswers(){
        return $this->hasMany(CommentAnswer::class);
    }

    public function likes(){
        return $this->morphMany(Like::class, 'likeable');
    }
}
