@extends('layouts.teacher')

@section('title', 'Create Module')
@section('page-title', 'Create Module')

@section('content')
<style>
    .create-module-container {
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

<div class="create-module-container">
    <div class="form-header">
        <h1>Create Module</h1>
        <p>Add a new module to your course with weightage and outline</p>
    </div>

    <form class="module-form" method="POST" action="{{ route('teacher.modules.store') }}">
        @csrf
        
        <div class="form-group">
            <label class="form-label" for="course_id">Course *</label>
            <select class="form-select" id="course_id" name="course_id" required>
                <option value="">Select a course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }} - {{ $course->category_name }}</option>
                @endforeach
            </select>
            @error('course_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="title">Module Title *</label>
            <input type="text" class="form-input" id="title" name="title" value="{{ old('title') }}" required>
            <div class="help-text">Enter a descriptive title for this module</div>
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Description *</label>
            <textarea class="form-textarea" id="description" name="description" required>{{ old('description') }}</textarea>
            <div class="help-text">Provide a detailed description of what this module covers</div>
            @error('description')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="position">Position *</label>
                <input type="number" class="form-input" id="position" name="position" value="{{ old('position', 1) }}" min="1" required>
                <div class="help-text">Order of this module in the course</div>
                @error('position')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="weightage">Weightage (%) *</label>
                <div class="weightage-input">
                    <input type="number" class="form-input" id="weightage" name="weightage" value="{{ old('weightage') }}" min="0" max="100" step="0.1" required>
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
            <textarea class="form-textarea" id="model_unit_outline" name="model_unit_outline" rows="8" required>{{ old('model_unit_outline') }}</textarea>
            <div class="help-text">Detailed outline of the unit, topics, learning objectives, and assessment criteria</div>
            @error('model_unit_outline')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('teacher.modules.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Module</button>
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
