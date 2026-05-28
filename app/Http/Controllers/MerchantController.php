<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Driver;

class MerchantController extends Controller
{
    public function dashboard()
    {
        $restaurants = Restaurant::where('owner_id', Auth::id())
            ->latest()
            ->get();

        if ($restaurants->count() == 0) {
            return redirect('/apply-merchant');
        }

        $activeRestaurants = $restaurants->where('status', 'active');

        if ($activeRestaurants->count() == 0) {
            return view('merchant.pending', compact('restaurants'));
        }

        $orders = Order::with(['user', 'driver', 'items.food', 'restaurant'])
            ->whereHas('restaurant', function ($q) {
                $q->where('owner_id', Auth::id());
            })
            ->latest()
            ->get();

        return view('merchant.dashboard', compact('restaurants', 'orders'));
    }
public function foods()
{
    $restaurants = \App\Models\Restaurant::with('foods')
        ->where('status', 'active')
        ->get();

    return view('merchant.foods', compact('restaurants'));
}
    public function create()
    {
        return view('merchant.apply');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('merchants', 'public');
        }

        Restaurant::create([
            'owner_id' => Auth::id(),
            'name' => $request->name,
            'address' => $request->address,
            'photo' => $photoPath,
            'category' => $request->category,
            'open_time' => $request->open_time,
            'close_time' => $request->close_time,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'pending',
        ]);

        return redirect('/merchant')
            ->with('success', 'Merchant berhasil didaftarkan. Menunggu persetujuan admin.');
    }

    public function acceptOrder($id)
    {
        $order = Order::with('driver')
            ->whereHas('restaurant', function ($q) {
                $q->where('owner_id', Auth::id());
            })
            ->findOrFail($id);

        $order->update([
            'merchant_status' => 'accepted',
        ]);

        if ($order->driver_status == 'accepted') {
            $order->update([
                'status' => 'driver_to_merchant',
            ]);
        }

        return redirect('/merchant')->with('success', 'Pesanan diterima merchant.');
    }

    public function rejectOrder($id)
{
    $order = Order::findOrFail($id);

    $order->update([
        'merchant_status' => 'rejected',
        'driver_status' => 'rejected',
        'status' => 'cancelled',
    ]);

    if ($order->driver_id) {
        Driver::where('id', $order->driver_id)->update([
            'status' => 'online',
        ]);
    }

    return redirect('/merchant')->with('success', 'Pesanan ditolak dan dibatalkan.');
}


    public function updateHours(Request $request, $id)
{
    $request->validate([
        'open_time' => 'required',
        'close_time' => 'required',
    ]);

    $restaurant = Restaurant::where('owner_id', Auth::id())
        ->where('id', $id)
        ->firstOrFail();

    $restaurant->update([
        'open_time' => $request->open_time,
        'close_time' => $request->close_time,
    ]);

    return redirect('/merchant')->with('success', 'Jam buka restoran berhasil diperbarui.');
}

public function createFood()
{
    $restaurants = Restaurant::where('owner_id', Auth::id())
        ->where('status', 'active')
        ->get();

    return view('merchant.food-create', compact('restaurants'));
}

public function storeFood(Request $request)
{
    $request->validate([
        'restaurant_id' => 'required',
        'name' => 'required',
        'price' => 'required|numeric',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $restaurant = Restaurant::where('owner_id', Auth::id())
        ->where('id', $request->restaurant_id)
        ->where('status', 'active')
        ->firstOrFail();

    $photoPath = null;

    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('foods', 'public');
    }

    Food::create([
        'restaurant_id' => $restaurant->id,
        'name' => $request->name,
        'price' => $request->price,
        'description' => $request->description,
        'photo' => $photoPath,
        'status' => 'available',
    ]);

    return redirect('/merchant')->with('success', 'Menu makanan berhasil ditambahkan.');
}

public function editRestaurant($id)
{
    $restaurant = Restaurant::where('owner_id', Auth::id())
        ->where('id', $id)
        ->firstOrFail();

    return view('merchant.restaurant-edit', compact('restaurant'));
}

public function updateRestaurant(Request $request, $id)
{
    $request->validate([
    'name' => 'required',
    'address' => 'required',
    'category' => 'required',
    'latitude' => 'required',
    'longitude' => 'required',
    'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
]);

    $restaurant = Restaurant::where('owner_id', Auth::id())
        ->where('id', $id)
        ->firstOrFail();

    $photoPath = $restaurant->photo;

    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('merchants', 'public');
    }
$schedules = [];

if ($request->has('schedule')) {
    foreach ($request->schedule as $day => $data) {
        if (isset($data['active'])) {
            $schedules[$day] = [
                'open' => $data['open'] ?? '09:00',
                'close' => $data['close'] ?? '21:00',
            ];
        }
    }
}
    $restaurant->update([
        'name' => $request->name,
        'address' => $request->address,
        'photo' => $photoPath,
        'category' => $request->category,
        'open_time' => $request->open_time,
        'close_time' => $request->close_time,
       'open_days' => $schedules,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
    ]);

    return redirect('/merchant')->with('success', 'Pengaturan merchant berhasil diperbarui.');
}
public function toggleOpen($id)
{
    $restaurant = Restaurant::where('owner_id', Auth::id())
        ->where('id', $id)
        ->firstOrFail();

    $restaurant->manual_closed = !$restaurant->manual_closed;
    $restaurant->save();

    return redirect('/merchant')
        ->with('success', $restaurant->manual_closed ? 'Merchant ditutup manual.' : 'Merchant dibuka kembali.');
}
public function notifCount()
{
    $user = auth()->user();

    $count = \App\Models\Order::where('restaurant_id', $user->id)
        ->where('merchant_status', 'pending')
        ->count();

    return response()->json([
        'count' => $count
    ]);
}
}