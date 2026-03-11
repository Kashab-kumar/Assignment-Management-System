@extends('layouts.student')

@section('title', 'My Grades')
@section('page-title', 'My Grades')

@section('content')
<style>
    .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; margin-bottom: 20px; }
    .card { background: #fff; padding: 16px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .card h3 { font-size: 13px; color: #666; margin-bottom: 6px; }
    .value { font-size: 24px; color: #27ae60; font-weight: bold; }
    .section { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 16px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f8f8; }
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
                <tr><td colspan="4">No graded assignment submissions yet.</td></tr>
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
                <tr><td colspan="4">No exam results available yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
