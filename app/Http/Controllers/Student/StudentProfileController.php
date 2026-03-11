<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class StudentProfileController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        $submissionCount = $student->submissions()->count();
        $gradedSubmissionCount = $student->submissions()->where('status', 'graded')->count();
        $examResultCount = $student->examResults()->count();

        return view('student.profile.index', compact(
            'student',
            'submissionCount',
            'gradedSubmissionCount',
            'examResultCount'
        ));
    }
}
