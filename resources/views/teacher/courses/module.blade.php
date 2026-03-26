@extends('layouts.teacher')

@section('title', $module->title)
@section('page-title', 'Module Workspace')

@section('content')
<style>
    .wrap { display: grid; gap: 14px; }
    .panel { background: #fff; border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; padding: 16px; }
    .header h2 { margin: 0; color: #111827; }
    .header p { margin: 6px 0 0; color: #475569; }
    .chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
    .chip { background: #eef2ff; color: #3730a3; border-radius: 999px; padding: 4px 10px; font-size: 12px; }
    .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
    .section-title { margin: 0 0 10px; color: #111827; }
    .list { display: grid; gap: 10px; }
    .item { border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px; }
    .item h4 { margin: 0 0 6px; color: #111827; }
    .item p { margin: 0; color: #6b7280; font-size: 13px; }
    .actions { margin-top: 8px; display: flex; gap: 8px; flex-wrap: wrap; }
    .btn { display: inline-block; text-decoration: none; border-radius: 8px; padding: 7px 12px; font-size: 13px; }
    .btn-primary { background: #2459ff; color: #fff; }
    .btn-muted { background: #f3f4f6; color: #111827; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; }
    th { font-size: 12px; color: #64748b; text-transform: uppercase; }
    td { color: #111827; }
    .empty { color: #6b7280; text-align: center; padding: 12px; }
    @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }
</style>

<div class="wrap">
    <div class="panel header">
        <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-muted" style="margin-bottom:10px;">&larr; Back to Course</a>
        <h2>{{ $module->title }}</h2>
        <p>{{ $course->name }} ({{ $course->code }})</p>
        <p>{{ $module->description ?: 'No module description provided.' }}</p>
        <div class="chips">
            <span class="chip">{{ $module->lesson_count }} lessons</span>
            <span class="chip">{{ $module->assignment_count }} assignments</span>
            <span class="chip">{{ $module->quiz_count }} quizzes</span>
            <span class="chip">Assigned Teacher: {{ $module->teacher?->name ?? 'Any assigned course teacher' }}</span>
        </div>
    </div>

    <div class="panel">
        <h3 class="section-title">Assignments</h3>
        <div class="actions">
            <a class="btn btn-primary" href="{{ route('teacher.assignments.create', ['course_id' => $course->id]) }}">Create Assignment</a>
            <a class="btn btn-muted" href="{{ route('teacher.assignments.index', ['course_id' => $course->id]) }}">View All Assignments</a>
        </div>
        <div class="list" style="margin-top:10px;">
            @forelse($assignments as $assignment)
                <div class="item">
                    <h4>{{ $assignment->title }}</h4>
                    <p>Due {{ $assignment->due_date?->format('M d, Y') ?: '-' }} | Submissions: {{ $assignment->submissions_count }}</p>
                    <div class="actions">
                        <a class="btn btn-primary" href="{{ route('teacher.assignments.show', $assignment) }}">Open Assignment</a>
                    </div>
                </div>
            @empty
                <div class="empty">No assignments found for this module's course.</div>
            @endforelse
        </div>
    </div>

    <div class="panel">
        <h3 class="section-title">Exams</h3>
        <div class="actions">
            <a class="btn btn-primary" href="{{ route('teacher.exams.create', ['course_id' => $course->id]) }}">Create Exam</a>
            <a class="btn btn-muted" href="{{ route('teacher.exams.index', ['course_id' => $course->id]) }}">View All Exams</a>
        </div>
        <div class="list" style="margin-top:10px;">
            @forelse($exams as $exam)
                <div class="item">
                    <h4>{{ $exam->title }}</h4>
                    <p>{{ ucfirst($exam->type) }} on {{ $exam->exam_date?->format('M d, Y') ?: '-' }} | Results: {{ $exam->results_count }}</p>
                    <div class="actions">
                        <a class="btn btn-primary" href="{{ route('teacher.exams.show', $exam) }}">Open Exam</a>
                    </div>
                </div>
            @empty
                <div class="empty">No exams found for this module's course.</div>
            @endforelse
        </div>
    </div>

    <div class="panel">
        <h3 class="section-title">Recents</h3>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Detail</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recents as $recent)
                    <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $recent['kind'])) }}</td>
                        <td>{{ $recent['title'] }}</td>
                        <td>{{ $recent['subtitle'] }}</td>
                        <td>{{ $recent['date']->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty">No recent activity for this module yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="panel">
        <h3 class="section-title">Grade Snapshot</h3>
        <div class="grid">
            <div>
                <h4 style="margin:0 0 8px;">Recent Submissions</h4>
                <table>
                    <thead><tr><th>Student</th><th>Assignment</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($recentSubmissions as $submission)
                            <tr>
                                <td>{{ $submission->student?->name ?? '-' }}</td>
                                <td>{{ $submission->assignment?->title ?? '-' }}</td>
                                <td>{{ ucfirst($submission->status) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="empty">No submission records.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>
                <h4 style="margin:0 0 8px;">Recent Exam Results</h4>
                <table>
                    <thead><tr><th>Student</th><th>Exam</th><th>Score</th></tr></thead>
                    <tbody>
                        @forelse($recentResults as $result)
                            <tr>
                                <td>{{ $result->student?->name ?? '-' }}</td>
                                <td>{{ $result->exam?->title ?? '-' }}</td>
                                <td>{{ $result->score }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="empty">No exam result records.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
