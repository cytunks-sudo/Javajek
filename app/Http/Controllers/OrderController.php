<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Food;
use App\Models\DeliverySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\RideSetting;

class OrderController extends Controller
{
    public function show($id)
    {
        $order = Order::findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function checkoutPage()
    {
        $cart = session('cart', []);

        return view('cart.checkout', compact('cart'));
    }

    public function calculateCheckout(Request $request)
    {
        $request->validate([
            'latitude'  => 'required',
            'longitude' => 'required',
            'address'   => 'required',
        ]);

        $cart = session('cart', []);

        if (count($cart) < 1) {
            return redirect('/cart');
        }

        $checkout = $this->makeCheckoutSummary(
            $cart,
            $request->latitude,
            $request->longitude,
            $request->address
        );

        session(['checkout_data' => $checkout]);

        return view('cart.checkout-result', [
            'address'           => $checkout['address'],
            'latitude'          => $checkout['latitude'],
            'longitude'         => $checkout['longitude'],
            'subtotalProduk'    => $checkout['subtotal_produk'],
            'totalOngkir'       => $checkout['total_ongkir'],
            'grandTotal'        => $checkout['grand_total'],
            'merchantSummaries' => $checkout['merchant_summaries'],
        ]);
    }

    public function storeOrder()
    {
        $cart = session('cart', []);
        $checkout = session('checkout_data');

        if (!$checkout || count($cart) < 1) {
            return redirect('/cart');
        }

        foreach ($checkout['merchant_summaries'] as $summary) {

            $order = new Order();

            $order->user_id = Auth::id();

            if (Schema::hasColumn('orders', 'restaurant_id')) {
                $order->restaurant_id = $summary['restaurant_id'];
            }

            if (Schema::hasColumn('orders', 'driver_id')) {
                $order->driver_id = null;
            }

            if (Schema::hasColumn('orders', 'total')) {
                $order->total = $summary['merchant_total'];
            }

            if (Schema::hasColumn('orders', 'delivery_fee')) {
                $order->delivery_fee = $summary['delivery_fee'];
            }

            if (Schema::hasColumn('orders', 'distance_km')) {
                $order->distance_km = $summary['distance_km'];
            }

            if (Schema::hasColumn('orders', 'address')) {
                $order->address = $checkout['address'];
            }

            if (Schema::hasColumn('orders', 'latitude')) {
                $order->latitude = $checkout['latitude'];
            }

            if (Schema::hasColumn('orders', 'longitude')) {
                $order->longitude = $checkout['longitude'];
            }

            if (Schema::hasColumn('orders', 'status')) {
                $order->status = 'searching_driver';
            }

            if (Schema::hasColumn('orders', 'merchant_status')) {
                $order->merchant_status = 'pending';
            }

            if (Schema::hasColumn('orders', 'driver_status')) {
                $order->driver_status = 'pending';
            }

            if (Schema::hasColumn('orders', 'driver_reject_count')) {
                $order->driver_reject_count = 0;
            }

            $order->save();
        }

        session()->forget('cart');
        session()->forget('checkout_data');

        return redirect('/my-orders');
    }

    private function makeCheckoutSummary($cart, $customerLat, $customerLng, $address)
    {
        $setting = DeliverySetting::first();

        $baseFee    = $setting->base_fee ?? 3000;
        $perKmFee   = $setting->per_km_fee ?? 2000;
        $minimumFee = $setting->minimum_fee ?? 5000;

        $foods = Food::with('restaurant')
            ->whereIn('id', collect($cart)->pluck('id'))
            ->get()
            ->keyBy('id');

        $groupedCart = collect($cart)->groupBy(function ($item) use ($foods) {
            $food = $foods[$item['id']] ?? null;
            return $food && $food->restaurant ? $food->restaurant->id : 0;
        });

        $subtotalProduk = 0;
        $totalOngkir = 0;
        $merchantSummaries = [];

        foreach ($groupedCart as $restaurantId => $items) {

            if (!$restaurantId) {
                continue;
            }

            $firstItem = collect($items)->first();
            $food = $foods[$firstItem['id']] ?? null;
            $restaurant = $food->restaurant ?? null;

            if (!$restaurant) {
                continue;
            }

            $merchantSubtotal = collect($items)->sum(function ($item) {
                return $item['price'] * $item['qty'];
            });

            $distanceKm = $this->calculateDistance(
                $customerLat,
                $customerLng,
                $restaurant->latitude,
                $restaurant->longitude
            );

            $deliveryFee = $baseFee + round($distanceKm * $perKmFee);
            $deliveryFee = max($minimumFee, $deliveryFee);
            $deliveryFee = ceil($deliveryFee / 500) * 500;
            $subtotalProduk += $merchantSubtotal;
            $totalOngkir += $deliveryFee;

            $merchantSummaries[] = [
                'restaurant_id'     => $restaurant->id,
                'restaurant'        => $restaurant->name,
                'items'             => $items,
                'subtotal'          => $merchantSubtotal,
                'distance_km'       => round($distanceKm, 1),
                'delivery_fee'      => $deliveryFee,
                'merchant_total'    => $merchantSubtotal + $deliveryFee,
                'merchant_latitude'  => $restaurant->latitude,
                'merchant_longitude' => $restaurant->longitude,
                ];
        }

        return [
            'latitude'           => $customerLat,
            'longitude'          => $customerLng,
            'address'            => $address,
            'subtotal_produk'    => $subtotalProduk,
            'total_ongkir'       => $totalOngkir,
            'grand_total'        => $subtotalProduk + $totalOngkir,
            'merchant_summaries' => $merchantSummaries,
        ];
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
{
    if ($lat1 === null || $lng1 === null || $lat2 === null || $lng2 === null) {
        return 0;
    }

    $earthRadius = 6371; // KM

    $lat1 = deg2rad((float) $lat1);
    $lng1 = deg2rad((float) $lng1);
    $lat2 = deg2rad((float) $lat2);
    $lng2 = deg2rad((float) $lng2);

    $latDelta = $lat2 - $lat1;
    $lngDelta = $lng2 - $lng1;

    $a = sin($latDelta / 2) * sin($latDelta / 2) +
         cos($lat1) * cos($lat2) *
         sin($lngDelta / 2) * sin($lngDelta / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c;
}

public function ojekPage()
{
    return view('ojek.index');
}

public function calculateOjek(Request $request)
{
    $request->validate([
        'pickup_latitude'        => 'required',
        'pickup_longitude'       => 'required',
        'destination_latitude'   => 'required',
        'destination_longitude'  => 'required',
        'pickup_address'         => 'required',
        'destination_address'    => 'required',
    ]);

    $distanceKm = $this->calculateDistance(
        $request->pickup_latitude,
        $request->pickup_longitude,
        $request->destination_latitude,
        $request->destination_longitude
    );

    $setting = RideSetting::first();

$baseFee = $setting->base_fee ?? 5000;
$perKmFee = $setting->per_km_fee ?? 2500;
$minimumFee = $setting->minimum_fee ?? 8000;


    $fare = $baseFee + ($distanceKm * $perKmFee);
    $fare = max($minimumFee, $fare);

    // pembulatan ke atas kelipatan 500
    $fare = ceil($fare / 500) * 500;

    session([
        'ojek_data' => [
            'pickup_latitude'       => $request->pickup_latitude,
            'pickup_longitude'      => $request->pickup_longitude,
            'destination_latitude'  => $request->destination_latitude,
            'destination_longitude' => $request->destination_longitude,
            'pickup_address'        => $request->pickup_address,
            'destination_address'   => $request->destination_address,
            'distance_km'           => round($distanceKm, 1),
            'fare'                  => $fare,
        ]
    ]);

    return view('ojek.result', [
        'pickupAddress'      => $request->pickup_address,
        'destinationAddress' => $request->destination_address,
        'distanceKm'         => round($distanceKm, 1),
        'fare'               => $fare,
    ]);
}

public function storeOjekOrder()
{
    $ojek = session('ojek_data');

    if (!$ojek) {
        return redirect('/ojek');
    }

    $order = new Order();
    $order->user_id = auth()->id();
    $order->total = $ojek['fare'];
    $order->status = 'searching_driver';
    $order->merchant_status = 'accepted';
    
    if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'order_type')) {
        $order->order_type = 'ojek';
    }

    if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'pickup_latitude')) {
        $order->pickup_latitude = $ojek['pickup_latitude'];
    }

    if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'pickup_longitude')) {
        $order->pickup_longitude = $ojek['pickup_longitude'];
    }

    if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'destination_latitude')) {
        $order->destination_latitude = $ojek['destination_latitude'];
    }

    if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'destination_longitude')) {
        $order->destination_longitude = $ojek['destination_longitude'];
    }

    if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'pickup_address')) {
        $order->pickup_address = $ojek['pickup_address'];
    }

    if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'destination_address')) {
        $order->destination_address = $ojek['destination_address'];
    }

    if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'distance_km')) {
        $order->distance_km = $ojek['distance_km'];
    }

    if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'driver_status')) {
        $order->driver_status = 'pending';
    }

    if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'driver_reject_count')) {
        $order->driver_reject_count = 0;
    }

    $order->save();

    session()->forget('ojek_data');

    return redirect('/my-orders');
}
}