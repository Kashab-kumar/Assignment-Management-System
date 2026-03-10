<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'course'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $courses = Course::where('is_active', true)->get();
        
        return view('admin.students.index', compact('students', 'courses'));
    }

    public function show(Student $student)
    {
        $student->load(['user', 'course', 'submissions.assignment']);
        return view('admin.students.show', compact('student'));
    }

    public function create()
    {
        $courses = Course::where('is_active', true)->get();
        return view('admin.students.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'student_id' => 'required|string|unique:students,student_id',
            'course_id' => 'required|exists:courses,id',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'student',
        ]);

        // Create student record
        Student::create([
            'user_id' => $user->id,
            'student_id' => $validated['student_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'course_id' => $validated['course_id'],
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully!');
    }

    public function edit(Student $student)
    {
        $student->load('user');
        $courses = Course::where('is_active', true)->get();
        return view('admin.students.edit', compact('student', 'courses'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user->id,
            'course_id' => 'required|exists:courses,id',
        ]);

        // Update user
        $student->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update student
        $student->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'course_id' => $validated['course_id'],
        ]);

        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Student updated successfully!');
    }

    public function destroy(Student $student)
    {
        $user = $student->user;
        $student->delete();
        $user->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully!');
    }
}