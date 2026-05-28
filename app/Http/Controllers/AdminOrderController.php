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
}