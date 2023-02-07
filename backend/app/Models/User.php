<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use function App\Auxiliary\getDevice;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'access',
        'name',
        'mobile',
        'email',
        'password',
        'status',
//        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Admin(){
        return $this->access == 'admin';
    }

    public function User(){
        return $this->access == 'user';
    }

    /**
     * @param string $userAgent
     * @param User $user
     * @return \Laravel\Sanctum\string|string
     */
    public function createNewToken(string $userAgent, User $user){
        $device = getDevice($userAgent);
        return $user->createToken($device)->plainTextToken;
    }

    public function codes(){
        return $this->hasMany(Code::class);
    }

    public function carts(){
        return $this->hasMany(Cart::class);
    }


    public function walletTransactions(){
        return $this->hasMany(WalletTransaction::class);
    }

    public function vipTransactions(){
        return $this->hasMany(VipTransaction::class);
    }

    public function checkouts(){
        return $this->hasMany(Checkout::class);
    }

    public function wallet(){
        return $this->hasOne(Wallet::class);
    }

    public function watchLists(){
        return $this->hasMany(WatchList::class);
    }

}
