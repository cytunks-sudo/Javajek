<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\DriverWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDriverWalletController extends Controller
{
    public function index()
{
    $drivers = Driver::with([
            'user',
            'walletTransactions' => function ($query) {
                $query->latest()->limit(10);
            }
        ])
        ->latest()
        ->get();

    return view('admin.driver-wallet.index', compact('drivers'));
}

    public function store(Request $request, $driverId)
    {
        $request->validate([
            'type' => 'required|in:topup,adjustment',
            'operation' => 'required|in:add,subtract',
            'amount' => 'required|numeric|min:1000',
            'description' => 'nullable|string|max:255',
        ]);

        $driver = Driver::with('user')->findOrFail($driverId);

        DB::transaction(function () use ($request, $driver) {

            $amount = (float) $request->amount;

            $before = (float) $driver->balance;

            if ($request->operation == 'add') {
                $after = $before + $amount;
                $realAmount = $amount;
            } else {
                $after = $before - $amount;
                $realAmount = -$amount;
            }

            if ($after < 0) {
                abort(422, 'Saldo driver tidak boleh minus.');
            }

            $driver->update([
                'balance' => $after,
            ]);

            DriverWalletTransaction::create([
                'driver_id' => $driver->id,
                'type' => $request->type,
                'amount' => $realAmount,
                'balance_before' => $before,
                'balance_after' => $after,
                'description' => $request->description
                    ?: ($request->operation == 'add'
                        ? 'Topup saldo manual admin'
                        : 'Pengurangan saldo manual admin'),
                'created_by' => Auth::id(),
            ]);
        });

        return back()->with('success', 'Saldo driver berhasil diperbarui.');
    }
}