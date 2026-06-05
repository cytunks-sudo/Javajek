<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\ChatMessage;


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
        'food_original_total',
        'food_markup_amount',
        'delivery_commission_amount',
        'admin_commission_amount',

    ];

    public function commissionTransaction()
{
    return $this->hasOne(\App\Models\DriverWalletTransaction::class)
        ->where('type', 'commission');
}

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

    public static function generateOrderNumber($type = 'food')
{
    $prefix = match ($type) {
        'food' => 'JF',
        'ojek' => 'JR',
        'car'  => 'JC',
        default => 'JF',
    };

    do {

        $number =
            $prefix .
            now()->format('dmyHi') .
            rand(10,99);

    } while (
        self::where('order_number', $number)->exists()
    );

    return $number;
}
public function chatMessages()
{
    return $this->hasMany(ChatMessage::class);
}

public function rating()
{
    return $this->hasOne(\App\Models\OrderRating::class);
}

}