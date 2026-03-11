<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;

class StudentGradeController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        $assignmentSubmissions = $student->submissions()
            ->with('assignment')
            ->where('status', 'graded')
            ->latest('submitted_at')
            ->get();

        $examResults = $student->examResults()
            ->with('exam')
            ->latest()
            ->get();

        $assignmentAverage = (float) $assignmentSubmissions->avg('score');
        $examAverage = (float) $examResults->avg('score');

        $maxAssignmentAverage = (float) Assignment::avg('max_score');
        $overallAverage = collect([$assignmentAverage, $examAverage])->filter(fn ($value) => $value > 0)->avg() ?? 0;

        return view('student.grades.index', compact(
            'student',
            'assignmentSubmissions',
            'examResults',
            'assignmentAverage',
            'examAverage',
            'maxAssignmentAverage',
            'overallAverage'
        ));
    }
}
