<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverWalletTransaction extends Model
{
    protected $fillable = [
        'driver_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'order_id',
        'created_by',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}