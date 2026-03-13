<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;

class StudentExamController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        $exams = Exam::with(['results' => function ($query) use ($student) {
            $query->where('student_id', $student->id);
        }, 'course'])->latest('exam_date')->get();

        $today = now()->startOfDay();

        $upcomingExams = $exams
            ->filter(fn ($exam) => $exam->exam_date->greaterThanOrEqualTo($today))
            ->sortBy('exam_date')
            ->values();

        $completedExams = $exams
            ->filter(fn ($exam) => $exam->exam_date->lessThan($today))
            ->sortByDesc('exam_date')
            ->values();

        $activeTab = $request->query('tab', 'upcoming');
        if (!in_array($activeTab, ['upcoming', 'completed'], true)) {
            $activeTab = 'upcoming';
        }

        $activeList = $activeTab === 'completed' ? $completedExams : $upcomingExams;

        $selectedExamId = (int) $request->query('exam', 0);
        $selectedExam = $activeList->firstWhere('id', $selectedExamId)
            ?? $activeList->first()
            ?? $exams->first();

        return view('student.exams.index', compact(
            'student',
            'activeTab',
            'selectedExam',
            'upcomingExams',
            'completedExams'
        ));
    }
}
