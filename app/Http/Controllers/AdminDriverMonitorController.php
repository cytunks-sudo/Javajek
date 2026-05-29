<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\AppSetting;

class AdminDriverMonitorController extends Controller
{
    public function index()
    {
        return view('admin.driver-monitor.index');
    }

    public function data()
{
    $drivers = Driver::with(['user', 'activeVehicles'])
        ->whereIn('status', ['online', 'busy'])
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();

    return response()->json([
        'drivers' => $drivers->flatMap(function ($driver) {

            if ($driver->activeVehicles->count() > 0) {
                return $driver->activeVehicles->map(function ($vehicle) use ($driver) {
                    return [
                        'id' => $driver->id . '-' . $vehicle->id,
                        'driver_id' => $driver->id,
                        'name' => $driver->user->name ?? 'Driver',
                        'phone' => $driver->user->phone ?? '-',
                        'vehicle_type' => $vehicle->vehicle_type,
                        'plate_number' => $vehicle->plate_number,
                        'vehicle_brand' => $vehicle->vehicle_brand,
                        'vehicle_color' => $vehicle->vehicle_color,
                        'latitude' => $driver->latitude,
                        'longitude' => $driver->longitude,
                        'status' => $driver->status,
                        'updated_at' => $driver->last_location_update
                            ? date('d M Y H:i:s', strtotime($driver->last_location_update))
                            : '-',
                    ];
                });
            }

            return [[
                'id' => $driver->id,
                'driver_id' => $driver->id,
                'name' => $driver->user->name ?? 'Driver',
                'phone' => $driver->user->phone ?? '-',
                'vehicle_type' => $driver->vehicle_type ?? 'motor',
                'plate_number' => $driver->plate_number ?? '-',
                'latitude' => $driver->latitude,
                'longitude' => $driver->longitude,
                'status' => $driver->status,
                'updated_at' => $driver->last_location_update
                    ? date('d M Y H:i:s', strtotime($driver->last_location_update))
                    : '-',
            ]];
        })->values(),
    ]);
}
}