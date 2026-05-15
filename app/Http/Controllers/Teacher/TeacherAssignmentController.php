<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Submission;
use App\Services\GradingService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $selectedCourseId = $request->integer('course_id') ?: null;
        $selectedModuleId = $request->integer('module_id') ?: null;
        $assignedCourseIds = $this->assignedCourseIds();

        if ($selectedCourseId && !in_array($selectedCourseId, $assignedCourseIds, true)) {
            $selectedCourseId = null;
            $selectedModuleId = null;
        }

        $assignments = Assignment::with(['course'])
            ->withCount('submissions')
            ->whereIn('course_id', $assignedCourseIds)
            ->when($selectedCourseId, fn ($q) => $q->where('course_id', $selectedCourseId))
            ->when($selectedModuleId, fn ($q) => $q->where('module_id', $selectedModuleId))
            ->latest()
            ->get();

        $courses = Course::query()
            ->whereIn('id', $assignedCourseIds)
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        return view('teacher.assignments.index', compact('assignments', 'courses', 'selectedCourseId', 'selectedModuleId'));
    }

    public function create(Request $request)
    {
        $selectedCourseId = $request->integer('course_id') ?: null;
        $selectedModuleId = $request->integer('module_id') ?: null;
        $selectedUnitId = $request->integer('unit_id') ?: null;
        $assignedCourseIds = $this->assignedCourseIds();

        // If module_id is provided, get the course_id from the module
        if ($selectedModuleId && !$selectedCourseId) {
            $module = CourseModule::find($selectedModuleId);
            if ($module && in_array($module->course_id, $assignedCourseIds, true)) {
                $selectedCourseId = $module->course_id;
            }
        }

        if ($selectedCourseId && !in_array($selectedCourseId, $assignedCourseIds, true)) {
            $selectedCourseId = null;
        }

        $courses = Course::query()
            ->whereIn('id', $assignedCourseIds)
            ->with(['modules' => fn ($query) => $query->with('units')->orderBy('position')])
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        return view('teacher.assignments.create', compact('courses', 'selectedCourseId', 'selectedModuleId', 'selectedUnitId'));
    }

    public function store(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $assignedCourseIds = $this->assignedCourseIds();

        $validated = $request->validate([
            'course_id' => ['required', Rule::in($assignedCourseIds)],
            'module_id' => 'required|exists:course_modules,id',
            'unit_id' => 'required|exists:units,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'instruction_file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,webp',
            'type' => 'required|in:essay,project,quiz,presentation,homework,lab,other',
            'due_date' => 'required|date',
            'max_score' => 'required|integer|min:1',
            'weightage' => 'required|numeric|min:0|max:100',
            'covered_topics' => 'nullable|array',
            'covered_topics.*' => 'string',
            'selected_questions' => 'nullable|array',
        ]);

        // Verify teacher owns this module
        $module = CourseModule::findOrFail($validated['module_id']);
        if ($module->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to this module');
        }

        $unit = \App\Models\Unit::findOrFail($validated['unit_id']);
        if ((int) $unit->module_id !== (int) $module->id) {
            return back()->withInput()->withErrors([
                'unit_id' => 'Selected chapter/unit does not belong to the selected module.',
            ]);
        }

        if ($request->hasFile('instruction_file')) {
            $file = $request->file('instruction_file');
            $validated['instruction_file_path'] = $file->store('assignment-instructions', 'public');
            $validated['instruction_file_name'] = $file->getClientOriginalName();
        }

        $validated['teacher_id'] = $teacher->id;
        $validated['unit_id'] = $unit->id;

        // Store covered_topics as JSON array
        $validated['covered_topics'] = $validated['covered_topics'] ?? [];
        // Normalize selected_questions to array of objects: [{id, marks}]
        if (!empty($validated['selected_questions'])) {
            $normalized = [];
            foreach ($validated['selected_questions'] as $sq) {
                if (is_array($sq)) {
                    $id = isset($sq['id']) ? intval($sq['id']) : null;
                    $marks = isset($sq['marks']) && $sq['marks'] !== '' ? floatval($sq['marks']) : null;
                } else {
                    $id = intval($sq);
                    $marks = null;
                }
                if ($id) $normalized[] = ['id' => $id, 'marks' => $marks];
            }
            $validated['selected_questions'] = $normalized;
        } else {
            $validated['selected_questions'] = null;
        }

        $assignment = Assignment::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Assignment created successfully!'], 201);
        }

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment created successfully!');
    }

    public function show(Assignment $assignment)
    {
        abort_unless(in_array($assignment->course_id, $this->assignedCourseIds(), true), 403);

        // Get all students in the course
        $allStudents = $assignment->course->students()->with('user')->get();

        // Get submissions with their students
        $submissions = $assignment->submissions()->with('student.user')->get();
        $submittedStudentIds = $submissions->pluck('student_id')->toArray();

        // Get students who haven't submitted
        $nonSubmittedStudents = $allStudents->filter(function ($student) use ($submittedStudentIds) {
            return !in_array($student->id, $submittedStudentIds, true);
        });

        return view('teacher.assignments.show', compact('assignment', 'submissions', 'nonSubmittedStudents', 'allStudents'));
    }

    public function gradeSubmission(Request $request, Submission $submission)
    {
        abort_unless(in_array($submission->assignment?->course_id, $this->assignedCourseIds(), true), 403);

        $validated = $request->validate([
            'score' => 'required|integer|min:0',
            'grade' => 'nullable|string|max:20',
            'feedback' => 'nullable|string',
        ]);

        $teacher = $request->user()->teacher;
        $grade = $validated['grade'] ?: $this->deriveLetterGrade((float) $validated['score'], (float) $submission->assignment->max_score);

        $submission->update([
            'score' => $validated['score'],
            'grade' => $grade,
            'feedback' => $validated['feedback'] ?? $submission->feedback,
            'status' => 'graded',
            'graded_by' => $teacher?->id,
            'graded_at' => now(),
        ]);

        return back()->with('success', 'Submission graded successfully!');
    }

    public function gradeWithAI(Submission $submission)
    {
        abort_unless(in_array($submission->assignment?->course_id, $this->assignedCourseIds(), true), 403);

        $assignment = $submission->assignment;
        $module = $assignment->module;
        $unitOutlines = $module ? $module->units : [];

        $gradingService = new GradingService();
        $result = $gradingService->gradeAssignment($submission, $assignment, $unitOutlines);

        $submission->update([
            'score' => $result['score'],
            'grade' => $this->deriveLetterGrade((float) $result['score'], (float) $assignment->max_score),
            'status' => 'graded',
            'feedback' => $result['feedback'] ?? null,
            'graded_by' => auth()->user()->teacher?->id,
            'graded_at' => now(),
        ]);

        return back()->with('success', 'Submission graded using ' . $result['method'] . ' approach!');
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
}
