@extends('layouts.student')

@section('title', 'My Grades')
@section('page-title', 'My Grades')

@section('content')
<style>
    .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; margin-bottom: 20px; }
    .card {
        background: #1e2235;
        padding: 18px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.06);
    }
    .card h3 { font-size: 13px; color: #94a3b8; margin-bottom: 8px; }
    .value { font-size: 28px; color: #7c3aed; font-weight: 700; }
    .section {
        background: #1e2235;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.06);
        margin-bottom: 16px;
    }
    .section h2 { color: #f1f5f9; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 11px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.06); }
    th {
        color: #94a3b8;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        background: rgba(0,0,0,0.12);
    }
    td { color: #cbd5e1; }
    tr:last-child td { border-bottom: none; }
    .empty { color: #64748b; text-align: center; }
</style>

<div class="stats">
    <div class="card"><h3>Assignment Average</h3><div class="value">{{ number_format($assignmentAverage, 2) }}</div></div>
    <div class="card"><h3>Exam Average</h3><div class="value">{{ number_format($examAverage, 2) }}</div></div>
    <div class="card"><h3>Overall Average</h3><div class="value">{{ number_format($overallAverage, 2) }}</div></div>
</div>

<div class="section">
    <h2 style="margin-bottom:12px;">Assignment Grades</h2>
    <table>
        <thead>
            <tr>
                <th>Assignment</th>
                <th>Score</th>
                <th>Max</th>
                <th>Submitted</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assignmentSubmissions as $submission)
                <tr>
                    <td>{{ $submission->assignment->title }}</td>
                    <td>{{ $submission->score }}</td>
                    <td>{{ $submission->assignment->max_score }}</td>
                    <td>{{ $submission->submitted_at->format('M d, Y h:i A') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty">No graded assignment submissions yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <h2 style="margin-bottom:12px;">Exam Grades</h2>
    <table>
        <thead>
            <tr>
                <th>Exam</th>
                <th>Score</th>
                <th>Max</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($examResults as $result)
                <tr>
                    <td>{{ $result->exam->title }}</td>
                    <td>{{ $result->score }}</td>
                    <td>{{ $result->exam->max_score }}</td>
                    <td>{{ $result->remarks ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty">No exam results available yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
