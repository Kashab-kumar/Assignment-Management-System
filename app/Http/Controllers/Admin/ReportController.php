<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Course;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // User statistics
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalStudents = User::where('role', 'student')->count();

        // Academic statistics
        $totalAssignments = Assignment::count();
        $totalSubmissions = Submission::count();
        $pendingSubmissions = Submission::where('status', 'pending')->count();
        $gradedSubmissions = Submission::where('status', 'graded')->count();

        // Course statistics
        $totalCourses = Course::count();
        $activeCourses = Course::where('is_active', true)->count();

        // Recent activity
        $recentUsers = User::with(['student', 'teacher'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $recentSubmissions = Submission::with(['student.user', 'assignment'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'totalUsers',
            'totalAdmins',
            'totalTeachers',
            'totalStudents',
            'totalAssignments',
            'totalSubmissions',
            'pendingSubmissions',
            'gradedSubmissions',
            'totalCourses',
            'activeCourses',
            'recentUsers',
            'recentSubmissions'
        ));
    }

    public function users()
    {
        $users = User::with(['student', 'teacher'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('admin.reports.users', compact('users'));
    }

    public function academic()
    {
        $assignments = Assignment::withCount('submissions')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $courses = Course::withCount('students')
            ->orderBy('students_count', 'desc')
            ->paginate(20);
        
        return view('admin.reports.academic', compact('assignments', 'courses'));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'users');
        
        // This would generate CSV/Excel files in a real implementation
        // For now, we'll just show a message
        
        return back()->with('success', 'Export functionality will be implemented soon!');
    }
}