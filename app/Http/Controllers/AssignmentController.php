<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::latest()->paginate(10);
        return view('assignments.index', compact('assignments'));
    }

    public function show(Assignment $assignment)
    {
        $student = auth()->user()->student;
        
        // Check if student record exists
        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }
        
        $submission = $assignment->submissions()->where('student_id', $student->id)->first();
        return view('assignments.show', compact('assignment', 'submission'));
    }
}
