<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSettingController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'notif_sound_mode' => 'required|in:default_hp,custom,silent',
            'notif_sound_file' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        $user->notif_sound_mode = $request->notif_sound_mode;

        if ($request->notif_sound_mode === 'custom') {
            $user->notif_sound_file = $request->notif_sound_file;
        } else {
            $user->notif_sound_file = null;
        }

        $user->save();

        return back()->with('success', 'Pengaturan notifikasi berhasil disimpan.');
    }
}