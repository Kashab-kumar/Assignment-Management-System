<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileAvatarController extends Controller
{
    public function adminSettings()
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        return $this->renderSettingsView('layouts.admin', route('admin.dashboard'), 'Administrator');
    }

    public function teacherSettings()
    {
        abort_unless(auth()->user()->isTeacher(), 403);
        return $this->renderSettingsView('layouts.teacher', route('teacher.dashboard'), 'Teacher');
    }

    public function studentSettings()
    {
        abort_unless(auth()->user()->isStudent(), 403);
        return $this->renderSettingsView('layouts.student', route('student.profile'), 'Student');
    }

    private function renderSettingsView(string $layout, string $backRoute, string $roleLabel)
    {
        $user = auth()->user();

        return view('profile.avatar', compact('layout', 'backRoute', 'user', 'roleLabel'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = auth()->user();

        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $path = $validated['avatar']->store('avatars', 'public');

        $user->update([
            'avatar_path' => $path,
        ]);

        return back()->with('success', 'Avatar updated successfully.');
    }

    public function destroy()
    {
        $user = auth()->user();

        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->update([
            'avatar_path' => null,
        ]);

        return back()->with('success', 'Avatar removed successfully.');
    }
}
