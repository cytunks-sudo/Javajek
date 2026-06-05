<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Voucher;

class CustomerVoucherController extends Controller
{
    public function index()
    {
        $completedOrder = Order::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->count();

        $vouchers = Voucher::where('is_active', 1)
            ->where(function ($q) use ($completedOrder) {
                if ($completedOrder > 0) {
                    $q->where('is_new_user_only', 0);
                }
            })
            ->latest()
            ->get();

        return view('customer.vouchers', compact(
            'vouchers',
            'completedOrder'
        ));
    }
}