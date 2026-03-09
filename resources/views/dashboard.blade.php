@extends('layouts.student')

@section('title', 'Student Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<style>
    .welcome-card { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; padding: 30px; border-radius: 8px; margin-bottom: 30px; }
    .welcome-card h2 { font-size: 28px; margin-bottom: 10px; }
    .welcome-card p { opacity: 0.9; }
    .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .stat-card h3 { color: #666; font-size: 14px; margin-bottom: 10px; }
    .stat-card .value { font-size: 32px; font-weight: bold; color: #27ae60; }
    .section { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .section h2 { margin-bottom: 15px; color: #333; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f8f8; font-weight: bold; }
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
    .badge-pending { background: #FFC107; color: white; }
    .badge-graded { background: #4CAF50; color: white; }
    .btn { padding: 8px 16px; background: #27ae60; color: white; text-decoration: none; border-radius: 4px; display: inline-block; }
</style>

<div class="welcome-card">
    <h2>Welcome back, {{ $student->name }}!</h2>
    <p>Student ID: {{ $student->student_id }} | Class: {{ $student->class }}</p>
</div>

<div class="stats">
    <div class="stat-card">
        <h3>Your Class Rank</h3>
        <div class="value">#{{ $myRank }}</div>
        <p>out of {{ $rankings->count() }} students</p>
    </div>
    <div class="stat-card">
        <h3>Average Score</h3>
        <div class="value">{{ number_format($student->getAverageScore(), 1) }}%</div>
    </div>
    <div class="stat-card">
        <h3>Pending Submissions</h3>
        <div class="value">{{ $assignments->filter(fn($a) => $a->submissions->isEmpty())->count() }}</div>
    </div>
</div>

<div class="section">
    <h2>Recent Assignments</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Score</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assignments->take(5) as $assignment)
            <tr>
                <td>{{ $assignment->title }}</td>
                <td>{{ ucfirst($assignment->type) }}</td>
                <td>{{ $assignment->due_date->format('M d, Y') }}</td>
                <td>
                    @if($assignment->submissions->first())
                        <span class="badge badge-{{ $assignment->submissions->first()->status }}">
                            {{ ucfirst($assignment->submissions->first()->status) }}
                        </span>
                    @else
                        <span class="badge badge-pending">Not Submitted</span>
                    @endif
                </td>
                <td>
                    @if($assignment->submissions->first() && $assignment->submissions->first()->score)
                        {{ $assignment->submissions->first()->score }}/{{ $assignment->max_score }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ route('assignments.show', $assignment) }}" class="btn">View</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6">No assignments yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <h2>Class Rankings</h2>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Student Name</th>
                <th>Student ID</th>
                <th>Average Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rankings->take(10) as $index => $ranking)
            <tr style="{{ $ranking['student']->id === $student->id ? 'background: #e8f5e9;' : '' }}">
                <td><strong>#{{ $index + 1 }}</strong></td>
                <td>{{ $ranking['student']->name }}</td>
                <td>{{ $ranking['student']->student_id }}</td>
                <td>{{ number_format($ranking['average'], 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
