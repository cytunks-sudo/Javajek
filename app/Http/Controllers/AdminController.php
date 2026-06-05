<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Order;
use App\Models\OrderRating;
use App\Models\Restaurant;

class AdminController extends Controller
{
    public function dashboard()
    {
        $topDrivers = Driver::with('user')
            ->withCount([
                'orders as completed_orders_count' => function ($q) {
                    $q->where('status', 'completed');
                }
            ])
            ->withAvg('ratings as average_rating', 'driver_rating')
            ->orderByDesc('completed_orders_count')
            ->limit(5)
            ->get();

        $topMerchants = Restaurant::with('owner')
            ->withCount([
                'orders as completed_orders_count' => function ($q) {
                    $q->where('status', 'completed');
                }
            ])
            ->withAvg('ratings as average_rating', 'merchant_rating')
            ->orderByDesc('completed_orders_count')
            ->limit(5)
            ->get();

        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $totalDrivers = Driver::count();
        $totalMerchants = Restaurant::count();

        return view('admin.dashboard', compact(
            'topDrivers',
            'topMerchants',
            'totalOrders',
            'completedOrders',
            'totalDrivers',
            'totalMerchants'
        ));
    }
}