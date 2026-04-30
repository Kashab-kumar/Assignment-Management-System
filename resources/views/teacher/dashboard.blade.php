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
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
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

<div class="welcome-card">
    <h2>Welcome back, {{ auth()->user()->name }}!</h2>
    <p>
        Teacher ID: {{ $teacherProfile->teacher_id ?? 'N/A' }} |
        Subject: {{ $teacherProfile->subject ?? 'Not set' }} |
        Courses: {{ $assignedCoursesCount }}
    </p>
</div>

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

@endsection
