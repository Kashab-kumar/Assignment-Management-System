@extends('layouts.teacher')

@section('title', 'Exams')
@section('page-title', 'Exams')

@section('content')
<style>
    .section { background: white; padding: 24px; border-radius: 12px; margin-bottom: 20px; border: 1px solid rgba(0,0,0,0.06); }
    .breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; font-size: 14px; }
    .breadcrumb a { color: #7c3aed; text-decoration: none; transition: color 0.2s; }
    .breadcrumb a:hover { color: #5b21b6; text-decoration: underline; }
    .breadcrumb span { color: #6b7280; }
    .filter-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 12px; }
    .filter-buttons { display: flex; gap: 8px; }
    .filter-btn { padding: 8px 16px; border: 1px solid rgba(0,0,0,0.1); border-radius: 6px; background: #ffffff; color: #64748b; text-decoration: none; font-size: 14px; font-weight: 500; transition: all 0.2s; cursor: pointer; }
    .filter-btn:hover { background: rgba(124,58,237,0.05); border-color: rgba(124,58,237,0.2); color: #4338ca; }
    .filter-btn.active { background: #7c3aed; color: #ffffff; border-color: #7c3aed; }
    .create-btn { background: #7c3aed; color: #ffffff; padding: 8px 16px; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; text-decoration: none; transition: background 0.2s; cursor: pointer; }
    .create-btn:hover { background: #6d28d9; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #e5e7eb; }
    th { background: #f9fafb; font-weight: 600; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; }
    td { color: #1f2937; }
    tr:hover { background: #f9fafb; }
    .type-pill { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 500; }
    .type-exam { background: #ede7f6; color: #5e35b1; }
    .type-quiz { background: #fff3e0; color: #f57c00; }
    .type-test { background: #fee2e2; color: #b91c1c; }
    .empty-state { text-align: center; padding: 60px 20px; color: #6b7280; }
</style>

@php
    $activeFilter = request()->query('filter', 'all');
    $typeLabels = ['exam' => 'Exam', 'quiz' => 'Quiz', 'test' => 'Test'];
@endphp

<div class="section">
    @if($selectedCourseId)
        @php($selectedCourse = $courses->firstWhere('id', $selectedCourseId))@endphp
        <nav class="breadcrumb">
            <a href="{{ route('teacher.courses.index') }}">Courses</a>
            <span>/</span>
            <a href="{{ route('teacher.courses.show', $selectedCourseId) }}">{{ $selectedCourse?->name ?? 'Course' }}</a>
            <span>/</span>
            <span>Exams</span>
        </nav>
    @endif

    <div class="filter-section">
        <div class="filter-buttons">
            <a href="{{ route('teacher.exams.index', array_filter(['course_id' => $selectedCourseId, 'module_id' => $selectedModuleId, 'filter' => 'all'])) }}" class="filter-btn {{ $activeFilter === 'all' ? 'active' : '' }}">All</a>
            <a href="{{ route('teacher.exams.index', array_filter(['course_id' => $selectedCourseId, 'module_id' => $selectedModuleId, 'filter' => 'exam'])) }}" class="filter-btn {{ $activeFilter === 'exam' ? 'active' : '' }}">Exam</a>
            <a href="{{ route('teacher.exams.index', array_filter(['course_id' => $selectedCourseId, 'module_id' => $selectedModuleId, 'filter' => 'quiz'])) }}" class="filter-btn {{ $activeFilter === 'quiz' ? 'active' : '' }}">Quiz</a>
            <a href="{{ route('teacher.exams.index', array_filter(['course_id' => $selectedCourseId, 'module_id' => $selectedModuleId, 'filter' => 'test'])) }}" class="filter-btn {{ $activeFilter === 'test' ? 'active' : '' }}">Test</a>
        </div>
        <a href="{{ route('teacher.exams.create', array_filter(['course_id' => $selectedCourseId, 'module_id' => $selectedModuleId])) }}" class="create-btn">+ Create Exam</a>
    </div>

    @if($exams->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Date</th>
                <th>Results</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($exams as $exam)
                <tr>
                    <td><strong>{{ $exam->title }}</strong></td>
                    <td><span class="type-pill type-{{ $exam->type }}">{{ $typeLabels[$exam->type] ?? ucfirst($exam->type) }}</span></td>
                    <td>{{ $exam->exam_date?->format('d/m/Y') ?: '-' }}</td>
                    <td>{{ $exam->results_count }} result(s)</td>
                    <td>
                        <a href="{{ route('teacher.exams.show', $exam) }}" class="create-btn">Open</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <h3>No Exams Yet</h3>
        <p>Create your first exam to get started!</p>
    </div>
    @endif
</div>
@endsection
