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
        $totalAssignments = Assignment::count();
        $totalSubmissions = Submission::count();
        $pendingGrading = Submission::where('status', 'pending')->count();
        $totalStudents = Student::count();
        
        $recentSubmissions = Submission::with(['student', 'assignment'])
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
}
