<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;

class AdminMerchantController extends Controller
{
   public function index()
{
    $restaurants = Restaurant::with('owner')
        ->whereNotIn('status', ['active','rejected'])
        ->latest()
        ->get();

    return view('admin.merchant-applications', compact('restaurants'));
}

    public function approve($id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $restaurant->update([
            'status' => 'active',
        ]);

        return redirect('/admin/merchant-applications')
            ->with('success', 'Merchant berhasil disetujui.');
    }

    public function reject($id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $restaurant->update([
            'status' => 'rejected',
        ]);

        return redirect('/admin/merchant-applications')
            ->with('success', 'Merchant ditolak.');
    }
}