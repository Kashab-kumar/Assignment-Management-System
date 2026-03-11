<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Http\Request;

class TeacherExamController extends Controller
{
    public function index()
    {
        $exams = Exam::withCount('results')
            ->withAvg('results', 'score')
            ->orderByDesc('exam_date')
            ->get();

        return view('teacher.exams.index', compact('exams'));
    }

    public function create()
    {
        return view('teacher.exams.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exam_date' => 'required|date',
            'max_score' => 'required|integer|min:1|max:1000',
        ]);

        Exam::create($validated);

        return redirect()->route('teacher.exams.index')->with('success', 'Exam created successfully.');
    }

    public function show(Exam $exam)
    {
        $results = $exam->results()->with('student.user')->orderByDesc('score')->get();
        $students = Student::with('user')->orderBy('name')->get();

        return view('teacher.exams.show', compact('exam', 'results', 'students'));
    }

    public function upsertResult(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'score' => 'required|integer|min:0|max:' . $exam->max_score,
            'remarks' => 'nullable|string|max:1000',
        ]);

        ExamResult::updateOrCreate(
            [
                'exam_id' => $exam->id,
                'student_id' => $validated['student_id'],
            ],
            [
                'score' => $validated['score'],
                'remarks' => $validated['remarks'] ?? null,
            ]
        );

        return back()->with('success', 'Exam result saved successfully.');
    }
}
