<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = [
        'app_name',
        'login_logo',
        'customer_logo',
        'driver_logo',
        'merchant_logo',
        'driver_map_icon',
        'home_banner',
        'primary_color',
        'secondary_color',
        'maintenance_mode',
    ];
}