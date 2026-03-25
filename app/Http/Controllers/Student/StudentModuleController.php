<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
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

        $course->load('teachers');

        $teacherNames = $course->teachers
            ->pluck('name')
            ->filter()
            ->unique()
            ->values();

        $modules = $course->modules()
            ->with('teacher')
            ->when($moduleItemsEnabled, fn ($query) => $query->with('items'))
            ->get();

        $moduleCards = $modules->map(function ($module) use ($teacherNames, $moduleItemsEnabled) {
            $moduleTeacherNames = collect();

            if ($module->teacher) {
                $moduleTeacherNames = collect([$module->teacher->name]);
            } elseif ($teacherNames->isNotEmpty()) {
                $moduleTeacherNames = $teacherNames;
            }

            return [
                'id' => $module->id,
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

        return view('student.modules.index', [
            'student' => $student,
            'course' => $course,
            'moduleCards' => $moduleCards,
            'moduleItemsEnabled' => $moduleItemsEnabled,
            'modulesEnabled' => true,
        ]);
    }
}
