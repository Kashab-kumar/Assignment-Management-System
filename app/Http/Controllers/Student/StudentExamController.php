<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ExamAnswer;
use App\Models\ExamResult;
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
        $activeFilter = $request->query('filter', 'all');
        $moduleId = $request->integer('module_id') ?: null;

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
            ->when($moduleId, function ($query) use ($moduleId) {
                $query->where('module_id', $moduleId);
            })
            ->when($activeFilter !== 'all', function ($query) use ($activeFilter) {
                $query->where('type', $activeFilter);
            })
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

        $module = $moduleId ? \App\Models\CourseModule::find($moduleId) : null;

        return view('student.exams.index', compact(
            'student',
            'activeTab',
            'activeFilter',
            'selectedExam',
            'upcomingExams',
            'completedExams',
            'module',
            'moduleId'
        ));
    }
            private function normalizeAnswer(string $text): string
            {
                $t = mb_strtolower($text);
                // Remove punctuation, collapse whitespace, and ascii-fy
                $t = preg_replace('/[^\p{L}\p{N}\s]+/u', '', $t);
                $t = preg_replace('/\s+/u', ' ', $t);
                $t = trim($t);

                // Convert to ASCII where possible
                if (function_exists('transliterator_transliterate')) {
                    $t = transliterator_transliterate('Any-Latin; Latin-ASCII;', $t);
                }

                return $t;
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

        // Check if exam is expired for all exam types
        $examStartsAt = $this->getExamStartDateTime($exam);
        $examEndsAt = $examStartsAt->copy()->addMinutes($exam->duration_minutes);
        $now = now();

        // Treat the exam as completed if either answers or a result record exists
        $hasSubmittedAnswers = $exam->answers->isNotEmpty();
        $hasCompletedAttempt = $hasSubmittedAnswers || $existingResult !== null;

        if (!$hasCompletedAttempt && $now->gt($examEndsAt)) {
            return redirect()->route('student.exams.index')
                ->withErrors(['error' => 'This assessment has expired and is no longer available.']);
        }

        // Check if this is a secure exam
        if ($exam->secure_mode) {
            // If student has already submitted answers, show the regular exam detail page with review
            if ($hasCompletedAttempt) {
                return view('student.exams.show', compact('exam', 'student', 'answers', 'existingResult'));
            }

            if ($now->lt($examStartsAt)) {
                return view('student.exams.secure-waiting', compact('exam', 'student', 'examStartsAt'));
            }

            if ($exam->end_datetime && $now->gt($exam->end_datetime)) {
                return redirect()->route('student.exams.index')
                    ->withErrors(['error' => 'This secure exam has ended.']);
            }

            // Check for existing session
            $existingSession = \App\Models\ExamSession::where('exam_id', $exam->id)
                ->where('student_id', $student->id)
                ->first();

            if ($existingSession && $existingSession->isTerminated() && $exam->answers()->where('student_id', $student->id)->doesntExist()) {
                return redirect()->route('student.exams.index')
                    ->withErrors(['error' => 'Your exam session was terminated: ' . $existingSession->termination_reason]);
            }

            return view('student.exams.secure-show', compact('exam', 'student', 'answers', 'existingResult'));
        }

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
            $totalScore = 0;

            foreach ($exam->questions as $question) {
                $studentAnswer = trim((string) $submittedAnswers[$question->id]);
                $correctAnswer = trim((string) ($question->answer_key ?? ''));
                // Determine correctness using fuzzy matching for short answers
                $isCorrect = null;

                if ($correctAnswer !== '' && $studentAnswer !== '') {
                    if ($question->question_type === 'multiple_choice') {
                        $parts = array_map('trim', explode('|', $correctAnswer));
                        if (count($parts) > 1) {
                            $correctAnswer = $parts[0];
                        } else {
                            $correctAnswer = $parts[0] ?? '';
                        }

                        $isCorrect = strcasecmp($studentAnswer, $correctAnswer) === 0;
                    } else {
                        // Short/long answer: normalize and apply fuzzy similarity
                        $normalizedStudent = $this->normalizeAnswer($studentAnswer);
                        $normalizedCorrect = $this->normalizeAnswer($correctAnswer);

                        if ($normalizedStudent === '' || $normalizedCorrect === '') {
                            $isCorrect = false;
                        } else {
                            $lev = levenshtein($normalizedStudent, $normalizedCorrect);
                            $maxLen = max(strlen($normalizedStudent), strlen($normalizedCorrect));
                            $similarity = $maxLen > 0 ? (1 - ($lev / $maxLen)) : 0;
                            $isCorrect = $similarity >= 0.75 || $lev <= 1;
                        }
                    }

                    if ($isCorrect) {
                        $totalScore += $question->points;
                    }
                }

                ExamAnswer::create([
                    'exam_question_id' => $question->id,
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                    'answer_text' => $studentAnswer,
                    'is_correct' => $isCorrect,
                ]);
            }

            // Save the auto-calculated score
            ExamResult::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                ],
                [
                    'score' => $totalScore,
                    'remarks' => 'Auto-graded',
                ]
            );

            if ($exam->secure_mode) {
                $session = \App\Models\ExamSession::where('exam_id', $exam->id)
                    ->where('student_id', $student->id)
                    ->first();

                if ($session && !$session->isTerminated()) {
                    $session->ended_at = now();
                    $session->termination_reason = 'Student submitted exam';
                    $session->save();
                }
            }
        });

        return redirect()->route('student.exams.index')
            ->with('success', 'Your answers have been submitted and auto-graded successfully.');
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
