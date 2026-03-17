<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamAnswer;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class StudentExamController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        $studentCourseId = Schema::hasColumn('students', 'course_id') ? $student->course_id : null;

        $exams = Exam::with([
            'results' => function ($query) use ($student) {
                $query->where('student_id', $student->id);
            },
            'course',
            'answers' => function ($query) use ($student) {
                $query->where('student_id', $student->id);
            },
        ])
            ->withCount('questions')
            ->when(
                Schema::hasColumn('students', 'course_id'),
                function ($query) use ($studentCourseId) {
                    if ($studentCourseId) {
                        $query->where(function ($inner) use ($studentCourseId) {
                            $inner->whereNull('course_id')
                                ->orWhere('course_id', $studentCourseId);
                        });
                    } else {
                        $query->whereNull('course_id');
                    }
                }
            )
            ->latest('exam_date')
            ->get();

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

    public function show(Exam $exam)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        $this->ensureExamAccess($exam, $student->id, $student->course_id);

        $exam->load([
            'course',
            'questions',
            'results' => function ($query) use ($student) {
                $query->where('student_id', $student->id);
            },
            'answers' => function ($query) use ($student) {
                $query->where('student_id', $student->id);
            },
        ]);

        $answers = $exam->answers->keyBy('exam_question_id');
        $existingResult = $exam->results->first();

        return view('student.exams.show', compact('exam', 'student', 'answers', 'existingResult'));
    }

    public function submit(Request $request, Exam $exam)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        $this->ensureExamAccess($exam, $student->id, $student->course_id);

        $alreadySubmitted = ExamAnswer::query()
            ->where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->route('student.exams.show', $exam)
                ->withErrors(['error' => 'You have already submitted this assessment and cannot change answers.']);
        }

        $now = now();
        $startsAt = $this->getExamStartDateTime($exam);

        if ($startsAt->isFuture() && $now->lt($startsAt)) {
            return redirect()->route('student.exams.show', $exam)
            ->withErrors(['error' => 'You can submit answers once the scheduled assessment time has started.']);
        }

        $exam->load('questions');

        if ($exam->questions->isEmpty()) {
            return redirect()->route('student.exams.show', $exam)
                ->withErrors(['error' => 'This assessment has no questions yet.']);
        }

        $submittedAnswers = $request->input('answers', []);
        $errors = [];

        foreach ($exam->questions as $question) {
            $answerText = trim((string) ($submittedAnswers[$question->id] ?? ''));

            if ($answerText === '') {
                $errors['answers.' . $question->id] = 'Please answer question ' . $question->position . '.';
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        DB::transaction(function () use ($exam, $student, $submittedAnswers) {
            foreach ($exam->questions as $question) {
                ExamAnswer::create([
                    'exam_question_id' => $question->id,
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                    'answer_text' => trim((string) $submittedAnswers[$question->id]),
                ]);
            }
        });

        return redirect()->route('student.exams.show', $exam)
            ->with('success', 'Your answers have been submitted successfully.');
    }

    private function ensureExamAccess(Exam $exam, int $studentId, ?int $courseId): void
    {
        if ($exam->course_id && Schema::hasColumn('students', 'course_id') && $courseId !== $exam->course_id) {
            abort(404);
        }

        if (!$studentId) {
            abort(404);
        }
    }

    private function getExamStartDateTime(Exam $exam): Carbon
    {
        $date = $exam->exam_date->copy();

        if (!empty($exam->exam_time)) {
            [$hour, $minute] = array_pad(explode(':', $exam->exam_time), 2, 0);

            return $date->setTime((int) $hour, (int) $minute, 0);
        }

        return $date->startOfDay();
    }
}
