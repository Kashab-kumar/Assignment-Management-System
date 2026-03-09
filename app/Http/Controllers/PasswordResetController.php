<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // Show forgot password form
    public function showForgotForm()
    {
        return view('forgot-password');
    }

    // Send reset link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Delete old tokens
        PasswordReset::where('email', $request->email)->delete();

        // Create new token
        $token = Str::random(60);
        
        PasswordReset::create([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // In production, send email here
        // For now, we'll just show the link
        $resetLink = route('password.reset', ['token' => $token, 'email' => $request->email]);

        return back()->with('success', "Password reset link: $resetLink (In production, this would be sent via email)");
    }

    // Show reset form
    public function showResetForm($token, Request $request)
    {
        return view('reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $passwordReset = PasswordReset::where('email', $request->email)
            ->where('created_at', '>=', now()->subHours(1))
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'This password reset token is invalid or has expired.']);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete used token
        PasswordReset::where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password reset successfully! You can now login with your new password.');
    }
}
