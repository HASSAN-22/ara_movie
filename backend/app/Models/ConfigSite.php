<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'email',
        'mobile',
        'phone',
        'logo',
        'logo_mobile',
        'copy_right',
        'telegram',
        'twitter',
        'facebook',
        'instagram',
        'omdb_api',
//        'captcha_key',
        'min_amount',
        'max_amount',
        'about_us',
        'contact_us',
        'vip',
        'page',
        'alert',
        'alert_link',
//        'captcha',
        'description',
    ];
}
