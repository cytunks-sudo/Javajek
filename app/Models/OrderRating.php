<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRating extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'driver_id',
        'restaurant_id',
        'driver_rating',
        'driver_review',
        'merchant_rating',
        'merchant_review',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}