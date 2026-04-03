@extends('layouts.teacher')

@section('title', 'Tests & Exams')
@section('page-title', 'Tests & Exams')

@section('content')
<style>
    .section { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .btn { padding: 8px 14px; background: #4CAF50; color: #fff; text-decoration: none; border-radius: 4px; }
    .filter-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; gap: 12px; }
    .filter-buttons { display: flex; gap: 8px; }
    .filter-btn { padding: 8px 16px; border: 1px solid rgba(0,0,0,0.1); border-radius: 6px; background: #ffffff; color: #64748b; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.2s; cursor: pointer; }
    .filter-btn:hover { background: rgba(124,58,237,0.05); border-color: rgba(124,58,237,0.2); color: #4338ca; }
    .filter-btn.active { background: #7c3aed; color: #ffffff; border-color: #7c3aed; }
    .create-btn { background: #10b981; color: #ffffff; padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: 500; text-decoration: none; transition: background 0.2s; cursor: pointer; }
    .create-btn:hover { background: #059669; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f8f8; }
    .type-pill { display: inline-block; padding: 4px 8px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .type-exam { background: #ede7f6; color: #5e35b1; }
    .type-quiz { background: #fff3e0; color: #f57c00; }
    .type-test { background: #fee2e2; color: #b91c1c; }
</style>

@php
    $activeFilter = request()->query('filter', 'all');
    $typeLabels = ['exam' => 'Exam', 'quiz' => 'Quiz', 'test' => 'Test'];
@endphp

<div class="section">
    <div class="filter-section">
        <div class="filter-buttons">
            <a href="{{ route('teacher.exams.index') }}?filter=all&course_id={{ $selectedCourseId }}" class="filter-btn {{ $activeFilter === 'all' ? 'active' : '' }}">All</a>
            <a href="{{ route('teacher.exams.index') }}?filter=exam&course_id={{ $selectedCourseId }}" class="filter-btn {{ $activeFilter === 'exam' ? 'active' : '' }}">Exam</a>
            <a href="{{ route('teacher.exams.index') }}?filter=quiz&course_id={{ $selectedCourseId }}" class="filter-btn {{ $activeFilter === 'quiz' ? 'active' : '' }}">Quiz</a>
            <a href="{{ route('teacher.exams.index') }}?filter=test&course_id={{ $selectedCourseId }}" class="filter-btn {{ $activeFilter === 'test' ? 'active' : '' }}">Test</a>
        </div>
        <a href="{{ route('teacher.exams.create', $selectedCourseId ? ['course_id' => $selectedCourseId] : []) }}" class="create-btn">+ Create</a>
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
            <a href="{{ route('teacher.exams.index') }}?filter={{ $activeFilter }}" class="btn" style="background:#666;">Clear</a>
        @endif
    </form>

    @if(session('success'))
        <div style="padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 16px;">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Course</th>
                <th>Date</th>
                <th>Questions</th>
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
                    <td><span class="type-pill type-{{ $exam->type }}">{{ $typeLabels[$exam->type] ?? ucfirst($exam->type) }}</span></td>
                    <td>{{ $exam->course?->name ?? '-' }}</td>
                    <td>{{ $exam->exam_date->format('M d, Y') }}</td>
                    <td>{{ $exam->questions_count }}</td>
                    <td>{{ $exam->max_score }}</td>
                    <td>{{ $exam->results_count }}</td>
                    <td>{{ $exam->results_avg_score ? number_format($exam->results_avg_score, 2) : '-' }}</td>
                    <td>
                        <a href="{{ route('teacher.exams.show', $exam) }}" style="color: #2196F3; text-decoration: none; margin-right: 8px;">View</a>
                        <a href="{{ route('teacher.exams.edit', $exam) }}" style="color: #4CAF50; text-decoration: none;">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No assessments created yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
