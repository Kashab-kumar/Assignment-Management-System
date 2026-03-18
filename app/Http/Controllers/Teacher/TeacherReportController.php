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
        $assignedCourseIds = $this->assignedCourseIds();

        $totalStudents = Student::whereIn('course_id', $assignedCourseIds)->count();
        $totalAssignments = Assignment::whereIn('course_id', $assignedCourseIds)->count();
        $totalExams = Exam::whereIn('course_id', $assignedCourseIds)->count();

        $gradedSubmissions = Submission::where('status', 'graded')
            ->whereHas('assignment', fn ($q) => $q->whereIn('course_id', $assignedCourseIds))
            ->count();
        $pendingSubmissions = Submission::where('status', 'pending')
            ->whereHas('assignment', fn ($q) => $q->whereIn('course_id', $assignedCourseIds))
            ->count();

        $avgAssignmentScore = round((float) Submission::where('status', 'graded')
            ->whereHas('assignment', fn ($q) => $q->whereIn('course_id', $assignedCourseIds))
            ->avg('score'), 2);
        $avgExamScore = round((float) \App\Models\ExamResult::whereHas('exam', fn ($q) => $q->whereIn('course_id', $assignedCourseIds))->avg('score'), 2);

        $topStudents = Student::with(['user', 'submissions', 'examResults'])
            ->whereIn('course_id', $assignedCourseIds)
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

    private function assignedCourseIds(): array
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return [];
        }

        return $teacher->courses()->pluck('courses.id')->all();
    }
}
