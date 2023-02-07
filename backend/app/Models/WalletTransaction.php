<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'wallet_transactions';

    protected $fillable = ['cart','user_id','amount','transaction_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
