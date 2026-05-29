<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RideSetting extends Model
{
    protected $fillable = [
        'base_fee',
        'per_km_fee',
        'minimum_fee',
    ];
}