<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\Unit;
use App\Models\StudentUnitGrade;
use App\Models\Assignment;
use App\Models\Submission;
use App\Services\UnitGradingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AnalyticsController extends Controller
{
    protected UnitGradingService $gradingService;

    public function __construct(UnitGradingService $gradingService)
    {
        $this->gradingService = $gradingService;
        $this->middleware('auth');
    }

    /**
     * Student Dashboard - Show student's syllabus mastery progress
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function studentDashboard(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Student profile not found');
        }

        $course = $student->course;

        if (!$course) {
            return redirect()->route('dashboard')->with('error', 'No course assigned');
        }

        // Get student's unit grades
        $unitGrades = $student->unitGrades()
            ->where('course_id', $course->id)
            ->with('unit')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get overall course progress
        $courseProgress = $this->gradingService->getStudentCourseProgress($student, $course);

        // Get all units in the course for calculation purposes
        $allUnits = $course->modules()
            ->with('units')
            ->get()
            ->pluck('units')
            ->flatten()
            ->sortBy('order');

        return view('analytics.student-dashboard', [
            'student' => $student,
            'course' => $course,
            'unitGrades' => $unitGrades,
            'allUnits' => $allUnits,
            'courseProgress' => $courseProgress,
        ]);
    }

    /**
     * Teacher Dashboard - Show class performance analytics
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function teacherDashboard(Request $request)
    {
        $user = Auth::user();

        // Get courses taught by this teacher
        $courses = Course::whereHas('teacher', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        if ($courses->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'No courses found');
        }

        // For simplicity, default to first course (can be made selectable)
        $course = $request->course_id
            ? $courses->find($request->course_id)
            : $courses->first();

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found');
        }

        // Get all units in the course
        $units = $course->modules()
            ->with('units')
            ->get()
            ->pluck('units')
            ->flatten()
            ->sortBy('order');

        // Get statistics for each unit
        $unitStatistics = [];
        foreach ($units as $unit) {
            $unitStatistics[$unit->id] = $this->gradingService->getClassUnitStatistics($unit, $course);
        }

        // Get class-wide statistics
        $allGrades = StudentUnitGrade::where('course_id', $course->id)->get();
        $classAverage = $allGrades->avg('percentage') ?? 0;

        $studentCount = $course->students()->count();
        $failingCount = StudentUnitGrade::where('course_id', $course->id)
            ->where('percentage', '<', 50)
            ->distinct('student_id')
            ->count('student_id');

        // Upcoming assignments (next 5)
        $teacher = $user->teacher;
        $upcomingAssignments = Assignment::where('course_id', $course->id)
            ->when($teacher, fn($q) => $q->where('teacher_id', $teacher->id))
            ->whereDate('due_date', '>=', now())
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // Pending grading: recent submissions for this course not graded yet
        $pendingSubmissions = Submission::whereHas('assignment', function ($q) use ($course, $teacher) {
                $q->where('course_id', $course->id)
                    ->when($teacher, fn($q2) => $q2->where('teacher_id', $teacher->id));
            })
            ->where(function ($q) {
                $q->whereNull('status')->orWhere('status', '!=', 'graded');
            })
            ->orderBy('submitted_at', 'desc')
            ->take(5)
            ->get();

        return view('analytics.teacher-dashboard', [
            'courses' => $courses,
            'course' => $course,
            'units' => $units,
            'unitStatistics' => $unitStatistics,
            'classAverage' => round($classAverage, 2),
            'studentCount' => $studentCount,
            'failingCount' => $failingCount,
            'upcomingAssignments' => $upcomingAssignments ?? collect(),
            'pendingSubmissions' => $pendingSubmissions ?? collect(),
        ]);
    }

    /**
     * API endpoint: Get syllabus mastery data for student
     * Returns data formatted for Chart.js
     *
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSyllabusMasteryData(Course $course)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $units = $course->modules()
            ->with('units')
            ->get()
            ->pluck('units')
            ->flatten()
            ->sortBy('order');

        $labels = [];
        $data = [];
        $colors = [];

        foreach ($units as $unit) {
            $labels[] = $unit->title;

            $grade = StudentUnitGrade::where('student_id', $student->id)
                ->where('unit_id', $unit->id)
                ->first();

            $percentage = $grade ? $grade->percentage : 0;
            $data[] = $percentage;

            // Color based on performance: red < 50%, yellow 50-80%, green >= 80%
            if ($percentage >= 80) {
                $colors[] = 'rgba(34, 197, 94, 0.8)'; // Green
            } elseif ($percentage >= 50) {
                $colors[] = 'rgba(234, 179, 8, 0.8)'; // Yellow
            } else {
                $colors[] = 'rgba(239, 68, 68, 0.8)'; // Red
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
            'passingThreshold' => 50,
        ]);
    }

    /**
     * API endpoint: Get class performance distribution for teacher
     * Returns data formatted for Chart.js
     *
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassPerformanceData(Course $course)
    {
        $units = $course->modules()
            ->with('units')
            ->get()
            ->pluck('units')
            ->flatten()
            ->sortBy('order');

        $labels = [];
        $averages = [];
        $passingRates = [];

        foreach ($units as $unit) {
            $labels[] = $unit->title;

            $stats = $this->gradingService->getClassUnitStatistics($unit, $course);
            $averages[] = $stats['average_percentage'];
            $passingRates[] = $stats['pass_rate'];
        }

        return response()->json([
            'labels' => $labels,
            'averages' => $averages,
            'passingRates' => $passingRates,
        ]);
    }

    /**
     * API endpoint: Get student performance distribution for a unit
     *
     * @param Unit $unit
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnitPerformanceDistribution(Unit $unit, Course $course)
    {
        $grades = StudentUnitGrade::where('unit_id', $unit->id)
            ->where('course_id', $course->id)
            ->orderBy('percentage')
            ->pluck('percentage')
            ->toArray();

        // Create buckets for histogram
        $buckets = [
            '0-10' => 0,
            '10-20' => 0,
            '20-30' => 0,
            '30-40' => 0,
            '40-50' => 0,
            '50-60' => 0,
            '60-70' => 0,
            '70-80' => 0,
            '80-90' => 0,
            '90-100' => 0,
        ];

        foreach ($grades as $percentage) {
            $bucket = (int)floor($percentage / 10) * 10;
            $key = $bucket . '-' . ($bucket + 10);
            if (isset($buckets[$key])) {
                $buckets[$key]++;
            }
        }

        return response()->json([
            'labels' => array_keys($buckets),
            'data' => array_values($buckets),
        ]);
    }

    /**
     * API endpoint: Get individual student performance summary
     *
     * @param Student $student
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentSummary(Student $student, Course $course)
    {
        $grades = $student->unitGrades()
            ->where('course_id', $course->id)
            ->with('unit')
            ->get();

        $summary = [
            'total_units' => $student->course_id == $course->id ? $course->modules()->count() : 0,
            'units_with_grades' => $grades->count(),
            'mastered_units' => $grades->where('status', 'Mastered')->count(),
            'in_progress_units' => $grades->where('status', 'In Progress')->count(),
            'failing_units' => $grades->where('status', 'Needs Attention')->count(),
            'average_percentage' => round($grades->avg('percentage') ?? 0, 2),
        ];

        return response()->json($summary);
    }

    /**
     * Trigger recalculation of all unit grades for a course
     *
     * @param Course $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function recalculateAllGrades(Course $course)
    {
        $this->authorize('update', $course);

        $units = $course->modules()
            ->with('units')
            ->get()
            ->pluck('units')
            ->flatten();

        foreach ($units as $unit) {
            $this->gradingService->calculateAllStudentUnitGrades($unit, $course);
        }

        return redirect()->back()->with('success', 'All unit grades have been recalculated');
    }
}
