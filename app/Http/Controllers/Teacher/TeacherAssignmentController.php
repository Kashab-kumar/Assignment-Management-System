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
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        return view('teacher.assignments.create', compact('courses', 'selectedCourseId', 'selectedModuleId'));
    }

    public function store(Request $request)
    {
        $teacher = auth()->user()->teacher;
        $assignedCourseIds = $this->assignedCourseIds();

        $validated = $request->validate([
            'course_id' => ['required', Rule::in($assignedCourseIds)],
            'module_id' => 'required|exists:course_modules,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'instruction_file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,webp',
            'type' => 'required|in:essay,project,quiz,presentation,homework,lab,other',
            'due_date' => 'required|date',
            'max_score' => 'required|integer|min:1',
            'weightage' => 'required|numeric|min:0|max:100'
        ]);

        // Verify teacher owns this module
        $module = CourseModule::findOrFail($validated['module_id']);
        if ($module->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to this module');
        }

        if ($request->hasFile('instruction_file')) {
            $file = $request->file('instruction_file');
            $validated['instruction_file_path'] = $file->store('assignment-instructions', 'public');
            $validated['instruction_file_name'] = $file->getClientOriginalName();
        }

        $validated['teacher_id'] = $teacher->id;

        $assignment = Assignment::create($validated);

        return redirect()->route('teacher.modules.show', $validated['module_id'])
            ->with('success', 'Assignment created successfully!');
    }

    public function show(Assignment $assignment)
    {
        abort_unless(in_array($assignment->course_id, $this->assignedCourseIds(), true), 403);

        $submissions = $assignment->submissions()->with('student')->get();
        return view('teacher.assignments.show', compact('assignment', 'submissions'));
    }

    public function gradeSubmission(Request $request, Submission $submission)
    {
        abort_unless(in_array($submission->assignment?->course_id, $this->assignedCourseIds(), true), 403);

        $validated = $request->validate([
            'score' => 'required|integer|min:0'
        ]);

        $submission->update([
            'score' => $validated['score'],
            'status' => 'graded'
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
            'status' => 'graded',
            'feedback' => $result['feedback'] ?? null
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
}
