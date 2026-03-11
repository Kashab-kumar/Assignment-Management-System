@extends('layouts.teacher')

@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')
<style>
    .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 20px; }
    .card { background: #fff; padding: 16px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .card h3 { margin-bottom: 6px; color: #666; font-size: 13px; }
    .value { font-size: 24px; font-weight: bold; color: #2196F3; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
    th { background: #f8f8f8; }
</style>

<div class="stats">
    <div class="card"><h3>Total Students</h3><div class="value">{{ $totalStudents }}</div></div>
    <div class="card"><h3>Total Assignments</h3><div class="value">{{ $totalAssignments }}</div></div>
    <div class="card"><h3>Total Exams</h3><div class="value">{{ $totalExams }}</div></div>
    <div class="card"><h3>Graded Submissions</h3><div class="value">{{ $gradedSubmissions }}</div></div>
    <div class="card"><h3>Pending Submissions</h3><div class="value">{{ $pendingSubmissions }}</div></div>
    <div class="card"><h3>Avg Assignment Score</h3><div class="value">{{ $avgAssignmentScore }}</div></div>
    <div class="card"><h3>Avg Exam Score</h3><div class="value">{{ $avgExamScore }}</div></div>
</div>

<div class="card">
    <h2 style="margin-bottom: 12px;">Top Students</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Student ID</th>
                <th>Average Score</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topStudents as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->student_id }}</td>
                    <td>{{ number_format($student->getAverageScore(), 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No report data available yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
