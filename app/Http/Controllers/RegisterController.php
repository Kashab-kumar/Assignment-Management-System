<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RegisterController extends Controller
{
    private function guardForRole(string $role): string
    {
        return match ($role) {
            'admin' => 'admin',
            'teacher' => 'teacher',
            default => 'student',
        };
    }

    // Show admin registration form
    public function showAdminRegister()
    {
        // Admin registration is now allowed for multiple admins
        return view('register-admin');
    }

    // Handle admin registration
    public function registerAdmin(Request $request)
    {
        // Multiple admin accounts are now allowed

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);

        Auth::guard($this->guardForRole($user->role))->login($user);

        return redirect()->route('admin.dashboard')->with('success', 'Admin account created successfully!');
    }

    // Show invitation registration form
    public function showInvitationRegister($token)
    {
        $invitation = Invitation::with('course')->where('token', $token)->firstOrFail();

        if (!$invitation->isValid()) {
            return redirect()->route('login')->withErrors(['email' => 'This invitation has expired or been used.']);
        }

        return view('register-invitation', compact('invitation'));
    }

    // Handle invitation registration
    public function registerInvitation(Request $request, $token)
    {
        $invitation = Invitation::with('course')->where('token', $token)->firstOrFail();

        if (!$invitation->isValid()) {
            return redirect()->route('login')->withErrors(['email' => 'This invitation has expired or been used.']);
        }

        $existingUser = User::where('email', $request->input('email'))->first();

        $canRecoverExistingAccount = $existingUser
            && $existingUser->role === $invitation->role
            && (
                ($invitation->role === 'student' && !$existingUser->student)
                || ($invitation->role === 'teacher' && !$existingUser->teacher)
            );

        $rules = [
            'email' => 'required|email' . ($canRecoverExistingAccount ? '' : '|unique:users,email'),
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ];

        if ($invitation->role === 'student') {
            $rules['student_id'] = 'required|string|unique:students,student_id';
            if (!$invitation->course_id) {
                $rules['class'] = 'required|string';
            }
        } elseif ($invitation->role === 'teacher') {
            $rules['teacher_id'] = 'required|string|unique:teachers,teacher_id';
            $rules['subject'] = 'required|string';
        }

        $validated = $request->validate($rules);

        $user = DB::transaction(function () use ($canRecoverExistingAccount, $existingUser, $validated, $invitation) {
            if ($canRecoverExistingAccount) {
                $user = $existingUser;
                $user->update([
                    'name' => $validated['name'],
                    'password' => Hash::make($validated['password']),
                ]);
            } else {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => $invitation->role,
                ]);
            }

            // Create role-specific record
            if ($invitation->role === 'student') {
                $studentData = [
                    'user_id' => $user->id,
                    'student_id' => $validated['student_id'],
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                ];

                if (Schema::hasColumn('students', 'course_id') && $invitation->course_id) {
                    $studentData['course_id'] = $invitation->course_id;
                }

                if (Schema::hasColumn('students', 'class')) {
                    $studentData['class'] = ($invitation->course?->class_name ?: null)
                        ?? ($validated['class'] ?? null);
                }

                Student::create($studentData);
            } elseif ($invitation->role === 'teacher') {
                Teacher::create([
                    'user_id' => $user->id,
                    'teacher_id' => $validated['teacher_id'],
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'subject' => $validated['subject'],
                ]);
            }

            // Track usage count only after the full registration succeeds.
            $invitation->increment('uses_count');

            return $user;
        });

        Auth::guard($this->guardForRole($user->role))->login($user);

        // Redirect based on role
        if ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard')->with('success', 'Registration completed successfully!');
        } else {
            return redirect()->route('dashboard')->with('success', 'Registration completed successfully!');
        }
    }
}
