<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class StudentProfileController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $moduleItemsEnabled = Schema::hasTable('course_module_items');

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        $submissionCount = $student->submissions()->count();
        $gradedSubmissionCount = $student->submissions()->where('status', 'graded')->count();
        $examResultCount = $student->examResults()->count();
        $courseModules = collect();

        if (Schema::hasTable('course_modules') && $student->course) {
            $courseModules = $student->course->modules()
                ->when($moduleItemsEnabled, fn ($query) => $query->with('items.creator'))
                ->get();
        }

        return view('student.profile.index', compact(
            'student',
            'submissionCount',
            'gradedSubmissionCount',
            'examResultCount',
            'courseModules',
            'moduleItemsEnabled'
        ));
    }
}
