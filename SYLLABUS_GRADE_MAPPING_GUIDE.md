# Syllabus-to-Grade Mapping System - Implementation Guide

## Overview

This guide explains the complete implementation of the "Syllabus-to-Grade" mapping system for your Assignment Management System. This system automatically tracks student progress against course units and weightages, providing visual analytics dashboards for both students and teachers.

## System Architecture

### Database Structure

#### 1. **Units Table** (Enhanced)
- `weightage_percent` - Percentage of total course marks allocated to this unit (e.g., 10, 15, 20)
- `is_active` - Boolean flag to include/exclude unit from calculations
- Related to assignments and exams for tracking unit-specific performance

#### 2. **Assignments Table** (Updated)
- Added `unit_id` - Foreign key linking assignment to a unit
- Added `course_id` - Foreign key for course reference

#### 3. **Exams Table** (Updated)
- Added `unit_id` - Foreign key linking exam to a unit
- Added `course_id` - Foreign key for course reference

#### 4. **Student_Unit_Grades Table** (New)
The core of the system - stores aggregated performance per student per unit:
- `student_id` (FK) - Student reference
- `unit_id` (FK) - Unit reference
- `course_id` (FK) - Course reference
- `achieved_score` - Total weighted score achieved by student in this unit
- `total_possible_score` - Maximum possible marks for this unit
- `percentage` - Calculated percentage (0-100)
- `status` - One of: 'Mastered', 'In Progress', 'Needs Attention'
- `attempt_count` - Number of submissions/attempts in this unit
- `first_attempted_at` - Date of first attempt
- `last_attempted_at` - Date of most recent attempt

## Implementation Steps

### Step 1: Run Migrations

Execute the database migrations to create/update the required tables:

```bash
php artisan migrate
```

This will:
1. Add `weightage_percent` and `is_active` fields to `units` table
2. Add `unit_id` and `course_id` fields to `assignments` table
3. Add `unit_id` and `course_id` fields to `exams` table
4. Create `student_unit_grades` table

### Step 2: Configure Unit Weightages

Teachers must set weightage percentages for each unit. The sum should ideally equal 100%.

Example:
- Unit 1: Variables & Data Types - 15%
- Unit 2: Control Flow - 20%
- Unit 3: Functions - 25%
- Unit 4: Object-Oriented Programming - 40%

Update units in your course management interface with these weightages.

### Step 3: Link Assignments and Exams to Units

When creating assignments or exams, specify which unit they belong to:

```php
// In TeacherAssignmentController
$assignment = Assignment::create([
    'title' => 'Assignment Title',
    'unit_id' => $unitId, // Must specify this
    'course_id' => $courseId, // Must specify this
    // ... other fields
]);
```

### Step 4: Access the Dashboards

#### For Students:
Navigate to: `/student/analytics/dashboard`

This shows:
- Overall progress percentage
- Unit-by-unit performance breakdown
- Syllabus mastery bar chart
- Detailed unit table with scores and status

#### For Teachers:
Navigate to: `/teacher/analytics/dashboard`

This shows:
- Class average performance
- Total students and those needing attention
- Unit average scores and pass rates
- Class performance distribution charts
- Detailed unit statistics table

## Models and Services

### UnitGradingService

Located at `app/Services/UnitGradingService.php`

**Key Methods:**

```php
// Calculate single student's unit grade
calculateUnitGrade(Student $student, Unit $unit, Course $course): StudentUnitGrade

// Calculate all students' grades for a unit
calculateAllStudentUnitGrades(Unit $unit, Course $course): void

// Get student's overall course progress
getStudentCourseProgress(Student $student, Course $course): array

// Get class statistics for a unit (teacher analytics)
getClassUnitStatistics(Unit $unit, Course $course): array
```

### StudentUnitGrade Model

Located at `app/Models/StudentUnitGrade.php`

**Useful Methods:**
```php
$grade->isMastered() // Returns true if status is 'Mastered'
$grade->isFailing()  // Returns true if percentage < 50
$grade->getStatusColorAttribute() // Returns color: 'green', 'yellow', or 'red'
```

**Relationships:**
```php
$grade->student()     // Belongs to Student
$grade->unit()        // Belongs to Unit
$grade->course()      // Belongs to Course
```

## Workflow Example

### 1. Course Setup (Teacher)
```
Teacher uploads Unit Outline
└── System creates units with weightage_percent
    ├── Unit 1: Python Basics (15%)
    ├── Unit 2: Loops & Conditions (25%)
    └── Unit 3: Functions (60%)
```

### 2. Task Creation (Teacher)
```
Teacher creates Assignment for Unit 1
└── Assignment is linked to Unit 1
    └── max_score = 50 points
```

### 3. Student Submission
```
Student submits assignment
└── Teacher grades it (score: 40/50)
    └── System updates student_unit_grades
        └── achieved_score = 40
        └── percentage = 80%
        └── status = 'Mastered'
```

### 4. Progress Tracking
```
Student views analytics dashboard
└── Sees Unit 1: 80% - Mastered (green bar)
    Overall progress updated with weightage
```

## Grade Calculation Logic

### Status Determination
```
percentage >= 80%  → 'Mastered' (Green)
50% ≤ percentage < 80% → 'In Progress' (Yellow)
percentage < 50%   → 'Needs Attention' (Red)
```

### Passing Threshold
- **RIM Standard**: 50% is the minimum passing threshold
- Bars below 50% are displayed in red
- Bars 50-80% are yellow
- Bars 80%+ are green

### Weighted Course Score
```
Overall % = Σ(Unit Percentage × Unit Weightage) / Total Weightage
```

Example:
- Unit 1: 85% × 15% = 12.75
- Unit 2: 72% × 25% = 18.00
- Unit 3: 88% × 60% = 52.80
- **Overall: 83.55%**

## API Endpoints

### Student Endpoints

**Get Syllabus Mastery Data:**
```
GET /student/api/analytics/syllabus-mastery/{course}
```
Returns: Chart.js formatted data with labels, scores, and colors

**Get Student Summary:**
```
GET /student/api/analytics/student-summary/{student}/{course}
```
Returns: JSON with unit counts and averages

### Teacher Endpoints

**Get Class Performance Data:**
```
GET /teacher/api/analytics/class-performance/{course}
```
Returns: Average scores and pass rates by unit

**Get Unit Performance Distribution:**
```
GET /teacher/api/analytics/unit-distribution/{unit}/{course}
```
Returns: Histogram data of student score distribution

**Recalculate All Grades:**
```
POST /teacher/analytics/recalculate-grades/{course}
```
Triggers recalculation of all student unit grades

## Using the Charts

### Chart.js Library

Both dashboards use **Chart.js 3.9.1** for visualization.

**CDN Link:**
```html
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
```

### Student Dashboard Charts

**1. Syllabus Mastery Bar Chart**
- Type: Bar chart
- X-axis: Unit names
- Y-axis: Percentage (0-100%)
- Features:
  - Color-coded bars (red < 50%, yellow 50-80%, green >= 80%)
  - Dotted red line at 50% threshold
  - Hover tooltips with exact percentages

### Teacher Dashboard Charts

**1. Average Scores by Unit (Line Chart)**
- Shows trend of class average across units
- Helps identify struggling units

**2. Pass Rate by Unit (Line Chart)**
- Shows percentage of students passing each unit
- Identifies units needing intervention

## Customization Options

### 1. Change Passing Threshold
In `UnitGradingService.php`, modify the `determineStatus()` method:
```php
private function determineStatus(float $percentage): string
{
    if ($percentage >= 85) { // Changed from 80
        return 'Mastered';
    } elseif ($percentage >= 60) { // Changed from 50
        return 'In Progress';
    } else {
        return 'Needs Attention';
    }
}
```

### 2. Add More Status Levels
Add new statuses to the migration and update the enum:
```php
$table->enum('status', ['Excellent', 'Good', 'Satisfactory', 'Needs Improvement', 'Failing'])
```

### 3. Custom Chart Colors
Edit the `getSyllabusMasteryData()` method in `AnalyticsController.php`:
```php
if ($percentage >= 90) {
    $colors[] = 'rgba(59, 130, 246, 0.8)'; // Blue for excellent
} // ... etc
```

## Troubleshooting

### Problem: Grades not updating
**Solution:** Call `recalculateAllGrades` endpoint or manually trigger:
```php
$service = new UnitGradingService();
$service->calculateAllStudentUnitGrades($unit, $course);
```

### Problem: Weightages don't sum to 100
**Solution:** Normalize or adjust:
```php
$totalWeightage = $units->sum('weightage_percent');
// In calculation, divide by actual total instead of 100
```

### Problem: Chart not displaying
**Solution:** Check browser console for errors, ensure:
1. Chart.js is loaded
2. Course ID is valid
3. Student has unit grades data

## Advanced Features to Add

### 1. Progress Notifications
Alert students when they're close to mastery in a unit

### 2. Predictive Analytics
Show students which units they should focus on

### 3. Benchmarking
Compare student performance against class average

### 4. Export Reports
Allow teachers to export unit grades as PDF/Excel

### 5. Unit Retake History
Track multiple attempts on same unit over time

### 6. Adaptive Learning Paths
Recommend next units based on mastery progression

## Performance Considerations

### Database Indexing
Add indexes for faster queries:
```php
$table->index(['student_id', 'course_id']);
$table->index(['unit_id', 'course_id']);
$table->index('percentage'); // For sorting/filtering
```

### Caching
Cache unit statistics for teacher dashboard:
```php
Cache::remember("unit-stats-{$unit->id}-{$course->id}", 3600, function () {
    return $gradingService->getClassUnitStatistics($unit, $course);
});
```

### Batch Calculations
Process grade calculations in queue jobs for large courses:
```php
dispatch(new CalculateUnitGrades($course))->onQueue('high');
```

## Integration Points

### 1. Submission Grading
When a submission is graded, automatically trigger:
```php
$service->calculateUnitGrade($student, $unit, $course);
```

### 2. Exam Results
When exam results are recorded, update unit grades:
```php
$service->calculateUnitGrade($student, $unit, $course);
```

### 3. Grade Export
Include unit grades when exporting student transcripts

## Testing

### Unit Tests
Test grade calculations:
```php
public function test_calculate_unit_grade()
{
    $student = Student::factory()->create();
    $unit = Unit::factory()->create();
    $course = Course::factory()->create();
    
    $grade = (new UnitGradingService())->calculateUnitGrade($student, $unit, $course);
    
    $this->assertEquals('In Progress', $grade->status);
    $this->assertGreaterThan(0, $grade->percentage);
}
```

### Manual Testing
1. Create a course with 3-4 units
2. Set weightages (ensure 100% total)
3. Create assignments linked to units
4. Have test students submit and grade
5. Check unit grades update correctly
6. Verify chart displays accurate data

## Security Considerations

1. **Authorization:** Only students can see their own analytics
2. **Only teachers can:** See class analytics, recalculate grades
3. **Data Privacy:** Don't expose other students' grades
4. **Rate Limiting:** Limit API calls for chart data

## Support & Maintenance

- Monitor query performance if dealing with 1000+ students
- Regular backups of student_unit_grades table
- Archive old grades periodically if course repeats
- Keep Chart.js library updated

---

## Quick Start Checklist

- [ ] Run migrations (`php artisan migrate`)
- [ ] Add unit weightages in course setup
- [ ] Link existing assignments to units
- [ ] Verify student dashboard shows at `/student/analytics/dashboard`
- [ ] Verify teacher dashboard shows at `/teacher/analytics/dashboard`
- [ ] Test grade calculation with sample submission
- [ ] Customize colors/thresholds as needed
- [ ] Configure backup/archival strategy
- [ ] Train teachers on the new system
- [ ] Communicate changes to students

---

**Last Updated:** May 11, 2026
**System Version:** 1.0
**Laravel Version:** 11.x
