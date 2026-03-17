<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ExamAnswer;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TeacherExamController extends Controller
{
    public function index(Request $request)
    {
        $selectedCourseId = $request->integer('course_id') ?: null;

        $exams = Exam::withCount('results')
            ->withCount('questions')
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
        $mode = in_array($request->input('mode'), ['quiz', 'test'], true) ? $request->input('mode') : 'exam';

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
            'type' => 'required|in:exam,quiz,test',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exam_date' => 'required|date',
            'exam_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'required|integer|min:1|max:600',
            'max_score' => 'required|integer|min:1|max:1000',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string|max:5000',
            'questions.*.question_type' => 'required|in:short_answer,long_answer',
            'questions.*.points' => 'nullable|integer|min:1|max:1000',
        ]);

        DB::transaction(function () use ($validated) {
            $exam = Exam::create([
                'course_id' => $validated['course_id'],
                'type' => $validated['type'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'exam_date' => $validated['exam_date'],
                'exam_time' => $validated['exam_time'] ?? null,
                'duration_minutes' => $validated['duration_minutes'],
                'max_score' => $validated['max_score'],
            ]);

            foreach ($validated['questions'] as $index => $question) {
                $exam->questions()->create([
                    'question_text' => $question['question_text'],
                    'question_type' => $question['question_type'],
                    'points' => (int) ($question['points'] ?? 1),
                    'position' => $index + 1,
                ]);
            }
        });

        return redirect()->route('teacher.courses.show', $validated['course_id'])
            ->with('success', 'Assessment created successfully for this course.');
    }

    public function show(Exam $exam)
    {
        $exam->load(['course', 'questions']);

        $results = $exam->results()->with('student.user')->orderByDesc('score')->get();

        $answerSheets = ExamAnswer::with(['student.user', 'question'])
            ->where('exam_id', $exam->id)
            ->get()
            ->groupBy('student_id');

        $students = Student::with('user')
            ->when(
                $exam->course_id && Schema::hasColumn('students', 'course_id'),
                fn ($q) => $q->where('course_id', $exam->course_id)
            )
            ->orderBy('name')
            ->get();

        return view('teacher.exams.show', compact('exam', 'results', 'students', 'answerSheets'));
    }

    public function edit(Exam $exam)
    {
        $courses = Course::query()
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        return view('teacher.exams.edit', compact('exam', 'courses'));
    }

    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'type' => 'required|in:exam,quiz,test',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exam_date' => 'required|date',
            'exam_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'required|integer|min:1|max:600',
            'max_score' => 'required|integer|min:1|max:1000',
        ]);

        $exam->update([
            'course_id' => $validated['course_id'],
            'type' => $validated['type'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'exam_date' => $validated['exam_date'],
            'exam_time' => $validated['exam_time'] ?? null,
            'duration_minutes' => $validated['duration_minutes'],
            'max_score' => $validated['max_score'],
        ]);

        return redirect()->route('teacher.exams.show', $exam)
            ->with('success', 'Assessment updated successfully.');
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
