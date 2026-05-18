<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Exam;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

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
                'courses' => collect(),
                'moduleItemsEnabled' => false,
                'modulesEnabled' => false,
            ]);
        }

        $course = $student->course;
        $moduleItemsEnabled = Schema::hasTable('course_module_items');

        if (!$course) {
            return view('student.modules.index', [
                'student' => $student,
                'courses' => collect(),
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

        $courses = $coursesForStudent->map(function ($studentCourse) {
            $teacherNames = $studentCourse->teachers
                ->pluck('name')
                ->filter()
                ->unique()
                ->values();

            return [
                'id' => $studentCourse->id,
                'code' => $studentCourse->code,
                'name' => $studentCourse->name,
                'category_name' => $studentCourse->category_name,
                'class_name' => $studentCourse->class_name,
                'description' => $studentCourse->description,
                'teachers' => $teacherNames,
            ];
        })
            ->sortBy([
                ['class_name', 'asc'],
                ['name', 'asc'],
            ])
            ->values();

        return view('student.modules.index', [
            'student' => $student,
            'courses' => $courses,
            'moduleItemsEnabled' => $moduleItemsEnabled,
            'modulesEnabled' => true,
        ]);
    }

    public function showCourse(Course $course)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        // Verify student has access to this course
        $allowedCourseIds = [];
        if ($student->course_id) {
            $allowedCourseIds[] = $student->course_id;
            if (!empty($student->course?->class_name)) {
                $allowedCourseIds = Course::query()
                    ->where('class_name', $student->course->class_name)
                    ->where('is_active', true)
                    ->pluck('id')
                    ->all();
            }
        }

        abort_unless(in_array($course->id, $allowedCourseIds, true), 404);

        $moduleItemsEnabled = Schema::hasTable('course_module_items');

        $teacherNames = $course->teachers
            ->pluck('name')
            ->filter()
            ->unique()
            ->values();

        $modules = $course->modules()
            ->with('teacher')
            ->when($moduleItemsEnabled, fn ($query) => $query->with(['items', 'units']))
            ->get();

        $moduleCards = $modules->map(function ($module) use ($course, $teacherNames, $moduleItemsEnabled) {
            $moduleTeacherNames = collect();

            if ($module->teacher) {
                $moduleTeacherNames = collect([$module->teacher->name]);
            } elseif ($teacherNames->isNotEmpty()) {
                $moduleTeacherNames = $teacherNames;
            }

            return [
                'id' => $module->id,
                'course_id' => $course->id,
                'course_name' => $course->name,
                'position' => (int) $module->position,
                'title' => $module->title,
                'description' => $module->description,
                'lesson_count' => (int) $module->lesson_count,
                'assignment_count' => (int) $module->assignment_count,
                'quiz_count' => (int) $module->quiz_count,
                // prefer counting actual units (chapters) if present, otherwise fall back to module items
                'item_count' => $moduleItemsEnabled ? ($module->units && $module->units->count() > 0 ? $module->units->count() : $module->items->count()) : 0,
                'is_active' => (bool) data_get($module, 'is_active', true),
                'teachers' => $moduleTeacherNames,
            ];
        })
            ->sortBy([
                ['position', 'asc'],
                ['id', 'asc'],
            ])
            ->values();

        return view('student.courses.show', [
            'student' => $student,
            'course' => $course,
            'modules' => $moduleCards,
            'teachers' => $teacherNames,
            'moduleItemsEnabled' => $moduleItemsEnabled,
        ]);
    }

    public function showUnitOutline(Course $course, CourseModule $module)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
        }

        // Verify access
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

        abort_unless(in_array($course->id, $allowedCourseIds, true), 404);
        abort_unless($module->course_id === $course->id, 404);

        $moduleItemsEnabled = Schema::hasTable('course_module_items');

        $module->load([
            'course',
            'teacher',
            'items' => fn ($query) => $query->with([
                'creator',
                'unit.assessmentConfigurations',
                'unit.assignments',
                'unit.tests',
                'unit.exams',
            ])->where('type', 'unit_outline')->latest('created_at'),
        ]);

        // Compute topic coverage per unit using covered_topics and selected_questions -> question tags/topics
        $coverage = [];
        // Collect all question ids referenced to eager load
        $questionIds = [];
        foreach ($module->items as $item) {
            $unit = $item->unit;
            if (!$unit) continue;
            $coverage[$unit->id] = [
                'unit' => $unit,
                'topics' => [],
            ];

            // gather topics from grading_criteria
            $topics = [];
            if ($unit->grading_criteria && is_array($unit->grading_criteria)) {
                foreach ($unit->grading_criteria as $c) {
                    if (is_array($c)) {
                        $t = $c['topic'] ?? $c['name'] ?? $c['description'] ?? null;
                        if ($t) $topics[] = $t;
                    } else {
                        $topics[] = $c;
                    }
                }
            }

            $topics = array_values(array_filter(array_map(fn($t) => is_string($t) ? trim($t) : null, $topics)));

            // initialize topic entries
            foreach ($topics as $t) {
                $coverage[$unit->id]['topics'][strtolower($t)] = [
                    'label' => $t,
                    'covered' => false,
                    'links' => [],
                ];
            }

            // scan unit assignments and exams
            $activities = collect();
            if ($unit->assignments) $activities = $activities->merge($unit->assignments);
            if ($unit->exams) $activities = $activities->merge($unit->exams);

            foreach ($activities as $act) {
                // covered_topics from assignment/exam
                $actCovered = $act->covered_topics ?? [];
                foreach ($actCovered as $ct) {
                    $key = strtolower(trim($ct));
                    if (isset($coverage[$unit->id]['topics'][$key])) {
                        $coverage[$unit->id]['topics'][$key]['covered'] = true;
                        $coverage[$unit->id]['topics'][$key]['links'][] = [
                            'type' => $act instanceof \App\Models\Assignment ? 'assignment' : 'exam',
                            'id' => $act->id,
                            'title' => $act->title ?? ($act->description ?? ''),
                        ];
                    }
                }

                // selected_questions on activity -> collect question ids
                if (!empty($act->selected_questions) && is_array($act->selected_questions)) {
                    foreach ($act->selected_questions as $sq) {
                        $qid = is_array($sq) && isset($sq['id']) ? $sq['id'] : $sq;
                        if ($qid) $questionIds[] = $qid;
                    }
                }
            }
        }

        $questionIds = array_values(array_unique($questionIds));
        $questions = [];
        if (!empty($questionIds)) {
            $qs = \App\Models\Question::whereIn('id', $questionIds)->get();
            foreach ($qs as $q) {
                $questions[$q->id] = $q;
            }
        }

        // second pass: match question topics/tags to unit topics and attach links
        foreach ($module->items as $item) {
            $unit = $item->unit;
            if (!$unit) continue;
            $activities = collect();
            if ($unit->assignments) $activities = $activities->merge($unit->assignments);
            if ($unit->exams) $activities = $activities->merge($unit->exams);

            foreach ($activities as $act) {
                if (!empty($act->selected_questions) && is_array($act->selected_questions)) {
                    foreach ($act->selected_questions as $sq) {
                        $qid = is_array($sq) && isset($sq['id']) ? $sq['id'] : $sq;
                        if (!$qid || !isset($questions[$qid])) continue;
                        $q = $questions[$qid];
                        $candidates = [];
                        if (!empty($q->topic)) $candidates[] = $q->topic;
                        if (!empty($q->tags) && is_array($q->tags)) $candidates = array_merge($candidates, $q->tags);

                        foreach ($candidates as $cand) {
                            $key = strtolower(trim($cand));
                            if (isset($coverage[$unit->id]['topics'][$key])) {
                                $coverage[$unit->id]['topics'][$key]['covered'] = true;
                                $coverage[$unit->id]['topics'][$key]['links'][] = [
                                    'type' => $act instanceof \App\Models\Assignment ? 'assignment' : 'exam',
                                    'id' => $act->id,
                                    'title' => $act->title ?? ($act->description ?? ''),
                                ];
                            }
                        }
                    }
                }
            }
        }

        return view('student.modules.unit-outline', compact('course', 'module', 'moduleItemsEnabled', 'coverage'));
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
            ->where('module_id', $module->id)
            ->latest('due_date')
            ->get();

        $exams = Exam::query()
            ->with([
                'results' => fn ($query) => $query->where('student_id', $student->id),
                'answers' => fn ($query) => $query->where('student_id', $student->id),
            ])
            ->where('course_id', $module->course_id)
            ->where(function ($query) use ($module) {
                $query->where('module_id', $module->id)
                    ->orWhereNull('module_id');
            })
            ->orderByDesc('exam_date')
            ->get();

        $gradedAssignments = $student->submissions()
            ->where('status', 'graded')
            ->whereHas('assignment', fn ($query) => $query->where('course_id', $module->course_id)->where('module_id', $module->id))
            ->with('assignment')
            ->latest('submitted_at')
            ->take(6)
            ->get();

        $examResults = $student->examResults()
            ->whereHas('exam', function ($query) use ($module) {
                $query->where('course_id', $module->course_id)
                    ->where(function ($inner) use ($module) {
                        $inner->where('module_id', $module->id)
                            ->orWhereNull('module_id');
                    });
            })
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
