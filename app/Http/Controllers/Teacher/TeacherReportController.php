<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Exam;
use App\Models\Submission;
use App\Models\Student;

class TeacherReportController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $totalAssignments = Assignment::count();
        $totalExams = Exam::count();

        $gradedSubmissions = Submission::where('status', 'graded')->count();
        $pendingSubmissions = Submission::where('status', 'pending')->count();

        $avgAssignmentScore = round((float) Submission::where('status', 'graded')->avg('score'), 2);
        $avgExamScore = round((float) \App\Models\ExamResult::avg('score'), 2);

        $topStudents = Student::with(['user', 'submissions', 'examResults'])
            ->get()
            ->sortByDesc(fn ($student) => $student->getAverageScore() ?? 0)
            ->take(10)
            ->values();

        return view('teacher.reports.index', compact(
            'totalStudents',
            'totalAssignments',
            'totalExams',
            'gradedSubmissions',
            'pendingSubmissions',
            'avgAssignmentScore',
            'avgExamScore',
            'topStudents'
        ));
    }
}
