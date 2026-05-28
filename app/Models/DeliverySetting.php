<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliverySetting extends Model
{
    protected $fillable = [
        'base_fee',
        'per_km_fee',
        'minimum_fee',
        'max_driver_radius_km',
    ];
}