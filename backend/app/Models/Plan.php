<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['title','price','days','description','image'];

    public function discounts(){
        return $this->hasMany(Discount::class);
    }
}
