<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;

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
        $course->load(['students.user'])
            ->loadCount(['assignments', 'exams']);

        $relatedCourses = collect();
        if (!empty($course->class_name)) {
            $relatedCourses = Course::where('class_name', $course->class_name)
                ->where('id', '!=', $course->id)
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'category_name']);
        }

        return view('teacher.courses.show', compact('course', 'relatedCourses'));
    }
}
