<?php

namespace App\Http\Controllers;

use App\Models\RideSetting;
use Illuminate\Http\Request;

class AdminRideSettingController extends Controller
{
    public function edit()
    {
        $setting = RideSetting::first();

        return view('admin.ride-setting', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
    'base_fee' => 'required|numeric',
    'per_km_fee' => 'required|numeric',
    'minimum_fee' => 'required|numeric',

    'car_base_fee' => 'required|numeric',
    'car_per_km_fee' => 'required|numeric',
    'car_minimum_fee' => 'required|numeric',
]);

        $setting = RideSetting::first();

        if (!$setting) {
            $setting = RideSetting::create($request->only([
                'base_fee',
                'per_km_fee',
                'minimum_fee',
                'car_base_fee',
                'car_per_km_fee',
                'car_minimum_fee',
            ]));
        } else {
            $setting->update($request->only([
                'base_fee',
                'per_km_fee',
                'minimum_fee',
                'car_base_fee',
                'car_per_km_fee',
                'car_minimum_fee',
            ]));
        }

        return back()->with('success', 'Setting tarif ojek berhasil diperbarui.');
    }
}