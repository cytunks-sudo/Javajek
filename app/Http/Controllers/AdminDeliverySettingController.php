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
            'driver_icon' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'login_logo' => 'nullable|image|mimes:png,jpg,jpeg,webp,gif|max:4096',
        
            ]);

        $setting = DeliverySetting::first();

        $data = [
            'base_fee' => $request->base_fee,
            'per_km_fee' => $request->per_km_fee,
            'minimum_fee' => $request->minimum_fee,
            'max_driver_radius_km' => $request->max_driver_radius_km,
        ];
        if ($request->hasFile('login_logo')) {
    $data['login_logo'] = $request->file('login_logo')->store('site-logos', 'public');
}

        if ($request->hasFile('driver_icon')) {
            $data['driver_icon'] = $request->file('driver_icon')->store('driver-icons', 'public');
        }

        $setting->update($data);

        return redirect('/admin/delivery-setting')
            ->with('success', 'Pengaturan ongkir berhasil disimpan.');
    }
}