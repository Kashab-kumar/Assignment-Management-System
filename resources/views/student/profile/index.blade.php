@extends('layouts.student')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<style>
    .grid { display:grid; grid-template-columns: 1fr 1fr; gap:16px; }
    .card { background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1); }
    .row { margin-bottom:10px; }
    .label { color:#666; font-size:13px; }
    .value { font-size:16px; font-weight:bold; }
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
