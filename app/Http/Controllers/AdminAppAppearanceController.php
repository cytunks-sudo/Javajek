<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AdminAppAppearanceController extends Controller
{
    public function edit()
    {
        $setting = AppSetting::first();

        if (!$setting) {
            $setting = AppSetting::create([
                'app_name' => 'JavaJek',
            ]);
        }

        return view('admin.app-appearance', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',

            'customer_driver_radius' => 'nullable|numeric|min:0',
            'ride_search_radius' => 'nullable|numeric|min:0',
            'merchant_radius' => 'nullable|numeric|min:0',

            'driver_min_balance' => 'nullable|numeric|min:0',
            'food_price_markup_percent' => 'nullable|numeric|min:0',
            'food_driver_commission_percent' => 'nullable|numeric|min:0',
            'ride_driver_commission_percent' => 'nullable|numeric|min:0',
            'car_driver_commission_percent' => 'nullable|numeric|min:0',

            'login_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'customer_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'driver_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'merchant_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'favicon' => 'nullable|file|mimes:jpg,jpeg,png,webp,ico|max:2048',
            'driver_map_icon' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'home_banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $setting = AppSetting::first();

        if (!$setting) {
            $setting = AppSetting::create([
                'app_name' => 'JavaJek',
            ]);
        }

        $data = [];

        if (Schema::hasColumn('app_settings', 'app_name')) {
            $data['app_name'] = $request->app_name ?? 'JavaJek';
        }

        if (Schema::hasColumn('app_settings', 'primary_color')) {
            $data['primary_color'] = $request->primary_color ?? '#f97316';
        }

        if (Schema::hasColumn('app_settings', 'secondary_color')) {
            $data['secondary_color'] = $request->secondary_color ?? '#fb923c';
        }

        if (Schema::hasColumn('app_settings', 'maintenance_mode')) {
            $data['maintenance_mode'] = $request->has('maintenance_mode') ? 1 : 0;
        }

        if (Schema::hasColumn('app_settings', 'customer_driver_radius')) {
            $data['customer_driver_radius'] = $request->customer_driver_radius ?? 5;
        }

        if (Schema::hasColumn('app_settings', 'ride_search_radius')) {
            $data['ride_search_radius'] = $request->ride_search_radius ?? 10;
        }

        if (Schema::hasColumn('app_settings', 'merchant_radius')) {
            $data['merchant_radius'] = $request->merchant_radius ?? 20;
        }

        if (Schema::hasColumn('app_settings', 'driver_min_balance')) {
            $data['driver_min_balance'] = $request->driver_min_balance ?? 0;
        }

        if (Schema::hasColumn('app_settings', 'food_price_markup_percent')) {
            $data['food_price_markup_percent'] = $request->food_price_markup_percent ?? 0;
        }

        if (Schema::hasColumn('app_settings', 'food_driver_commission_percent')) {
            $data['food_driver_commission_percent'] = $request->food_driver_commission_percent ?? 0;
        }

        if (Schema::hasColumn('app_settings', 'ride_driver_commission_percent')) {
            $data['ride_driver_commission_percent'] = $request->ride_driver_commission_percent ?? 0;
        }

        if (Schema::hasColumn('app_settings', 'car_driver_commission_percent')) {
            $data['car_driver_commission_percent'] = $request->car_driver_commission_percent ?? 0;
        }

foreach ([
    'login_logo',
    'customer_logo',
    'driver_logo',
    'merchant_logo',
    'favicon',
    'driver_map_icon',
    'home_banner',
] as $field) {
    if (
        Schema::hasColumn('app_settings', $field) &&
        $request->hasFile($field)
    ) {
        $data[$field] = $request
            ->file($field)
            ->store('app-settings', 'public');
    }
}

        $setting->update($data);

        return back()->with('success', 'Tampilan aplikasi berhasil disimpan.');
    }
}