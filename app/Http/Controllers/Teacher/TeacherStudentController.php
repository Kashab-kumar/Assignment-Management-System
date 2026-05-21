<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Invitation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class TeacherStudentController extends Controller
{
    public function index()
    {
        $assignedCourseIds = $this->assignedCourseIds();

        $students = Student::with(['user', 'course'])
            ->withCount('submissions')
            ->withCount('examResults')
            ->whereIn('course_id', $assignedCourseIds)
            ->orderBy('name')
            ->get();

        $courses = Course::query()
            ->whereIn('id', $assignedCourseIds)
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        $studentInvitations = Invitation::with('course')
            ->where('role', 'student')
            ->where('invited_by', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        return view('teacher.students.index', compact('students', 'courses', 'studentInvitations'));
    }

    public function create()
    {
        $assignedCourseIds = $this->assignedCourseIds();

        $courses = Course::query()
            ->whereIn('id', $assignedCourseIds)
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        return view('teacher.students.create', compact('courses'));
    }

    public function storeInvitation(Request $request)
    {
        $assignedCourseIds = $this->assignedCourseIds();

        $validated = $request->validate([
            'course_id' => ['required', Rule::in($assignedCourseIds)],
            'max_uses' => 'nullable|integer|min:1',
        ]);

        $invitation = Invitation::create([
            'token' => Str::random(32),
            'role' => 'student',
            'course_id' => $validated['course_id'],
            'invited_by' => auth()->id(),
            'expires_at' => now()->addDays(30),
            'max_uses' => $validated['max_uses'] ?? null,
        ]);

        return redirect()->route('teacher.students.invitations.show', $invitation)
            ->with('success', 'Student invitation link created successfully.');
    }

    public function showInvitation(Invitation $invitation)
    {
        abort_unless(
            $invitation->role === 'student' && $invitation->invited_by === auth()->id(),
            403
        );

        $invitation->load('course');
        $invitePath = route('register.invitation', ['token' => $invitation->token], false);
        $inviteLink = request()->getSchemeAndHttpHost() . $invitePath;

        return view('teacher.students.invitation-show', compact('invitation', 'inviteLink'));
    }

    public function store(Request $request)
    {
        $assignedCourseIds = $this->assignedCourseIds();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'student_id' => 'required|string|unique:students,student_id',
            'course_id' => ['required', Rule::in($assignedCourseIds)],
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        // Create student record
        Student::create([
            'user_id' => $user->id,
            'student_id' => $validated['student_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'course_id' => $validated['course_id'],
        ]);

        return redirect()->route('teacher.students.index')
            ->with('success', 'Student created successfully!');
    }

    private function assignedCourseIds(): array
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return [];
        }

        return $teacher->courses()->pluck('courses.id')->all();
    }
}
