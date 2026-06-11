<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Food;
use App\Models\DeliverySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\RideSetting;
use App\Models\OrderRating;
use App\Models\Voucher;
use App\Models\VoucherUsage;

class OrderController extends Controller
{
    public function show($id)
    {
        $order = Order::findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function driverLocation($id)
    {
        $order = Order::with('driver')->findOrFail($id);

        if (!$order->driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver belum tersedia.'
            ]);
        }

        return response()->json([
            'success' => true,
            'latitude' => $order->driver->latitude,
            'longitude' => $order->driver->longitude,
            'status' => $order->status,
            'driver_name' => $order->driver->user->name ?? 'Driver'
        ]);
    }

    public function checkoutPage()
    {
        $cart = session('cart', []);

        return view('cart.checkout', compact('cart'));
    }

    public function calculateCheckout(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'address' => 'required',
            'voucher_code' => 'nullable|string',
        ]);

        $cart = session('cart', []);

        if (count($cart) < 1) {
            return redirect('/cart');
        }

        $checkout = $this->makeCheckoutSummary(
            $cart,
            $request->latitude,
            $request->longitude,
            $request->address,
            $request->voucher_code
        );

        session(['checkout_data' => $checkout]);

        $completedOrder = Order::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->count();

        $availableVouchers = Voucher::where('is_active', 1)
            ->where(function ($q) use ($completedOrder) {
                if ($completedOrder > 0) {
                    $q->where('is_new_user_only', 0);
                }
            })
            ->where(function ($q) {
                $today = now()->toDateString();

                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) {
                $today = now()->toDateString();

                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $today);
            })
            ->where(function ($q) {
                $q->where('quota', 0)
                    ->orWhereColumn('used_count', '<', 'quota');
            })
            ->where(function ($q) {
                $q->where('service_type', 'all')
                    ->orWhere('service_type', 'food');
            })
            ->latest()
            ->get();

        return view('cart.checkout-result', [
            'address' => $checkout['address'],
            'latitude' => $checkout['latitude'],
            'longitude' => $checkout['longitude'],
            'subtotalProduk' => $checkout['subtotal_produk'],
            'totalOngkir' => $checkout['total_ongkir'],
            'grandTotal' => $checkout['grand_total'],
            'merchantSummaries' => $checkout['merchant_summaries'],
            'voucherCode' => $checkout['voucher_code'],
            'voucherDiscount' => $checkout['voucher_discount'],
            'grandTotalBefore' => $checkout['grand_total_before'],
            'voucherMessage' => $checkout['voucher_message'],
            'availableVouchers' => $availableVouchers,
        ]);
    }

    public function storeOrder()
    {
        $cart = session('cart', []);
        $checkout = session('checkout_data');

        if (!$checkout || count($cart) < 1) {
            return redirect('/cart');
        }

        $firstOrderId = null;

        $grandTotalBefore = $checkout['grand_total_before'] ?? 0;
        $totalVoucherDiscount = $checkout['voucher_discount'] ?? 0;

        foreach ($checkout['merchant_summaries'] as $summary) {
            $merchantTotalBefore = $summary['merchant_total'] ?? 0;

            $merchantVoucherDiscount = 0;

            if ($grandTotalBefore > 0 && $totalVoucherDiscount > 0) {
                $merchantVoucherDiscount = round(
                    $totalVoucherDiscount * ($merchantTotalBefore / $grandTotalBefore)
                );
            }

            $merchantFinalTotal = max(0, $merchantTotalBefore - $merchantVoucherDiscount);

            $order = new Order();

            $order->order_number = Order::generateOrderNumber('food');
            $order->user_id = Auth::id();

            if (Schema::hasColumn('orders', 'order_type')) {
                $order->order_type = 'food';
            }

            if (Schema::hasColumn('orders', 'restaurant_id')) {
                $order->restaurant_id = $summary['restaurant_id'];
            }

            if (Schema::hasColumn('orders', 'driver_id')) {
                $order->driver_id = null;
            }

            if (Schema::hasColumn('orders', 'total')) {
                $order->total = $merchantFinalTotal;
            }

            if (Schema::hasColumn('orders', 'grand_total')) {
                $order->grand_total = $merchantFinalTotal;
            }

            if (Schema::hasColumn('orders', 'voucher_id')) {
                $order->voucher_id = $checkout['voucher_id'] ?? null;
            }

            if (Schema::hasColumn('orders', 'voucher_code')) {
                $order->voucher_code = $checkout['voucher_code'] ?? null;
            }

            if (Schema::hasColumn('orders', 'voucher_discount')) {
                $order->voucher_discount = $merchantVoucherDiscount;
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

            if (!$firstOrderId) {
                $firstOrderId = $order->id;
            }

            foreach ($summary['items'] as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'food_id' => $item['id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                ]);
            }
        }

        if (!empty($checkout['voucher_id']) && ($checkout['voucher_discount'] ?? 0) > 0) {
            VoucherUsage::create([
                'voucher_id' => $checkout['voucher_id'],
                'user_id' => auth()->id(),
                'order_id' => $firstOrderId,
                'voucher_code' => $checkout['voucher_code'],
                'service_type' => 'food',
                'discount_amount' => $checkout['voucher_discount'],
            ]);

            Voucher::where('id', $checkout['voucher_id'])->increment('used_count');
        }

        session()->forget('cart');
        session()->forget('checkout_data');

        return redirect('/my-orders');
    }

    private function makeCheckoutSummary($cart, $customerLat, $customerLng, $address, $voucherCode = null)
    {
        $setting = DeliverySetting::first();

        $baseFee = $setting->base_fee ?? 3000;
        $perKmFee = $setting->per_km_fee ?? 2000;
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
                'restaurant_id' => $restaurant->id,
                'restaurant' => $restaurant->name,
                'items' => $items,
                'subtotal' => $merchantSubtotal,
                'distance_km' => round($distanceKm, 1),
                'delivery_fee' => $deliveryFee,
                'merchant_total' => $merchantSubtotal + $deliveryFee,
                'merchant_latitude' => $restaurant->latitude,
                'merchant_longitude' => $restaurant->longitude,
            ];
        }

        $grandTotalBefore = $subtotalProduk + $totalOngkir;

        $voucherDiscount = 0;
        $voucherId = null;
        $voucherCodeFinal = null;
        $voucherMessage = null;

        if (!empty($voucherCode)) {
            $voucher = Voucher::where('code', strtoupper(trim($voucherCode)))
                ->where('is_active', 1)
                ->first();

            if (!$voucher) {
                $voucherMessage = 'Voucher tidak ditemukan atau tidak aktif.';
            } else {
                $completedOrder = Order::where('user_id', auth()->id())
                    ->where('status', 'completed')
                    ->count();

                $today = now()->toDateString();

                if (!in_array($voucher->service_type ?? 'all', ['all', 'food'])) {
                    $voucherMessage = 'Voucher tidak berlaku untuk layanan Food.';
                } elseif ($voucher->start_date && $today < $voucher->start_date) {
                    $voucherMessage = 'Voucher belum berlaku.';
                } elseif ($voucher->end_date && $today > $voucher->end_date) {
                    $voucherMessage = 'Voucher sudah expired.';
                } elseif ($voucher->quota > 0 && $voucher->used_count >= $voucher->quota) {
                    $voucherMessage = 'Kuota voucher sudah habis.';
                } elseif ($voucher->is_new_user_only && $completedOrder > 0) {
                    $voucherMessage = 'Voucher ini khusus pengguna baru.';
                } elseif ($grandTotalBefore < $voucher->minimum_order) {
                    $voucherMessage = 'Minimum order voucher belum terpenuhi.';
                } else {
                    if ($voucher->type === 'fixed') {
                        $voucherDiscount = min($voucher->value, $grandTotalBefore);
                    } elseif ($voucher->type === 'percent') {
                        $voucherDiscount = round($grandTotalBefore * ($voucher->value / 100));
                    } elseif ($voucher->type === 'free_delivery') {
                        $voucherDiscount = min($totalOngkir, $grandTotalBefore);
                    }

                    if (($voucher->maximum_discount ?? 0) > 0) {
                        $voucherDiscount = min($voucherDiscount, $voucher->maximum_discount);
                    }

                    $voucherId = $voucher->id;
                    $voucherCodeFinal = $voucher->code;
                    $voucherMessage = 'Voucher berhasil digunakan.';
                }
            }
        }

        $grandTotalAfter = max(0, $grandTotalBefore - $voucherDiscount);

        return [
            'latitude' => $customerLat,
            'longitude' => $customerLng,
            'address' => $address,
            'subtotal_produk' => $subtotalProduk,
            'total_ongkir' => $totalOngkir,
            'grand_total_before' => $grandTotalBefore,
            'voucher_id' => $voucherId,
            'voucher_code' => $voucherCodeFinal,
            'voucher_discount' => $voucherDiscount,
            'voucher_message' => $voucherMessage,
            'grand_total' => $grandTotalAfter,
            'merchant_summaries' => $merchantSummaries,
        ];
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        if ($lat1 === null || $lng1 === null || $lat2 === null || $lng2 === null) {
            return 0;
        }

        $earthRadius = 6371;

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
            'pickup_latitude' => 'required',
            'pickup_longitude' => 'required',
            'destination_latitude' => 'required',
            'destination_longitude' => 'required',
            'pickup_address' => 'required',
            'destination_address' => 'required',
            'voucher_code' => 'nullable|string',
        ]);

        $distanceKm = $this->calculateDistance(
            $request->pickup_latitude,
            $request->pickup_longitude,
            $request->destination_latitude,
            $request->destination_longitude
        );

        $setting = RideSetting::first();

        $baseFee = $setting->base_fee
            ?? $setting->ride_base_fee
            ?? 5000;

        $perKmFee = $setting->per_km_fee
            ?? $setting->ride_per_km_fee
            ?? 2500;

        $minimumFee = $setting->minimum_fee
            ?? $setting->ride_minimum_fee
            ?? 8000;

        $fareBefore = $baseFee + ($distanceKm * $perKmFee);
        $fareBefore = max($minimumFee, $fareBefore);
        $fareBefore = ceil($fareBefore / 500) * 500;

        $voucherResult = $this->applyRideVoucher($fareBefore, $request->voucher_code, 'ojek');

        session([
            'ojek_data' => [
                'pickup_latitude' => $request->pickup_latitude,
                'pickup_longitude' => $request->pickup_longitude,
                'destination_latitude' => $request->destination_latitude,
                'destination_longitude' => $request->destination_longitude,
                'pickup_address' => $request->pickup_address,
                'destination_address' => $request->destination_address,
                'distance_km' => round($distanceKm, 1),
                'fare_before' => $fareBefore,
                'fare' => $voucherResult['final_fare'],
                'voucher_id' => $voucherResult['voucher_id'],
                'voucher_code' => $voucherResult['voucher_code'],
                'voucher_discount' => $voucherResult['voucher_discount'],
            ]
        ]);

        return view('ojek.result', [
            'pickupAddress' => $request->pickup_address,
            'destinationAddress' => $request->destination_address,
            'pickupLatitude' => $request->pickup_latitude,
            'pickupLongitude' => $request->pickup_longitude,
            'destinationLatitude' => $request->destination_latitude,
            'destinationLongitude' => $request->destination_longitude,
            'distanceKm' => round($distanceKm, 1),
            'fareBefore' => $fareBefore,
            'fare' => $voucherResult['final_fare'],
            'voucherCode' => $voucherResult['voucher_code'],
            'voucherDiscount' => $voucherResult['voucher_discount'],
            'voucherMessage' => $voucherResult['voucher_message'],
            'availableVouchers' => $this->availableRideVouchers('ojek'),
        ]);
    }

    public function storeOjekOrder()
    {
        $ojek = session('ojek_data');

        if (!$ojek) {
            return redirect('/ojek');
        }

        $finalFare = $ojek['fare'] ?? 0;

        $order = new Order();

        $order->order_number = Order::generateOrderNumber('ojek');
        $order->user_id = auth()->id();

        if (Schema::hasColumn('orders', 'total')) {
            $order->total = $finalFare;
        }

        if (Schema::hasColumn('orders', 'grand_total')) {
            $order->grand_total = $finalFare;
        }

        $order->status = 'searching_driver';
        $order->merchant_status = 'accepted';

        if (Schema::hasColumn('orders', 'order_type')) {
            $order->order_type = 'ojek';
        }

        if (Schema::hasColumn('orders', 'pickup_latitude')) {
            $order->pickup_latitude = $ojek['pickup_latitude'];
        }

        if (Schema::hasColumn('orders', 'pickup_longitude')) {
            $order->pickup_longitude = $ojek['pickup_longitude'];
        }

        if (Schema::hasColumn('orders', 'destination_latitude')) {
            $order->destination_latitude = $ojek['destination_latitude'];
        }

        if (Schema::hasColumn('orders', 'destination_longitude')) {
            $order->destination_longitude = $ojek['destination_longitude'];
        }

        if (Schema::hasColumn('orders', 'pickup_address')) {
            $order->pickup_address = $ojek['pickup_address'];
        }

        if (Schema::hasColumn('orders', 'destination_address')) {
            $order->destination_address = $ojek['destination_address'];
        }

        if (Schema::hasColumn('orders', 'distance_km')) {
            $order->distance_km = $ojek['distance_km'];
        }

        if (Schema::hasColumn('orders', 'driver_status')) {
            $order->driver_status = 'pending';
        }

        if (Schema::hasColumn('orders', 'driver_reject_count')) {
            $order->driver_reject_count = 0;
        }

        if (Schema::hasColumn('orders', 'voucher_id')) {
            $order->voucher_id = $ojek['voucher_id'] ?? null;
        }

        if (Schema::hasColumn('orders', 'voucher_code')) {
            $order->voucher_code = $ojek['voucher_code'] ?? null;
        }

        if (Schema::hasColumn('orders', 'voucher_discount')) {
            $order->voucher_discount = $ojek['voucher_discount'] ?? 0;
        }

        $order->save();

        if (!empty($ojek['voucher_id']) && ($ojek['voucher_discount'] ?? 0) > 0) {
            VoucherUsage::create([
                'voucher_id' => $ojek['voucher_id'],
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'voucher_code' => $ojek['voucher_code'],
                'service_type' => 'ojek',
                'discount_amount' => $ojek['voucher_discount'],
            ]);

            Voucher::where('id', $ojek['voucher_id'])->increment('used_count');
        }

        session()->forget('ojek_data');

        return redirect('/my-orders');
    }

    public function carPage()
    {
        return view('car.index');
    }

    public function calculateCar(Request $request)
    {
        $request->validate([
            'pickup_latitude' => 'required',
            'pickup_longitude' => 'required',
            'destination_latitude' => 'required',
            'destination_longitude' => 'required',
            'pickup_address' => 'required',
            'destination_address' => 'required',
            'voucher_code' => 'nullable|string',
        ]);

        $distanceKm = $this->calculateDistance(
            $request->pickup_latitude,
            $request->pickup_longitude,
            $request->destination_latitude,
            $request->destination_longitude
        );

        $setting = RideSetting::first();

        $baseFee = $setting->car_base_fee ?? 10000;
        $perKmFee = $setting->car_per_km_fee ?? 4000;
        $minimumFee = $setting->car_minimum_fee ?? 15000;

        $fareBefore = $baseFee + ($distanceKm * $perKmFee);
        $fareBefore = max($minimumFee, $fareBefore);
        $fareBefore = ceil($fareBefore / 500) * 500;

        $voucherResult = $this->applyRideVoucher($fareBefore, $request->voucher_code, 'car');

        session([
            'car_data' => [
                'pickup_latitude' => $request->pickup_latitude,
                'pickup_longitude' => $request->pickup_longitude,
                'destination_latitude' => $request->destination_latitude,
                'destination_longitude' => $request->destination_longitude,
                'pickup_address' => $request->pickup_address,
                'destination_address' => $request->destination_address,
                'distance_km' => round($distanceKm, 1),
                'fare_before' => $fareBefore,
                'fare' => $voucherResult['final_fare'],
                'voucher_id' => $voucherResult['voucher_id'],
                'voucher_code' => $voucherResult['voucher_code'],
                'voucher_discount' => $voucherResult['voucher_discount'],
            ]
        ]);

        return view('car.result', [
            'pickupAddress' => $request->pickup_address,
            'destinationAddress' => $request->destination_address,
            'pickupLatitude' => $request->pickup_latitude,
            'pickupLongitude' => $request->pickup_longitude,
            'destinationLatitude' => $request->destination_latitude,
            'destinationLongitude' => $request->destination_longitude,
            'distanceKm' => round($distanceKm, 1),
            'fareBefore' => $fareBefore,
            'fare' => $voucherResult['final_fare'],
            'voucherCode' => $voucherResult['voucher_code'],
            'voucherDiscount' => $voucherResult['voucher_discount'],
            'voucherMessage' => $voucherResult['voucher_message'],
            'availableVouchers' => $this->availableRideVouchers('car'),
        ]);
    }

    public function storeCarOrder()
    {
        $car = session('car_data');

        if (!$car) {
            return redirect('/car');
        }

        $finalFare = $car['fare'] ?? 0;

        $order = new Order();

        $order->order_number = Order::generateOrderNumber('car');
        $order->user_id = auth()->id();

        if (Schema::hasColumn('orders', 'total')) {
            $order->total = $finalFare;
        }

        if (Schema::hasColumn('orders', 'grand_total')) {
            $order->grand_total = $finalFare;
        }

        $order->status = 'searching_driver';
        $order->merchant_status = 'accepted';
        $order->driver_status = 'pending';
        $order->driver_reject_count = 0;
        $order->order_type = 'car';

        $order->pickup_latitude = $car['pickup_latitude'];
        $order->pickup_longitude = $car['pickup_longitude'];
        $order->destination_latitude = $car['destination_latitude'];
        $order->destination_longitude = $car['destination_longitude'];
        $order->pickup_address = $car['pickup_address'];
        $order->destination_address = $car['destination_address'];
        $order->distance_km = $car['distance_km'];

        if (Schema::hasColumn('orders', 'voucher_id')) {
            $order->voucher_id = $car['voucher_id'] ?? null;
        }

        if (Schema::hasColumn('orders', 'voucher_code')) {
            $order->voucher_code = $car['voucher_code'] ?? null;
        }

        if (Schema::hasColumn('orders', 'voucher_discount')) {
            $order->voucher_discount = $car['voucher_discount'] ?? 0;
        }

        $order->save();

        if (!empty($car['voucher_id']) && ($car['voucher_discount'] ?? 0) > 0) {
            VoucherUsage::create([
                'voucher_id' => $car['voucher_id'],
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'voucher_code' => $car['voucher_code'],
                'service_type' => 'car',
                'discount_amount' => $car['voucher_discount'],
            ]);

            Voucher::where('id', $car['voucher_id'])->increment('used_count');
        }

        session()->forget('car_data');

        return redirect('/my-orders');
    }

    public function storeRating(Request $request, $id)
    {
        $order = Order::with('rating')->findOrFail($id);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'completed') {
            return back()->with('error', 'Rating hanya bisa diberikan setelah order selesai.');
        }

        if ($order->rating) {
            return back()->with('error', 'Order ini sudah pernah diberi rating.');
        }

        $rules = [
            'driver_rating' => 'nullable|integer|min:1|max:5',
            'driver_review' => 'nullable|string|max:1000',
            'merchant_rating' => 'nullable|integer|min:1|max:5',
            'merchant_review' => 'nullable|string|max:1000',
        ];

        $request->validate($rules);

        OrderRating::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'driver_id' => $order->driver_id,
            'restaurant_id' => $order->restaurant_id,
            'driver_rating' => $request->driver_rating,
            'driver_review' => $request->driver_review,
            'merchant_rating' => $order->restaurant_id ? $request->merchant_rating : null,
            'merchant_review' => $order->restaurant_id ? $request->merchant_review : null,
        ]);

        return back()->with('success', 'Terima kasih, rating berhasil disimpan.');
    }

    private function availableRideVouchers($serviceType)
    {
        $completedOrder = Order::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->count();

        return Voucher::where('is_active', 1)
            ->where(function ($q) use ($serviceType) {
                $q->where('service_type', 'all')
                    ->orWhere('service_type', $serviceType);
            })
            ->where(function ($q) use ($completedOrder) {
                if ($completedOrder > 0) {
                    $q->where('is_new_user_only', 0);
                }
            })
            ->where(function ($q) {
                $today = now()->toDateString();

                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', $today);
            })
            ->where(function ($q) {
                $today = now()->toDateString();

                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $today);
            })
            ->where(function ($q) {
                $q->where('quota', 0)
                    ->orWhereColumn('used_count', '<', 'quota');
            })
            ->latest()
            ->get();
    }

    private function applyRideVoucher($fare, $voucherCode, $serviceType)
    {
        $result = [
            'voucher_id' => null,
            'voucher_code' => null,
            'voucher_discount' => 0,
            'voucher_message' => null,
            'final_fare' => $fare,
        ];

        if (empty($voucherCode)) {
            return $result;
        }

        $voucher = Voucher::where('code', strtoupper(trim($voucherCode)))
            ->where('is_active', 1)
            ->first();

        if (!$voucher) {
            $result['voucher_message'] = 'Voucher tidak ditemukan atau tidak aktif.';
            return $result;
        }

        if (!in_array($voucher->service_type ?? 'all', ['all', $serviceType])) {
            $result['voucher_message'] = 'Voucher tidak berlaku untuk layanan ini.';
            return $result;
        }

        if ($voucher->type === 'free_delivery') {
            $result['voucher_message'] = 'Voucher gratis ongkir hanya berlaku untuk pesanan makanan.';
            return $result;
        }

        $completedOrder = Order::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->count();

        $today = now()->toDateString();

        if ($voucher->start_date && $today < $voucher->start_date) {
            $result['voucher_message'] = 'Voucher belum berlaku.';
            return $result;
        }

        if ($voucher->end_date && $today > $voucher->end_date) {
            $result['voucher_message'] = 'Voucher sudah expired.';
            return $result;
        }

        if ($voucher->quota > 0 && $voucher->used_count >= $voucher->quota) {
            $result['voucher_message'] = 'Kuota voucher sudah habis.';
            return $result;
        }

        if ($voucher->is_new_user_only && $completedOrder > 0) {
            $result['voucher_message'] = 'Voucher ini khusus pengguna baru.';
            return $result;
        }

        if ($fare < $voucher->minimum_order) {
            $result['voucher_message'] = 'Minimum order voucher belum terpenuhi.';
            return $result;
        }

        if ($voucher->type === 'fixed') {
            $discount = min($voucher->value, $fare);
        } elseif ($voucher->type === 'percent') {
            $discount = round($fare * ($voucher->value / 100));
        } else {
            $discount = 0;
        }

        if (($voucher->maximum_discount ?? 0) > 0) {
            $discount = min($discount, $voucher->maximum_discount);
        }

        $result['voucher_id'] = $voucher->id;
        $result['voucher_code'] = $voucher->code;
        $result['voucher_discount'] = $discount;
        $result['voucher_message'] = 'Voucher berhasil digunakan.';
        $result['final_fare'] = max(0, $fare - $discount);

        return $result;
    }
}