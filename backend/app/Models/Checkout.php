<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','cart_id','amount','status'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function cart(){
        return $this->belongsTo(Cart::class);
    }
}
