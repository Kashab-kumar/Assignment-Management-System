<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Invitation;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeacherStudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'course'])
            ->withCount('submissions')
            ->withCount('examResults')
            ->orderBy('name')
            ->get();

        $courses = Course::query()
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

    public function storeInvitation(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
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
}
