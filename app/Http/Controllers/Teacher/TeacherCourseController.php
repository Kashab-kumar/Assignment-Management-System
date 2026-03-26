<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TeacherCourseController extends Controller
{
    public function index()
    {
        $selectedCategory = request('category_name');
        $selectedClass = request('class_name');
        $assignedCourseIds = $this->assignedCourseIds();

        $courses = Course::withCount('students')
            ->whereIn('id', $assignedCourseIds)
            ->when($selectedCategory, fn ($q) => $q->where('category_name', $selectedCategory))
            ->when($selectedClass, fn ($q) => $q->where('class_name', $selectedClass))
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        $courseTree = $courses
            ->groupBy(fn ($c) => $c->category_name ?: 'Uncategorized')
            ->map(fn ($cat) => $cat->groupBy(fn ($c) => $c->class_name ?: 'Unassigned Class'));

        $categoryOptions = Course::whereNotNull('category_name')->where('category_name', '!=', '')
            ->distinct()->orderBy('category_name')->pluck('category_name');

        $classOptions = Course::whereNotNull('class_name')->where('class_name', '!=', '')
            ->distinct()->orderBy('class_name')->pluck('class_name');

        return view('teacher.courses.index', compact(
            'courses', 'courseTree', 'categoryOptions', 'classOptions', 'selectedCategory', 'selectedClass'
        ));
    }

    public function show(Course $course)
    {
        abort_unless(in_array($course->id, $this->assignedCourseIds(), true), 403);

        $modulesEnabled = Schema::hasTable('course_modules');
        $moduleItemsEnabled = Schema::hasTable('course_module_items');

        $course->load(['students.user'])
            ->loadCount(['assignments', 'exams']);

        if ($modulesEnabled) {
            $course->load([
                'modules' => function ($query) use ($moduleItemsEnabled) {
                    $query->with('teacher');
                    if ($moduleItemsEnabled) {
                        $query->with('items.creator');
                    }
                },
            ]);
        }

        $relatedCourses = collect();
        if (!empty($course->class_name)) {
            $relatedCourses = Course::where('class_name', $course->class_name)
                ->where('id', '!=', $course->id)
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'category_name']);
        }

        return view('teacher.courses.show', compact('course', 'relatedCourses', 'modulesEnabled', 'moduleItemsEnabled'));
    }

    public function storeModuleItem(Request $request, Course $course, CourseModule $module)
    {
        abort_unless(in_array($course->id, $this->assignedCourseIds(), true), 403);

        $teacher = $request->user()->teacher;

        if (!$teacher) {
            abort(403);
        }

        if (!Schema::hasTable('course_module_items')) {
            return back()->withErrors(['error' => 'Course module items table not found. Please run migrations first.']);
        }

        if ($module->course_id !== $course->id) {
            abort(404);
        }

        if (!empty($module->teacher_id) && (int) $module->teacher_id !== (int) $teacher->id) {
            return back()->withErrors([
                'error' => 'You can only add content to modules assigned to you.',
            ]);
        }

        $validated = $request->validate([
            'type' => 'required|in:unit_outline,quiz,test,note',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        $nextPosition = ($module->items()->max('position') ?? 0) + 1;

        $module->items()->create([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content' => $validated['description'] ?? null,
            'position' => $nextPosition,
            'created_by' => $request->user()->id,
            'is_active' => true,
        ]);

        return back()->with('success', 'Module content added successfully.');
    }

    public function showModule(Course $course, CourseModule $module)
    {
        abort_unless(in_array($course->id, $this->assignedCourseIds(), true), 403);

        if ($module->course_id !== $course->id) {
            abort(404);
        }

        $moduleItemsEnabled = Schema::hasTable('course_module_items');

        $module->load([
            'course',
            'teacher',
            'items' => fn ($query) => $query->with('creator')->latest('created_at')->take(12),
        ]);

        $assignments = Assignment::query()
            ->withCount('submissions')
            ->where('course_id', $course->id)
            ->latest('due_date')
            ->take(10)
            ->get();

        $exams = Exam::query()
            ->withCount('results')
            ->withAvg('results', 'score')
            ->where('course_id', $course->id)
            ->orderByDesc('exam_date')
            ->take(10)
            ->get();

        $recentSubmissions = Submission::query()
            ->with(['student', 'assignment'])
            ->whereHas('assignment', fn ($query) => $query->where('course_id', $course->id))
            ->latest('submitted_at')
            ->take(10)
            ->get();

        $recentResults = ExamResult::query()
            ->with(['student', 'exam'])
            ->whereHas('exam', fn ($query) => $query->where('course_id', $course->id))
            ->latest()
            ->take(10)
            ->get();

        $recents = collect();

        if ($moduleItemsEnabled) {
            $recents = $module->items
                ->toBase()
                ->map(function ($item) {
                    return [
                        'kind' => 'module_item',
                        'title' => $item->title,
                        'subtitle' => ucfirst(str_replace('_', ' ', $item->type)) . ' by ' . ($item->creator?->name ?? 'Teacher'),
                        'date' => $item->created_at,
                    ];
                });
        }

        $recents = $recents
            ->merge($recentSubmissions->map(function ($submission) {
                return [
                    'kind' => 'submission',
                    'title' => $submission->assignment?->title ?? 'Assignment submission',
                    'subtitle' => ($submission->student?->name ?? 'Student') . ' submitted',
                    'date' => $submission->submitted_at,
                ];
            }))
            ->merge($recentResults->map(function ($result) {
                return [
                    'kind' => 'grade',
                    'title' => $result->exam?->title ?? 'Assessment result',
                    'subtitle' => ($result->student?->name ?? 'Student') . ' scored ' . $result->score,
                    'date' => $result->created_at,
                ];
            }))
            ->filter(fn ($item) => !empty($item['date']))
            ->sortByDesc('date')
            ->take(12)
            ->values();

        return view('teacher.courses.module', compact(
            'course',
            'module',
            'assignments',
            'exams',
            'recentSubmissions',
            'recentResults',
            'recents',
            'moduleItemsEnabled'
        ));
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
