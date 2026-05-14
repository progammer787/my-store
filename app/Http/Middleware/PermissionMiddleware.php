<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission): mixed
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        $user = auth()->user();
        $user->load('roleModel'); // force load relation

        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'Your account is inactive.');
        }

        if (!$user->hasAccess($permission)) {
            abort(403, 'You do not have permission to access this section.');
        }

        return $next($request);
    }
}