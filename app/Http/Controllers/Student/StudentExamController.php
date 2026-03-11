<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;

class StudentExamController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        $exams = Exam::with(['results' => function ($query) use ($student) {
            $query->where('student_id', $student->id);
        }])->latest('exam_date')->get();

        return view('student.exams.index', compact('student', 'exams'));
    }
}
