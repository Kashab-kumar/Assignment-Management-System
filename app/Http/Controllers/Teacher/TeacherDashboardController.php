<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Student;
use Illuminate\Http\Request;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $assignedCourseIds = $this->assignedCourseIds();

        $totalAssignments = Assignment::whereIn('course_id', $assignedCourseIds)->count();
        $totalSubmissions = Submission::whereHas('assignment', fn ($q) => $q->whereIn('course_id', $assignedCourseIds))->count();
        $pendingGrading = Submission::where('status', 'pending')
            ->whereHas('assignment', fn ($q) => $q->whereIn('course_id', $assignedCourseIds))
            ->count();
        $totalStudents = Student::whereIn('course_id', $assignedCourseIds)->count();
        
        $recentSubmissions = Submission::with(['student', 'assignment'])
            ->whereHas('assignment', fn ($q) => $q->whereIn('course_id', $assignedCourseIds))
            ->latest()
            ->take(10)
            ->get();
        
        return view('teacher.dashboard', compact(
            'totalAssignments',
            'totalSubmissions', 
            'pendingGrading',
            'totalStudents',
            'recentSubmissions'
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
