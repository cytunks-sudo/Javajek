<?php

namespace App\Http\Controllers;

use App\Models\VoucherUsage;
use Illuminate\Http\Request;

class AdminVoucherUsageController extends Controller
{
    public function index(Request $request)
    {
        $query = VoucherUsage::with(['voucher', 'user', 'order'])
            ->latest();

        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('voucher_code', 'like', "%{$search}%")
                    ->orWhere('service_type', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('order', function ($o) use ($search) {
                        $o->where('order_number', 'like', "%{$search}%")
                          ->orWhere('order_code', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->service_type) {
            $query->where('service_type', $request->service_type);
        }

        $usages = $query->get();

        $totalUsage = VoucherUsage::count();
        $totalDiscount = VoucherUsage::sum('discount_amount');
        $foodUsage = VoucherUsage::where('service_type', 'food')->count();
        $rideUsage = VoucherUsage::whereIn('service_type', ['ojek', 'car'])->count();

        return view('admin.voucher-usages.index', compact(
            'usages',
            'totalUsage',
            'totalDiscount',
            'foodUsage',
            'rideUsage'
        ));
    }
}