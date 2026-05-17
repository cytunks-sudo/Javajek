<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class MerchantController extends Controller
{
    public function dashboard()
    {
        $restaurants = Restaurant::where('owner_id', Auth::id())
            ->latest()
            ->get();

        return view('merchant.dashboard', compact('restaurants'));
    }
}