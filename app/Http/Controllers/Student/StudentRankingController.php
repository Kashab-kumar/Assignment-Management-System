<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Schema;

class StudentRankingController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        $hasCourseId = Schema::hasColumn('students', 'course_id');

        if ($hasCourseId) {
            $students = Student::with('course')
                ->where('course_id', $student->course_id)
                ->get();
            $groupLabel = 'Course';
            $groupValue = optional($student->course)->name ?? 'Not assigned';
        } else {
            $students = Student::where('class', $student->class)->get();
            $groupLabel = 'Class';
            $groupValue = $student->class ?? 'Not assigned';
        }

        $rankings = $students->map(function ($item) {
            return [
                'student' => $item,
                'average' => $item->getAverageScore(),
            ];
        })->sortByDesc('average')->values();

        $myRank = $rankings->search(function ($item) use ($student) {
            return $item['student']->id === $student->id;
        });

        $myRank = $myRank === false ? null : $myRank + 1;

        return view('student.rankings.index', compact('student', 'rankings', 'myRank', 'groupLabel', 'groupValue'));
    }
}
