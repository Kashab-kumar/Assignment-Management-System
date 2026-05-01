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
        margin-bottom: 8px;
        font-weight: 500;
        color: #374151;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .google-question-card {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        transition: box-shadow 0.2s;
    }

    .google-question-card:hover {
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    }

    .google-question-header {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 16px;
    }

    .google-question-input {
        flex: 1;
        font-size: 16px;
        font-weight: 500;
        border: none;
        border-bottom: 1px solid transparent;
        padding: 8px 0;
        background: transparent;
        transition: border-color 0.2s;
    }

    .google-question-input:focus {
        outline: none;
        border-bottom-color: #673ab7;
    }

    .google-question-input::placeholder {
        color: #9e9e9e;
    }

    .google-question-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .google-type-select {
        border: none;
        background: transparent;
        font-size: 14px;
        color: #673ab7;
        cursor: pointer;
        font-weight: 500;
    }

    .google-type-select:focus {
        outline: none;
    }

    .google-action-btn {
        background: transparent;
        border: none;
        color: #5f6368;
        cursor: pointer;
        padding: 4px;
        border-radius: 50%;
        transition: background 0.2s;
    }

    .google-action-btn:hover {
        background: #f1f3f4;
    }

    .google-answer-area {
        margin-top: 12px;
    }

    .google-answer-preview {
        width: 100%;
        border: none;
        background: #f8f9fa;
        padding: 12px;
        border-radius: 4px;
        resize: none;
        min-height: 40px;
        font-family: inherit;
        color: #5f6368;
    }

    .google-mcq-options {
        margin-top: 12px;
    }

    .google-mcq-option-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }

    .google-option-icon {
        color: #5f6368;
        font-size: 16px;
    }

    .google-correct-radio {
        width: 18px;
        height: 18px;
        accent-color: #673ab7;
        cursor: pointer;
    }

    .google-correct-label {
        font-size: 11px;
        color: #673ab7;
        margin-left: 4px;
        font-weight: 500;
    }

    .google-option-input {
        flex: 1;
        border: none;
        border-bottom: 1px solid transparent;
        padding: 8px 0;
        font-size: 14px;
        background: transparent;
        transition: border-color 0.2s;
    }

    .google-option-input:focus {
        outline: none;
        border-bottom-color: #673ab7;
    }

    .google-option-input::placeholder {
        color: #9e9e9e;
    }

    .google-remove-option {
        background: transparent;
        border: none;
        color: #5f6368;
        cursor: pointer;
        font-size: 20px;
        padding: 4px;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .google-mcq-option-row:hover .google-remove-option {
        opacity: 1;
    }

    .google-add-option {
        background: transparent;
        border: none;
        color: #673ab7;
        cursor: pointer;
        font-size: 14px;
        padding: 8px 0;
        font-weight: 500;
        transition: color 0.2s;
    }

    .google-add-option:hover {
        color: #5e35b1;
    }

    .google-points-input {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #e0e0e0;
    }

    .google-points-input label {
        font-size: 14px;
        color: #5f6368;
        font-weight: 500;
    }

    .google-points-input input {
        border: 1px solid #e0e0e0;
        border-radius: 4px;
    }

    .google-add-question {
        width: 100%;
        background: transparent;
        border: none;
        color: #673ab7;
        cursor: pointer;
        padding: 16px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 8px;
        transition: background 0.2s;
        border: 1px dashed #e0e0e0;
        margin-top: 16px;
    }

    .google-add-question:hover {
        background: #f5f5f5;
        border-color: #673ab7;
    }

    .hidden {
        display: none !important;
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
                        <option value="quiz" {{ old('type', $mode) == 'quiz' ? 'selected' : '' }}>Quiz</option>
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
                    <select id="course_id" name="course_id" class="form-control" required onchange="loadModules(this.value)">
                        <option value="">Select a course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" 
                                    data-modules="{{ json_encode($course->modules ?? []) }}"
                                    {{ old('course_id', $selectedCourseId) == $course->id ? 'selected' : '' }}>
                                {{ $course->category_name ?: 'Uncategorized' }} / {{ $course->class_name ?: 'Unassigned' }} / {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="module_id">Module</label>
                    <select id="module_id" name="module_id" class="form-control">
                        <option value="">Select a module (optional)</option>
                    </select>
                    <small style="color: #6b7280;">Assign this exam to a specific module</small>
                    @error('module_id')
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
                            @php
                                $questionType = $question['question_type'] ?? 'short_answer';
                                $options = [];
                                if ($questionType === 'multiple_choice' && !empty($question['answer_key'])) {
                                    $options = array_map('trim', explode('|', $question['answer_key']));
                                }
                            @endphp
                            <div class="google-question-card" data-index="{{ $index }}" data-type="{{ $questionType }}">
                                <div class="google-question-header">
                                    <input type="text" name="questions[{{ $index }}][question_text]" class="google-question-input" placeholder="Question" value="{{ $question['question_text'] ?? '' }}" required>
                                    <div class="google-question-actions">
                                        <select name="questions[{{ $index }}][question_type]" class="google-type-select" onchange="toggleQuestionType({{ $index }}, this.value)">
                                            <option value="short_answer" {{ $questionType == 'short_answer' ? 'selected' : '' }}>Short answer</option>
                                            <option value="long_answer" {{ $questionType == 'long_answer' ? 'selected' : '' }}>Paragraph</option>
                                            <option value="multiple_choice" {{ $questionType == 'multiple_choice' ? 'selected' : '' }}>Multiple choice</option>
                                        </select>
                                        <button type="button" class="google-action-btn" onclick="removeQuestion({{ $index }})" title="Delete">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Short/Long Answer Input -->
                                <div class="google-answer-area {{ $questionType === 'multiple_choice' ? 'hidden' : '' }}" data-area="text-answer-{{ $index }}">
                                    <textarea class="google-answer-preview" readonly placeholder="Short answer text"></textarea>
                                    <div class="google-points-input">
                                        <label>Points:</label>
                                        <input type="number" name="questions[{{ $index }}][points]" value="{{ $question['points'] ?? 1 }}" min="1" style="width: 60px; padding: 4px 8px;">
                                    </div>
                                </div>

                                <!-- Multiple Choice Options -->
                                <div class="google-mcq-options {{ $questionType !== 'multiple_choice' ? 'hidden' : '' }}" data-area="mcq-options-{{ $index }}">
                                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 8px;">Click the radio button to mark the correct answer:</div>
                                    @foreach($options as $optIndex => $option)
                                        <div class="google-mcq-option-row">
                                            <input type="radio" name="correct-answer-{{ $index }}" class="google-correct-radio" {{ $optIndex === 0 ? 'checked' : '' }} onchange="updateCorrectAnswer({{ $index }})">
                                            <input type="text" class="google-option-input" value="{{ $option }}" placeholder="Option {{ $optIndex + 1 }}">
                                            <button type="button" class="google-remove-option" onclick="removeOption({{ $index }}, this)">×</button>
                                        </div>
                                    @endforeach
                                    <button type="button" class="google-add-option" onclick="addOption({{ $index }})">Add option</button>
                                    <div class="google-points-input">
                                        <label>Points:</label>
                                        <input type="number" name="questions[{{ $index }}][points]" value="{{ $question['points'] ?? 1 }}" min="1" style="width: 60px; padding: 4px 8px;">
                                    </div>
                                </div>

                                <!-- Hidden field to store MCQ options -->
                                <input type="hidden" name="questions[{{ $index }}][answer_key]" class="google-answer-key" value="{{ $question['answer_key'] ?? '' }}">
                            </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" class="google-add-question" id="add-question-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    Add question
                </button>
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
const selectedModuleId = {{ $selectedModuleId ?? 'null' }};

document.addEventListener('DOMContentLoaded', function() {
    // Initialize question count if there are existing questions
    const existingQuestions = document.querySelectorAll('.google-question-card');
    if (existingQuestions.length > 0) {
        questionCount = existingQuestions.length;
        
        // Add input event listeners for MCQ options
        existingQuestions.forEach((question, index) => {
            const typeSelect = question.querySelector('select[name*="[question_type]"]');
            if (typeSelect && typeSelect.value === 'multiple_choice') {
                // Add event listeners to option inputs
                const optionInputs = question.querySelectorAll('.google-option-input');
                optionInputs.forEach(input => {
                    input.addEventListener('input', () => updateCorrectAnswer(index));
                });
                
                // Add event listeners to correct answer radio buttons
                const radioButtons = question.querySelectorAll('.google-correct-radio');
                radioButtons.forEach(radio => {
                    radio.addEventListener('change', () => updateCorrectAnswer(index));
                });
                
                // Initialize correct answer
                updateCorrectAnswer(index);
            }
        });
    }

    // Load modules for initially selected course
    const courseSelect = document.getElementById('course_id');
    if (courseSelect.value) {
        loadModules(courseSelect.value);
    }
    
    // Add event listener for add question button
    const addQuestionBtn = document.getElementById('add-question-btn');
    if (addQuestionBtn) {
        addQuestionBtn.addEventListener('click', function(e) {
            e.preventDefault();
            alert('Button clicked!');
            addQuestion();
        });
        a

function loadModules(courseId) {
    const moduleSelect = document.getElementById('module_id');
    const courseOption = document.querySelector(`#course_id option[value="${courseId}"]`);
    
    moduleSelect.innerHTML = '<option value="">Select a module (optional)</option>';
    
    if (!courseOption || !courseOption.dataset.modules) {
        return;
    }
    
    try {
        const modules = JSON.parse(courseOption.dataset.modules);
        modules.forEach(module => {
            const option = document.createElement('option');
            option.value = module.id;
            option.textContent = `Module ${module.position}: ${module.title}`;
            if (module.id == selectedModuleId) {
                option.selected = true;
            }
            moduleSelect.appendChild(option);
        });
    } catch (e) {
        console.error('Error parsing modules:', e);
    }
}

function addQuestion() {
    const container = document.getElementById('questions-container');
    
    if (!container) {
        console.error('questions-container not found');
        return;
    }
    
    // Simple test - just add a div
    const testDiv = document.createElement('div');
    testDiv.innerHTML = '<h3>Test Question ' + (questionCount + 1) + '</h3>';
    testDiv.style.padding = '20px';
    testDiv.style.border = '2px solid red';
    container.appendChild(testDiv);
    
    questionCount++;
    console.log('Question added, count:', questionCount);
}

function removeQuestion(index) {
    const questionItem = document.querySelector(`.google-question-card[data-index="${index}"]`);
    if (questionItem) {
        questionItem.remove();
    }
}

function toggleQuestionType(index, questionType) {
    const questionCard = document.querySelector(`.google-question-card[data-index="${index}"]`);
    if (!questionCard) return;

    questionCard.dataset.type = questionType;

    const textAnswerArea = questionCard.querySelector(`[data-area="text-answer-${index}"]`);
    const mcqOptionsArea = questionCard.querySelector(`[data-area="mcq-options-${index}"]`);
    const answerPreview = textAnswerArea.querySelector('.google-answer-preview');

    if (questionType === 'multiple_choice') {
        textAnswerArea.classList.add('hidden');
        mcqOptionsArea.classList.remove('hidden');
        updateAnswerKey(index);
    } else {
        textAnswerArea.classList.remove('hidden');
        mcqOptionsArea.classList.add('hidden');
        
        if (questionType === 'long_answer') {
            answerPreview.placeholder = 'Long answer text';
            answerPreview.style.minHeight = '120px';
        } else {
            answerPreview.placeholder = 'Short answer text';
            answerPreview.style.minHeight = '40px';
        }
        
        // Clear answer key for text questions
        const answerKeyInput = questionCard.querySelector('.google-answer-key');
        if (answerKeyInput) {
            answerKeyInput.value = '';
        }
    }
}

function addOption(questionIndex) {
    const questionCard = document.querySelector(`.google-question-card[data-index="${questionIndex}"]`);
    if (!questionCard) return;
input tye="radio" name="correct-nswer-${questionIdex}"crrec-rado" hage=udteCorrectAnswer(${questionIdex})"
    const mcqOptionsArea = questionCard.querySelector(`[data-area="mcq-options-${questionIndex}"]`);
    const addOptionBtn = mcqOptionsArea.querySelector('.google-add-option');
    
    const optionCount = mcqOptionsArea.querySelectorAll('.google-mcq-option-row').length + 1;
    const optionHtml = `
        <div class="google-mcq-option-row">
            <span class="google-option-icon">○</span>
            <input type="text" class="google-option-input" placeholder="Option ${optionCount}">
            <button type="button" class="google-remove-option" onclick="removeOption(${questionIndex}, this)">×</button>
        </div>);
    updateCorrectAnswer(questionIndex
    `;
    
    addOptionBtn.insertAdjacentHTML('beforebegin', optionHtml);
    
    // Add input event listener to update answer key
    const newOptionInput = mcqOptionsArea.querySelectorAll('.google-option-input')[optionCount - 1];
    newOptionInput.addEventListener('input', () => updateAnswerKey(questionIndex));
}

function removeOption(questionIndex, button) {
    const mcqOptionsArea = button.closest('.google-mcq-options');
    const optionRow = button.closest('.google-mcq-option-row');
    
    // Ensure at least 2 options remain
    if (mcqOptionsArea.querySelectorAll('.google-mcq-option-row').length <= 2) {
        alert('Multiple choice questions must have at least 2 options');
        return;
    }
    
    optionRow.remove();
    updateCorrectAnswer(questionIndex);
}

function updateAnswerKey(questionIndex) {
    const questionCard = document.querySelector(`.google-question-card[data-index="${questionIndex}"]`);
    if (!questionCard) return;

    const answerKeyInput = questionCard.querySelector('.google-answer-key');
    const optionInputs = questionCard.querySelectorAll('.google-option-input');
    
    const options = [];
    optionInputs.forEach(input => {
        const value = input.value.trim();
        if (value) {
            options.push(value);
        }
    });
    
    answerKeyInput.value = options.join('|');
}

function updateCorrectAnswer(questionIndex) {
    updateAnswerKey(questionIndex);
    
    const questionCard = document.querySelector(`.google-question-card[data-index="${questionIndex}"]`);
    if (!questionCard) return;

    const answerKeyInput = questionCard.querySelector('.google-answer-key');
    const correctRadio = questionCard.querySelector('.google-correct-radio:checked');
    const optionInputs = questionCard.querySelectorAll('.google-option-input');
    
    if (!correctRadio || optionInputs.length === 0) return;
    
    // Find the index of the selected radio button
    const radioButtons = questionCard.querySelectorAll('.google-correct-radio');
    let selectedIndex = -1;
    radioButtons.forEach((radio, index) => {
        if (radio === correctRadio) {
            selectedIndex = index;
        }
    });
    
    // Get the correct answer value
    if (selectedIndex >= 0 && selectedIndex < optionInputs.length) {
        const correctAnswer = optionInputs[selectedIndex].value.trim();
        
        // Store the correct answer in a data attribute
        questionCard.dataset.correctAnswer = correctAnswer;
        
        // Update the answer key to store both options and correct answer
        const allOptions = [];
        optionInputs.forEach(input => {
            const value = input.value.trim();
            if (value) {
                allOptions.push(value);
            }
        });
        
        // Format: correct_answer|option1|option2|option3...
        if (correctAnswer && allOptions.length > 0) {
            answerKeyInput.value = correctAnswer + '|' + allOptions.join('|');
        }
    }
}
</script>
@endsection
