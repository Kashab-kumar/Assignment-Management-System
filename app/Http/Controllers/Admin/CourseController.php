<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $selectedCategory = request('category_name');
        $selectedClass = request('class_name');

        $courses = Course::withCount('students')
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
        $course->load(['students.user']);

        $relatedCourses = collect();
        if (!empty($course->class_name)) {
            $relatedCourses = Course::query()
                ->where('class_name', $course->class_name)
                ->where('id', '!=', $course->id)
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'category_name']);
        }

        return view('admin.courses.show', compact('course', 'relatedCourses'));
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

        return view('admin.courses.create', compact('categoryOptions', 'classOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:courses,code',
            'category_name' => 'required|string|max:255',
            'class_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Course::create($validated);

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
