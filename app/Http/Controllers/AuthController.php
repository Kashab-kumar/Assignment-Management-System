<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ])->onlyInput('email');
        }

        $guard = $this->guardForRole($user->role);

        if (Auth::guard($guard)->attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::guard($guard)->user();

            // Redirect based on role
            if ($user->isAdmin()) {
                return redirect('/admin/dashboard');
            } elseif ($user->isTeacher()) {
                return redirect('/teacher/dashboard');
            } else {
                return redirect('/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    public function logout(Request $request, string $guard)
    {
        if (!in_array($guard, ['admin', 'teacher', 'student'], true)) {
            abort(404);
        }

        Auth::guard($guard)->logout();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function guardForRole(string $role): string
    {
        return match ($role) {
            'admin' => 'admin',
            'teacher' => 'teacher',
            default => 'student',
        };
    }
}
