<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Driver;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function count()
    {
        $driverCount = 0;
        $merchantCount = 0;

        $driver = Driver::where('user_id', Auth::id())->first();

        if ($driver) {
            $driverCount = Order::where('driver_id', $driver->id)
                ->where('driver_status', 'pending')
                ->count();
        }

        $restaurantIds = Restaurant::where('owner_id', Auth::id())
            ->pluck('id');

        if ($restaurantIds->count() > 0) {
            $merchantCount = Order::whereIn('restaurant_id', $restaurantIds)
                ->where('merchant_status', 'pending')
                ->count();
        }

        return response()->json([
            'driver' => $driverCount,
            'merchant' => $merchantCount,
            'total' => $driverCount + $merchantCount,
        ]);
    }
}