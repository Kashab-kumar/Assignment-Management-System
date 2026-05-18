@extends('layouts.teacher')

@section('title', 'Teacher Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<style>
    .content {
        background: #f6f8fc;
        min-height: 100vh;
    }

    .welcome-card {
        background: linear-gradient(135deg, #111827 0%, #000000 100%);
        color: white;
        padding: 30px 32px;
        border-radius: 14px;
        margin-bottom: 24px;
    }
    .welcome-card h2 { font-size: 26px; font-weight: 700; margin-bottom: 8px; }
    .welcome-card p { font-size: 14px; opacity: 0.85; }

    .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: #ffffff; padding: 22px; border-radius: 12px; border: 1px solid rgba(0,0,0,0.06); }
    .stat-card h3 { color: #475569; font-size: 13px; margin-bottom: 10px; }
    .stat-card .value { font-size: 34px; font-weight: 700; color: #7c3aed; }
    .section { background: #ffffff; padding: 22px; border-radius: 12px; margin-bottom: 20px; border: 1px solid rgba(0,0,0,0.06); }
    .section h2 { margin-bottom: 15px; color: #1f2937; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.06); }
    th { background: rgba(0,0,0,0.05); font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em; }
    td { color: #475569; }
    tr:last-child td { border-bottom: none; }
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
    .badge-pending { background: rgba(245,158,11,0.16); color: #f59e0b; border: 1px solid rgba(245,158,11,0.28); }
    .badge-graded { background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.25); }
    .empty { color: #64748b; text-align: center; }
</style>

@php($teacherProfile = auth()->user()->teacher)
@php($assignedCoursesCount = $teacherProfile ? $teacherProfile->courses()->count() : 0)

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
    <h2>Grading Queue</h2>
    @if($pendingSubmissions->isEmpty())
        <div class="empty">No pending submissions to grade.</div>
    @else
        <x-ui.table>
            <x-slot name="head">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </x-slot>

            @foreach($pendingSubmissions as $sub)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $sub->student->name ?? 'Student' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $sub->assignment->title ?? 'Assignment' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sub->created_at->diffForHumans() }}</td>
                <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('teacher.courses.assignment-grading', $sub->assignment->course) }}" class="inline-flex items-center px-3 py-1 rounded bg-yellow-100 text-yellow-800 text-sm font-semibold">Grade</a></td>
            </tr>
            @endforeach
        </x-ui.table>
    @endif
</div>

<div class="section">
    <h2>Upcoming (14 days)</h2>
    @if($upcomingAssignments->isEmpty() && $upcomingExams->isEmpty())
        <div class="empty">No upcoming assignments or exams in the next 14 days.</div>
    @else
        <x-ui.table>
            <x-slot name="head">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </x-slot>

            @foreach($upcomingAssignments as $a)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Assignment</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $a->title }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $a->course?->name ?? 'Course' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ optional($a->due_date)->toFormattedDateString() ?: $a->due_date }}</td>
            </tr>
            @endforeach

            @foreach($upcomingExams as $e)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Exam</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $e->title }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $e->course?->name ?? 'Course' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ optional($e->exam_date)->toFormattedDateString() ?: $e->exam_date }}</td>
            </tr>
            @endforeach
        </x-ui.table>
    @endif
</div>

@endsection
