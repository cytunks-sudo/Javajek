<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'food_id',
        'qty',
        'price',
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}