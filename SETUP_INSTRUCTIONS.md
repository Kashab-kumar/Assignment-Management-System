# Assignment Management System - Setup Instructions

## Overview
A Laravel-based system where students can submit assignments, homework, and view exam results with class rankings.

## Features
- Student dashboard with class rankings
- Assignment and homework submission
- Exam results tracking
- File upload support
- Real-time ranking calculation

## Database Structure

### Tables
1. **students** - Student profiles linked to users
2. **assignments** - Assignments and homework
3. **submissions** - Student submissions with scores
4. **exams** - Exam information
5. **exam_results** - Student exam scores

## Setup Steps

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Sample Data
```bash
php artisan db:seed --class=AssignmentSystemSeeder
```

This creates:
- 10 sample students (student1@example.com to student10@example.com)
- Password for all: `password`
- 3 assignments
- 1 exam with results
- Sample submissions

### 3. Configure Storage
```bash
php artisan storage:link
```

### 4. Start Development Server
```bash
php artisan serve
```

## Usage

### Login Credentials
- Email: student1@example.com (or student2, student3, etc.)
- Password: password

### Routes
- `/dashboard` - Main dashboard with rankings
- `/assignments` - List all assignments
- `/assignments/{id}` - View and submit assignment

## Key Features Explained

### Dashboard
- Shows your class rank
- Displays average score
- Lists pending submissions
- Shows top 10 class rankings
- Recent assignments overview

### Assignment Submission
- Text content submission
- File upload (max 10MB)
- View submission status
- See graded scores

### Class Rankings
- Calculated from assignment submissions and exam results
- Real-time updates
- Highlighted current student position

## File Structure
```
app/
├── Models/
│   ├── Student.php
│   ├── Assignment.php
│   ├── Submission.php
│   ├── Exam.php
│   └── ExamResult.php
├── Http/Controllers/
│   ├── DashboardController.php
│   ├── AssignmentController.php
│   └── SubmissionController.php
resources/views/
├── dashboard.blade.php
└── assignments/
    ├── index.blade.php
    └── show.blade.php
```

## Next Steps (Optional Enhancements)

1. Add authentication scaffolding (Laravel Breeze/Jetstream)
2. Create admin panel for teachers
3. Add email notifications
4. Implement deadline reminders
5. Add grade analytics and charts
6. Export reports to PDF
7. Add comments/feedback on submissions
