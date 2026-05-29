<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\DriverVehicle;

class Driver extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_type',
        'plate_number',
        'vehicle_brand',
        'vehicle_color',
        'status',
        'latitude',
        'longitude',
        'penalty_until',
        'penalty_reason',
        'last_location_update',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicles()
    {
        return $this->hasMany(DriverVehicle::class);
    }

    public function activeVehicles()
{
    return $this->hasMany(DriverVehicle::class)->where('is_active', true);
}
}