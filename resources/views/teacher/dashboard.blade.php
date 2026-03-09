@extends('layouts.teacher')

@section('title', 'Teacher Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<style>
    .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .stat-card h3 { color: #666; font-size: 14px; margin-bottom: 10px; }
    .stat-card .value { font-size: 32px; font-weight: bold; color: #2196F3; }
    .section { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .section h2 { margin-bottom: 15px; color: #333; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f8f8; font-weight: bold; }
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
    .badge-pending { background: #FFC107; color: white; }
    .badge-graded { background: #4CAF50; color: white; }
</style>

<div class="stats">
    <div class="stat-card">
        <h3>Total Assignments</h3>
        <div class="value">{{ $totalAssignments }}</div>
    </div>
    <div class="stat-card">
        <h3>Total Submissions</h3>
        <div class="value">{{ $totalSubmissions }}</div>
    </div>
    <div class="stat-card">
        <h3>Pending Grading</h3>
        <div class="value">{{ $pendingGrading }}</div>
    </div>
    <div class="stat-card">
        <h3>Total Students</h3>
        <div class="value">{{ $totalStudents }}</div>
    </div>
</div>

<div class="section">
    <h2>Recent Submissions</h2>
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Assignment</th>
                <th>Submitted</th>
                <th>Status</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentSubmissions as $submission)
            <tr>
                <td>{{ $submission->student->name }}</td>
                <td>{{ $submission->assignment->title }}</td>
                <td>{{ $submission->submitted_at->format('M d, Y h:i A') }}</td>
                <td><span class="badge badge-{{ $submission->status }}">{{ ucfirst($submission->status) }}</span></td>
                <td>{{ $submission->score ?? '-' }}/{{ $submission->assignment->max_score }}</td>
            </tr>
            @empty
            <tr><td colspan="5">No submissions yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
