<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id','title','slug','status'];

    public function category(){
        return $this->belongsTo(Category::class,'parent_id','id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public function movies(){
        return $this->hasMany(Movie::class);
    }
}
