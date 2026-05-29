<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;

class AdminAppAppearanceController extends Controller
{
    public function edit()
    {
        $setting = AppSetting::first();

        if (!$setting) {
            $setting = AppSetting::create([
                'app_name' => 'JavaJek'
            ]);
        }

        return view('admin.app-appearance', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = AppSetting::first();

        if (!$setting) {
            $setting = AppSetting::create([
                'app_name' => 'JavaJek'
            ]);
        }

        $data = [
            'app_name' => $request->app_name,
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'maintenance_mode' => $request->has('maintenance_mode'),
            'customer_driver_radius'
    => $request->customer_driver_radius,

'ride_search_radius'
    => $request->ride_search_radius,

'merchant_radius'
    => $request->merchant_radius,
    
            ];

        foreach([
            'login_logo',
            'customer_logo',
            'driver_logo',
            'merchant_logo',
            'driver_map_icon',
            'home_banner'
        ] as $field){

            if($request->hasFile($field)){
                $data[$field] = $request
                    ->file($field)
                    ->store('app-settings','public');
            }
        }

        $setting->update($data);

        return back()->with(
            'success',
            'Tampilan aplikasi berhasil disimpan.'
        );
    }
}