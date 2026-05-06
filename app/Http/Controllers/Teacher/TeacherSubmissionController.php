<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;

class TeacherSubmissionController extends Controller
{
    public function index(Request $request, Assignment $assignment = null)
    {
        $assignedCourseIds = $this->assignedCourseIds();

        // If assignment is provided, filter by that assignment
        if ($assignment) {
            abort_unless(in_array($assignment->course_id, $assignedCourseIds, true), 403);
            $submissions = $assignment->submissions()->with('student.user', 'assignment')->latest('submitted_at')->get();
            $pageTitle = 'Submissions for ' . $assignment->title;
        } else {
            // Otherwise, show all submissions
            $submissions = Submission::with(['student.user', 'assignment'])
                ->whereHas('assignment', fn ($q) => $q->whereIn('course_id', $assignedCourseIds))
                ->latest('submitted_at')
                ->get();
            $pageTitle = 'All Student Submissions';
        }

        return view('teacher.submissions.index', compact('submissions', 'assignment', 'pageTitle'));
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
