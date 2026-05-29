<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Restaurant;
use App\Models\Driver;
use App\Models\AppSetting;

class HomeController extends Controller
{
    public function activeDrivers()
{
    $setting = AppSetting::first();

    $customer = auth()->user();

    $radius = $setting->customer_driver_radius ?? 5;

    $drivers = Driver::with(['user', 'activeVehicles'])
        ->where('status', 'online')
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();

    $drivers = $drivers->flatMap(function ($driver) use ($customer) {

        $distanceKm = null;

        if (
            $customer &&
            $customer->latitude &&
            $customer->longitude &&
            $driver->latitude &&
            $driver->longitude
        ) {
            $distanceKm = $this->calculateDistance(
                $customer->latitude,
                $customer->longitude,
                $driver->latitude,
                $driver->longitude
            );
        }

        return $driver->activeVehicles->map(function ($vehicle) use ($driver, $distanceKm) {
            return [
                'id' => $driver->id . '-' . $vehicle->id,
                'driver_id' => $driver->id,
                'name' => $driver->user->name ?? 'Driver',

                'latitude' => $driver->latitude,
                'longitude' => $driver->longitude,

                'vehicle_type' => strtolower($vehicle->vehicle_type),
                'plate_number' => strtoupper($vehicle->plate_number),

                'distance' => $distanceKm ? round($distanceKm, 1) : null,
            ];
        });

    })
    ->filter(function ($driver) use ($radius) {
        return $driver['distance'] === null || $driver['distance'] <= $radius;
    })
    ->values();

    return response()->json([
        'icon' => $setting && $setting->driver_map_icon
            ? asset('storage/' . $setting->driver_map_icon)
            : null,

        'radius' => $radius,

        'drivers' => $drivers,
    ]);
}

    public function index()
    {
        $setting = AppSetting::first();
        $customer = auth()->user();

        $merchantRadius = $setting->merchant_radius ?? 20;

        $foods = Food::with('restaurant')
            ->latest()
            ->get();

        $merchants = $foods
            ->pluck('restaurant')
            ->filter()
            ->unique('id')
            ->values();

        foreach ($merchants as $merchant) {

            $distanceKm = null;

            if (
                $customer &&
                $customer->latitude &&
                $customer->longitude &&
                $merchant->latitude &&
                $merchant->longitude
            ) {
                $distanceKm = $this->calculateDistance(
                    $customer->latitude,
                    $customer->longitude,
                    $merchant->latitude,
                    $merchant->longitude
                );
            }

            $merchant->distance_km = $distanceKm ? round($distanceKm, 1) : 0;
        }

        $merchants = $merchants
            ->filter(function ($merchant) use ($merchantRadius) {
                return $merchant->distance_km <= $merchantRadius;
            })
            ->sortBy('distance_km')
            ->values();

        return view('home', compact('foods', 'merchants'));
    }

    public function merchantFoods($id)
    {
        $merchant = Restaurant::findOrFail($id);

        $foods = Food::with('restaurant')
            ->where('restaurant_id', $id)
            ->latest()
            ->get();

        return view('merchant-foods', compact(
            'merchant',
            'foods'
        ));
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
}