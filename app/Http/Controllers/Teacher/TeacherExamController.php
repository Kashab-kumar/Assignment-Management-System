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
use Illuminate\Validation\Rule;

class TeacherExamController extends Controller
{
    public function index(Request $request)
    {
        $selectedCourseId = $request->integer('course_id') ?: null;
        $selectedModuleId = $request->integer('module_id') ?: null;
        $activeFilter = $request->query('filter', 'all');
        $assignedCourseIds = $this->assignedCourseIds();

        if ($selectedCourseId && !in_array($selectedCourseId, $assignedCourseIds, true)) {
            $selectedCourseId = null;
            $selectedModuleId = null;
        }

        $exams = Exam::withCount('results')
            ->withCount('questions')
            ->withAvg('results', 'score')
            ->whereIn('course_id', $assignedCourseIds)
            ->when($selectedCourseId, fn ($q) => $q->where('course_id', $selectedCourseId))
            ->when($selectedModuleId, fn ($q) => $q->where('module_id', $selectedModuleId))
            ->when($activeFilter !== 'all', function ($query) use ($activeFilter) {
                $query->where('type', $activeFilter);
            })
            ->orderByDesc('exam_date')
            ->get();

        $courses = Course::query()
            ->whereIn('id', $assignedCourseIds)
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        return view('teacher.exams.index', compact('exams', 'courses', 'selectedCourseId', 'selectedModuleId', 'activeFilter'));
    }

    public function create(Request $request)
    {
        $selectedCourseId = $request->integer('course_id') ?: null;
        $selectedModuleId = $request->integer('module_id') ?: null;
        $mode = in_array($request->input('mode'), ['quiz', 'test'], true) ? $request->input('mode') : 'exam';
        $assignedCourseIds = $this->assignedCourseIds();

        // If module_id is provided, get the course_id from the module
        if ($selectedModuleId && !$selectedCourseId) {
            $module = \App\Models\CourseModule::find($selectedModuleId);
            if ($module && in_array($module->course_id, $assignedCourseIds, true)) {
                $selectedCourseId = $module->course_id;
            }
        }

        if ($selectedCourseId && !in_array($selectedCourseId, $assignedCourseIds, true)) {
            $selectedCourseId = null;
        }

        $courses = Course::query()
            ->whereIn('id', $assignedCourseIds)
            ->with(['modules' => fn ($query) => $query->orderBy('position')])
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        return view('teacher.exams.create', compact('courses', 'selectedCourseId', 'selectedModuleId', 'mode'));
    }

    public function store(Request $request)
    {
        $assignedCourseIds = $this->assignedCourseIds();

        $validated = $request->validate([
            'course_id' => ['required', Rule::in($assignedCourseIds)],
            'module_id' => 'nullable|exists:course_modules,id',
            'type' => 'required|in:exam,quiz,test',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exam_date' => 'required|date',
            'exam_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'required|integer|min:1|max:600',
            'max_score' => 'required|integer|min:1|max:1000',
            'secure_mode' => 'nullable|boolean',
            'secure_instructions' => 'nullable|string',
            'max_violations' => 'nullable|integer|min:1|max:10',
            'max_warnings' => 'nullable|integer|min:1|max:20',
            'questions' => 'nullable|array',
            'questions.*.question_text' => 'nullable|string|max:5000',
            'questions.*.answer_key' => 'nullable|string|max:5000',
            'questions.*.question_type' => 'nullable|in:short_answer,long_answer,multiple_choice',
            'questions.*.points' => 'nullable|integer|min:1|max:1000',
        ]);

        // Convert secure_mode to boolean (checkbox sends "0" or "1")
        $validated['secure_mode'] = isset($validated['secure_mode']) && $validated['secure_mode'] == '1';

        DB::transaction(function () use ($validated) {
            $exam = Exam::create([
                'course_id' => $validated['course_id'],
                'module_id' => $validated['module_id'] ?? null,
                'type' => $validated['type'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'exam_date' => $validated['exam_date'],
                'exam_time' => $validated['exam_time'] ?? null,
                'duration_minutes' => $validated['duration_minutes'],
                'max_score' => $validated['max_score'],
                'secure_mode' => $validated['secure_mode'] ?? false,
                'secure_instructions' => $validated['secure_instructions'] ?? null,
                'max_violations' => $validated['max_violations'] ?? 3,
                'max_warnings' => $validated['max_warnings'] ?? 5,
            ]);

            if (!empty($validated['questions'])) {
                foreach ($validated['questions'] as $index => $question) {
                    $exam->questions()->create([
                        'question_text' => $question['question_text'],
                        'answer_key' => $question['answer_key'] ?? null,
                        'question_type' => $question['question_type'],
                        'points' => (int) ($question['points'] ?? 1),
                        'position' => $index + 1,
                    ]);
                }
            }
        });

        return redirect()->route('teacher.courses.show', $validated['course_id'])
            ->with('success', 'Assessment created successfully for this course.');
    }

    public function show(Exam $exam)
    {
        abort_unless(in_array($exam->course_id, $this->assignedCourseIds(), true), 403);

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
        abort_unless(in_array($exam->course_id, $this->assignedCourseIds(), true), 403);

        $courses = Course::query()
            ->whereIn('id', $this->assignedCourseIds())
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        return view('teacher.exams.edit', compact('exam', 'courses'));
    }

    public function update(Request $request, Exam $exam)
    {
        $assignedCourseIds = $this->assignedCourseIds();
        abort_unless(in_array($exam->course_id, $assignedCourseIds, true), 403);

        $validated = $request->validate([
            'course_id' => ['required', Rule::in($assignedCourseIds)],
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
        abort_unless(in_array($exam->course_id, $this->assignedCourseIds(), true), 403);

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

    private function assignedCourseIds(): array
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return [];
        }

        return $teacher->courses()->pluck('courses.id')->all();
    }
}
