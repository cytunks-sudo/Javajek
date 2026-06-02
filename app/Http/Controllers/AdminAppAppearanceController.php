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

        foreach ([
            'login_logo',
            'customer_logo',
            'driver_logo',
            'merchant_logo',
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

        return back()->with(
            'success',
            'Tampilan aplikasi berhasil disimpan.'
        );
    }
}