<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;

class TeacherGradeController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'course'])
            ->with(['submissions.assignment', 'examResults.exam'])
            ->orderBy('name')
            ->get();

        return view('teacher.grades.index', compact('students'));
    }
}
