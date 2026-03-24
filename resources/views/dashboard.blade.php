@extends('layouts.student')

@section('title', 'Student Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        color: white;
        padding: 28px 32px;
        border-radius: 12px;
        margin-bottom: 24px;
    }
    .welcome-card h2 { font-size: 26px; font-weight: 700; margin-bottom: 8px; }
    .welcome-card p { font-size: 14px; opacity: 0.85; }

    .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .stat-card {
        background: #ffffff;
        padding: 22px 24px;
        border-radius: 12px;
        border: 1px solid rgba(0,0,0,0.06);
    }
    .stat-card h3 { color: #475569; font-size: 13px; font-weight: 500; margin-bottom: 12px; }
    .stat-card .value { font-size: 34px; font-weight: 700; color: #7c3aed; line-height: 1; }
    .stat-card .value.white { color: #1f2937; }
    .stat-card p { font-size: 12px; color: #64748b; margin-top: 6px; }

    .section {
        background: #ffffff;
        padding: 22px 24px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid rgba(0,0,0,0.06);
    }
    .section h2 { margin-bottom: 18px; color: #1f2937; font-size: 17px; font-weight: 600; }

    table { width: 100%; border-collapse: collapse; }
    th {
        padding: 10px 14px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid rgba(255,255,255,0.07);
    }
    td {
        padding: 13px 14px;
        text-align: left;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        font-size: 14px;
        color: #000000;
    }
    tr:last-child td { border-bottom: none; }
    tr.highlight-row td { color: #a78bfa; }

    .badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 500; display: inline-block; }
    .badge-pending, .badge-not-submitted { background: rgba(245,158,11,0.18); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
    .badge-graded, .badge-submitted { background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.25); }
    .badge-late { background: rgba(239,68,68,0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.25); }

    .btn {
        padding: 7px 16px;
        background: #7c3aed;
        color: white;
        text-decoration: none;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 500;
        display: inline-block;
        transition: background 0.2s;
    }
    .btn:hover { background: #6d28d9; }
</style>

<div class="welcome-card">
    <h2>Welcome back, {{ $student->name }}!</h2>
    <p>Student ID: {{ $student->student_id }} | {{ $groupLabel }}: {{ $groupValue }}</p>
</div>

<div class="stats">
    <div class="stat-card">
        <h3>Your Class Rank</h3>
        <div class="value">{{ $myRank ? '#' . $myRank : '-' }}</div>
        <p>out of {{ $rankings->count() }} students</p>
    </div>
    <div class="stat-card">
        <h3>Average Score</h3>
        <div class="value">{{ number_format((float) $student->getAverageScore(), 1) }}%</div>
    </div>
    <div class="stat-card">
        <h3>Pending Submissions</h3>
        <div class="value white">{{ $assignments->filter(fn($a) => $a->submissions->isEmpty())->count() }}</div>
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
                    <a href="{{ route('student.assignments.show', $assignment) }}" class="btn">View</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="color:#64748b; text-align:center; padding:20px;">No assignments yet</td></tr>
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
            <tr class="{{ $ranking['student']->id === $student->id ? 'highlight-row' : '' }}">
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
