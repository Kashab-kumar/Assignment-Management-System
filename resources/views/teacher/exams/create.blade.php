@extends('layouts.teacher')

@section('title', $mode === 'quiz' ? 'Create Quiz' : 'Create Exam')
@section('page-title', $mode === 'quiz' ? 'Create Quiz' : 'Create Exam')

@section('content')
<style>
    .card { background: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 900px; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; margin-bottom: 6px; font-weight: bold; }
    .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
    .btn { padding: 10px 18px; background: #2196F3; color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
    .btn-link { margin-left: 10px; color: #666; text-decoration: none; }
</style>

<div class="card">
    @if($errors->any())
        <div style="padding: 12px; background: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 16px;">
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 8px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('teacher.exams.store') }}">
        @csrf

        <div class="form-group">
            <label for="course_id">Course *</label>
            <select id="course_id" name="course_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Choose a course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ (string) old('course_id', $selectedCourseId) === (string) $course->id ? 'selected' : '' }}>
                        {{ $course->category_name ?: 'Uncategorized' }} / {{ $course->class_name ?: 'Unassigned' }} / {{ $course->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="title">{{ $mode === 'quiz' ? 'Quiz Title' : 'Exam Title' }} *</label>
            <input id="title" name="title" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="exam_date">Exam Date *</label>
            <input type="date" id="exam_date" name="exam_date" value="{{ old('exam_date') }}" required>
        </div>

        <div class="form-group">
            <label for="max_score">Maximum Score *</label>
            <input type="number" id="max_score" name="max_score" min="1" max="1000" value="{{ old('max_score', 100) }}" required>
        </div>

        <button type="submit" class="btn">{{ $mode === 'quiz' ? 'Create Quiz' : 'Create Exam' }}</button>
        <a class="btn-link" href="{{ $selectedCourseId ? route('teacher.courses.show', $selectedCourseId) : route('teacher.exams.index') }}">Cancel</a>
    </form>
</div>
@endsection
