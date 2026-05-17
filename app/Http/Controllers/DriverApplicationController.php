<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\DriverApplication;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverApplicationController extends Controller
{
    public function create()
{
    $application = DriverApplication::where('user_id', Auth::id())
        ->latest()
        ->first();

    if ($application) {

        if ($application->status == 'pending') {
            return view('driver.pending');
        }

        if ($application->status == 'approved') {
            return redirect('/driver');
        }

        if ($application->status == 'rejected') {
            return view('driver.rejected');
        }
    }

    return view('driver.apply');
}

    public function store(Request $request)
    {
        DriverApplication::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'phone' => $request->phone,
                'vehicle_type' => $request->vehicle_type,
                'plate_number' => $request->plate_number,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => 'pending',
            ]
        );

        UserRole::updateOrCreate(
            ['user_id' => Auth::id(), 'role' => 'driver'],
            ['status' => 'pending']
        );

        return redirect('/')->with('success', 'Pengajuan driver berhasil dikirim.');
    }

    public function adminIndex()
{
    $applications = DriverApplication::with('user')
        ->where('status', 'pending')
        ->latest()
        ->get();

    return view('admin.driver-applications.index', compact('applications'));
}

    public function approve($id)
    {
        $app = DriverApplication::findOrFail($id);

        $app->update(['status' => 'approved']);

        UserRole::updateOrCreate(
            ['user_id' => $app->user_id, 'role' => 'driver'],
            ['status' => 'approved']
        );

        Driver::updateOrCreate(
            ['user_id' => $app->user_id],
            [
                'vehicle_type' => $app->vehicle_type,
                'plate_number' => $app->plate_number,
                'latitude' => $app->latitude,
                'longitude' => $app->longitude,
                'status' => 'offline',
            ]
        );

        return redirect('/admin/driver-applications');
    }

    public function reject($id)
    {
        $app = DriverApplication::findOrFail($id);

        $app->update(['status' => 'rejected']);

        UserRole::updateOrCreate(
            ['user_id' => $app->user_id, 'role' => 'driver'],
            ['status' => 'rejected']
        );

        return redirect('/admin/driver-applications');
    }
}