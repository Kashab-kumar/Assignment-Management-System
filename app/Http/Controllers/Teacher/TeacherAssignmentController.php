<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Submission;
use Illuminate\Http\Request;

class TeacherAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $selectedCourseId = $request->integer('course_id') ?: null;

        $assignments = Assignment::with(['course'])
            ->withCount('submissions')
            ->when($selectedCourseId, fn ($q) => $q->where('course_id', $selectedCourseId))
            ->latest()
            ->get();

        $courses = Course::query()
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        return view('teacher.assignments.index', compact('assignments', 'courses', 'selectedCourseId'));
    }

    public function create(Request $request)
    {
        $selectedCourseId = $request->integer('course_id') ?: null;

        $courses = Course::query()
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        return view('teacher.assignments.create', compact('courses', 'selectedCourseId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:assignment,homework',
            'due_date' => 'required|date',
            'max_score' => 'required|integer|min:1'
        ]);

        Assignment::create($validated);

        return redirect()->route('teacher.courses.show', $validated['course_id'])
            ->with('success', 'Assignment created successfully for this course.');
    }

    public function show(Assignment $assignment)
    {
        $submissions = $assignment->submissions()->with('student')->get();
        return view('teacher.assignments.show', compact('assignment', 'submissions'));
    }

    public function gradeSubmission(Request $request, Submission $submission)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0'
        ]);

        $submission->update([
            'score' => $validated['score'],
            'status' => 'graded'
        ]);

        return back()->with('success', 'Submission graded successfully!');
    }
}
