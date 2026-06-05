<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DeliverySetting;
use App\Models\DriverVehicle;
use App\Models\AppSetting;
use App\Models\DriverWalletTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\OrderRating;


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

        $orders = Order::where(function($q) use ($driver) {
        $q->where('driver_id', $driver->id)
          ->orWhere(function($x) {
              $x->whereNull('driver_id')
                ->where('status', 'searching_driver');
          });
    })
    ->whereNotIn('status', ['completed', 'cancelled'])
    ->latest()
    ->get();

    $setting = \App\Models\AppSetting::first();

$minimumBalance = $setting->driver_min_balance ?? 0;

$driverBalance = $driver->balance ?? 0;

$walletWarning = null;

if ($driverBalance < 0) {

    $walletWarning =
        'Saldo Anda minus Rp '.number_format(abs($driverBalance)).
        '. Silakan topup saldo ke admin agar dapat menerima order kembali.';

}
elseif ($driverBalance < $minimumBalance) {

    $walletWarning =
        'Saldo Anda kurang dari minimum Rp '.number_format($minimumBalance).
        '. Silakan topup saldo agar bisa online.';
}

$completedDriverOrders = \App\Models\Order::where('driver_id', $driver->id)
    ->where('status', 'completed');

$driverIncomeToday = (clone $completedDriverOrders)
    ->whereDate('updated_at', today())
    ->get()
    ->sum(function($order){
        return ($order->grand_total ?? $order->total ?? 0)
            - ($order->admin_commission_amount ?? 0);
    });

$driverIncomeMonth = (clone $completedDriverOrders)
    ->whereMonth('updated_at', now()->month)
    ->whereYear('updated_at', now()->year)
    ->get()
    ->sum(function($order){
        return ($order->grand_total ?? $order->total ?? 0)
            - ($order->admin_commission_amount ?? 0);
    });

$driverCompletedOrders = (clone $completedDriverOrders)->count();

$driverCommissionTotal = \App\Models\DriverWalletTransaction::where('driver_id', $driver->id)
    ->where('type', 'commission')
    ->sum('amount');

$driverCommissionTotal = abs($driverCommissionTotal);

$driverAverageRating = OrderRating::where('driver_id', $driver->id)
    ->whereNotNull('driver_rating')
    ->avg('driver_rating');

$driverTotalReviews = OrderRating::where('driver_id', $driver->id)
    ->whereNotNull('driver_rating')
    ->count();

$driverLatestReviews = OrderRating::with(['customer', 'order'])
    ->where('driver_id', $driver->id)
    ->whereNotNull('driver_rating')
    ->latest()
    ->limit(5)
    ->get();


        return view('driver.dashboard', compact(
            'driver', 
            'orders', 
            'walletWarning', 
            'minimumBalance', 
            'driverIncomeToday', 
            'driverIncomeMonth', 
            'driverCompletedOrders', 
            'driverCommissionTotal',
            'driverAverageRating',
            'driverTotalReviews',
            'driverLatestReviews',
            ));
    }

public function history()
{
    $driver = Driver::where('user_id', Auth::id())->firstOrFail();

    $orders = Order::where('driver_id', $driver->id)
        ->whereIn('status', ['completed', 'cancelled'])
        ->latest()
        ->get();

    return view('driver.history', compact('driver', 'orders'));
}

    public function setStatus($status)
{
    $driver = Driver::where('user_id', Auth::id())->first();

    if (!$driver) {
        return redirect('/driver')
            ->with('error', 'Data driver tidak ditemukan.');
    }

    if (!in_array($status, ['online', 'offline'])) {
        return redirect('/driver')
            ->with('error', 'Status driver tidak valid.');
    }

    if (
        $status == 'online' &&
        $driver->penalty_until &&
        now()->lt($driver->penalty_until)
    ) {
        return redirect('/driver')
            ->with('error', 'Akun driver terkena penalti sampai ' . $driver->penalty_until);
    }

    $setting = \App\Models\AppSetting::first();

$minimumBalance = $setting->driver_min_balance ?? 0;

if (
    $status == 'online' &&
    ($driver->balance ?? 0) < $minimumBalance
) {
    return redirect('/driver')
        ->with(
            'error',
            'Saldo driver kurang dari minimum Rp '.number_format($minimumBalance)
        );
}

    if ($status == 'online') {

        $setting = \App\Models\AppSetting::first();

        $minBalance = $setting->driver_min_balance ?? 0;
        $driverBalance = $driver->balance ?? 0;

        if ($driverBalance < $minBalance) {
            return redirect('/driver')
                ->with(
                    'error',
                    'Saldo tidak mencukupi. Minimal saldo untuk online Rp ' . number_format($minBalance)
                );
        }
    }

    $driver->update([
        'status' => $status,
    ]);

    return redirect('/driver')
        ->with(
            'success',
            $status == 'online'
                ? 'Driver berhasil online.'
                : 'Driver berhasil offline.'
        );
}

public function activeLocations()
{
    $drivers = Driver::with(['user', 'activeVehicles'])
        ->whereIn('status', ['online', 'busy'])
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();

    return response()->json([
        'drivers' => $drivers->flatMap(function ($driver) {
            return $driver->activeVehicles->map(function ($vehicle) use ($driver) {
                return [
                    'id' => $driver->id . '-' . $vehicle->id,
                    'driver_id' => $driver->id,
                    'name' => $driver->user->name ?? 'Driver',
                    'vehicle_type' => strtolower($vehicle->vehicle_type),
                    'plate_number' => strtoupper($vehicle->plate_number),
                    'latitude' => $driver->latitude,
                    'longitude' => $driver->longitude,
                ];
            });
        })->values(),
    ]);
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
    $driver = Driver::where('user_id', Auth::id())->firstOrFail();

    $allowedStatuses = [
        'driver_to_merchant',
        'dalam_pengiriman',
        'driver_to_pickup',
        'driver_to_destination',
        'completed'
    ];

    if (!in_array($status, $allowedStatuses)) {
        return redirect('/driver')
            ->with('error', 'Status tidak valid.');
    }

    $order = Order::with(['items.food'])
        ->where('id', $id)
        ->where('driver_id', $driver->id)
        ->firstOrFail();

    if ($status != 'completed') {
        $order->update([
            'status' => $status
        ]);

        return redirect('/driver')
            ->with('success', 'Status order diperbarui.');
    }

    DB::transaction(function () use ($order, $driver, $status) {

        $setting = AppSetting::first();
        $orderType = $order->order_type ?? 'food';

        $commissionAmount = 0;

        if ($orderType == 'car') {

            $commissionPercent = $setting->car_driver_commission_percent ?? 0;
            $baseAmount = $order->total ?? 0;

            $commissionAmount = round($baseAmount * ($commissionPercent / 100));

        } elseif ($orderType == 'ojek') {

            $commissionPercent = $setting->ride_driver_commission_percent ?? 0;
            $baseAmount = $order->total ?? 0;

            $commissionAmount = round($baseAmount * ($commissionPercent / 100));

        } else {

            /*
            |--------------------------------------------------------------------------
            | FOOD
            |--------------------------------------------------------------------------
            | Driver pegang uang makanan markup + ongkir.
            | Jadi saldo driver dipotong:
            | markup food + komisi ongkir.
            */

            $foodOriginalTotal = 0;
            $foodSellingTotal = 0;

            foreach ($order->items as $item) {
                $qty = $item->qty ?? 1;

                // Harga asli merchant dari tabel foods
                $originalPrice = $item->food->price ?? $item->price;

                // Harga jual customer dari order_items
                $sellingPrice = $item->price ?? $originalPrice;

                $foodOriginalTotal += $originalPrice * $qty;
                $foodSellingTotal += $sellingPrice * $qty;
            }

            $foodMarkupAmount = max(0, $foodSellingTotal - $foodOriginalTotal);

            $deliveryCommissionPercent = $setting->food_driver_commission_percent ?? 0;

            $deliveryCommissionAmount = round(
                ($order->delivery_fee ?? 0) * ($deliveryCommissionPercent / 100)
            );

            $commissionAmount = $foodMarkupAmount + $deliveryCommissionAmount;

            $order->update([
                'food_original_total' => $foodOriginalTotal,
                'food_markup_amount' => $foodMarkupAmount,
                'delivery_commission_amount' => $deliveryCommissionAmount,
                'admin_commission_amount' => $commissionAmount,
            ]);
        }

        $freshDriver = Driver::where('id', $driver->id)
            ->lockForUpdate()
            ->first();

        $before = (float) ($freshDriver->balance ?? 0);
        $after = $before - $commissionAmount;

        $order->update([
            'status' => $status
        ]);

        $minimumBalance = $setting->driver_min_balance ?? 0;

        $freshDriver->update([
            'balance' => $after,
            'status' => $after >= $minimumBalance ? 'online' : 'offline',
        ]);

        if ($commissionAmount > 0) {
            DriverWalletTransaction::create([
                'driver_id' => $freshDriver->id,
                'type' => 'commission',
                'amount' => -$commissionAmount,
                'balance_before' => $before,
                'balance_after' => $after,
                'order_id' => $order->id,
                'description' => 'Komisi admin ' . strtoupper($orderType) . ' order ' . ($order->order_number ?? '#'.$order->id),
                'created_by' => null,
            ]);
        }
    });

    return redirect('/driver')
        ->with('success', 'Order selesai. Komisi admin berhasil dipotong dari saldo driver.');
}
public function acceptOrder($id)
{
    $driver = Driver::where('user_id', Auth::id())->firstOrFail();

    $order = Order::where('id', $id)
        ->where(function($q) use ($driver) {
            $q->where('driver_id', $driver->id)
              ->orWhereNull('driver_id');
        })
        ->firstOrFail();

    if ($order->status == 'cancelled') {
        return redirect('/driver')->with('error', 'Pesanan sudah dibatalkan.');
    }

    if (in_array($order->order_type, ['ojek', 'car'])) {
        $nextStatus = 'driver_to_pickup';
    } else {
        $nextStatus = 'driver_to_merchant';
    }

    $order->update([
        'driver_id' => $driver->id,
        'driver_status' => 'accepted',
        'merchant_status' => 'accepted',
        'status' => $nextStatus,
    ]);

    $driver->update([
        'status' => 'busy',
    ]);

    return redirect('/driver')->with('success', 'Pesanan diterima.');
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


  public function settings()
{
    $driver = Driver::where('user_id', auth()->id())
        ->with('vehicles')
        ->firstOrFail();

    return view('driver.settings', compact('driver'));
}

public function updateSettings(Request $request)
{
    $driver = Driver::where('user_id', Auth::id())->firstOrFail();
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:30',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'vehicle_type' => 'nullable|string|max:50',
        'plate_number' => 'nullable|string|max:50',
        'vehicle_brand' => 'nullable|string|max:100',
        'vehicle_color' => 'nullable|string|max:100',
        'latitude' => 'nullable',
        'longitude' => 'nullable',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;

    if ($request->hasFile('photo')) {
        $user->photo = $request->file('photo')->store('profiles', 'public');
    }

    $user->save();

    $driver->vehicle_type = $request->vehicle_type;
    $driver->plate_number = $request->plate_number;

    if (\Illuminate\Support\Facades\Schema::hasColumn('drivers', 'vehicle_brand')) {
        $driver->vehicle_brand = $request->vehicle_brand;
    }

    if (\Illuminate\Support\Facades\Schema::hasColumn('drivers', 'vehicle_color')) {
        $driver->vehicle_color = $request->vehicle_color;
    }

    if ($request->latitude && $request->longitude) {
        $driver->latitude = $request->latitude;
        $driver->longitude = $request->longitude;
        $driver->last_location_update = now();
    }

    $driver->save();

    return redirect('/driver/settings')
        ->with('success', 'Setting driver berhasil diperbarui.');
}

    public function notifCount()
    {
        $driver = Driver::where('user_id', Auth::id())->first();

        if (!$driver) {
            return response()->json([
                'count' => 0,
            ]);
        }

        $count = Order::where(function($q) use ($driver){

        $q->where('driver_id', $driver->id)
          ->where('driver_status', 'pending')

          ->orWhere(function($x){

                $x->whereNull('driver_id')
                  ->where('status', 'searching_driver');

          });

    })
    ->count();

        return response()->json([
            'count' => $count,
        ]);
    }
    public function addVehicle(Request $request)
{
    $driver = Driver::where('user_id', Auth::id())->firstOrFail();

    $request->validate([
        'vehicle_type' => 'required|in:motor,mobil',
        'plate_prefix' => 'required|string|max:2',
        'plate_number_middle' => 'required|string|max:4',
        'plate_suffix' => 'required|string|max:3',
        'vehicle_brand' => 'nullable|string|max:100',
        'vehicle_color' => 'nullable|string|max:100',
    ]);

    $plateNumber = strtoupper(
        $request->plate_prefix . ' ' .
        $request->plate_number_middle . ' ' .
        $request->plate_suffix
    );

    DriverVehicle::create([
        'driver_id' => $driver->id,
        'vehicle_type' => $request->vehicle_type,
        'plate_number' => $plateNumber,
        'vehicle_brand' => $request->vehicle_brand,
        'vehicle_color' => $request->vehicle_color,
        'is_active' => false,
    ]);

    return redirect('/driver/settings')
        ->with('success', 'Kendaraan berhasil ditambahkan.');
}

public function setActiveVehicle($id)
{
    $driver = Driver::where('user_id', Auth::id())->firstOrFail();

    $vehicle = DriverVehicle::where('driver_id', $driver->id)
        ->where('id', $id)
        ->firstOrFail();

    DriverVehicle::where('driver_id', $driver->id)
        ->where('vehicle_type', $vehicle->vehicle_type)
        ->update(['is_active' => false]);

    $vehicle->update(['is_active' => true]);

    return redirect('/driver/settings')
        ->with('success', strtoupper($vehicle->vehicle_type) . ' aktif berhasil diganti.');
}

public function deleteVehicle($id)
{
    $driver = Driver::where('user_id', Auth::id())->firstOrFail();

    $vehicle = DriverVehicle::where('driver_id', $driver->id)
        ->where('id', $id)
        ->firstOrFail();

    $vehicle->delete();

    return redirect('/driver/settings')
        ->with('success', 'Kendaraan berhasil dihapus.');
}

public function walletHistory()
{
    $driver = Driver::where('user_id', Auth::id())
        ->firstOrFail();

    $transactions = DriverWalletTransaction::where('driver_id', $driver->id)
        ->latest()
        ->get();

    return view('driver.wallet-history', compact(
        'driver',
        'transactions'
    ));
}

public function income()
{
    $driver = Driver::where('user_id', Auth::id())
        ->firstOrFail();

    $orders = Order::with([
            'user',
            'restaurant',
            'items.food',
            'commissionTransaction',
        ])
        ->where('driver_id', $driver->id)
        ->where('status', 'completed')
        ->latest()
        ->get();

    $todayIncome = $orders
        ->where('updated_at', '>=', now()->startOfDay())
        ->sum(function($order){
            $cash = ($order->grand_total ?? 0) > 0
                ? $order->grand_total
                : ($order->total ?? 0);

            $commission = $order->commissionTransaction
                ? abs($order->commissionTransaction->amount)
                : ($order->admin_commission_amount ?? 0);

            return $cash - $commission;
        });

    $monthIncome = $orders
        ->where('updated_at', '>=', now()->startOfMonth())
        ->sum(function($order){
            $cash = ($order->grand_total ?? 0) > 0
                ? $order->grand_total
                : ($order->total ?? 0);

            $commission = $order->commissionTransaction
                ? abs($order->commissionTransaction->amount)
                : ($order->admin_commission_amount ?? 0);

            return $cash - $commission;
        });

    $totalCommission = $orders->sum(function($order){
        return $order->commissionTransaction
            ? abs($order->commissionTransaction->amount)
            : ($order->admin_commission_amount ?? 0);
    });

    return view('driver.income', compact(
        'driver',
        'orders',
        'todayIncome',
        'monthIncome',
        'totalCommission'
    ));
}

}