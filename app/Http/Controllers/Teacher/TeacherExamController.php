<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TeacherExamController extends Controller
{
    public function index(Request $request)
    {
        $selectedCourseId = $request->integer('course_id') ?: null;

        $exams = Exam::withCount('results')
            ->with('course')
            ->withAvg('results', 'score')
            ->when($selectedCourseId, fn ($q) => $q->where('course_id', $selectedCourseId))
            ->orderByDesc('exam_date')
            ->get();

        $courses = Course::query()
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        return view('teacher.exams.index', compact('exams', 'courses', 'selectedCourseId'));
    }

    public function create(Request $request)
    {
        $selectedCourseId = $request->integer('course_id') ?: null;
        $mode = $request->input('mode') === 'quiz' ? 'quiz' : 'exam';

        $courses = Course::query()
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        return view('teacher.exams.create', compact('courses', 'selectedCourseId', 'mode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exam_date' => 'required|date',
            'max_score' => 'required|integer|min:1|max:1000',
        ]);

        Exam::create($validated);

        return redirect()->route('teacher.courses.show', $validated['course_id'])
            ->with('success', 'Exam/Quiz created successfully for this course.');
    }

    public function show(Exam $exam)
    {
        $exam->load('course');

        $results = $exam->results()->with('student.user')->orderByDesc('score')->get();

        $students = Student::with('user')
            ->when(
                $exam->course_id && Schema::hasColumn('students', 'course_id'),
                fn ($q) => $q->where('course_id', $exam->course_id)
            )
            ->orderBy('name')
            ->get();

        return view('teacher.exams.show', compact('exam', 'results', 'students'));
    }

    public function upsertResult(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'score' => 'required|integer|min:0|max:' . $exam->max_score,
            'remarks' => 'nullable|string|max:1000',
        ]);

        if ($exam->course_id && Schema::hasColumn('students', 'course_id')) {
            $isStudentInCourse = Student::query()
                ->where('id', $validated['student_id'])
                ->where('course_id', $exam->course_id)
                ->exists();

            if (!$isStudentInCourse) {
                return back()
                    ->withInput()
                    ->withErrors(['student_id' => 'Selected student is not enrolled in this course.']);
            }
        }

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
