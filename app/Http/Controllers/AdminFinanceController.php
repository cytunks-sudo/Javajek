<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\DriverWalletTransaction;
use Carbon\Carbon;

class AdminFinanceController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();

        $commissionToday = DriverWalletTransaction::where('type', 'commission')
            ->whereDate('created_at', $today)
            ->sum('amount');

        $commissionMonth = DriverWalletTransaction::where('type', 'commission')
            ->where('created_at', '>=', $monthStart)
            ->sum('amount');

        $topupTotal = DriverWalletTransaction::where('type', 'topup')
            ->where('amount', '>', 0)
            ->sum('amount');

        $withdrawTotal = DriverWalletTransaction::where('amount', '<', 0)
            ->whereIn('type', ['adjustment'])
            ->sum('amount');

        $totalDriverBalance = Driver::sum('balance');

        $minusDrivers = Driver::with('user')
            ->where('balance', '<', 0)
            ->get();

        $lowBalanceDrivers = Driver::with('user')
            ->where('balance', '>=', 0)
            ->where('balance', '<', 20000)
            ->get();

        $latestTransactions = DriverWalletTransaction::with('driver.user')
            ->latest()
            ->limit(15)
            ->get();

        return view('admin.finance.index', compact(
            'commissionToday',
            'commissionMonth',
            'topupTotal',
            'withdrawTotal',
            'totalDriverBalance',
            'minusDrivers',
            'lowBalanceDrivers',
            'latestTransactions'
        ));
    }
}