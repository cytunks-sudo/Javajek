<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::with('user')->latest()->get();

        return view('admin.drivers.index', compact('drivers'));
    }

    public function create()
    {
        $users = User::where('role', 'driver')->get();

        return view('admin.drivers.create', compact('users'));
    }

    public function store(Request $request)
    {
        Driver::create([
            'user_id' => $request->user_id,
            'vehicle_type' => $request->vehicle_type,
            'plate_number' => $request->plate_number,
            'status' => 'offline',
        ]);

        return redirect('/admin/drivers');
    }
    public function stop($id)
{
    $driver = Driver::findOrFail($id);

    $driver->update([
        'status' => 'offline',
    ]);

    \App\Models\UserRole::updateOrCreate(
        [
            'user_id' => $driver->user_id,
            'role' => 'driver',
        ],
        [
            'status' => 'rejected',
        ]
    );

    return redirect('/admin/drivers');
}

public function penalty(Request $request, $id)
{
    $driver = Driver::findOrFail($id);

    $days = (int) $request->days;

    $driver->update([
        'status' => 'offline',
        'penalty_until' => now()->addDays($days),
        'penalty_reason' => $request->reason,
    ]);

    return redirect('/admin/drivers');
}

public function clearPenalty($id)
{
    $driver = Driver::findOrFail($id);

    $driver->update([
        'penalty_until' => null,
        'penalty_reason' => null,
    ]);

    return redirect('/admin/drivers');
}
public function stopped()
{
    $drivers = Driver::with('user')
        ->whereHas('user.roles', function ($q) {
            $q->where('role', 'driver')
              ->where('status', 'rejected');
        })
        ->latest()
        ->get();

    return view('admin.drivers.stopped', compact('drivers'));
}

public function penaltyList()
{
    $drivers = Driver::with('user')
        ->whereNotNull('penalty_until')
        ->latest()
        ->get();

    return view('admin.drivers.penalty', compact('drivers'));
}
}