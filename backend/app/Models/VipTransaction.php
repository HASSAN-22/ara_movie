<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VipTransaction extends Model
{
    use HasFactory;

    protected $table = 'vip_transactions';

    protected $fillable = ['user_id','cart','plan','discount','type','price','transaction_id','expire'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
