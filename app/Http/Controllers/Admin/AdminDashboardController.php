<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        
        // Only count students and teachers that have valid user records
        $totalStudents = Student::whereHas('user')->count();
        $totalTeachers = Teacher::whereHas('user')->count();
        $totalAssignments = Assignment::count();
        
        // Get courses that have valid teachers
        $totalCourses = \App\Models\Course::whereHas('teachers', function($query) {
            $query->whereHas('user');
        })->count();
        
        $recentUsers = User::latest()->take(10)->get();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalStudents',
            'totalTeachers',
            'totalAssignments',
            'totalCourses',
            'recentUsers'
        ));
    }
}
