<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CourseController extends Controller
{
    public function index()
    {
        $selectedCategory = request('category_name');
        $selectedClass = request('class_name');

        $courses = Course::with(['teachers:id,name'])
            ->withCount('students')
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                $query->where('category_name', $selectedCategory);
            })
            ->when($selectedClass, function ($query) use ($selectedClass) {
                $query->where('class_name', $selectedClass);
            })
            ->orderBy('category_name')
            ->orderBy('class_name')
            ->orderBy('name')
            ->get();

        $courseTree = $courses
            ->groupBy(fn ($course) => $course->category_name ?: 'Uncategorized')
            ->map(function ($categoryCourses) {
                return $categoryCourses->groupBy(fn ($course) => $course->class_name ?: 'Unassigned Class');
            });

        $categoryOptions = Course::query()
            ->whereNotNull('category_name')
            ->where('category_name', '!=', '')
            ->distinct()
            ->orderBy('category_name')
            ->pluck('category_name');

        $classOptions = Course::query()
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name');

        return view('admin.courses.index', compact('courses', 'courseTree', 'categoryOptions', 'classOptions', 'selectedCategory', 'selectedClass'));
    }

    public function show(Course $course)
    {
        $modulesEnabled = Schema::hasTable('course_modules');
        $moduleItemsEnabled = Schema::hasTable('course_module_items');

        $course->load(['students.user', 'teachers']);

        $availableTeachers = $course->teachers;

        if ($availableTeachers->isEmpty()) {
            $availableTeachers = Teacher::query()->orderBy('name')->get(['id', 'name', 'teacher_id']);
        }

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
            $relatedCourses = Course::query()
                ->where('class_name', $course->class_name)
                ->where('id', '!=', $course->id)
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'category_name']);
        }

        return view('admin.courses.show', compact('course', 'relatedCourses', 'modulesEnabled', 'moduleItemsEnabled', 'availableTeachers'));
    }

    public function storeModule(Request $request, Course $course)
    {
        if (!Schema::hasTable('course_modules')) {
            return back()->withErrors(['error' => 'Course modules table not found. Please run migrations first.']);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'lesson_count' => 'nullable|integer|min:0',
            'assignment_count' => 'nullable|integer|min:0',
            'quiz_count' => 'nullable|integer|min:0',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $nextPosition = ($course->modules()->max('position') ?? 0) + 1;

        $course->modules()->create([
            'teacher_id' => $validated['teacher_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'position' => $nextPosition,
            'lesson_count' => (int) ($validated['lesson_count'] ?? 0),
            'assignment_count' => (int) ($validated['assignment_count'] ?? 0),
            'quiz_count' => (int) ($validated['quiz_count'] ?? 0),
            'is_active' => true,
        ]);

        if (!empty($validated['teacher_id'])) {
            $course->teachers()->syncWithoutDetaching([(int) $validated['teacher_id']]);
        }

        return back()->with('success', 'Module added successfully.');
    }

    public function create()
    {
        $categoryOptions = Course::query()
            ->whereNotNull('category_name')
            ->where('category_name', '!=', '')
            ->distinct()
            ->orderBy('category_name')
            ->pluck('category_name');

        $classOptions = Course::query()
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name');

        $teachers = Teacher::query()
            ->orderBy('name')
            ->get(['id', 'name', 'teacher_id']);

        return view('admin.courses.create', compact('categoryOptions', 'classOptions', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:courses,code',
            'category_name' => 'required|string|max:255',
            'class_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'modules' => 'nullable|array',
            'modules.*.title' => 'required_with:modules|string|max:255',
            'modules.*.description' => 'nullable|string|max:2000',
            'modules.*.lesson_count' => 'nullable|integer|min:0',
            'modules.*.assignment_count' => 'nullable|integer|min:0',
            'modules.*.quiz_count' => 'nullable|integer|min:0',
            'modules.*.teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $course = Course::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'category_name' => $validated['category_name'],
            'class_name' => $validated['class_name'],
            'description' => $validated['description'] ?? null,
        ]);

        $modules = collect($validated['modules'] ?? [])->filter(function ($module) {
            return !empty(trim((string) ($module['title'] ?? '')));
        })->values();

        if ($modules->isNotEmpty()) {
            foreach ($modules as $index => $module) {
                $course->modules()->create([
                    'teacher_id' => !empty($module['teacher_id']) ? (int) $module['teacher_id'] : null,
                    'title' => trim((string) $module['title']),
                    'description' => $module['description'] ?? null,
                    'position' => $index + 1,
                    'lesson_count' => (int) ($module['lesson_count'] ?? 0),
                    'assignment_count' => (int) ($module['assignment_count'] ?? 0),
                    'quiz_count' => (int) ($module['quiz_count'] ?? 0),
                    'is_active' => true,
                ]);
            }

            $teacherIds = $modules
                ->pluck('teacher_id')
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values()
                ->all();

            if (!empty($teacherIds)) {
                $course->teachers()->syncWithoutDetaching($teacherIds);
            }
        }

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully!');
    }

    public function edit(Course $course)
    {
        $categoryOptions = Course::query()
            ->whereNotNull('category_name')
            ->where('category_name', '!=', '')
            ->distinct()
            ->orderBy('category_name')
            ->pluck('category_name');

        $classOptions = Course::query()
            ->whereNotNull('class_name')
            ->where('class_name', '!=', '')
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name');

        return view('admin.courses.edit', compact('course', 'categoryOptions', 'classOptions'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:courses,code,' . $course->id,
            'category_name' => 'required|string|max:255',
            'class_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {
        // Check if course has students
        if ($course->students()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete course with enrolled students.']);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully!');
    }
}
