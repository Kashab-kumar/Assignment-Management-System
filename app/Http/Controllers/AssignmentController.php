<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        $moduleId = $request->integer('module_id') ?: null;

        $query = Assignment::with(['submissions' => function ($q) use ($student) {
            $q->where('student_id', $student->id);
        }]);

        // Filter by module if module_id is provided
        if ($moduleId) {
            $query->where('module_id', $moduleId);
        }

        $allAssignments = $query->latest()->get();

        $pending   = $allAssignments->filter(fn($a) => $a->submissions->isEmpty());
        $submitted = $allAssignments->filter(fn($a) => $a->submissions->isNotEmpty() && $a->submissions->first()->status !== 'graded');
        $graded    = $allAssignments->filter(fn($a) => $a->submissions->isNotEmpty() && $a->submissions->first()->status === 'graded');

        $activeTab = request('tab', 'pending');
        $module = $moduleId ? \App\Models\CourseModule::find($moduleId) : null;

        return view('assignments.index', compact('pending', 'submitted', 'graded', 'activeTab', 'module', 'moduleId'));
    }

    public function show(Assignment $assignment)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        $submission = $assignment->submissions()->where('student_id', $student->id)->first();

        $allAssignments = Assignment::with(['submissions' => function ($q) use ($student) {
            $q->where('student_id', $student->id);
        }])->get();

        $pendingCount   = $allAssignments->filter(fn($a) => $a->submissions->isEmpty())->count();
        $submittedCount = $allAssignments->filter(fn($a) => $a->submissions->isNotEmpty() && $a->submissions->first()->status !== 'graded')->count();
        $gradedCount    = $allAssignments->filter(fn($a) => $a->submissions->isNotEmpty() && $a->submissions->first()->status === 'graded')->count();

        return view('assignments.show', compact('assignment', 'submission', 'pendingCount', 'submittedCount', 'gradedCount'));
    }
}
