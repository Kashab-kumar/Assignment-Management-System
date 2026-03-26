<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Exam;
use Illuminate\Support\Facades\Schema;

class StudentModuleController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        if (!Schema::hasTable('course_modules')) {
            return view('student.modules.index', [
                'student' => $student,
                'course' => $student->course,
                'moduleCards' => collect(),
                'moduleItemsEnabled' => false,
                'modulesEnabled' => false,
            ]);
        }

        $course = $student->course;
        $moduleItemsEnabled = Schema::hasTable('course_module_items');

        if (!$course) {
            return view('student.modules.index', [
                'student' => $student,
                'course' => null,
                'moduleCards' => collect(),
                'moduleItemsEnabled' => $moduleItemsEnabled,
                'modulesEnabled' => true,
            ]);
        }

        if (!empty($course->class_name)) {
            $coursesForStudent = Course::query()
                ->with('teachers')
                ->where('class_name', $course->class_name)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        } else {
            $coursesForStudent = Course::query()
                ->with('teachers')
                ->whereKey($course->id)
                ->get();
        }

        $moduleCards = $coursesForStudent
            ->flatMap(function ($studentCourse) use ($moduleItemsEnabled) {
                $teacherNames = $studentCourse->teachers
                    ->pluck('name')
                    ->filter()
                    ->unique()
                    ->values();

                $modules = $studentCourse->modules()
                    ->with('teacher')
                    ->when($moduleItemsEnabled, fn ($query) => $query->with('items'))
                    ->get();

                return $modules->map(function ($module) use ($studentCourse, $teacherNames, $moduleItemsEnabled) {
                    $moduleTeacherNames = collect();

                    if ($module->teacher) {
                        $moduleTeacherNames = collect([$module->teacher->name]);
                    } elseif ($teacherNames->isNotEmpty()) {
                        $moduleTeacherNames = $teacherNames;
                    }

                    return [
                        'id' => $module->id,
                        'course_id' => $studentCourse->id,
                        'course_name' => $studentCourse->name,
                        'course_code' => $studentCourse->code,
                        'class_name' => $studentCourse->class_name,
                        'position' => (int) $module->position,
                        'title' => $module->title,
                        'description' => $module->description,
                        'lesson_count' => (int) $module->lesson_count,
                        'assignment_count' => (int) $module->assignment_count,
                        'quiz_count' => (int) $module->quiz_count,
                        'item_count' => $moduleItemsEnabled ? $module->items->count() : 0,
                        'is_active' => (bool) data_get($module, 'is_active', true),
                        'teachers' => $moduleTeacherNames,
                    ];
                });
            })
            ->sortBy([
                ['class_name', 'asc'],
                ['course_name', 'asc'],
                ['position', 'asc'],
                ['id', 'asc'],
            ])
            ->values();

        return view('student.modules.index', [
            'student' => $student,
            'course' => $course,
            'coursesCount' => $coursesForStudent->count(),
            'moduleCards' => $moduleCards,
            'moduleItemsEnabled' => $moduleItemsEnabled,
            'modulesEnabled' => true,
        ]);
    }

    public function show(CourseModule $module)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        if (!$student->course_id) {
            abort(404);
        }

        $allowedCourseIds = Course::query()
            ->where('is_active', true)
            ->where(function ($query) use ($student) {
                $query->whereKey($student->course_id);

                if (!empty($student->course?->class_name)) {
                    $query->orWhere('class_name', $student->course->class_name);
                }
            })
            ->pluck('id')
            ->all();

        abort_unless(in_array($module->course_id, $allowedCourseIds, true), 404);

        $moduleItemsEnabled = Schema::hasTable('course_module_items');

        $module->load([
            'course',
            'teacher',
            'course.teachers',
            'items' => fn ($query) => $query->with('creator')->latest('created_at')->take(8),
        ]);

        $assignments = Assignment::query()
            ->with(['submissions' => fn ($query) => $query->where('student_id', $student->id)])
            ->where('course_id', $module->course_id)
            ->latest('due_date')
            ->take(8)
            ->get();

        $exams = Exam::query()
            ->with([
                'results' => fn ($query) => $query->where('student_id', $student->id),
                'answers' => fn ($query) => $query->where('student_id', $student->id),
            ])
            ->where('course_id', $module->course_id)
            ->orderByDesc('exam_date')
            ->take(8)
            ->get();

        $gradedAssignments = $student->submissions()
            ->where('status', 'graded')
            ->whereHas('assignment', fn ($query) => $query->where('course_id', $module->course_id))
            ->with('assignment')
            ->latest('submitted_at')
            ->take(6)
            ->get();

        $examResults = $student->examResults()
            ->whereHas('exam', fn ($query) => $query->where('course_id', $module->course_id))
            ->with('exam')
            ->latest()
            ->take(6)
            ->get();

        $assignmentAverage = (float) ($gradedAssignments->avg('score') ?? 0);
        $examAverage = (float) ($examResults->avg('score') ?? 0);
        $overallAverage = collect([$assignmentAverage, $examAverage])
            ->filter(fn ($value) => $value > 0)
            ->avg() ?? 0;

        $recents = collect();

        if ($moduleItemsEnabled) {
            $recents = $module->items
                ->toBase()
                ->map(function ($item) {
                    return [
                        'kind' => 'module_item',
                        'title' => $item->title,
                        'subtitle' => ucfirst(str_replace('_', ' ', $item->type)),
                        'date' => $item->created_at,
                    ];
                });
        }

        $recents = $recents
            ->merge($assignments->map(function ($assignment) {
                return [
                    'kind' => 'assignment',
                    'title' => $assignment->title,
                    'subtitle' => 'Assignment due ' . $assignment->due_date?->format('M d, Y'),
                    'date' => $assignment->due_date,
                ];
            }))
            ->merge($exams->map(function ($exam) {
                return [
                    'kind' => 'exam',
                    'title' => $exam->title,
                    'subtitle' => ucfirst($exam->type) . ' on ' . $exam->exam_date?->format('M d, Y'),
                    'date' => $exam->exam_date,
                ];
            }))
            ->filter(fn ($item) => !empty($item['date']))
            ->sortByDesc('date')
            ->take(10)
            ->values();

        return view('student.modules.show', compact(
            'student',
            'module',
            'assignments',
            'exams',
            'gradedAssignments',
            'examResults',
            'assignmentAverage',
            'examAverage',
            'overallAverage',
            'recents',
            'moduleItemsEnabled'
        ));
    }
}
