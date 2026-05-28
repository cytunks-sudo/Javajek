<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function dashboard()
    {
        $driver = Driver::where('user_id', Auth::id())->first();

        if (!$driver) {
            return redirect('/apply-driver');
        }

        if ($driver->approval_status == 'pending') {
            return view('driver.pending', compact('driver'));
        }

        if ($driver->approval_status == 'rejected') {
            return view('driver.rejected', compact('driver'));
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
            'status' => $status,
        ]);

        return redirect('/driver');
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $driver = Driver::where('user_id', Auth::id())->first();

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver tidak ditemukan.',
            ], 404);
        }

        $driver->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'last_location_update' => now(),
        ]);

        return response()->json([
            'success' => true,
            'latitude' => $driver->latitude,
            'longitude' => $driver->longitude,
        ]);
    }

    public function updateOrderStatus($id, $status)
    {
        $driver = Driver::where('user_id', Auth::id())->first();

        if (!$driver) {
            return redirect('/driver');
        }

        if (!in_array($status, ['driver_to_merchant', 'dalam_pengiriman', 'completed'])) {
            return redirect('/driver');
        }

        $order = Order::where('id', $id)
            ->where('driver_id', $driver->id)
            ->firstOrFail();

        $order->update([
            'status' => $status,
        ]);

        if ($status == 'completed') {
            $driver->update([
                'status' => 'online',
            ]);
        }

        return redirect('/driver')->with('success', 'Status pesanan diperbarui.');
    }

    public function acceptOrder($id)
{
    $driver = Driver::where('user_id', Auth::id())->firstOrFail();

    $order = Order::with('restaurant')
        ->where('id', $id)
        ->where('driver_id', $driver->id)
        ->firstOrFail();

    if ($order->status == 'cancelled') {
        return redirect('/driver')->with('error', 'Pesanan sudah dibatalkan.');
    }

    $order->update([
        'driver_status' => 'accepted',
        'merchant_status' => 'accepted',
        'status' => 'driver_to_merchant',
    ]);

    $driver->update([
        'status' => 'busy',
    ]);

    return redirect('/driver')->with('success', 'Pesanan diterima. Silakan menuju merchant.');
}

    public function rejectOrder($id)
    {
        $driver = Driver::where('user_id', Auth::id())->firstOrFail();

        $order = Order::with('restaurant')
            ->where('id', $id)
            ->where('driver_id', $driver->id)
            ->firstOrFail();

        $driver->update([
            'status' => 'online',
        ]);

        $newDriver = $this->findNearestDriver($order->restaurant, $driver->id, 5);

        if ($newDriver) {
            $order->update([
                'driver_id' => $newDriver->id,
                'driver_status' => 'pending',
                'status' => 'waiting_response',
                'driver_reject_count' => $order->driver_reject_count + 1,
            ]);
        } else {
            $order->update([
                'driver_id' => null,
                'driver_status' => 'rejected',
                'status' => 'searching_driver',
                'driver_reject_count' => $order->driver_reject_count + 1,
            ]);
        }

        return redirect('/driver')->with('success', 'Pesanan ditolak. Sistem mencari driver lain.');
    }

    private function findNearestDriver($restaurant, $excludeDriverId = null, $maxRadiusKm = 5)
    {
        if (!$restaurant || !$restaurant->latitude || !$restaurant->longitude) {
            return null;
        }

        $query = Driver::where('status', 'online')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($excludeDriverId) {
            $query->where('id', '!=', $excludeDriverId);
        }

        return $query->selectRaw("
                *,
                (6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                )) AS distance
            ", [
                $restaurant->latitude,
                $restaurant->longitude,
                $restaurant->latitude,
            ])
            ->having('distance', '<=', $maxRadiusKm)
            ->orderBy('distance')
            ->first();
    }

    public function notifCount()
    {
        $driver = Driver::where('user_id', Auth::id())->first();

        if (!$driver) {
            return response()->json([
                'count' => 0,
            ]);
        }

        $count = Order::where('driver_id', $driver->id)
            ->where('driver_status', 'pending')
            ->where('status', 'waiting_response')
            ->count();

        return response()->json([
            'count' => $count,
        ]);
    }
}