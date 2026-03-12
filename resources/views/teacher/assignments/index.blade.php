@extends('layouts.teacher')

@section('title', 'Manage Assignments')
@section('page-title', 'Manage Assignments')

@section('content')
<style>
    .section { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f8f8; font-weight: bold; }
    .btn { padding: 8px 16px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px; display: inline-block; border: none; cursor: pointer; font-size: 14px; }
    .btn:hover { background: #1976D2; }
    .btn-success { background: #4CAF50; }
    .btn-success:hover { background: #45a049; }
    .btn-danger { background: #e74c3c; }
    .btn-danger:hover { background: #c0392b; }
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
    .badge-assignment { background: #2196F3; color: white; }
    .badge-homework { background: #FF9800; color: white; }
    .alert-success { padding: 15px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px; }
    .empty-state { text-align: center; padding: 40px; color: #666; }
    .empty-state svg { width: 80px; height: 80px; fill: #ddd; margin-bottom: 20px; }
</style>

<div class="section">
    <div class="section-header">
        <h2>All Assignments</h2>
        <a href="{{ route('teacher.assignments.create', $selectedCourseId ? ['course_id' => $selectedCourseId] : []) }}" class="btn btn-success">+ Create New Assignment</a>
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
            <a href="{{ route('teacher.assignments.index') }}" class="btn" style="background:#666;">Clear</a>
        @endif
    </form>

    @if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if($assignments->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Course</th>
                <th>Type</th>
                <th>Due Date</th>
                <th>Max Score</th>
                <th>Submissions</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assignment)
            <tr>
                <td><strong>{{ $assignment->title }}</strong></td>
                <td>{{ $assignment->course?->name ?? '-' }}</td>
                <td><span class="badge badge-{{ $assignment->type }}">{{ ucfirst($assignment->type) }}</span></td>
                <td>{{ $assignment->due_date->format('M d, Y') }}</td>
                <td>{{ $assignment->max_score }} pts</td>
                <td>{{ $assignment->submissions_count }} submission(s)</td>
                <td>
                    <a href="{{ route('teacher.assignments.show', $assignment) }}" class="btn">View Details</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
        <h3>No Assignments Yet</h3>
        <p>Create your first assignment to get started!</p>
    </div>
    @endif
</div>
@endsection
