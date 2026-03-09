<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Assignment;
use App\Models\Exam;
use App\Models\Submission;
use App\Models\ExamResult;
use Illuminate\Support\Facades\Hash;

class AssignmentSystemSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create teachers
        for ($i = 1; $i <= 3; $i++) {
            $teacherUser = User::create([
                'name' => "Teacher $i",
                'email' => "teacher$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ]);

            Teacher::create([
                'user_id' => $teacherUser->id,
                'teacher_id' => 'TCH' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => "Teacher $i",
                'email' => "teacher$i@example.com",
                'subject' => ['Mathematics', 'Science', 'English'][$i - 1],
            ]);
        }

        // Create students
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => "Student $i",
                'email' => "student$i@example.com",
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);

            Student::create([
                'user_id' => $user->id,
                'student_id' => 'STU' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => "Student $i",
                'email' => "student$i@example.com",
                'class' => 'Class A',
            ]);
        }

        // Create assignments
        Assignment::create([
            'title' => 'Math Assignment 1',
            'description' => 'Complete exercises 1-10 from chapter 3',
            'type' => 'assignment',
            'due_date' => now()->addDays(7),
            'max_score' => 100,
        ]);

        Assignment::create([
            'title' => 'Science Homework',
            'description' => 'Write a report on photosynthesis',
            'type' => 'homework',
            'due_date' => now()->addDays(5),
            'max_score' => 50,
        ]);

        Assignment::create([
            'title' => 'English Essay',
            'description' => 'Write a 500-word essay on your favorite book',
            'type' => 'assignment',
            'due_date' => now()->addDays(10),
            'max_score' => 100,
        ]);

        // Create exams
        Exam::create([
            'title' => 'Midterm Exam',
            'description' => 'Covers chapters 1-5',
            'exam_date' => now()->subDays(5),
            'max_score' => 100,
        ]);

        // Create sample submissions and exam results
        $students = Student::all();
        $assignments = Assignment::all();
        $exam = Exam::first();

        foreach ($students as $student) {
            // Random submissions
            foreach ($assignments->random(2) as $assignment) {
                Submission::create([
                    'student_id' => $student->id,
                    'assignment_id' => $assignment->id,
                    'content' => 'Sample submission content',
                    'score' => rand(60, 100),
                    'status' => 'graded',
                ]);
            }

            // Exam results
            ExamResult::create([
                'student_id' => $student->id,
                'exam_id' => $exam->id,
                'score' => rand(65, 98),
                'remarks' => 'Good performance',
            ]);
        }
    }
}
