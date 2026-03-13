<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        $allAssignments = Assignment::with(['submissions' => function ($q) use ($student) {
            $q->where('student_id', $student->id);
        }])->latest()->get();

        $pending   = $allAssignments->filter(fn($a) => $a->submissions->isEmpty());
        $submitted = $allAssignments->filter(fn($a) => $a->submissions->isNotEmpty() && $a->submissions->first()->status !== 'graded');
        $graded    = $allAssignments->filter(fn($a) => $a->submissions->isNotEmpty() && $a->submissions->first()->status === 'graded');

        $activeTab = request('tab', 'pending');

        return view('assignments.index', compact('pending', 'submitted', 'graded', 'activeTab'));
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
