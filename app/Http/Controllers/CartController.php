<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use App\Models\Driver;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        return view('cart.index', compact('cart'));
    }

    public function add($id)
    {
        $food = Food::with('restaurant')->findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
        } else {
           $cart[$id] = [
    'id' => $food->id,
    'name' => $food->name,
    'restaurant' => $food->restaurant->name,
    'restaurant_latitude' => $food->restaurant->latitude,
    'restaurant_longitude' => $food->restaurant->longitude,
    'price' => $food->price,
    'qty' => 1,
    'photo' => $food->photo,
];
        }

        session()->put('cart', $cart);

        return redirect('/cart');
    }
    public function increase($id)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        $cart[$id]['qty']++;
    }

    session()->put('cart', $cart);

    return redirect('/cart');
}

public function decrease($id)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        $cart[$id]['qty']--;

        if ($cart[$id]['qty'] <= 0) {
            unset($cart[$id]);
        }
    }

    session()->put('cart', $cart);

    return redirect('/cart');
}

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        session()->put('cart', $cart);

        return redirect('/cart');
    }

    public function clear()
    {
        session()->forget('cart');

        return redirect('/');
    }

    private function calculateDistanceKm($lat1, $lon1, $lat2, $lon2)
    {
        if (!$lat1 || !$lon1 || !$lat2 || !$lon2) {
            return 0;
        }

        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLon / 2) *
            sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    private function calculateDeliveryFee($distanceKm)
    {
        $baseFee = 5000;
        $perKm = 2000;

        if ($distanceKm <= 0) {
            return 0;
        }

        if ($distanceKm <= 1) {
            return $baseFee;
        }

        return $baseFee + (ceil($distanceKm - 1) * $perKm);
    }

    private function findNearestDriver($latitude, $longitude, $maxRadiusKm = 5)
    {
        if (!$latitude || !$longitude) {
            return null;
        }

        return Driver::where('status', 'online')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("
                *,
                (6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                )) AS distance
            ", [
                $latitude,
                $longitude,
                $latitude
            ])
            ->having('distance', '<=', $maxRadiusKm)
            ->orderBy('distance')
            ->first();
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (count($cart) == 0) {
            return redirect('/cart');
        }

        $customer = Auth::user();

        if (!$customer->latitude || !$customer->longitude) {
            return redirect('/cart')->with('error', 'Lokasi customer belum tersedia. Aktifkan GPS terlebih dahulu.');
        }

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $firstItem = reset($cart);

        $food = Food::with('restaurant')->findOrFail($firstItem['id']);
        $restaurant = $food->restaurant;

        if (!$restaurant || !$restaurant->latitude || !$restaurant->longitude) {
            return redirect('/cart')->with('error', 'Lokasi merchant belum tersedia.');
        }

        $distanceKm = $this->calculateDistanceKm(
            $customer->latitude,
            $customer->longitude,
            $restaurant->latitude,
            $restaurant->longitude
        );

        $deliveryFee = $this->calculateDeliveryFee($distanceKm);

        $grandTotal = $total + $deliveryFee;

        $driver = $this->findNearestDriver(
            $restaurant->latitude,
            $restaurant->longitude,
            5
        );

        $order = Order::create([
            'user_id' => Auth::id(),
            'restaurant_id' => $restaurant->id,
            'driver_id' => $driver ? $driver->id : null,

            'total' => $total,
            'delivery_fee' => $deliveryFee,
            'grand_total' => $grandTotal,

            'customer_latitude' => $customer->latitude,
            'customer_longitude' => $customer->longitude,

            'merchant_latitude' => $restaurant->latitude,
            'merchant_longitude' => $restaurant->longitude,

            'distance_km' => $distanceKm,

            'status' => $driver ? 'waiting_response' : 'searching_driver',
            'merchant_status' => 'pending',
            'driver_status' => $driver ? 'pending' : 'rejected',
            'driver_reject_count' => 0,
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'food_id' => $item['id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
            ]);
        }

        session()->forget('cart');

        return redirect('/my-orders');
    }

    public function orders()
{
    $orders = \App\Models\Order::where('user_id', auth()->id())
        ->whereNotIn('status', ['completed', 'cancelled'])
        ->latest()
        ->get();

    return view('cart.orders', compact('orders'));
}

public function orderHistory()
{
    $orders = \App\Models\Order::where('user_id', auth()->id())
        ->whereIn('status', ['completed', 'cancelled'])
        ->latest()
        ->get();

    return view('cart.order-history', compact('orders'));
}


}