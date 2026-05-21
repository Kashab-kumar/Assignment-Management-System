<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['student', 'teacher'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            // Admin may only create teacher accounts now
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);
        // Create teacher record for admin-created users
        Teacher::create([
            'user_id' => $user->id,
            'teacher_id' => 'TCH' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
            'name' => $user->name,
            'email' => $user->email,
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        $user->load(['student', 'teacher']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $user->load(['student', 'teacher']);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            // Role cannot be changed via this form; admin-created users are teachers
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting admin accounts
        if ($user->isAdmin()) {
            return back()->withErrors(['error' => 'Cannot delete admin accounts.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}
