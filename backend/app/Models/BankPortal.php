<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankPortal extends Model
{
    use HasFactory;

    protected $table = 'bank_portals';

    protected $fillable = ['bank_name','code_id','username','password','status'];
}
