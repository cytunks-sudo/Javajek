<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleRedirect
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        if ($user->role === $role || $user->hasRole($role)) {
            return $next($request);
        }

        abort(403);
    }
}