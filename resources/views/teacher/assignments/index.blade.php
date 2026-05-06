@extends('layouts.teacher')

@section('title', 'Assignments')
@section('page-title', 'Assignments')

@section('content')
<style>
    .section { background: white; padding: 24px; border-radius: 12px; margin-bottom: 20px; border: 1px solid rgba(0,0,0,0.06); }
    .breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; font-size: 14px; }
    .breadcrumb a { color: #7c3aed; text-decoration: none; transition: color 0.2s; }
    .breadcrumb a:hover { color: #5b21b6; text-decoration: underline; }
    .breadcrumb span { color: #6b7280; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #e5e7eb; }
    th { background: #f9fafb; font-weight: 600; color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; }
    td { color: #1f2937; }
    tr:hover { background: #f9fafb; }
    .btn { padding: 8px 16px; background: #7c3aed; color: white; text-decoration: none; border-radius: 8px; display: inline-block; border: none; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.2s; }
    .btn:hover { background: #6d28d9; transform: translateY(-1px); }
    .badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 500; }
    .badge-assignment { background: #dbeafe; color: #1e40af; }
    .badge-homework { background: #fef3c7; color: #92400e; }
    .badge-project { background: #d1fae5; color: #065f46; }
    .empty-state { text-align: center; padding: 60px 20px; color: #6b7280; }
</style>

<div class="section">
    @if($selectedCourseId)
        @php($selectedCourse = $courses->firstWhere('id', $selectedCourseId))@endphp
        <nav class="breadcrumb">
            <a href="{{ route('teacher.courses.index') }}">Courses</a>
            <span>/</span>
            <a href="{{ route('teacher.courses.show', $selectedCourseId) }}">{{ $selectedCourse?->name ?? 'Course' }}</a>
            <span>/</span>
            <span>Assignments</span>
        </nav>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">All Assignments</h2>
        <a href="{{ route('teacher.assignments.create', array_filter(['course_id' => $selectedCourseId, 'module_id' => $selectedModuleId])) }}" class="btn">+ Create Assignment</a>
    </div>

    @if($assignments->count() > 0)
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
            @foreach($assignments as $assignment)
            <tr>
                <td><strong>{{ $assignment->title }}</strong></td>
                <td><span class="badge badge-{{ $assignment->type }}">{{ ucfirst($assignment->type) }}</span></td>
                <td>{{ $assignment->due_date?->format('d/m/Y') ?: '-' }}</td>
                <td>{{ $assignment->submissions_count }} submission(s)</td>
                <td>
                    <a href="{{ route('teacher.assignments.submissions.index', $assignment) }}" class="btn">View Submissions</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <h3>No Assignments Yet</h3>
        <p>Create your first assignment to get started!</p>
    </div>
    @endif
</div>
@endsection
