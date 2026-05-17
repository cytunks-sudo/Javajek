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
    $order = Order::findOrFail($id);

    $order->update([
        'driver_id' => $request->driver_id,
        'status' => 'delivery',
    ]);

    Driver::where('id', $request->driver_id)
        ->update([
            'status' => 'busy',
        ]);

    return redirect('/admin/orders');
}
    public function updateStatus($id, $status)
    {
        $allowed = ['pending', 'accepted', 'delivery', 'completed', 'cancelled'];

        if (!in_array($status, $allowed)) {
            abort(404);
        }

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $status,
        ]);

        return redirect('/admin/orders');
    }
}