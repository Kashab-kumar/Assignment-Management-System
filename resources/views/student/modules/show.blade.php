@extends('layouts.student')

@section('title', $module->title)
@section('page-title', 'Module Workspace')

@section('content')
<style>
    .wrap { display: grid; gap: 14px; }
    .panel { background: #fff; border: 1px solid #dbe2ec; border-radius: 12px; padding: 16px; }
    .header h2 { margin: 0; color: #0f172a; }
    .header p { margin: 6px 0 0; color: #475569; }
    .chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
    .chip { background: #e0ecff; color: #2459ff; border-radius: 999px; padding: 4px 10px; font-size: 12px; }
    .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
    .metric { border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px; }
    .metric h4 { margin: 0 0 6px; font-size: 12px; color: #64748b; text-transform: uppercase; }
    .metric .value { font-size: 26px; font-weight: 700; color: #0f172a; }
    .section-title { margin: 0 0 10px; color: #0f172a; }
    .list { display: grid; gap: 10px; }
    .item { border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px; }
    .item h4 { margin: 0 0 6px; color: #0f172a; }
    .item p { margin: 0; color: #64748b; font-size: 13px; }
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
        <a href="{{ route('student.modules.index') }}" class="btn btn-muted" style="margin-bottom:10px;">&larr; Back to Modules</a>
        <h2>{{ $module->title }}</h2>
        <p>{{ $module->course->name }} ({{ $module->course->code }})</p>
        <p>{{ $module->description ?: 'No module description provided.' }}</p>
        <div class="chips">
            <span class="chip">{{ $module->lesson_count }} lessons</span>
            <span class="chip">{{ $module->assignment_count }} assignments</span>
            <span class="chip">{{ $module->quiz_count }} quizzes</span>
            <span class="chip">Teacher: {{ $module->teacher?->name ?? 'Course teacher' }}</span>
        </div>
    </div>

    <div class="panel">
        <h3 class="section-title">My Grades</h3>
        <div class="grid">
            <div class="metric"><h4>Assignment Average</h4><div class="value">{{ number_format($assignmentAverage, 2) }}</div></div>
            <div class="metric"><h4>Exam Average</h4><div class="value">{{ number_format($examAverage, 2) }}</div></div>
            <div class="metric"><h4>Overall Average</h4><div class="value">{{ number_format($overallAverage, 2) }}</div></div>
            <div class="metric"><h4>Graded Records</h4><div class="value">{{ $gradedAssignments->count() + $examResults->count() }}</div></div>
        </div>
    </div>

    <div class="panel">
        <h3 class="section-title">Assignments</h3>
        <div class="actions">
            <a class="btn btn-primary" href="{{ route('student.assignments.index', ['module_id' => $module->id]) }}">View All Assignments for This Module</a>
        </div>
        <p style="margin-top: 10px; color: #64748b;">Open the dedicated page for a cleaner assignments table view.</p>
    </div>

    <div class="panel">
        <h3 class="section-title">Exams</h3>
        <div class="actions">
            <a class="btn btn-primary" href="{{ route('student.exams.index', ['module_id' => $module->id]) }}">View All Exams for This Module</a>
        </div>
        <p style="margin-top: 10px; color: #64748b;">Open the dedicated page for a cleaner exams table view.</p>
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
        <h3 class="section-title">Recent Grade Items</h3>
        <div class="grid">
            <div>
                <h4 style="margin:0 0 8px;">Assignment Grades</h4>
                <table>
                    <thead><tr><th>Title</th><th>Score</th></tr></thead>
                    <tbody>
                        @forelse($gradedAssignments as $submission)
                            <tr>
                                <td>{{ $submission->assignment->title }}</td>
                                <td>{{ $submission->score }}/{{ $submission->assignment->max_score }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="empty">No graded assignments yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>
                <h4 style="margin:0 0 8px;">Exam Grades</h4>
                <table>
                    <thead><tr><th>Title</th><th>Score</th></tr></thead>
                    <tbody>
                        @forelse($examResults as $result)
                            <tr>
                                <td>{{ $result->exam->title }}</td>
                                <td>{{ $result->score }}/{{ $result->exam->max_score }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="empty">No exam grades yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
