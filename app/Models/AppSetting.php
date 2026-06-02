<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = [
    'app_name',
    'primary_color',
    'secondary_color',
    'maintenance_mode',
    'customer_driver_radius',
    'ride_search_radius',
    'merchant_radius',
    'login_logo',
    'customer_logo',
    'driver_logo',
    'merchant_logo',
    'driver_map_icon',
    'home_banner',
];
}