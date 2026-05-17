<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function dashboard()
    {
        $driver = Driver::where('user_id', Auth::id())->first();

        if (!$driver) {
            return view('driver.not-registered');
        }

        $orders = Order::where('driver_id', $driver->id)
            ->latest()
            ->get();

        return view('driver.dashboard', compact('driver', 'orders'));
    }

    public function setStatus($status)
{
    $driver = Driver::where('user_id', Auth::id())->first();

    if (!$driver) {
        return redirect('/driver');
    }

    if (!in_array($status, ['online', 'offline'])) {
        return redirect('/driver');
    }

    if ($status == 'online' && $driver->penalty_until && now()->lt($driver->penalty_until)) {
        return redirect('/driver')
            ->with('error', 'Akun driver terkena penalti sampai ' . $driver->penalty_until);
    }

    $driver->update([
        'status' => $status
    ]);

    return redirect('/driver');
}

    public function updateOrderStatus($id, $status)
{
    $driver = Driver::where('user_id', Auth::id())->first();

    if (!$driver) {
        return redirect('/driver');
    }

    if (!in_array($status, ['delivery', 'completed'])) {
        return redirect('/driver');
    }

    $order = Order::where('id', $id)
        ->where('driver_id', $driver->id)
        ->firstOrFail();

    $order->update([
        'status' => $status
    ]);

    if ($status == 'completed') {
        $driver->update([
            'status' => 'online'
        ]);
    }

    return redirect('/driver');
}
public function acceptOrder($id)
{
    $driver = Driver::where('user_id', Auth::id())->firstOrFail();

    $order = Order::where('id', $id)
        ->where('driver_id', $driver->id)
        ->firstOrFail();

    $order->update([
        'status' => 'accepted',
    ]);

    $driver->update([
        'status' => 'busy',
    ]);

    return redirect('/driver');
}

public function rejectOrder($id)
{
    $driver = Driver::where('user_id', Auth::id())->firstOrFail();

    $order = Order::where('id', $id)
        ->where('driver_id', $driver->id)
        ->firstOrFail();

    $order->update([
        'driver_id' => null,
        'status' => 'pending',
    ]);

    $driver->update([
        'status' => 'online',
    ]);

    return redirect('/driver');
}
}