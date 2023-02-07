<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = ['plan_id','discount','title','expire','code'];

    public function plan(){
        return $this->belongsTo(Plan::class);
    }
}
