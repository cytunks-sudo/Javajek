<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'address',
        'phone',
        'logo',
        'photo',
        'category',
        'open_time',
        'close_time',
        'open_days',
        'manual_closed',
        'latitude',
        'longitude',
        'status',
    ];

    protected $casts = [
        'open_days' => 'array',
        'manual_closed' => 'boolean',
    ];

    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ratings()
    {
        return $this->hasMany(OrderRating::class);
    }
}