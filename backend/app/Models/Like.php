<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['ip','type','likeable_id','likable_type'];

    public function likeable(){
        return $this->morphTo();
    }
}
