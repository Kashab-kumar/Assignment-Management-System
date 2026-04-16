@extends('layouts.teacher')

@section('title', 'Edit Module')
@section('page-title', 'Edit Module')

@section('content')
<style>
    .edit-module-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 32px;
        color: white;
        text-align: center;
    }

    .form-header h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .form-header p {
        font-size: 16px;
        opacity: 0.9;
    }

    .module-form {
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

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
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

    .teacher-section {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .teacher-section h3 {
        color: #0369a1;
        margin-bottom: 16px;
    }

    .teacher-info {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .teacher-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }

    .teacher-details h4 {
        margin: 0;
        color: #1f2937;
    }

    .teacher-details p {
        margin: 0;
        color: #6b7280;
        font-size: 14px;
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

<div class="edit-module-container">
    <div class="form-header">
        <h1>Edit Module</h1>
        <p>Update module details, weightage, and teacher assignment</p>
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

    <form class="module-form" method="POST" action="{{ route('teacher.modules.update', $module) }}">
        @csrf
        @method('PUT')
        
        <div class="teacher-section">
            <h3>Current Teacher Assignment</h3>
            @if($module->teacher)
                <div class="teacher-info">
                    <div class="teacher-avatar">{{ substr($module->teacher->user->name, 0, 1) }}</div>
                    <div class="teacher-details">
                        <h4>{{ $module->teacher->user->name }}</h4>
                        <p>{{ $module->teacher->user->email }}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="teacher_id">Change Teacher (Optional)</label>
                    <select class="form-select" id="teacher_id" name="teacher_id">
                        <option value="">Keep current teacher ({{ $module->teacher->user->name }})</option>
                        @php
                            $currentTeacherId = $module->teacher_id;
                            $availableTeachers = \App\Models\Teacher::where('id', '!=', $currentTeacherId)->get();
                        @endphp
                        @foreach($availableTeachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->user->name }} - {{ $teacher->user->email }}</option>
                        @endforeach
                    </select>
                    <div class="help-text">Select a new teacher if you want to reassign this module</div>
                    @error('teacher_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            @else
                <div class="form-group">
                    <label class="form-label" for="teacher_id">Assign Teacher *</label>
                    <select class="form-select" id="teacher_id" name="teacher_id" required>
                        <option value="">Select a teacher</option>
                        @php
                            $availableTeachers = \App\Models\Teacher::all();
                        @endphp
                        @foreach($availableTeachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->user->name }} - {{ $teacher->user->email }}</option>
                        @endforeach
                    </select>
                    <div class="help-text">Assign a teacher to manage this module</div>
                    @error('teacher_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            @endif
        </div>
        
        <div class="form-group">
            <label class="form-label" for="course_id">Course *</label>
            <select class="form-select" id="course_id" name="course_id" required>
                <option value="">Select a course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id', $module->course_id) == $course->id ? 'selected' : '' }}>
                        {{ $course->name }} - {{ $course->category_name }}
                    </option>
                @endforeach
            </select>
            @error('course_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="title">Module Title *</label>
            <input type="text" class="form-input" id="title" name="title" value="{{ old('title', $module->title) }}" required>
            <div class="help-text">Enter a descriptive title for this module</div>
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Description *</label>
            <textarea class="form-textarea" id="description" name="description" required>{{ old('description', $module->description) }}</textarea>
            <div class="help-text">Provide a detailed description of what this module covers</div>
            @error('description')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="position">Position *</label>
                <input type="number" class="form-input" id="position" name="position" value="{{ old('position', $module->position) }}" min="1" required>
                <div class="help-text">Order of this module in the course</div>
                @error('position')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="weightage">Weightage (%) *</label>
                <div class="weightage-input">
                    <input type="number" class="form-input" id="weightage" name="weightage" value="{{ old('weightage', $module->weightage) }}" min="0" max="100" step="0.1" required>
                    <span class="weightage-suffix">%</span>
                </div>
                <div class="help-text">Weightage of this module in the overall course grade</div>
                @error('weightage')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="model_unit_outline">Model Unit Outline *</label>
            <textarea class="form-textarea" id="model_unit_outline" name="model_unit_outline" rows="8" required>{{ old('model_unit_outline', $module->model_unit_outline) }}</textarea>
            <div class="help-text">Detailed outline of the unit, topics, learning objectives, and assessment criteria</div>
            @error('model_unit_outline')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('teacher.modules.show', $module) }}" class="btn btn-secondary">Cancel</a>
            @if(auth()->user()->teacher && auth()->user()->teacher->id === $module->teacher_id || auth()->user()->role === 'admin')
                <button type="submit" class="btn btn-primary">Update Module</button>
            @endif
            @if(auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('teacher.modules.destroy', $module) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this module? This action cannot be undone.')">Delete Module</button>
                </form>
            @endif
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('.module-form');
    const weightageInput = document.getElementById('weightage');
    const positionInput = document.getElementById('position');

    weightageInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        if (value < 0) this.value = 0;
        if (value > 100) this.value = 100;
    });

    positionInput.addEventListener('input', function() {
        const value = parseInt(this.value);
        if (value < 1) this.value = 1;
    });

    // Character counter for textarea
    const outlineTextarea = document.getElementById('model_unit_outline');
    outlineTextarea.addEventListener('input', function() {
        const charCount = this.value.length;
        if (charCount > 2000) {
            this.value = this.value.substring(0, 2000);
        }
    });
});
</script>
@endsection
