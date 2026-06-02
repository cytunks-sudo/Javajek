<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
{
    $orders = Order::with([
            'items.food',
            'user',
            'driver.user',
            'restaurant',
        ])
        ->whereNotIn('status', ['completed', 'cancelled'])
        ->latest()
        ->get();

    return view('admin.orders.index', compact('orders'));
}

public function history()
{
    $orders = Order::with([
            'items.food',
            'user',
            'driver.user',
            'restaurant',
        ])
        ->whereIn('status', ['completed', 'cancelled'])
        ->latest()
        ->get();

    return view('admin.orders.history', compact('orders'));
}

    public function assignDriver(Request $request, $id)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $order = Order::with('restaurant')->findOrFail($id);

        if (in_array($order->status, ['completed', 'cancelled'])) {
            return redirect('/admin/orders')
                ->with('error', 'Order sudah selesai/dibatalkan.');
        }

        $driver = Driver::where('id', $request->driver_id)
            ->where('status', 'online')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->first();

        if (!$driver) {
            return redirect('/admin/orders')
                ->with('error', 'Driver tidak online atau lokasi driver belum aktif.');
        }

        $radiusKm = $this->getOrderRadiusKm($order);

        $targetLocation = $this->getOrderTargetLocation($order);

        if (!$targetLocation) {
            return redirect('/admin/orders')
                ->with('error', 'Lokasi order tidak lengkap.');
        }

        $distanceKm = $this->calculateDistanceKm(
            $targetLocation['lat'],
            $targetLocation['lng'],
            $driver->latitude,
            $driver->longitude
        );

        if ($distanceKm > $radiusKm) {
            return redirect('/admin/orders')
                ->with('error', 'Driver di luar radius. Jarak driver: '.number_format($distanceKm, 1).' km, radius: '.$radiusKm.' km.');
        }

        $order->update([
            'driver_id' => $driver->id,
            'status' => 'waiting_response',
            'driver_status' => 'pending',
        ]);

        return redirect('/admin/orders')
            ->with('success', 'Order berhasil dikirim ke driver. Menunggu respon driver.');
    }

    public function updateStatus($id, $status)
    {
        $allowedStatuses = [
            'cancelled',
        ];

        if (!in_array($status, $allowedStatuses)) {
            return redirect('/admin/orders')
                ->with('error', 'Admin hanya boleh membatalkan order. Status perjalanan diubah oleh driver.');
        }

        $order = Order::findOrFail($id);

        if (in_array($order->status, ['completed', 'cancelled'])) {
            return redirect('/admin/orders')
                ->with('error', 'Order sudah selesai/dibatalkan.');
        }

        $order->update([
            'status' => 'cancelled',
            'driver_status' => 'cancelled',
            'merchant_status' => 'cancelled',
        ]);

        if ($order->driver_id) {
            Driver::where('id', $order->driver_id)->update([
                'status' => 'online',
            ]);
        }

        return redirect('/admin/orders')
            ->with('success', 'Order berhasil dibatalkan.');
    }

    public static function availableDriversForOrder($order)
    {
        $targetLocation = self::staticOrderTargetLocation($order);

        if (!$targetLocation) {
            return collect();
        }

        $radiusKm = self::staticOrderRadiusKm($order);

        return Driver::with('user')
            ->where('status', 'online')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($driver) use ($targetLocation) {
                $driver->distance_km = self::staticCalculateDistanceKm(
                    $targetLocation['lat'],
                    $targetLocation['lng'],
                    $driver->latitude,
                    $driver->longitude
                );

                return $driver;
            })
            ->filter(function ($driver) use ($radiusKm) {
                return $driver->distance_km <= $radiusKm;
            })
            ->sortBy('distance_km')
            ->values();
    }

    private function getOrderTargetLocation($order)
    {
        return self::staticOrderTargetLocation($order);
    }

    private static function staticOrderTargetLocation($order)
    {
        if (in_array($order->order_type, ['ojek', 'car'])) {
            if ($order->pickup_latitude && $order->pickup_longitude) {
                return [
                    'lat' => $order->pickup_latitude,
                    'lng' => $order->pickup_longitude,
                ];
            }

            if ($order->latitude && $order->longitude) {
                return [
                    'lat' => $order->latitude,
                    'lng' => $order->longitude,
                ];
            }
        }

        if ($order->restaurant && $order->restaurant->latitude && $order->restaurant->longitude) {
            return [
                'lat' => $order->restaurant->latitude,
                'lng' => $order->restaurant->longitude,
            ];
        }

        if ($order->latitude && $order->longitude) {
            return [
                'lat' => $order->latitude,
                'lng' => $order->longitude,
            ];
        }

        return null;
    }

    private function getOrderRadiusKm($order)
    {
        return self::staticOrderRadiusKm($order);
    }

    private static function staticOrderRadiusKm($order)
    {
        $setting = AppSetting::first();

        if (in_array($order->order_type, ['ojek', 'car'])) {
            return (float) ($setting->ride_search_radius ?? 10);
        }

        return (float) ($setting->merchant_radius ?? 20);
    }

    private function calculateDistanceKm($lat1, $lng1, $lat2, $lng2)
    {
        return self::staticCalculateDistanceKm($lat1, $lng1, $lat2, $lng2);
    }

    private static function staticCalculateDistanceKm($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad((float) $lat2 - (float) $lat1);
        $dLng = deg2rad((float) $lng2 - (float) $lng1);

        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad((float) $lat1)) *
            cos(deg2rad((float) $lat2)) *
            sin($dLng / 2) *
            sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}