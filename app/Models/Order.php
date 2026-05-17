<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Driver;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orders()
{
    $orders = Order::where('user_id', Auth::id())
        ->latest()
        ->get();

    return view('cart.orders', compact('orders'));
}
public function user()
{
    return $this->belongsTo(User::class);
}

public function driver()
{
    return $this->belongsTo(Driver::class);
}
}