# Quick Reference Guide - Syllabus-to-Grade Mapping

## Quick Start (TL;DR)

```bash
# 1. Run migrations
php artisan migrate

# 2. Access dashboards
# Student:  http://yoursite.com/student/analytics/dashboard
# Teacher:  http://yoursite.com/teacher/analytics/dashboard

# 3. Set unit weightages in course management (e.g., 15%, 25%, 60%)

# 4. Link assignments/exams to units via unit_id field

# 5. Watch grades automatically update as students submit work
```

## File Structure

```
├── database/migrations/
│   ├── 2026_05_11_000000_add_weightage_to_units_table.php
│   ├── 2026_05_11_000001_add_unit_id_to_assignments_table.php
│   ├── 2026_05_11_000002_add_unit_id_to_exams_table.php
│   └── 2026_05_11_000003_create_student_unit_grades_table.php
│
├── app/Models/
│   ├── StudentUnitGrade.php                    [NEW]
│   ├── Unit.php                                [UPDATED]
│   ├── Student.php                             [UPDATED]
│   ├── Assignment.php                          [UPDATED]
│   └── Exam.php                                [UPDATED]
│
├── app/Services/
│   └── UnitGradingService.php                  [NEW]
│
├── app/Http/Controllers/
│   └── AnalyticsController.php                 [NEW]
│
├── resources/views/analytics/
│   ├── student-dashboard.blade.php             [NEW]
│   └── teacher-dashboard.blade.php             [NEW]
│
├── routes/
│   └── web.php                                 [UPDATED - added routes]
│
├── docs/
│   ├── SYLLABUS_GRADE_MAPPING_GUIDE.md         [NEW - Full guide]
│   ├── SYSTEM_ARCHITECTURE_DIAGRAMS.md         [NEW - Visual diagrams]
│   └── QUICK_REFERENCE.md                      [This file]
```

## Code Examples

### 1. Calculate Grade for a Student

```php
use App\Services\UnitGradingService;
use App\Models\Student;
use App\Models\Unit;
use App\Models\Course;

$gradingService = app(UnitGradingService::class);
$student = Student::find(1);
$unit = Unit::find(5);
$course = Course::find(3);

$unitGrade = $gradingService->calculateUnitGrade($student, $unit, $course);

echo $unitGrade->percentage;  // 75.50
echo $unitGrade->status;      // "In Progress"
```

### 2. Recalculate All Grades for a Course

```php
$service = app(UnitGradingService::class);
$course = Course::find(3);

foreach ($course->modules as $module) {
    foreach ($module->units as $unit) {
        $service->calculateAllStudentUnitGrades($unit, $course);
    }
}
```

### 3. Get Course Progress for Student

```php
$service = app(UnitGradingService::class);
$student = Student::find(1);
$course = Course::find(3);

$progress = $service->getStudentCourseProgress($student, $course);
// Returns:
// {
//   "overall_percentage": 72.45,
//   "units_completed": 4,
//   "total_units": 4,
//   "mastered": 1,
//   "in_progress": 2,
//   "needs_attention": 1
// }
```

### 4. Get Class Statistics

```php
$service = app(UnitGradingService::class);
$unit = Unit::find(5);
$course = Course::find(3);

$stats = $service->getClassUnitStatistics($unit, $course);
// Returns:
// {
//   "average_percentage": 68.5,
//   "median_percentage": 70.0,
//   "highest_percentage": 95.0,
//   "lowest_percentage": 25.0,
//   "students_passing": 28,
//   "students_failing": 2,
//   "total_students": 30,
//   "pass_rate": 93.33
// }
```

### 5. Trigger Calculation on Submission Grade

```php
// In TeacherSubmissionController (after grading)
$submission->score = $score;
$submission->status = 'graded';
$submission->save();

// Auto-trigger grade calculation
$unit = $submission->assignment->unit;
if ($unit) {
    $service = app(UnitGradingService::class);
    $service->calculateUnitGrade(
        $submission->student,
        $unit,
        $submission->assignment->course
    );
}
```

## Route Reference

### Student Routes

| Method | Endpoint | Action |
|--------|----------|--------|
| GET | `/student/analytics/dashboard` | View student dashboard |
| GET | `/student/api/analytics/syllabus-mastery/{course}` | Get chart data |
| GET | `/student/api/analytics/student-summary/{student}/{course}` | Get summary stats |

### Teacher Routes

| Method | Endpoint | Action |
|--------|----------|--------|
| GET | `/teacher/analytics/dashboard` | View teacher dashboard |
| GET | `/teacher/api/analytics/class-performance/{course}` | Get class performance data |
| GET | `/teacher/api/analytics/unit-distribution/{unit}/{course}` | Get distribution data |
| POST | `/teacher/analytics/recalculate-grades/{course}` | Recalculate all grades |

## Database Queries

### Get Student's Unit Grades

```php
$grades = StudentUnitGrade::where('student_id', 1)
    ->where('course_id', 3)
    ->with('unit')
    ->orderBy('percentage', 'desc')
    ->get();

foreach ($grades as $grade) {
    echo $grade->unit->title . ": " . $grade->percentage . "%\n";
}
```

### Get Failing Students in a Unit

```php
$failingStudents = StudentUnitGrade::where('unit_id', 5)
    ->where('percentage', '<', 50)
    ->with('student.user')
    ->get();

foreach ($failingStudents as $grade) {
    echo $grade->student->name . " - " . $grade->percentage . "%\n";
}
```

### Get Class Average Progress

```php
$average = StudentUnitGrade::where('course_id', 3)
    ->avg('percentage');

echo "Class Average: " . round($average, 2) . "%";
```

## Customization Examples

### Change Status Thresholds

Edit `app/Services/UnitGradingService.php`:

```php
private function determineStatus(float $percentage): string
{
    if ($percentage >= 90) {
        return 'Excellent';      // Changed from 'Mastered'
    } elseif ($percentage >= 75) {
        return 'Good';           // Changed
    } elseif ($percentage >= 50) {
        return 'Satisfactory';   // Changed
    } else {
        return 'Failing';        // Changed
    }
}
```

### Add Auto-calculation to Exam Grading

Edit `app/Http/Controllers/Teacher/TeacherExamController.php`:

```php
public function upsertResult(Request $request, Exam $exam)
{
    $result = ExamResult::updateOrCreate(
        ['exam_id' => $exam->id, 'student_id' => $request->student_id],
        ['score' => $request->score]
    );
    
    // NEW: Auto-calculate unit grade
    if ($exam->unit_id) {
        $service = app(UnitGradingService::class);
        $service->calculateUnitGrade(
            Student::find($request->student_id),
            $exam->unit,
            $exam->course
        );
    }
    
    return response()->json($result);
}
```

## Chart.js Customization

### Change Bar Colors

In `AnalyticsController.php`, `getSyllabusMasteryData()` method:

```php
foreach ($units as $unit) {
    $percentage = /* ... */;
    
    // Customize colors here
    if ($percentage >= 90) {
        $colors[] = 'rgba(59, 130, 246, 0.8)';   // Blue
    } elseif ($percentage >= 70) {
        $colors[] = 'rgba(34, 197, 94, 0.8)';   // Green
    } elseif ($percentage >= 50) {
        $colors[] = 'rgba(234, 179, 8, 0.8)';   // Amber
    } else {
        $colors[] = 'rgba(239, 68, 68, 0.8)';   // Red
    }
}
```

### Add Chart Title & Subtitle

In blade view:

```php
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-900">Unit Performance</h2>
        <p class="text-gray-600">Your progress across course units</p>
    </div>
    <canvas id="syllabusMasteryChart"></canvas>
</div>
```

## Troubleshooting

### Issue: Grades showing as 0%

**Check:**
1. Unit has assignments/exams linked (`unit_id` set)
2. Student has submissions (`submission.status = 'graded'`)
3. Call `recalculateAllGrades` endpoint
4. Verify max_score > 0 on assignments/exams

### Issue: Chart not loading

**Check:**
1. Course ID in URL is valid
2. Student has unit grades (check DB)
3. Browser console for JS errors
4. Chart.js CDN is accessible

### Issue: Wrong pass rate

**Check:**
1. Status calculation uses correct threshold (50% by default)
2. All grades have been calculated
3. Weightages sum to 100% (if using weighted calculation)

### Issue: Dashboard very slow

**Optimize:**
1. Add database indexes:
   ```php
   $table->index(['student_id', 'course_id']);
   $table->index(['unit_id', 'course_id']);
   ```

2. Cache statistics:
   ```php
   $stats = Cache::remember("unit-{$unit->id}", 3600, fn() => 
       $service->getClassUnitStatistics($unit, $course)
   );
   ```

## Testing Checklist

- [ ] Unit created with weightage_percent
- [ ] Assignment linked to unit (unit_id set)
- [ ] Exam linked to unit (unit_id set)
- [ ] Student submission graded successfully
- [ ] StudentUnitGrade record created/updated
- [ ] Student dashboard shows correct percentage
- [ ] Color coding correct (red/yellow/green)
- [ ] Teacher dashboard shows class average
- [ ] Charts load without errors
- [ ] Responsive on mobile

## Performance Tips

1. **Use eager loading:**
   ```php
   $grades = StudentUnitGrade::with('student', 'unit', 'course')->get();
   ```

2. **Batch calculations:**
   ```php
   foreach ($students->chunk(50) as $chunk) {
       // Calculate grades for chunk
   }
   ```

3. **Queue long operations:**
   ```php
   dispatch(new CalculateUnitGrades($course))->onQueue('high');
   ```

4. **Cache frequently accessed data:**
   ```php
   Cache::tags(['unit-grades'])->flush(); // Invalidate when needed
   ```

## Support Resources

- Full Implementation Guide: `SYLLABUS_GRADE_MAPPING_GUIDE.md`
- System Diagrams: `SYSTEM_ARCHITECTURE_DIAGRAMS.md`
- Service Code: `app/Services/UnitGradingService.php`
- Controller Code: `app/Http/Controllers/AnalyticsController.php`

---

**Last Updated:** May 11, 2026
**Version:** 1.0
