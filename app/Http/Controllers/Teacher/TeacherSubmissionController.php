<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Submission;

class TeacherSubmissionController extends Controller
{
    public function index()
    {
        $submissions = Submission::with(['student.user', 'assignment'])
            ->latest('submitted_at')
            ->get();

        return view('teacher.submissions.index', compact('submissions'));
    }
}
