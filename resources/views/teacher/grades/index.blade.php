@extends('layouts.teacher')

@section('title', 'Grades')
@section('page-title', 'Grades')

@section('content')
<style>
    .section { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f8f8; }
</style>

<div class="section">
    <h2 style="margin-bottom: 16px;">Grade Overview</h2>

    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Course</th>
                <th>Assignment Avg</th>
                <th>Exam Avg</th>
                <th>Overall Avg</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                @php
                    $assignmentAvg = $student->submissions->where('status', 'graded')->avg('score');
                    $examAvg = $student->examResults->avg('score');
                    $overallAvg = collect([$assignmentAvg, $examAvg])->filter(fn($v) => $v !== null)->avg();
                @endphp
                <tr>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->course?->name ?? '-' }}</td>
                    <td>{{ $assignmentAvg !== null ? number_format($assignmentAvg, 2) : '-' }}</td>
                    <td>{{ $examAvg !== null ? number_format($examAvg, 2) : '-' }}</td>
                    <td>{{ $overallAvg !== null ? number_format($overallAvg, 2) : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No student grade data found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
