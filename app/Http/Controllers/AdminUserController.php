<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::with('roles')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhereHas('roles', function ($r) use ($search) {
                            $r->where('role', 'like', "%{$search}%")
                              ->orWhere('status', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->get();

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

        return redirect('/admin/users')
            ->with('success', 'Role user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (
            $user->username === 'admin' ||
            $user->role === 'admin' ||
            $user->email === 'admin@javajek.local'
        ) {
            return redirect('/admin/users')
                ->with('error', 'Akun admin utama tidak boleh dihapus.');
        }

        $user->roles()->delete();
        $user->delete();

        return redirect('/admin/users')
            ->with('success', 'User berhasil dihapus.');
    }
}