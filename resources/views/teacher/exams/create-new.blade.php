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
</style>

<div class="assessment-container">
    <div class="assessment-header">
        <h1>Create New Assessment</h1>
        <p>Fill in the details to create an exam, quiz, or test</p>
    </div>

    <div class="assessment-body">
        <form method="POST" action="{{ route('teacher.exams.store') }}">
            @csrf

            <!-- Assessment Type -->
            <div class="form-section">
                <h2 class="form-section-title">Assessment Type</h2>
                <div class="assessment-type-selector">
                    <div class="type-option @if(old('type', 'exam') == 'exam') selected @endif">
                        <input type="radio" name="type" value="exam" id="type_exam" @if(old('type', 'exam') == 'exam') checked @endif>
                        <label for="type_exam">
                            <h3>Exam</h3>
                            <p>Full-length formal assessment</p>
                        </label>
                    </div>
                    <div class="type-option @if(old('type', 'exam') == 'quiz') selected @endif">
                        <input type="radio" name="type" value="quiz" id="type_quiz" @if(old('type', 'exam') == 'quiz') checked @endif>
                        <label for="type_quiz">
                            <h3>Quiz</h3>
                            <p>Short practice quiz</p>
                        </label>
                    </div>
                    <div class="type-option @if(old('type', 'exam') == 'test') selected @endif">
                        <input type="radio" name="type" value="test" id="type_test" @if(old('type', 'exam') == 'test') checked @endif>
                        <label for="type_test">
                            <h3>Test</h3>
                            <p>Unit test or checkpoint</p>
                        </label>
                    </div>
                </div>
                @error('type')
                    <div class="error-message">{{ $message }}</div>
                @enderror
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
                        <label for="exam_date">Deadline</label>
                        <input type="datetime-local" id="exam_date" name="exam_date" class="form-control" 
                               value="{{ old('exam_date') }}">
                        @error('exam_date')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="duration_minutes">Duration (minutes) <span class="required">*</span></label>
                        <input type="number" id="duration_minutes" name="duration_minutes" class="form-control" 
                               value="{{ old('duration_minutes', 30) }}" min="1" required>
                        @error('duration_minutes')
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
                        <label for="max_score">Total Questions <span class="required">*</span></label>
                        <input type="number" id="max_score" name="max_score" class="form-control" 
                               value="{{ old('max_score', 10) }}" min="1" required>
                        @error('max_score')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="passing_score">Passing Score (%)</label>
                        <input type="number" id="passing_score" name="passing_score" class="form-control" 
                               value="{{ old('passing_score', 60) }}" min="0" max="100">
                        @error('passing_score')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="instructions">Instructions for Students</label>
                    <textarea id="instructions" name="instructions" class="form-control" 
                              placeholder="Enter instructions that students will see before starting...">{{ old('instructions') }}</textarea>
                    @error('instructions')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Additional Settings -->
                <div class="checkbox-group">
                    <input type="checkbox" id="randomize_questions" name="randomize_questions" 
                           @if(old('randomize_questions')) checked @endif>
                    <label for="randomize_questions">Randomize Questions</label>
                    <span style="color: #6b7280; font-size: 12px;">Shuffle question order for each student</span>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="show_results_immediately" name="show_results_immediately" 
                           @if(old('show_results_immediately')) checked @endif>
                    <label for="show_results_immediately">Show Results Immediately</label>
                    <span style="color: #6b7280; font-size: 12px;">Students see scores after submission</span>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('teacher.exams.index', ['course_id' => $selectedCourseId]) }}" class="btn btn-outline">Cancel</a>
                <button type="submit" name="action" value="draft" class="btn btn-secondary">Save as Draft</button>
                <button type="submit" name="action" value="publish" class="btn btn-primary">Publish Now</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle assessment type selection
    const typeOptions = document.querySelectorAll('.type-option');
    
    typeOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            typeOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked option
            this.classList.add('selected');
            
            // Check the radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
        });
    });
});
</script>
@endsection
