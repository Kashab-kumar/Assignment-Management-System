@extends('layouts.teacher')

@section('title', 'Exams')
@section('page-title', 'Exams')

@section('content')
<style>
    .section { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .btn { padding: 8px 14px; background: #4CAF50; color: #fff; text-decoration: none; border-radius: 4px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f8f8; }
</style>

<div class="section">
    <div class="header">
        <h2>Exam List</h2>
        <a href="{{ route('teacher.exams.create', $selectedCourseId ? ['course_id' => $selectedCourseId] : []) }}" class="btn">+ Create Exam / Quiz</a>
    </div>

    <form method="GET" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <label for="course_id" style="font-weight:bold;">Filter by Course:</label>
        <select id="course_id" name="course_id" onchange="this.form.submit()" style="padding:8px 10px; border:1px solid #ddd; border-radius:4px; min-width:260px;">
            <option value="">All Courses</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" {{ (string) $selectedCourseId === (string) $course->id ? 'selected' : '' }}>
                    {{ $course->category_name ?: 'Uncategorized' }} / {{ $course->class_name ?: 'Unassigned' }} / {{ $course->name }}
                </option>
            @endforeach
        </select>
        @if($selectedCourseId)
            <a href="{{ route('teacher.exams.index') }}" class="btn" style="background:#666;">Clear</a>
        @endif
    </form>

    @if(session('success'))
        <div style="padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 16px;">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Course</th>
                <th>Date</th>
                <th>Max Score</th>
                <th>Results</th>
                <th>Average</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($exams as $exam)
                <tr>
                    <td>{{ $exam->title }}</td>
                    <td>{{ $exam->course?->name ?? '-' }}</td>
                    <td>{{ $exam->exam_date->format('M d, Y') }}</td>
                    <td>{{ $exam->max_score }}</td>
                    <td>{{ $exam->results_count }}</td>
                    <td>{{ $exam->results_avg_score ? number_format($exam->results_avg_score, 2) : '-' }}</td>
                    <td><a href="{{ route('teacher.exams.show', $exam) }}" style="color: #2196F3; text-decoration: none;">View</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No exams created yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
