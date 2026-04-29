@extends('layouts.teacher')

@section('title', 'Create New Assessment')
@section('page-title', 'Create New Assessment')

@section('content')
<style>
    .assessment-container {
        max-width: 800px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .assessment-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 32px;
        text-align: center;
    }

    .assessment-header h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .assessment-header p {
        font-size: 16px;
        opacity: 0.9;
    }

    .assessment-body {
        padding: 32px;
    }

    .form-section {
        margin-bottom: 32px;
    }

    .form-section-title {
        font-size: 20px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e5e7eb;
    }

    .assessment-type-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .type-option {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .type-option:hover {
        border-color: #667eea;
        background: #f8f9ff;
    }

    .type-option.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, #667eea10, #764ba210);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .type-option input[type="radio"] {
        display: none;
    }

    .type-option h3 {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .type-option p {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-group label .required {
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
        background: #ffffff;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-control::placeholder {
        color: #9ca3af;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #667eea;
    }

    .checkbox-group label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
        margin-top: 32px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    .btn-outline {
        background: transparent;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }

    .btn-outline:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    .error-message {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
    }

    .question-item {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .question-number {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }

    .btn-remove-question {
        background: #ef4444;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn-remove-question:hover {
        background: #dc2626;
    }
</style>

<div class="assessment-container">
    <div class="assessment-header">
        <h1>Create New Assessment</h1>
        <p>Fill in the details to create an exam, quiz, or test</p>
    </div>

    @if($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
            <strong style="color: #dc2626;">Please fix the following errors:</strong>
            <ul style="margin: 10px 0 0 20px; color: #dc2626;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="assessment-body">
        <form method="POST" action="{{ route('teacher.exams.store') }}">
            @csrf

            <!-- Assessment Type -->
            <div class="form-section">
                <h2 class="form-section-title">Assessment Type</h2>
                <div class="form-group">
                    <label class="form-label" for="type">Assessment Type <span class="required">*</span></label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="">Select assessment type</option>
                        <option value="exam" {{ old('type', $mode) == 'exam' ? 'selected' : '' }}>Exam</option>
                        <option value="test" {{ old('type', $mode) == 'test' ? 'selected' : '' }}>Test</option>
                    </select>
                    <div class="help-text">Choose the type of assessment you want to create</div>
                    @error('type')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Basic Information -->
            <div class="form-section">
                <h2 class="form-section-title">Basic Information</h2>
                
                <div class="form-group">
                    <label for="title">Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" 
                           value="{{ old('title') }}" 
                           placeholder="e.g., Midterm Exam - Chapter 1-5" required>
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="course_id">Course <span class="required">*</span></label>
                    <select id="course_id" name="course_id" class="form-control" required>
                        <option value="">Select a course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" 
                                    {{ old('course_id', $selectedCourseId) == $course->id ? 'selected' : '' }}>
                                {{ $course->category_name ?: 'Uncategorized' }} / {{ $course->class_name ?: 'Unassigned' }} / {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="exam_date">Exam Date <span class="required">*</span></label>
                        <input type="date" id="exam_date" name="exam_date" class="form-control" 
                               value="{{ old('exam_date') }}" required>
                        @error('exam_date')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="exam_time">Exam Time</label>
                        <input type="time" id="exam_time" name="exam_time" class="form-control" 
                               value="{{ old('exam_time') }}">
                        @error('exam_time')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" 
                              placeholder="Brief description of the assessment...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Assessment Settings -->
            <div class="form-section">
                <h2 class="form-section-title">Assessment Settings</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="max_score">Max Score <span class="required">*</span></label>
                        <input type="number" id="max_score" name="max_score" class="form-control" 
                               value="{{ old('max_score', 100) }}" min="1" required>
                        @error('max_score')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="duration_minutes">Duration (minutes) <span class="required">*</span></label>
                        <input type="number" id="duration_minutes" name="duration_minutes" class="form-control" 
                               value="{{ old('duration_minutes', 30) }}" min="1" max="600" required>
                        @error('duration_minutes')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="secure_instructions">Secure Mode Instructions</label>
                    <textarea id="secure_instructions" name="secure_instructions" class="form-control" 
                              placeholder="Instructions for secure exam mode...">{{ old('secure_instructions') }}</textarea>
                    @error('secure_instructions')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Secure Mode Settings -->
                <div class="checkbox-group">
                    <input type="hidden" name="secure_mode" value="0">
                    <input type="checkbox" id="secure_mode" name="secure_mode" value="1"
                           @if(old('secure_mode')) checked @endif>
                    <label for="secure_mode">Enable Secure Mode</label>
                    <span style="color: #6b7280; font-size: 12px;">Prevent cheating during exam</span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="max_violations">Max Violations</label>
                        <input type="number" id="max_violations" name="max_violations" class="form-control" 
                               value="{{ old('max_violations', 3) }}" min="1" max="10">
                        @error('max_violations')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="max_warnings">Max Warnings</label>
                        <input type="number" id="max_warnings" name="max_warnings" class="form-control" 
                               value="{{ old('max_warnings', 5) }}" min="1" max="20">
                        @error('max_warnings')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Questions Section -->
            <div class="form-section">
                <h2 class="form-section-title">Questions</h2>
                <div id="questions-container">
                    @if(old('questions'))
                        @foreach(old('questions') as $index => $question)
                            <div class="question-item" data-index="{{ $index }}">
                                <div class="question-header">
                                    <span class="question-number">Question {{ $index + 1 }}</span>
                                    <button type="button" class="btn-remove-question" onclick="removeQuestion({{ $index }})">Remove</button>
                                </div>
                                <div class="form-group">
                                    <label>Question Text <span class="required">*</span></label>
                                    <textarea name="questions[{{ $index }}][question_text]" class="form-control" rows="3" required>{{ $question['question_text'] ?? '' }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Answer Key (Correct Answer) <span class="required">*</span></label>
                                    <textarea name="questions[{{ $index }}][answer_key]" class="form-control" rows="2" placeholder="Enter the correct answer for auto-grading">{{ $question['answer_key'] ?? '' }}</textarea>
                                    <small style="color: #6b7280;">This will be used to automatically grade student answers</small>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Question Type</label>
                                        <select name="questions[{{ $index }}][question_type]" class="form-control">
                                            <option value="short_answer" {{ ($question['question_type'] ?? '') == 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                                            <option value="long_answer" {{ ($question['question_type'] ?? '') == 'long_answer' ? 'selected' : '' }}>Long Answer</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Points</label>
                                        <input type="number" name="questions[{{ $index }}][points]" class="form-control" value="{{ $question['points'] ?? 1 }}" min="1">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" class="btn btn-outline" onclick="addQuestion()" style="margin-top: 16px;">+ Add Question</button>
                @error('questions')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('teacher.exams.index', ['course_id' => $selectedCourseId]) }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Assessment</button>
            </div>
        </form>
    </div>
</div>

<script>
let questionCount = 0;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize question count if there are existing questions
    const existingQuestions = document.querySelectorAll('.question-item');
    if (existingQuestions.length > 0) {
        questionCount = existingQuestions.length;
    }
});

function addQuestion() {
    const container = document.getElementById('questions-container');
    const questionHtml = `
        <div class="question-item" data-index="${questionCount}">
            <div class="question-header">
                <span class="question-number">Question ${questionCount + 1}</span>
                <button type="button" class="btn-remove-question" onclick="removeQuestion(${questionCount})">Remove</button>
            </div>
            <div class="form-group">
                <label>Question Text <span class="required">*</span></label>
                <textarea name="questions[${questionCount}][question_text]" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Answer Key (Correct Answer) <span class="required">*</span></label>
                <textarea name="questions[${questionCount}][answer_key]" class="form-control" rows="2" placeholder="Enter the correct answer for auto-grading"></textarea>
                <small style="color: #6b7280;">This will be used to automatically grade student answers</small>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Question Type</label>
                    <select name="questions[${questionCount}][question_type]" class="form-control">
                        <option value="short_answer">Short Answer</option>
                        <option value="long_answer">Long Answer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Points</label>
                    <input type="number" name="questions[${questionCount}][points]" class="form-control" value="1" min="1">
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', questionHtml);
    questionCount++;
}

function removeQuestion(index) {
    const questionItem = document.querySelector(`.question-item[data-index="${index}"]`);
    if (questionItem) {
        questionItem.remove();
    }
}
</script>
@endsection
