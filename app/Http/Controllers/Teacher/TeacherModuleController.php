<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\CourseModule;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Exam;
use Illuminate\Http\Request;

class TeacherModuleController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;

        // Only show modules where this teacher is explicitly assigned as the instructor
        $modules = CourseModule::with(['course', 'assignments', 'exams'])
            ->where('teacher_id', $teacher->id)
            ->orderBy('position')
            ->get();

        return view('teacher.modules.index', compact('modules'));
    }

    public function show(CourseModule $module)
    {
        // Verify teacher owns this module
        $teacher = auth()->user()->teacher;
        if ($module->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access');
        }

        $module->load(['course', 'assignments', 'exams', 'course.students']);

        // Get module activities
        $activities = $this->getModuleActivities($module);

        return view('teacher.modules.show', compact('module', 'activities'));
    }

    public function create()
    {
        $teacher = auth()->user()->teacher;
        $courses = Course::whereHas('teachers', function($query) use ($teacher) {
            $query->where('course_teacher.teacher_id', $teacher->id);
        })->get();

        return view('teacher.modules.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'weightage' => 'required|numeric|min:0|max:100',
            'model_unit_outline' => 'required|string',
            'position' => 'required|integer|min:1'
        ]);

        $teacher = auth()->user()->teacher;

        $module = CourseModule::create([
            'course_id' => $request->course_id,
            'teacher_id' => $teacher->id,
            'title' => $request->title,
            'description' => $request->description,
            'weightage' => $request->weightage,
            'model_unit_outline' => $request->model_unit_outline,
            'position' => $request->position,
            'is_active' => true
        ]);

        return redirect()->route('teacher.modules.show', $module)
            ->with('success', 'Module created successfully!');
    }

    public function edit(CourseModule $module)
    {
        $teacher = auth()->user()->teacher;
        if ($module->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access');
        }

        $courses = Course::whereHas('teachers', function($query) use ($teacher) {
            $query->where('course_teacher.teacher_id', $teacher->id);
        })->get();

        return view('teacher.modules.edit', compact('module', 'courses'));
    }

    public function update(Request $request, CourseModule $module)
    {
        $teacher = auth()->user()->teacher;
        if ($module->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'weightage' => 'required|numeric|min:0|max:100',
            'model_unit_outline' => 'required|string',
            'position' => 'required|integer|min:1'
        ]);

        $module->update($request->all());

        return redirect()->route('teacher.modules.show', $module)
            ->with('success', 'Module updated successfully!');
    }

    public function destroy(CourseModule $module)
    {
        $teacher = auth()->user()->teacher;
        if ($module->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access');
        }

        $module->delete();

        return redirect()->route('teacher.modules.index')
            ->with('success', 'Module deleted successfully!');
    }

    private function getModuleActivities(CourseModule $module)
    {
        $activities = collect();

        // Get assignments
        $assignments = $module->assignments->map(function($assignment) {
            return [
                'type' => 'assignment',
                'id' => $assignment->id,
                'title' => $assignment->title,
                'description' => $assignment->description,
                'weightage' => $assignment->weightage ?? 0,
                'max_marks' => $assignment->max_marks ?? 100,
                'due_date' => $assignment->due_date,
                'status' => $this->getAssignmentStatus($assignment),
                'created_at' => $assignment->created_at
            ];
        });

        // Get exams
        $exams = $module->exams->map(function($exam) {
            return [
                'type' => 'exam',
                'id' => $exam->id,
                'title' => $exam->title,
                'description' => $exam->description,
                'weightage' => $exam->weightage ?? 0,
                'max_marks' => $exam->max_marks ?? 100,
                'exam_date' => $exam->exam_date,
                'status' => $this->getExamStatus($exam),
                'created_at' => $exam->created_at
            ];
        });

        return $activities->merge($assignments)->merge($exams)->sortBy('created_at');
    }

    private function getAssignmentStatus($assignment)
    {
        $now = now();

        if ($assignment->due_date && $now->gt($assignment->due_date)) {
            return 'overdue';
        } elseif ($assignment->due_date && $now->diffInDays($assignment->due_date) <= 3) {
            return 'due_soon';
        } else {
            return 'active';
        }
    }

    private function getExamStatus($exam)
    {
        $now = now();

        if ($exam->exam_date && $now->gt($exam->exam_date)) {
            return 'completed';
        } elseif ($exam->exam_date && $now->diffInDays($exam->exam_date) <= 3) {
            return 'upcoming';
        } else {
            return 'scheduled';
        }
    }
}
