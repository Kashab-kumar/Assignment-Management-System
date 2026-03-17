@extends('layouts.teacher')

@php
    $typeLabels = ['exam' => 'Exam', 'quiz' => 'Quiz', 'test' => 'Test'];
    $assessmentType = old('type', $mode);
    if (!in_array($assessmentType, ['exam', 'quiz', 'test'], true)) {
        $assessmentType = 'exam';
    }

    $questionItems = old('questions', [['question_text' => '', 'question_type' => 'short_answer', 'points' => 1]]);
    if (!is_array($questionItems) || $questionItems === []) {
        $questionItems = [['question_text' => '', 'question_type' => 'short_answer', 'points' => 1]];
    }
@endphp

@section('title', 'Create ' . $typeLabels[$assessmentType])
@section('page-title', 'Create ' . $typeLabels[$assessmentType])

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
    .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; color: var(--text-color); }
    .btn { padding: 10px 18px; background: #2196F3; color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
    .btn-link { margin-left: 10px; color: var(--text-color); text-decoration: none; }
    .btn-secondary { background: #111827; color: #fff; }
    .questions-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin: 24px 0 12px; }
    .questions-note { color: var(--text-color); font-size: 14px; margin-bottom: 12px; }
    .question-card { border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px; margin-bottom: 14px; background: #f9fafb; }
    .question-card-header { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 12px; }
    .question-grid { display: grid; grid-template-columns: minmax(0, 1fr) 180px 120px; gap: 12px; }
    .question-card textarea { min-height: 120px; resize: vertical; }
    .question-remove { background: #ef4444; color: #fff; border: 0; border-radius: 6px; padding: 8px 12px; cursor: pointer; }

    @media (max-width: 900px) {
        .question-grid { grid-template-columns: 1fr; }
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

    <form method="POST" action="{{ route('teacher.exams.store') }}">
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
        </div>

        <div class="form-group">
            <label for="type">Assessment Type *</label>
            <select id="type" name="type" required>
                @foreach($typeLabels as $value => $label)
                    <option value="{{ $value }}" {{ old('type', $assessmentType) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="title">Assessment Title *</label>
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
            <label for="exam_time">Start Time</label>
            <input type="time" id="exam_time" name="exam_time" value="{{ old('exam_time') }}">
        </div>

        <div class="form-group">
            <label for="duration_minutes">Duration (Minutes) *</label>
            <input type="number" id="duration_minutes" name="duration_minutes" min="1" max="600" value="{{ old('duration_minutes', 90) }}" required>
        </div>

        <div class="form-group">
            <label for="max_score">Maximum Score *</label>
            <input type="number" id="max_score" name="max_score" min="1" max="1000" value="{{ old('max_score', 100) }}" required>
        </div>

        <div class="questions-header">
            <div>
                <h3 style="margin:0;">Questions</h3>
                <div class="questions-note">Add the questions students should answer. Long answer questions will render as a textarea on the student side.</div>
            </div>
            <button type="button" class="btn btn-secondary" id="add-question">+ Add Question</button>
        </div>

        <div id="question-list">
            @foreach($questionItems as $index => $question)
                <div class="question-card" data-question-card>
                    <div class="question-card-header">
                        <strong>Question {{ $loop->iteration }}</strong>
                        <button type="button" class="question-remove" data-remove-question {{ count($questionItems) === 1 ? 'style=display:none;' : '' }}>Remove</button>
                    </div>
                    <div class="question-grid">
                        <div class="form-group" style="margin-bottom:0;">
                            <label>Question Text *</label>
                            <textarea name="questions[{{ $index }}][question_text]" required>{{ $question['question_text'] ?? '' }}</textarea>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label>Answer Field *</label>
                            <select name="questions[{{ $index }}][question_type]" required>
                                <option value="short_answer" {{ ($question['question_type'] ?? 'short_answer') === 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                                <option value="long_answer" {{ ($question['question_type'] ?? '') === 'long_answer' ? 'selected' : '' }}>Long Answer</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label>Points *</label>
                            <input type="number" name="questions[{{ $index }}][points]" min="1" max="1000" value="{{ $question['points'] ?? 1 }}" required>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn">Create Assessment</button>
        <a class="btn-link" href="{{ $selectedCourseId ? route('teacher.courses.show', $selectedCourseId) : route('teacher.exams.index') }}">Cancel</a>
    </form>
</div>

<template id="question-template">
    <div class="question-card" data-question-card>
        <div class="question-card-header">
            <strong>Question __NUMBER__</strong>
            <button type="button" class="question-remove" data-remove-question>Remove</button>
        </div>
        <div class="question-grid">
            <div class="form-group" style="margin-bottom:0;">
                <label>Question Text *</label>
                <textarea name="questions[__INDEX__][question_text]" required></textarea>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label>Answer Field *</label>
                <select name="questions[__INDEX__][question_type]" required>
                    <option value="short_answer">Short Answer</option>
                    <option value="long_answer">Long Answer</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label>Points *</label>
                <input type="number" name="questions[__INDEX__][points]" min="1" max="1000" value="1" required>
            </div>
        </div>
    </div>
</template>

<script>
    const questionList = document.getElementById('question-list');
    const questionTemplate = document.getElementById('question-template').innerHTML;
    const addQuestionButton = document.getElementById('add-question');

    function refreshQuestionCards() {
        const cards = questionList.querySelectorAll('[data-question-card]');

        cards.forEach((card, index) => {
            const title = card.querySelector('strong');
            const removeButton = card.querySelector('[data-remove-question]');
            if (title) {
                title.textContent = `Question ${index + 1}`;
            }

            card.querySelectorAll('textarea, select, input').forEach((field) => {
                field.name = field.name.replace(/questions\[\d+\]/, `questions[${index}]`);
            });

            if (removeButton) {
                removeButton.style.display = cards.length === 1 ? 'none' : 'inline-block';
            }
        });
    }

    addQuestionButton.addEventListener('click', () => {
        const index = questionList.querySelectorAll('[data-question-card]').length;
        const markup = questionTemplate
            .replace(/__INDEX__/g, index)
            .replace(/__NUMBER__/g, index + 1);

        questionList.insertAdjacentHTML('beforeend', markup);
        refreshQuestionCards();
    });

    questionList.addEventListener('click', (event) => {
        if (!event.target.matches('[data-remove-question]')) {
            return;
        }

        const card = event.target.closest('[data-question-card]');
        if (card) {
            card.remove();
            refreshQuestionCards();
        }
    });

    refreshQuestionCards();
</script>
@endsection
