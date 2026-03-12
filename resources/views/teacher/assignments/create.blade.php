@extends('layouts.teacher')

@section('title', 'Create Assignment')
@section('page-title', 'Create New Assignment')

@section('content')
<style>
    .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 900px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
    .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; font-family: Arial, sans-serif; }
    .form-group textarea { min-height: 150px; resize: vertical; }
    .form-group small { color: #666; font-size: 12px; }
    .btn { padding: 12px 24px; background: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; text-decoration: none; display: inline-block; }
    .btn:hover { background: #1976D2; }
    .btn-secondary { background: #666; margin-left: 10px; }
    .btn-secondary:hover { background: #555; }
    .alert-danger { padding: 15px; background: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 20px; }
</style>

<div class="card">
    @if($errors->any())
    <div class="alert-danger">
        <strong>Please fix the following errors:</strong>
        <ul style="margin: 10px 0 0 20px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('teacher.assignments.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="course_id">Course *</label>
            <select id="course_id" name="course_id" required>
                <option value="">Choose a course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ (string) old('course_id', $selectedCourseId) === (string) $course->id ? 'selected' : '' }}>
                        {{ $course->category_name ?: 'Uncategorized' }} / {{ $course->class_name ?: 'Unassigned' }} / {{ $course->name }}
                    </option>
                @endforeach
            </select>
            <small>Assignment will be visible for this course context</small>
        </div>
        
        <div class="form-group">
            <label for="title">Assignment Title *</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="e.g., Chapter 5 Homework" required>
        </div>

        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" placeholder="Provide detailed instructions for the assignment..." required>{{ old('description') }}</textarea>
            <small>Explain what students need to do</small>
        </div>

        <div class="form-group">
            <label for="type">Type *</label>
            <select id="type" name="type" required>
                <option value="assignment" {{ old('type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                <option value="homework" {{ old('type') == 'homework' ? 'selected' : '' }}>Homework</option>
            </select>
        </div>

        <div class="form-group">
            <label for="due_date">Due Date *</label>
            <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}" min="{{ date('Y-m-d') }}" required>
            <small>Students can submit until this date</small>
        </div>

        <div class="form-group">
            <label for="max_score">Maximum Score *</label>
            <input type="number" id="max_score" name="max_score" value="{{ old('max_score', 100) }}" min="1" max="1000" required>
            <small>Total points for this assignment</small>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn">Create Assignment</button>
            <a href="{{ $selectedCourseId ? route('teacher.courses.show', $selectedCourseId) : route('teacher.assignments.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
