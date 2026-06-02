<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverApplication extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'vehicle_type',
        'plate_number',
        'address',
        'latitude',
        'longitude',
        'photo',
        'sim_photo',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}