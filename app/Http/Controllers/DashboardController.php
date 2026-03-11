<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Assignment;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Check if user has student record
        if (!$user->student) {
            return view('dashboard-error', [
                'message' => 'Your student profile is not set up. Please contact the administrator.',
                'user' => $user
            ]);
        }

        $student = $user->student;

        $assignments = Assignment::with(['submissions' => function($q) use ($student) {
            $q->where('student_id', $student->id);
        }])->latest()->get();

        $exams = Exam::with(['results' => function($q) use ($student) {
            $q->where('student_id', $student->id);
        }])->latest()->get();

        $hasCourseId = Schema::hasColumn('students', 'course_id');

        if ($hasCourseId) {
            $students = Student::where('course_id', $student->course_id)->get();
            $groupLabel = 'Course';
            $groupValue = optional($student->course)->name ?? 'Not assigned';
        } else {
            $students = Student::where('class', $student->class)->get();
            $groupLabel = 'Class';
            $groupValue = $student->class ?? 'Not assigned';
        }

        $rankings = $students->map(function($s) {
            return [
                'student' => $s,
                'average' => $s->getAverageScore()
            ];
        })->sortByDesc('average')->values();

        $myRankIndex = $rankings->search(function($item) use ($student) {
            return $item['student']->id === $student->id;
        });

        $myRank = $myRankIndex === false ? null : $myRankIndex + 1;

        return view('dashboard', compact('assignments', 'exams', 'rankings', 'myRank', 'student', 'groupLabel', 'groupValue'));
    }
}
