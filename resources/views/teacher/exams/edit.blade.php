@extends('layouts.teacher')

@php
    $typeLabels = ['exam' => 'Exam', 'quiz' => 'Quiz', 'test' => 'Test'];
@endphp

@section('title', 'Edit Assessment')
@section('page-title', 'Edit Assessment')

@section('content')
<style>
    .card {
        --text-color: #111827;
        background: #fff;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        max-width: 980px;
        color: var(--text-color);
    }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; margin-bottom: 6px; font-weight: bold; color: var(--text-color); }
    .form-group input, .form-group textarea, .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        color: var(--text-color);
    }
    .btn { padding: 10px 18px; background: #2196F3; color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
    .btn-link { margin-left: 10px; color: var(--text-color); text-decoration: none; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    @media (max-width: 900px) {
        .form-grid { grid-template-columns: 1fr; }
    }
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

    <form method="POST" action="{{ route('teacher.exams.update', $exam) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="course_id">Course *</label>
            <select id="course_id" name="course_id" required>
                <option value="">Choose a course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ (string) old('course_id', $exam->course_id) === (string) $course->id ? 'selected' : '' }}>
                        {{ $course->category_name ?: 'Uncategorized' }} / {{ $course->class_name ?: 'Unassigned' }} / {{ $course->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="type">Assessment Type *</label>
            <select id="type" name="type" required>
                @foreach($typeLabels as $value => $label)
                    <option value="{{ $value }}" {{ old('type', $exam->type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="title">Assessment Title *</label>
            <input id="title" name="title" value="{{ old('title', $exam->title) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description">{{ old('description', $exam->description) }}</textarea>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="exam_date">Exam Date *</label>
                <input type="date" id="exam_date" name="exam_date" value="{{ old('exam_date', $exam->exam_date?->format('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label for="exam_time">Start Time</label>
                <input type="time" id="exam_time" name="exam_time" value="{{ old('exam_time', $exam->exam_time ? substr($exam->exam_time, 0, 5) : '') }}">
            </div>

            <div class="form-group">
                <label for="duration_minutes">Duration (Minutes) *</label>
                <input type="number" id="duration_minutes" name="duration_minutes" min="1" max="600" value="{{ old('duration_minutes', $exam->duration_minutes ?? 90) }}" required>
            </div>

            <div class="form-group">
                <label for="max_score">Maximum Score *</label>
                <input type="number" id="max_score" name="max_score" min="1" max="1000" value="{{ old('max_score', $exam->max_score) }}" required>
            </div>
        </div>

        <button type="submit" class="btn">Update Assessment</button>
        <a class="btn-link" href="{{ route('teacher.exams.show', $exam) }}">Cancel</a>
    </form>
</div>
@endsection
