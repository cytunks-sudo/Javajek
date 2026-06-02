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

            if (request()->get('retry') == 1) {
                return view('driver.apply');
            }

            return view('driver.rejected');
        }
    }

    return view('driver.apply');
}

    public function store(Request $request)
{
    $request->validate([
        'phone' => 'required',
        'vehicle_type' => 'required',
        'plate_number' => 'required',
        'address' => 'required',
        'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        'sim_photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
    ]);

    $photo = $request->file('photo')->store('driver-applications', 'public');
    $simPhoto = $request->file('sim_photo')->store('driver-applications', 'public');

    DriverApplication::updateOrCreate(
        ['user_id' => Auth::id()],
        [
            'phone' => $request->phone,
            'vehicle_type' => $request->vehicle_type,
            'plate_number' => $request->plate_number,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'photo' => $photo,
            'sim_photo' => $simPhoto,
            'status' => 'pending',
        ]
    );

    UserRole::updateOrCreate(
        ['user_id' => Auth::id(), 'role' => 'driver'],
        ['status' => 'pending']
    );

    return redirect('/driver')
        ->with('success', 'Pengajuan driver berhasil dikirim. Menunggu approval admin.');
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