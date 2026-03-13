<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TeacherCourseController extends Controller
{
    public function index()
    {
        $selectedCategory = request('category_name');
        $selectedClass = request('class_name');

        $courses = Course::withCount('students')
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
        $modulesEnabled = Schema::hasTable('course_modules');
        $moduleItemsEnabled = Schema::hasTable('course_module_items');

        $course->load(['students.user'])
            ->loadCount(['assignments', 'exams']);

        if ($modulesEnabled) {
            $course->load([
                'modules' => function ($query) use ($moduleItemsEnabled) {
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
        if (!Schema::hasTable('course_module_items')) {
            return back()->withErrors(['error' => 'Course module items table not found. Please run migrations first.']);
        }

        if ($module->course_id !== $course->id) {
            abort(404);
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
}
