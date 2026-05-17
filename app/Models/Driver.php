<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_type',
        'plate_number',
        'status',
        'latitude',
        'longitude',
        'penalty_until',
        'penalty_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}