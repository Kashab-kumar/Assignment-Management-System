<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;

class TeacherAssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::withCount('submissions')->latest()->get();
        return view('teacher.assignments.index', compact('assignments'));
    }

    public function create()
    {
        return view('teacher.assignments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:assignment,homework',
            'due_date' => 'required|date',
            'max_score' => 'required|integer|min:1'
        ]);

        Assignment::create($validated);

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment created successfully!');
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
