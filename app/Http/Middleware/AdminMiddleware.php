<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        $user = auth()->user();
        $user->load('roleModel');

        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'Your account is inactive.');
        }

        // Allow if super_admin, admin, or has any role assigned
        if (!$user->isSuperAdmin() && !$user->isAdmin() && !$user->roleModel) {
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}