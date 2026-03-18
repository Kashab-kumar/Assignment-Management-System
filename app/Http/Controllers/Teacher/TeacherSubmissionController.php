<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Submission;

class TeacherSubmissionController extends Controller
{
    public function index()
    {
        $assignedCourseIds = $this->assignedCourseIds();

        $submissions = Submission::with(['student.user', 'assignment'])
            ->whereHas('assignment', fn ($q) => $q->whereIn('course_id', $assignedCourseIds))
            ->latest('submitted_at')
            ->get();

        return view('teacher.submissions.index', compact('submissions'));
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
