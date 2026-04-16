@extends('layouts.teacher')

@section('title', 'Create Assignment')
@section('page-title', 'Create New Assignment')

@section('content')
<style>
    .assignment-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }

    .assignment-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 32px;
        color: white;
        text-align: center;
    }

    .assignment-header h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .assignment-header p {
        font-size: 16px;
        opacity: 0.9;
    }

    .assignment-form {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #1f2937;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 16px;
        min-height: 120px;
        resize: vertical;
        transition: border-color 0.3s ease;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 16px;
        background: white;
        transition: border-color 0.3s ease;
    }

    .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .weightage-input {
        position: relative;
    }

    .weightage-input .form-input {
        padding-right: 40px;
    }

    .weightage-suffix {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-weight: 600;
    }

    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        margin-top: 32px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #4b5563;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .help-text {
        font-size: 14px;
        color: #6b7280;
        margin-top: 4px;
    }

    .error {
        color: #dc2626;
        font-size: 14px;
        margin-top: 4px;
    }

    .alert-danger {
        padding: 16px;
        background: #fee2e2;
        color: #dc2626;
        border-radius: 8px;
        margin-bottom: 24px;
        border: 1px solid #fecaca;
    }

    .module-info {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .module-info h4 {
        color: #0369a1;
        margin-bottom: 8px;
    }

    .module-info p {
        color: #0c4a6e;
        margin: 0;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>

<div class="assignment-container">
    <div class="assignment-header">
        <h1>Create Assignment</h1>
        <p>Create a new assignment for your module with weightage and instructions</p>
    </div>

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

    @if(request('module_id'))
        @php
            $module = \App\Models\CourseModule::find(request('module_id'));
        @endphp
        @if($module)
            <div class="module-info">
                <h4>Creating assignment for: {{ $module->title }}</h4>
                <p>Course: {{ $module->course->name }} | Module Weightage: {{ $module->weightage }}%</p>
            </div>
        @endif
    @endif

    <form class="assignment-form" method="POST" action="{{ route('teacher.assignments.store') }}">
        @csrf
        
        @if(request('module_id'))
            <input type="hidden" name="module_id" value="{{ request('module_id') }}">
        @endif
        
        <div class="form-group">
            <label class="form-label" for="course_id">Course *</label>
            <select class="form-select" id="course_id" name="course_id" required>
                <option value="">Select a course</option>
                @php
                    $teacher = auth()->user()->teacher;
                    $courses = \App\Models\Course::whereHas('teachers', function($query) use ($teacher) {
                        $query->where('teacher_id', $teacher->id);
                    })->get();
                @endphp
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                        {{ $course->name }} - {{ $course->category_name }}
                    </option>
                @endforeach
            </select>
            @error('course_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="module_id">Module *</label>
            <select class="form-select" id="module_id" name="module_id" required>
                <option value="">Select a module</option>
                @php
                    $teacher = auth()->user()->teacher;
                    $modules = \App\Models\CourseModule::where('teacher_id', $teacher->id)->get();
                @endphp
                @foreach($modules as $module)
                    <option value="{{ $module->id }}" {{ old('module_id') == $module->id || (request('module_id') == $module->id) ? 'selected' : '' }}>
                        {{ $module->title }} - {{ $module->course->name }}
                    </option>
                @endforeach
            </select>
            @error('module_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="title">Assignment Title *</label>
            <input type="text" class="form-input" id="title" name="title" value="{{ old('title') }}" required>
            <div class="help-text">Enter a descriptive title for this assignment</div>
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Description *</label>
            <textarea class="form-textarea" id="description" name="description" required>{{ old('description') }}</textarea>
            <div class="help-text">Provide a detailed description of the assignment requirements</div>
            @error('description')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="instructions">Instructions</label>
            <textarea class="form-textarea" id="instructions" name="instructions" rows="6">{{ old('instructions') }}</textarea>
            <div class="help-text">Detailed instructions for students on how to complete the assignment</div>
            @error('instructions')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="type">Assignment Type *</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="">Select type</option>
                    <option value="essay" {{ old('type') == 'essay' ? 'selected' : '' }}>Essay</option>
                    <option value="project" {{ old('type') == 'project' ? 'selected' : '' }}>Project</option>
                    <option value="quiz" {{ old('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                    <option value="presentation" {{ old('type') == 'presentation' ? 'selected' : '' }}>Presentation</option>
                    <option value="homework" {{ old('type') == 'homework' ? 'selected' : '' }}>Homework</option>
                    <option value="lab" {{ old('type') == 'lab' ? 'selected' : '' }}>Lab Work</option>
                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('type')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="max_score">Maximum Score *</label>
                <input type="number" class="form-input" id="max_score" name="max_score" value="{{ old('max_score', 100) }}" min="1" required>
                <div class="help-text">Maximum points students can earn</div>
                @error('max_score')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="weightage">Weightage (%) *</label>
                <div class="weightage-input">
                    <input type="number" class="form-input" id="weightage" name="weightage" value="{{ old('weightage') }}" min="0" max="100" step="0.1" required>
                    <span class="weightage-suffix">%</span>
                </div>
                <div class="help-text">Weightage of this assignment in the module grade</div>
                @error('weightage')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="due_date">Due Date *</label>
                <input type="datetime-local" class="form-input" id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                <div class="help-text">When students must submit their work</div>
                @error('due_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ request('module_id') ? route('teacher.modules.show', request('module_id')) : route('teacher.modules.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Assignment</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('.assignment-form');
    const weightageInput = document.getElementById('weightage');
    const maxScoreInput = document.getElementById('max_score');
    const dueDateInput = document.getElementById('due_date');

    // Set minimum date to today
    const now = new Date();
    const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
    dueDateInput.min = localDateTime;

    weightageInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        if (value < 0) this.value = 0;
        if (value > 100) this.value = 100;
    });

    maxScoreInput.addEventListener('input', function() {
        const value = parseInt(this.value);
        if (value < 1) this.value = 1;
    });

    // Auto-select course when module is selected
    const moduleSelect = document.getElementById('module_id');
    const courseSelect = document.getElementById('course_id');

    moduleSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            // Parse course info from option text
            const optionText = selectedOption.text;
            const courseName = optionText.split(' - ')[1];
            
            // Find and select the corresponding course
            for (let option of courseSelect.options) {
                if (option.text.includes(courseName)) {
                    courseSelect.value = option.value;
                    break;
                }
            }
        }
    });

    // Character counter for textareas
    const textareas = document.querySelectorAll('.form-textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            const charCount = this.value.length;
            const maxLength = 2000;
            if (charCount > maxLength) {
                this.value = this.value.substring(0, maxLength);
            }
        });
    });
});
</script>
@endsection
