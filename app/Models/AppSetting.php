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
    'driver_min_balance',
'food_price_markup_percent',
'food_driver_commission_percent',
'ride_driver_commission_percent',
'car_driver_commission_percent',
];

protected $casts = [
    'driver_min_balance' => 'decimal:2',
    'food_price_markup_percent' => 'decimal:2',
    'food_driver_commission_percent' => 'decimal:2',
    'ride_driver_commission_percent' => 'decimal:2',
    'car_driver_commission_percent' => 'decimal:2',
];

}