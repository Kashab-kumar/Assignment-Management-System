@extends('layouts.student')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<style>
    .grid { display:grid; grid-template-columns: 1fr 1fr; gap:16px; }
    .card {
        background: #ffffff;
        padding: 22px;
        border-radius: 12px;
        border: 1px solid rgba(0,0,0,0.06);
    }
    .card h2 { color: #1f2937; }
    .row {
        margin-bottom: 12px;
        padding: 10px 12px;
        background: rgba(0,0,0,0.02);
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.04);
    }
    .row:last-child { margin-bottom: 0; }
    .label { color: #000000; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em; }
    .value { font-size: 16px; font-weight: 700; color: #1f2937; margin-top: 2px; }
    @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }
</style>

<div class="grid">
    <div class="card">
        <h2 style="margin-bottom:12px;">Personal Information</h2>
        <div class="row"><div class="label">Name</div><div class="value">{{ $student->name }}</div></div>
        <div class="row"><div class="label">Email</div><div class="value">{{ $student->email }}</div></div>
        <div class="row"><div class="label">Student ID</div><div class="value">{{ $student->student_id }}</div></div>
        <div class="row"><div class="label">Course</div><div class="value">{{ $student->course?->name ?? 'Not assigned' }}</div></div>
    </div>

    <div class="card">
        <h2 style="margin-bottom:12px;">Academic Snapshot</h2>
        <div class="row"><div class="label">Total Submissions</div><div class="value">{{ $submissionCount }}</div></div>
        <div class="row"><div class="label">Graded Submissions</div><div class="value">{{ $gradedSubmissionCount }}</div></div>
        <div class="row"><div class="label">Exam Results</div><div class="value">{{ $examResultCount }}</div></div>
        <div class="row"><div class="label">Average Score</div><div class="value">{{ number_format($student->getAverageScore(), 2) }}</div></div>
    </div>
</div>
@endsection
