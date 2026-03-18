<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.teachers.index', compact('teachers'));
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'courses']);
        return view('admin.teachers.show', compact('teacher'));
    }

    public function create()
    {
        $courses = Course::query()
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        return view('admin.teachers.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'teacher_id' => 'required|string|unique:teachers,teacher_id',
            'subject' => 'required|string|max:255',
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'teacher',
        ]);

        // Create teacher record
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'teacher_id' => $validated['teacher_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
        ]);

        $teacher->courses()->sync($validated['course_ids'] ?? []);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully!');
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load(['user', 'courses']);

        $courses = Course::query()
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        $selectedCourseIds = $teacher->courses->pluck('id')->all();

        return view('admin.teachers.edit', compact('teacher', 'courses', 'selectedCourseIds'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $teacher->user->id,
            'subject' => 'required|string|max:255',
            'course_ids' => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        // Update user
        $teacher->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update teacher
        $teacher->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
        ]);

        $teacher->courses()->sync($validated['course_ids'] ?? []);

        return redirect()->route('admin.teachers.show', $teacher)
            ->with('success', 'Teacher updated successfully!');
    }

    public function destroy(Teacher $teacher)
    {
        $user = $teacher->user;
        $teacher->delete();
        $user->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully!');
    }
}