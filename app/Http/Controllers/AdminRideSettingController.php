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
            'base_fee' => 'required|numeric|min:0',
            'per_km_fee' => 'required|numeric|min:0',
            'minimum_fee' => 'required|numeric|min:0',
        ]);

        $setting = RideSetting::first();

        if (!$setting) {
            $setting = RideSetting::create($request->only([
                'base_fee',
                'per_km_fee',
                'minimum_fee',
            ]));
        } else {
            $setting->update($request->only([
                'base_fee',
                'per_km_fee',
                'minimum_fee',
            ]));
        }

        return back()->with('success', 'Setting tarif ojek berhasil diperbarui.');
    }
}