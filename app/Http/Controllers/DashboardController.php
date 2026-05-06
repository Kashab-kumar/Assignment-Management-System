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

        // Get upcoming assignments (due date in future and not submitted)
        $upcomingAssignments = Assignment::with(['submissions' => function($q) use ($student) {
            $q->where('student_id', $student->id);
        }])
            ->where('due_date', '>', now())
            ->orderBy('due_date', 'asc')
            ->get()
            ->filter(function($a) {
                return $a->submissions->isEmpty();
            })
            ->values();

        // Get upcoming exams (exam date in future and not taken)
        $upcomingExams = Exam::with(['results' => function($q) use ($student) {
            $q->where('student_id', $student->id);
        }])
            ->where('exam_date', '>', now())
            ->orderBy('exam_date', 'asc')
            ->get()
            ->filter(function($e) {
                return $e->results->isEmpty();
            })
            ->values();

        // Combine and sort all upcoming tasks
        $upcomingTasks = collect()
            ->merge($upcomingAssignments->map(function($a) {
                return [
                    'type' => 'assignment',
                    'title' => $a->title,
                    'due_date' => $a->due_date,
                    'model' => $a
                ];
            }))
            ->merge($upcomingExams->map(function($e) {
                return [
                    'type' => 'exam',
                    'title' => $e->title,
                    'due_date' => $e->exam_date,
                    'model' => $e
                ];
            }))
            ->sortBy('due_date')
            ->values();

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

        return view('dashboard', compact('assignments', 'exams', 'upcomingTasks', 'upcomingAssignments', 'upcomingExams', 'rankings', 'myRank', 'student', 'groupLabel', 'groupValue'));
    }
}
