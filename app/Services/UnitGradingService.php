<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Unit;
use App\Models\Course;
use App\Models\StudentUnitGrade;
use App\Models\Submission;
use App\Models\ExamResult;
use App\Models\UnitAssessmentConfiguration;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UnitGradingService
{
    /**
     * Calculate and update a student's grade for a specific unit
     * Uses weighted assessment types if configured, otherwise simple average
     *
     * @param Student $student
     * @param Unit $unit
     * @param Course $course
     * @return StudentUnitGrade
     */
    public function calculateUnitGrade(Student $student, Unit $unit, Course $course): StudentUnitGrade
    {
        // Check if unit has assessment configurations
        $configurations = $unit->assessmentConfigurations()->where('is_active', true)->get();

        if ($configurations->isNotEmpty()) {
            // Use weighted calculation based on assessment configurations
            $percentage = $this->calculateWeightedUnitGrade($student, $unit, $configurations);
        } else {
            // Fallback to simple average if no configurations
            $percentage = $this->calculateSimpleUnitGrade($student, $unit);
        }

        // Determine status based on percentage
        $status = $this->determineStatus($percentage);

        // Get total attempt count
        $attemptCount = $this->getAttemptCount($student, $unit);

        // Get or create the student unit grade record
        $unitGrade = StudentUnitGrade::updateOrCreate(
            [
                'student_id' => $student->id,
                'unit_id' => $unit->id,
                'course_id' => $course->id,
            ],
            [
                'achieved_score' => null, // Will be calculated based on weights
                'total_possible_score' => null,
                'percentage' => round($percentage, 2),
                'status' => $status,
                'attempt_count' => $attemptCount,
                'first_attempted_at' => $this->getFirstAttemptDate($student, $unit),
                'last_attempted_at' => now()->toDateString(),
            ]
        );

        return $unitGrade;
    }

    /**
     * Calculate unit grade using weighted assessment types
     *
     * @param Student $student
     * @param Unit $unit
     * @param $configurations
     * @return float
     */
    private function calculateWeightedUnitGrade(Student $student, Unit $unit, $configurations): float
    {
        $weightedScore = 0;
        $totalWeight = 0;

        foreach ($configurations as $config) {
            $weight = $config->weight_percent / 100; // Convert to decimal

            // Calculate average for this assessment type
            $assessmentAverage = $this->getAssessmentTypeAverage($student, $unit, $config->assessment_type);

            // Add weighted score
            $weightedScore += ($assessmentAverage * $weight);
            $totalWeight += $weight;
        }

        // Return weighted average (should be 0-100)
        return $totalWeight > 0 ? $weightedScore : 0;
    }

    /**
     * Calculate unit grade using simple average (no weights)
     * Fallback method if assessment configurations not set
     *
     * @param Student $student
     * @param Unit $unit
     * @return float
     */
    private function calculateSimpleUnitGrade(Student $student, Unit $unit): float
    {
        $assignments = $unit->assignments()->get();
        $exams = $unit->exams()->get();

        // Calculate total possible marks
        $assignmentTotalMarks = $assignments->sum('max_score');
        $examTotalMarks = $exams->sum('max_score');
        $totalPossibleMarks = $assignmentTotalMarks + $examTotalMarks;

        // Get student's achieved marks
        $assignmentAchievedMarks = $this->getStudentAssignmentMarks($student, $assignments);
        $examAchievedMarks = $this->getStudentExamMarks($student, $exams);
        $totalAchievedMarks = $assignmentAchievedMarks + $examAchievedMarks;

        // Calculate percentage
        return $totalPossibleMarks > 0
            ? ($totalAchievedMarks / $totalPossibleMarks) * 100
            : 0;
    }

    /**
     * Get average percentage for a specific assessment type in a unit
     *
     * @param Student $student
     * @param Unit $unit
     * @param string $assessmentType
     * @return float
     */
    private function getAssessmentTypeAverage(Student $student, Unit $unit, string $assessmentType): float
    {
        $scores = [];
        $totalPossible = 0;

        // Get assignments of this type
        if (in_array($assessmentType, ['assignment', 'homework', 'project', 'practical'])) {
            $assignments = $unit->assignments()
                ->where('assessment_type', $assessmentType)
                ->get();

            foreach ($assignments as $assignment) {
                $submission = Submission::where('student_id', $student->id)
                    ->where('assignment_id', $assignment->id)
                    ->where('status', 'graded')
                    ->latest()
                    ->first();

                if ($submission) {
                    $score = ($submission->score / $assignment->max_score) * 100;
                    $scores[] = $score;
                    $totalPossible += 1;
                }
            }
        }

        // Get exams of this type
        if (in_array($assessmentType, ['exam', 'quiz', 'test'])) {
            $exams = $unit->exams()
                ->where('assessment_type', $assessmentType)
                ->get();

            foreach ($exams as $exam) {
                $result = ExamResult::where('student_id', $student->id)
                    ->where('exam_id', $exam->id)
                    ->latest()
                    ->first();

                if ($result) {
                    $score = ($result->score / $exam->max_score) * 100;
                    $scores[] = $score;
                    $totalPossible += 1;
                }
            }
        }

        // Return average or 0 if no assessments found
        return count($scores) > 0 ? array_sum($scores) / count($scores) : 0;
    /**
     * Calculate grades for all students in a unit
     *
     * @param Unit $unit
     * @param Course $course
     * @return void
     */
    public function calculateAllStudentUnitGrades(Unit $unit, Course $course): void
    {
        $students = $course->students()->get();

        foreach ($students as $student) {
            $this->calculateUnitGrade($student, $unit, $course);
        }
    }

    /**
     * Get student's total marks from assignments in this unit
     *
     * @param Student $student
     * @param $assignments
     * @return float
     */
    private function getStudentAssignmentMarks(Student $student, $assignments): float
    {
        $totalMarks = 0;

        foreach ($assignments as $assignment) {
            $submission = Submission::where('student_id', $student->id)
                ->where('assignment_id', $assignment->id)
                ->where('status', 'graded')
                ->latest()
                ->first();

            if ($submission) {
                $totalMarks += $submission->score ?? 0;
            }
        }

        return $totalMarks;
    }

    /**
     * Get student's total marks from exams in this unit
     *
     * @param Student $student
     * @param $exams
     * @return float
     */
    private function getStudentExamMarks(Student $student, $exams): float
    {
        $totalMarks = 0;

        foreach ($exams as $exam) {
            $result = ExamResult::where('student_id', $student->id)
                ->where('exam_id', $exam->id)
                ->latest()
                ->first();

            if ($result) {
                $totalMarks += $result->score ?? 0;
            }
        }

        return $totalMarks;
    }

    /**
     * Determine status based on percentage
     * RIM passing threshold: 50%
     * Mastered: >= 80%
     * In Progress: 50-80%
     * Needs Attention: < 50%
     *
     * @param float $percentage
     * @return string
     */
    private function determineStatus(float $percentage): string
    {
        if ($percentage >= 80) {
            return 'Mastered';
        } elseif ($percentage >= 50) {
            return 'In Progress';
        } else {
            return 'Needs Attention';
        }
    }

    /**
     * Get total attempt count for a student in a unit
     *
     * @param Student $student
     * @param Unit $unit
     * @return int
     */
    private function getAttemptCount(Student $student, Unit $unit): int
    {
        $assignmentAttempts = Submission::whereHas('assignment', function ($query) use ($unit) {
            $query->where('unit_id', $unit->id);
        })
            ->where('student_id', $student->id)
            ->count();

        $examAttempts = ExamResult::whereHas('exam', function ($query) use ($unit) {
            $query->where('unit_id', $unit->id);
        })
            ->where('student_id', $student->id)
            ->count();

        return $assignmentAttempts + $examAttempts;
    }

    /**
     * Get the first attempt date for a student in a unit
     *
     * @param Student $student
     * @param Unit $unit
     * @return string|null
     */
    private function getFirstAttemptDate(Student $student, Unit $unit): ?string
    {
        $firstAssignmentDate = Submission::whereHas('assignment', function ($query) use ($unit) {
            $query->where('unit_id', $unit->id);
        })
            ->where('student_id', $student->id)
            ->oldest('created_at')
            ->value('created_at');

        $firstExamDate = ExamResult::whereHas('exam', function ($query) use ($unit) {
            $query->where('unit_id', $unit->id);
        })
            ->where('student_id', $student->id)
            ->oldest('created_at')
            ->value('created_at');

        // Return the earliest date
        if ($firstAssignmentDate && $firstExamDate) {
            return min($firstAssignmentDate, $firstExamDate)->toDateString();
        } elseif ($firstAssignmentDate) {
            return Carbon::parse($firstAssignmentDate)->toDateString();
        } elseif ($firstExamDate) {
            return Carbon::parse($firstExamDate)->toDateString();
        }

        return null;
    }

    /**
     * Get overall course progress for a student based on unit weights
     *
     * @param Student $student
     * @param Course $course
     * @return array
     */
    public function getStudentCourseProgress(Student $student, Course $course): array
    {
        $unitGrades = $student->unitGrades()
            ->where('course_id', $course->id)
            ->with('unit')
            ->get();

        $units = $course->modules()
            ->with('units')
            ->get()
            ->pluck('units')
            ->flatten();

        $totalWeightage = $units->sum('weightage_percent');
        $weightedScore = 0;

        foreach ($unitGrades as $unitGrade) {
            $unitWeightage = $unitGrade->unit->weightage_percent ?? 0;
            $weightedScore += ($unitGrade->percentage * $unitWeightage) / 100;
        }

        $overallPercentage = $totalWeightage > 0 ? ($weightedScore / $totalWeightage) * 100 : 0;

        return [
            'overall_percentage' => round($overallPercentage, 2),
            'units_completed' => $unitGrades->count(),
            'total_units' => $units->count(),
            'mastered' => $unitGrades->where('status', 'Mastered')->count(),
            'in_progress' => $unitGrades->where('status', 'In Progress')->count(),
            'needs_attention' => $unitGrades->where('status', 'Needs Attention')->count(),
        ];
    }

    /**
     * Get class statistics for a unit (for teacher analytics)
     *
     * @param Unit $unit
     * @param Course $course
     * @return array
     */
    public function getClassUnitStatistics(Unit $unit, Course $course): array
    {
        $grades = StudentUnitGrade::where('unit_id', $unit->id)
            ->where('course_id', $course->id)
            ->get();

        $averagePercentage = $grades->avg('percentage') ?? 0;
        $medianPercentage = $this->calculateMedian($grades->pluck('percentage')->toArray());
        $highestPercentage = $grades->max('percentage') ?? 0;
        $lowestPercentage = $grades->min('percentage') ?? 0;

        $studentsAboveThreshold = $grades->where('percentage', '>=', 50)->count();
        $studentsBelowThreshold = $grades->where('percentage', '<', 50)->count();

        return [
            'average_percentage' => round($averagePercentage, 2),
            'median_percentage' => round($medianPercentage, 2),
            'highest_percentage' => round($highestPercentage, 2),
            'lowest_percentage' => round($lowestPercentage, 2),
            'students_passing' => $studentsAboveThreshold,
            'students_failing' => $studentsBelowThreshold,
            'total_students' => $grades->count(),
            'pass_rate' => $grades->count() > 0 ? round(($studentsAboveThreshold / $grades->count()) * 100, 2) : 0,
        ];
    }

    /**
     * Calculate median value
     *
     * @param array $array
     * @return float
     */
    private function calculateMedian(array $array): float
    {
        if (empty($array)) {
            return 0;
        }

        sort($array);
        $count = count($array);
        $middle = floor($count / 2);

        if ($count % 2 === 0) {
            return ($array[$middle - 1] + $array[$middle]) / 2;
        }

        return $array[$middle];
    }
}
