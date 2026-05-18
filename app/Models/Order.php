<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'restaurant_id',
        'driver_id',
        'total',
        'status',
        'merchant_status',
        'driver_status',
        'driver_reject_count',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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