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
                'price' => $food->price,
                'qty' => 1,
            ];
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

    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (count($cart) == 0) {
            return redirect('/cart');
        }

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $firstItem = reset($cart);

        $food = Food::with('restaurant')->findOrFail($firstItem['id']);
        $restaurant = $food->restaurant;

        $driver = null;

        if ($restaurant && $restaurant->latitude && $restaurant->longitude) {
            $driver = Driver::where('status', 'online')
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
                    $restaurant->latitude,
                    $restaurant->longitude,
                    $restaurant->latitude
                ])
                ->orderBy('distance')
                ->first();
        }

       $order = Order::create([
    'user_id' => Auth::id(),
    'restaurant_id' => $restaurant ? $restaurant->id : null,
    'driver_id' => $driver ? $driver->id : null,
    'total' => $total,
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
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('cart.orders', compact('orders'));
    }
}