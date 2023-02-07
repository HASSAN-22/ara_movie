<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id','name','email','comment_id','answer','status'];

    public function comment(){
        return $this->belongsTo(Comment::class);
    }

    public function likes(){
        return $this->morphMany(Like::class, 'likeable');
    }

    public function children()
    {
        return $this->hasMany(CommentAnswer::class, 'parent_id')->with('children');
    }

}
