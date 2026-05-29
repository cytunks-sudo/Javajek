<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverVehicle extends Model
{
    protected $fillable = [
        'driver_id',
        'vehicle_type',
        'plate_number',
        'vehicle_brand',
        'vehicle_color',
        'is_active',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}