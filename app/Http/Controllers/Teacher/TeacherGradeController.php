<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;

class TeacherGradeController extends Controller
{
    public function index()
    {
        $assignedCourseIds = $this->assignedCourseIds();

        $students = Student::with(['user', 'course'])
            ->with(['submissions.assignment', 'examResults.exam'])
            ->whereIn('course_id', $assignedCourseIds)
            ->orderBy('name')
            ->get();

        return view('teacher.grades.index', compact('students'));
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
