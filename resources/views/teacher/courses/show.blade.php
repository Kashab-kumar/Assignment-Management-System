@extends('layouts.teacher')

@section('title', $course->name)
@section('page-title', $course->name)

@section('content')
<style>
    .course-container { background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 30px; }
    .course-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
    .course-code { font-family: monospace; background: #f0f0f0; padding: 4px 8px; border-radius: 4px; font-size: 14px; color: #666; }
    .status-badge { padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; }
    .status-active { background: #4CAF50; color: white; }
    .status-inactive { background: #f44336; color: white; }
    .course-description { color: #666; line-height: 1.6; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 4px; }
    .students-section { margin-top: 30px; }
    .students-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .students-table th { background: #f8f9fa; padding: 12px 15px; text-align: left; font-weight: 600; color: #555; border-bottom: 2px solid #dee2e6; }
    .students-table td { padding: 12px 15px; border-bottom: 1px solid #eee; }
    .students-table tr:hover { background: #f8f9fa; }
    .btn { padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 14px; margin-right: 10px; }
    .btn-back { background: #666; color: white; }
    .btn-invite { background: #2196F3; color: white; }
    .btn-assign { background: #4CAF50; color: white; }
    .btn-quiz { background: #FF9800; color: white; }
    .btn-exam { background: #8E44AD; color: white; }
    .stats-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin: 16px 0 22px; }
    .stat-card { background: #f8f9fa; border: 1px solid #eee; border-radius: 8px; padding: 14px; }
    .stat-card h4 { margin: 0 0 6px 0; color: #666; font-size: 12px; text-transform: uppercase; }
    .stat-card p { margin: 0; font-size: 24px; font-weight: 700; color: #2c3e50; }
    .empty-state { text-align: center; padding: 40px; color: #666; }
    .related-list { list-style: none; margin-top: 10px; padding: 0; }
    .related-list li { padding: 8px 0; border-bottom: 1px solid #eee; }
    .related-list li:last-child { border-bottom: none; }
</style>

<div class="course-container">
    <div class="course-header">
        <div>
            <h1 style="margin: 0 0 10px 0; color: #333;">{{ $course->name }}</h1>
            <span class="course-code">{{ $course->code }}</span>
            <div style="margin-top: 10px; color: #666;">Category: <strong>{{ $course->category_name ?: 'Uncategorized' }}</strong></div>
            <div style="margin-top: 5px; color: #666;">Class: <strong>{{ $course->class_name ?: 'Unassigned' }}</strong></div>
        </div>
        <span class="status-badge status-{{ $course->is_active ? 'active' : 'inactive' }}">
            {{ $course->is_active ? 'Active' : 'Inactive' }}
        </span>
    </div>

    @if($course->description)
    <div class="course-description">{{ $course->description }}</div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <h4>Assignments</h4>
            <p>{{ $course->assignments_count }}</p>
        </div>
        <div class="stat-card">
            <h4>Exams / Quizzes</h4>
            <p>{{ $course->exams_count }}</p>
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <a href="{{ route('teacher.courses.index') }}" class="btn btn-back">← Back to Courses</a>
        <a href="{{ route('teacher.assignments.create', ['course_id' => $course->id]) }}" class="btn btn-assign">Give Assignment</a>
        <a href="{{ route('teacher.exams.create', ['course_id' => $course->id, 'mode' => 'quiz']) }}" class="btn btn-quiz">Create Quiz</a>
        <a href="{{ route('teacher.exams.create', ['course_id' => $course->id]) }}" class="btn btn-exam">Create Exam</a>
        <a href="{{ route('teacher.students.index') }}" class="btn btn-invite">Invite Students</a>
    </div>

    <div class="students-section">
        <h3>Enrolled Students ({{ $course->students->count() }})</h3>

        @if($course->students->count() > 0)
        <table class="students-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @foreach($course->students as $student)
                <tr>
                    <td><strong>{{ $student->student_id }}</strong></td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <p>No students enrolled in this course yet.</p>
            <a href="{{ route('teacher.students.index') }}" class="btn btn-invite" style="margin-top:12px; display:inline-block;">Invite Students</a>
        </div>
        @endif
    </div>

    @if($relatedCourses->count() > 0)
    <div class="students-section">
        <h3>Other Courses in {{ $course->class_name }} ({{ $relatedCourses->count() }})</h3>
        <ul class="related-list">
            @foreach($relatedCourses as $related)
            <li>
                <a href="{{ route('teacher.courses.show', $related) }}" style="color:#2196F3; text-decoration:none;">
                    {{ $related->name }}
                </a>
                <span style="color:#999; font-size:13px; margin-left:8px;">{{ $related->category_name }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
