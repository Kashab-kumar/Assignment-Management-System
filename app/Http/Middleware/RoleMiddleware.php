<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role !== $role) {
            // Prevent 403 dead-ends: keep isolation but send user to their own area.
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('role_notice', 'You are logged in as Admin.');
            }

            if ($user->isTeacher()) {
                return redirect()->route('teacher.dashboard')
                    ->with('role_notice', 'You are logged in as Teacher.');
            }

            if ($user->isStudent()) {
                return redirect()->route('dashboard')
                    ->with('role_notice', 'You are logged in as Student.');
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        }

        return $next($request);
    }
}
