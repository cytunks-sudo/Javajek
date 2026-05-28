<?php

namespace App\Http\Controllers;

use App\Models\DeliverySetting;
use Illuminate\Http\Request;

class AdminDeliverySettingController extends Controller
{
    public function edit()
    {
        $setting = DeliverySetting::first();

        return view('admin.delivery-setting', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'base_fee' => 'required|integer|min:0',
            'per_km_fee' => 'required|integer|min:0',
            'minimum_fee' => 'required|integer|min:0',
            'max_driver_radius_km' => 'required|integer|min:1',
        ]);

        $setting = DeliverySetting::first();

        $setting->update([
            'base_fee' => $request->base_fee,
            'per_km_fee' => $request->per_km_fee,
            'minimum_fee' => $request->minimum_fee,
            'max_driver_radius_km' => $request->max_driver_radius_km,
        ]);

        return redirect('/admin/delivery-setting')
            ->with('success', 'Pengaturan ongkir berhasil disimpan.');
    }
}