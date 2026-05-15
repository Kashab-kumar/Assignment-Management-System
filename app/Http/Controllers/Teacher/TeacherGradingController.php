<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamResult;
use App\Models\Student;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class TeacherGradingController extends Controller
{
    public function assignmentIndex(Course $course, Request $request)
    {
        $this->authorizeCourse($course);

        $search = trim((string) $request->query('search', ''));
        $selectedModuleId = $request->integer('module_id') ?: null;
        $sort = $request->query('sort', 'latest');

        $assignments = Assignment::query()
            ->with(['module', 'unit'])
            ->withCount('submissions')
            ->where('course_id', $course->id)
            ->when($search !== '', fn ($query) => $query->where('title', 'like', '%' . $search . '%'))
            ->when($selectedModuleId, fn ($query) => $query->where('module_id', $selectedModuleId))
            ->when($sort === 'oldest', fn ($query) => $query->orderBy('created_at'))
            ->when($sort === 'latest', fn ($query) => $query->latest())
            ->get()
            ->map(function (Assignment $assignment) use ($course) {
                $submittedCount = $assignment->submissions_count ?? 0;
                $pendingCount = $assignment->submissions()
                    ->where(function ($query) {
                        $query->whereNull('status')->orWhere('status', '!=', 'graded');
                    })
                    ->count();

                return [
                    'model' => $assignment,
                    'title' => $assignment->title,
                    'chapter' => $assignment->unit?->title ?: $assignment->module?->title ?: 'General',
                    'due_date' => $assignment->due_date,
                    'total_students' => $course->students()->count(),
                    'submitted' => $submittedCount,
                    'pending' => $pendingCount,
                ];
            });

        $assignments = $this->paginateCollection($assignments, 10)->withQueryString();
        $modules = $course->modules()->orderBy('position')->get(['id', 'title']);

        return view('teacher.grading.assignment-index', compact('course', 'assignments', 'modules', 'search', 'selectedModuleId', 'sort'));
    }

    public function examIndex(Course $course, Request $request)
    {
        $this->authorizeCourse($course);

        $search = trim((string) $request->query('search', ''));
        $selectedModuleId = $request->integer('module_id') ?: null;
        $sort = $request->query('sort', 'latest');

        $exams = Exam::query()
            ->with(['module', 'unit'])
            ->withCount(['results', 'answers'])
            ->where('course_id', $course->id)
            ->when($search !== '', fn ($query) => $query->where('title', 'like', '%' . $search . '%'))
            ->when($selectedModuleId, fn ($query) => $query->where('module_id', $selectedModuleId))
            ->when($sort === 'oldest', fn ($query) => $query->orderBy('created_at'))
            ->when($sort === 'latest', fn ($query) => $query->latest())
            ->get()
            ->map(function (Exam $exam) use ($course) {
                $submittedCount = $this->countExamAttempts($exam);
                $gradedCount = $exam->results_count ?? 0;
                $pendingCount = max($submittedCount - $gradedCount, 0);

                return [
                    'model' => $exam,
                    'title' => $exam->title,
                    'chapter' => $exam->unit?->title ?: $exam->module?->title ?: 'General',
                    'due_date' => $exam->exam_date,
                    'total_students' => $course->students()->count(),
                    'submitted' => $submittedCount,
                    'pending' => $pendingCount,
                ];
            });

        $exams = $this->paginateCollection($exams, 10)->withQueryString();
        $modules = $course->modules()->orderBy('position')->get(['id', 'title']);

        return view('teacher.grading.exam-index', compact('course', 'exams', 'modules', 'search', 'selectedModuleId', 'sort'));
    }

    public function assignmentSubmissions(Assignment $assignment, Request $request)
    {
        $this->authorizeCourse($assignment->course);

        $assignment->load(['course', 'module', 'unit']);

        $search = trim((string) $request->query('search', ''));
        $statusFilter = $request->query('status', 'all');

        $students = Student::query()
            ->with('user')
            ->where('course_id', $assignment->course_id)
            ->orderBy('name')
            ->get();

        $submissions = $assignment->submissions()
            ->with('student.user')
            ->get()
            ->keyBy('student_id');

        $rows = $students->map(function (Student $student) use ($submissions, $assignment) {
            $submission = $submissions->get($student->id);
            $status = 'missing';
            $submittedAt = null;
            $marks = null;

            if ($submission) {
                $submittedAt = $submission->submitted_at;
                $marks = $submission->score;

                if ($submission->status === 'graded') {
                    $status = 'graded';
                } elseif ($submittedAt && $assignment->due_date && $submittedAt->greaterThan($assignment->due_date)) {
                    $status = 'late';
                } else {
                    $status = 'submitted';
                }
            }

            return [
                'student' => $student,
                'submission' => $submission,
                'status' => $status,
                'submitted_at' => $submittedAt,
                'marks' => $marks,
            ];
        });

        $rows = $rows
            ->filter(function (array $row) use ($search, $statusFilter) {
                $matchesSearch = $search === ''
                    || str_contains(strtolower((string) ($row['student']->name ?? '')), strtolower($search))
                    || str_contains(strtolower((string) ($row['student']->student_id ?? '')), strtolower($search));

                $matchesStatus = $statusFilter === 'all' || $row['status'] === $statusFilter;

                return $matchesSearch && $matchesStatus;
            })
            ->values();

        $submissions = $this->paginateCollection($rows, 10)->withQueryString();

        return view('teacher.grading.assignment-submissions', compact('assignment', 'submissions', 'search', 'statusFilter'));
    }

    public function showAssignmentSubmission(Submission $submission)
    {
        $submission->load(['student.user', 'assignment.course', 'assignment.module', 'assignment.unit']);
        $this->authorizeCourse($submission->assignment->course);

        $orderedSubmissions = $submission->assignment->submissions()
            ->with('student.user')
            ->orderBy('submitted_at')
            ->get()
            ->values();

        $currentIndex = $orderedSubmissions->search(fn (Submission $item) => $item->id === $submission->id);
        $previousSubmission = $currentIndex !== false ? $orderedSubmissions->get($currentIndex - 1) : null;
        $nextSubmission = $currentIndex !== false ? $orderedSubmissions->get($currentIndex + 1) : null;

        return view('teacher.grading.submission-show', compact('submission', 'previousSubmission', 'nextSubmission'));
    }

    public function updateAssignmentSubmission(Request $request, Submission $submission)
    {
        $submission->load('assignment.course');
        $this->authorizeCourse($submission->assignment->course);

        $validated = $request->validate([
            'score' => 'required|numeric|min:0',
            'grade' => 'nullable|string|max:20',
            'feedback' => 'nullable|string',
            'publish_action' => 'nullable|in:draft,publish',
        ]);

        $teacher = $request->user()->teacher;
        $publish = ($validated['publish_action'] ?? 'publish') === 'publish';
        $grade = $validated['grade'] ?: $this->deriveLetterGrade((float) $validated['score'], (float) $submission->assignment->max_score);

        $submission->update([
            'score' => $validated['score'],
            'grade' => $grade,
            'feedback' => $validated['feedback'] ?? null,
            'status' => $publish ? 'graded' : 'pending',
            'graded_by' => $teacher?->id,
            'graded_at' => $publish ? now() : null,
        ]);

        return back()->with('success', $publish ? 'Grade published successfully.' : 'Draft saved successfully.');
    }

    public function examSubmissions(Exam $exam, Request $request)
    {
        $this->authorizeCourse($exam->course);

        $search = trim((string) $request->query('search', ''));
        $statusFilter = $request->query('status', 'all');

        $students = Student::query()
            ->with('user')
            ->where('course_id', $exam->course_id)
            ->orderBy('name')
            ->get();

        $answers = ExamAnswer::query()
            ->with(['student.user', 'question'])
            ->where('exam_id', $exam->id)
            ->get()
            ->groupBy('student_id');

        $results = ExamResult::query()
            ->where('exam_id', $exam->id)
            ->get()
            ->keyBy('student_id');

        $rows = $students->map(function (Student $student) use ($answers, $results) {
            $studentAnswers = $answers->get($student->id, collect());
            $result = $results->get($student->id);

            $status = 'missing';
            if ($result) {
                $status = 'graded';
            } elseif ($studentAnswers->isNotEmpty()) {
                $status = 'submitted';
            }

            $submittedAt = $studentAnswers->first()?->created_at;

            return [
                'student' => $student,
                'status' => $status,
                'submitted_at' => $submittedAt,
                'submitted' => $studentAnswers->isNotEmpty() ? 1 : 0,
                'pending' => $result ? 0 : ($studentAnswers->isNotEmpty() ? 1 : 0),
                'result' => $result,
                'answers' => $studentAnswers,
            ];
        });

        $rows = $rows
            ->filter(function (array $row) use ($search, $statusFilter) {
                $matchesSearch = $search === ''
                    || str_contains(strtolower((string) ($row['student']->name ?? '')), strtolower($search))
                    || str_contains(strtolower((string) ($row['student']->student_id ?? '')), strtolower($search));

                $matchesStatus = $statusFilter === 'all' || $row['status'] === $statusFilter;

                return $matchesSearch && $matchesStatus;
            })
            ->values();

        $students = $this->paginateCollection($rows, 10)->withQueryString();

        return view('teacher.grading.exam-submissions', compact('exam', 'students', 'search', 'statusFilter'));
    }

    public function showExamStudent(Exam $exam, Student $student)
    {
        $this->authorizeCourse($exam->course);
        abort_unless((int) $student->course_id === (int) $exam->course_id, 404);

        $answers = ExamAnswer::query()
            ->with('question')
            ->where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->get()
            ->sortBy(fn (ExamAnswer $answer) => $answer->question?->position ?? $answer->id)
            ->values();

        $result = ExamResult::query()
            ->where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        $orderedStudents = Student::query()
            ->with('user')
            ->where('course_id', $exam->course_id)
            ->orderBy('name')
            ->get()
            ->values();

        $currentIndex = $orderedStudents->search(fn (Student $item) => $item->id === $student->id);
        $previousStudent = $currentIndex !== false ? $orderedStudents->get($currentIndex - 1) : null;
        $nextStudent = $currentIndex !== false ? $orderedStudents->get($currentIndex + 1) : null;

        return view('teacher.grading.exam-student-show', compact('exam', 'student', 'answers', 'result', 'previousStudent', 'nextStudent'));
    }

    public function updateExamStudent(Request $request, Exam $exam, Student $student)
    {
        $this->authorizeCourse($exam->course);
        abort_unless((int) $student->course_id === (int) $exam->course_id, 404);

        $validated = $request->validate([
            'score' => 'required|numeric|min:0',
            'grade' => 'nullable|string|max:20',
            'feedback' => 'nullable|string',
            'publish_action' => 'nullable|in:draft,publish',
        ]);

        $teacher = $request->user()->teacher;
        $publish = ($validated['publish_action'] ?? 'publish') === 'publish';
        $grade = $validated['grade'] ?: $this->deriveLetterGrade((float) $validated['score'], (float) $exam->max_score);

        ExamResult::updateOrCreate(
            ['exam_id' => $exam->id, 'student_id' => $student->id],
            [
                'score' => $validated['score'],
                'grade' => $grade,
                'feedback' => $validated['feedback'] ?? null,
                'remarks' => $validated['feedback'] ?? null,
                'graded_by' => $teacher?->id,
                'graded_at' => $publish ? now() : null,
            ]
        );

        return back()->with('success', $publish ? 'Exam grade published successfully.' : 'Exam draft saved successfully.');
    }

    private function authorizeCourse(Course $course): void
    {
        abort_unless(in_array($course->id, $this->assignedCourseIds(), true), 403);
    }

    private function assignedCourseIds(): array
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return [];
        }

        return $teacher->courses()->pluck('courses.id')->all();
    }

    private function deriveLetterGrade(float $score, float $maxScore): string
    {
        if ($maxScore <= 0) {
            return 'N/A';
        }

        $percent = ($score / $maxScore) * 100;

        return match (true) {
            $percent >= 90 => 'A+',
            $percent >= 85 => 'A',
            $percent >= 80 => 'A-',
            $percent >= 75 => 'B+',
            $percent >= 70 => 'B',
            $percent >= 65 => 'B-',
            $percent >= 60 => 'C+',
            $percent >= 55 => 'C',
            $percent >= 50 => 'D',
            default => 'F',
        };
    }

    private function countExamAttempts(Exam $exam): int
    {
        return ExamAnswer::query()
            ->where('exam_id', $exam->id)
            ->select('student_id')
            ->distinct()
            ->count('student_id');
    }

    private function paginateCollection(Collection $items, int $perPage): LengthAwarePaginator
    {
        $page = Paginator::resolveCurrentPage() ?: 1;
        $items = $items->values();

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]
        );
    }
}
