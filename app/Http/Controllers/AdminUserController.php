<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->get();

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:customer,driver,merchant,admin',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        UserRole::updateOrCreate(
            [
                'user_id' => $id,
                'role' => $request->role,
            ],
            [
                'status' => $request->status,
            ]
        );

        return redirect('/admin/users');
    }

    public function destroy($id)
{
    $user = User::findOrFail($id);

    if ($user->email == 'admin@javajek.com') {
        return redirect('/admin/users');
    }

    $user->delete();

    return redirect('/admin/users');
}
}