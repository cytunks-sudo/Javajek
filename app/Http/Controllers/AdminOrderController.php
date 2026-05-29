<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Driver;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.food', 'user'])
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }
public function assignDriver(Request $request, $id)
{
    $request->validate([
        'driver_id' => 'required|exists:drivers,id',
    ]);

    $order = Order::findOrFail($id);

    $order->update([
        'driver_id' => $request->driver_id,
        'status' => 'waiting_response',
        'driver_status' => 'pending',
    ]);

    Driver::where('id', $request->driver_id)
        ->update([
            'status' => 'busy',
        ]);

    return redirect('/admin/orders')
        ->with('success', 'Driver berhasil ditugaskan.');
}
public function updateStatus($id, $status)
{
    $allowedStatuses = [
        'searching_driver',
        'waiting_response',
        'driver_to_merchant',
        'dalam_pengiriman',
        'driver_to_pickup',
        'driver_to_destination',
        'completed',
        'cancelled',
    ];

    if (!in_array($status, $allowedStatuses)) {
        return redirect('/admin/orders')
            ->with('error', 'Status order tidak valid.');
    }

    $order = \App\Models\Order::findOrFail($id);

    $order->status = $status;

    if ($status == 'cancelled') {
        $order->driver_status = 'cancelled';
        $order->merchant_status = 'cancelled';
    }

    if ($status == 'completed') {
        $order->driver_status = 'completed';
        $order->merchant_status = 'completed';
    }

    $order->save();

    if ($order->driver_id && in_array($status, ['cancelled', 'completed'])) {
        $driver = \App\Models\Driver::find($order->driver_id);

        if ($driver) {
            $driver->status = 'online';
            $driver->save();
        }
    }

    return redirect('/admin/orders')
        ->with('success', 'Status order berhasil diperbarui.');
}
}