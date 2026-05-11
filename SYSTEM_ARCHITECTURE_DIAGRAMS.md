# System Architecture Diagram

## Data Flow: Submission → Grade → Unit Progress

```
┌─────────────────────────────────────────────────────────────┐
│                    TEACHER WORKFLOW                         │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  1. Create Unit with Weightage                              │
│     Unit 1: Python Basics (15%)                             │
│     Unit 2: Loops & Functions (25%)                         │
│     Unit 3: OOP (60%)                                       │
│                                                              │
│  2. Create Assignment linked to Unit                        │
│     Assignment → unit_id = 1                                │
│     max_score = 50 points                                   │
│                                                              │
│  3. Grade Student Submission                                │
│     Score: 40/50 = 80%                                      │
│                                                              │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│              GRADING SERVICE CALCULATION                     │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  UnitGradingService::calculateUnitGrade()                   │
│                                                              │
│  1. Get all assignments for Unit 1                          │
│     - Assignment 1: 40/50 (submitted, graded)              │
│     - Assignment 2: 0/50 (not submitted)                   │
│     Total achieved: 40, Possible: 100                       │
│                                                              │
│  2. Get all exams for Unit 1                                │
│     - Quiz 1: 15/20 (submitted, graded)                    │
│     Total achieved: 15, Possible: 20                        │
│                                                              │
│  3. Calculate Percentage                                    │
│     (40 + 15) / (100 + 20) = 55/120 = 45.83%              │
│                                                              │
│  4. Determine Status                                        │
│     45.83% < 50% → "Needs Attention" (RED)                │
│                                                              │
│  5. Update student_unit_grades table                        │
│     ├── achieved_score: 55                                  │
│     ├── total_possible_score: 120                           │
│     ├── percentage: 45.83                                   │
│     └── status: "Needs Attention"                           │
│                                                              │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                 STUDENT ANALYTICS                           │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  Student Dashboard: /student/analytics/dashboard            │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  Syllabus Mastery Progress                           │  │
│  │                                                      │  │
│  │  Unit 1: ████████░░ 80% ✓ Mastered (GREEN)         │  │
│  │  Unit 2: ██████░░░░ 60% → In Progress (YELLOW)     │  │
│  │  Unit 3: ████░░░░░░ 45% ✗ Needs Attention (RED)    │  │
│  │          [50% passing threshold line]               │  │
│  │                                                      │  │
│  │  Overall Progress: 72%                              │  │
│  │  Calculated as:                                      │  │
│  │  (80% × 15%) + (60% × 25%) + (45% × 60%)           │  │
│  │  = 12% + 15% + 27% = 54%                            │  │
│  │                                                      │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                              │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│              TEACHER CLASS ANALYTICS                         │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  Teacher Dashboard: /teacher/analytics/dashboard            │
│                                                              │
│  Class Metrics:                                             │
│  • Class Average: 68%                                       │
│  • Total Students: 30                                       │
│  • Passing (≥50%): 25 students (83%)                       │
│  • Failing (<50%): 5 students (17%)                        │
│                                                              │
│  Unit Performance:                                          │
│  ┌─────────────────────────────────────────┐               │
│  │ Unit Performance Distribution             │               │
│  │ (Line Chart showing trend)                │               │
│  │                                           │               │
│  │ 85% ╱╲                 Avg Scores       │               │
│  │ 75% │  ╲    ╱╲ ╱╲ Unit 1: 78%        │               │
│  │ 65% │   ╲  ╱  ╲╱╲  Unit 2: 72%        │               │
│  │ 55% │    ╲╱       ╲ Unit 3: 62%       │               │
│  │ 45% │              Unit 4: 58%         │               │
│  │      └─────────────────────────────────┘               │
│  │ Pass Rate: ▓▓▓▓▓▓▓▓▓▓ 98%               │               │
│  │           ▓▓▓▓▓▓▓▓░░ 87%                │               │
│  │           ▓▓▓▓▓▓░░░░ 75%                │               │
│  │           ▓▓▓▓░░░░░░ 62%                │               │
│  └─────────────────────────────────────────┘               │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

## Database Schema (Simplified)

```
┌──────────────────────────┐
│      Units               │
├──────────────────────────┤
│ id (PK)                  │
│ module_id (FK)           │
│ title                    │
│ weightage_percent    ← NEW
│ is_active            ← NEW
│ max_marks                │
│ ...                      │
└───────────┬──────────────┘
            │ 1
            │
     ┌──────┴──────┐
     │ 1        M  │
     │             │
┌────▼─────────┐  ┌──────────────────────┐
│ Assignments  │  │ Student_Unit_Grades  │
├──────────────┤  ├──────────────────────┤
│ id (PK)      │  │ id (PK)              │
│ unit_id  ←NEW│  │ student_id (FK)      │
│ course_id←NEW│  │ unit_id (FK)         │
│ title        │  │ course_id (FK)       │
│ max_score    │  │ achieved_score       │
│ ...          │  │ total_possible_score │
└──────────────┘  │ percentage           │
                  │ status               │
┌──────────────┐  │ attempt_count        │
│ Exams        │  │ first_attempted_at   │
├──────────────┤  │ last_attempted_at    │
│ id (PK)      │  │ created_at           │
│ unit_id  ←NEW│  │ updated_at           │
│ course_id←NEW│  └──────────────────────┘
│ title        │           ▲
│ max_score    │           │ 1
│ ...          │           │
└──────────────┘     ┌─────┴──────┐
     │               │     1     M │
     │         ┌─────▼───────┐
     │         │   Students  │
     │         ├─────────────┤
     │         │ id (PK)     │
     │         │ user_id (FK)│
     │         │ course_id   │
     │         │ ...         │
     │         └─────────────┘
     │
     └──► Many assignments/exams per unit
          Each with grades tracked in
          student_unit_grades table
```

## Grade Status Color Coding

```
Student Score Range → Status → Display Color → Teacher Action
─────────────────────────────────────────────────────────────
      ≥ 80%       →  Mastered  → 🟢 GREEN    → Praise & advance
    50% - 80%     → In Progress → 🟡 YELLOW   → Support & guide
       < 50%      → Needs      → 🔴 RED      → Intervention &
                    Attention             reteach

                   ↓ 50% Threshold Line
            (RIM Passing Standard)
```

## API Response Example

### GET /student/api/analytics/syllabus-mastery/{course}

```json
{
  "labels": [
    "Variables & Data Types",
    "Loops & Conditions",
    "Functions",
    "Object-Oriented Programming"
  ],
  "data": [85, 72, 60, 45],
  "colors": [
    "rgba(34, 197, 94, 0.8)",    // Green - Mastered
    "rgba(234, 179, 8, 0.8)",    // Yellow - In Progress
    "rgba(234, 179, 8, 0.8)",    // Yellow - In Progress
    "rgba(239, 68, 68, 0.8)"     // Red - Needs Attention
  ],
  "passingThreshold": 50
}
```

## Implementation Timeline

```
Phase 1: Database        Phase 2: Backend      Phase 3: Frontend
────────────────        ──────────────        ────────────────
Migration files      UnitGradingService    Student Dashboard
Models & Relations   AnalyticsController   Teacher Dashboard
└─ Run migrations    └─ Services injected   └─ Chart.js charts
   └─ Ready!           └─ Data prepared       └─ Live!
   
   (2 mins)           (5 mins)               (3 mins)
```

## Key Formulas

### Unit Percentage Calculation
```
Unit % = (Total Achieved Score) / (Total Possible Score) × 100
```

### Overall Course Score (Weighted)
```
Overall % = Σ(Unit % × Unit Weightage%) / Σ(Unit Weightage%)

Example:
= (85 × 15 + 72 × 25 + 60 × 40 + 45 × 20) / 100
= (1275 + 1800 + 2400 + 900) / 100
= 6375 / 100
= 63.75%
```

### Class Average for Unit
```
Class Avg % = Σ(Student Unit %) / Number of Students
```

### Pass Rate
```
Pass Rate % = (Students with % ≥ 50) / Total Students × 100
```
