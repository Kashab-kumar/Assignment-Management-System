@extends('layouts.teacher')

@section('title', 'Edit Unit Outline')
@section('page-title', 'Edit Unit Outline')

@section('content')
<style>
    .container {
        max-width: 1100px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .header {
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        color: white;
        padding: 32px;
        text-align: center;
    }

    .header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
    }

    .header p {
        margin: 8px 0 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .body {
        padding: 32px;
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
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
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
        background: linear-gradient(135deg, #7c3aed, #5b21b6);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
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

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #6b7280;
    }

    .breadcrumb a {
        color: #7c3aed;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .current-file {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 16px;
    }

    .current-file-header {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .file-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .btn-sm {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .upload-box {
        display: block;
        border: 2px dashed #e5e7eb;
        border-radius: 8px;
        padding: 28px;
        text-align: center;
        cursor: pointer;
        margin-top: 8px;
        transition: all 0.3s ease;
    }

    .upload-box:hover {
        border-color: #7c3aed;
        background: #faf5ff;
    }
</style>

<div class="container">
    <div class="header">
        <h1>Edit Unit Outline</h1>
        <p>{{ $module->title }} - {{ $course->name }}</p>
    </div>

    <div class="body">
        <nav class="breadcrumb">
            <a href="{{ route('teacher.courses.index') }}">Courses</a>
            <span>/</span>
            <a href="{{ route('teacher.courses.show', $course) }}">{{ $course->name }}</a>
            <span>/</span>
            <a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}">{{ $module->title }}</a>
            <span>/</span>
            <span>Edit Unit Outline</span>
        </nav>

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

        <form method="POST" action="{{ route('teacher.courses.modules.items.update', [$course, $module, $item]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Topic/Title <span class="required">*</span></label>
                <input type="text" id="title" name="title" class="form-control"
                       value="{{ old('title', $item->title) }}"
                       placeholder="e.g., Introduction to Photosynthesis" required>
                @error('title')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            @if($item->file_path)
            <div class="current-file">
                <div class="current-file-header">Current File</div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 24px;">📄</span>
                    <div>
                        <div style="font-weight: 500;">{{ $item->file_name }}</div>
                        <div style="font-size: 12px; color: #6b7280;">Uploaded on {{ $item->created_at->format('d M Y') }}</div>
                    </div>
                </div>
                <div class="file-actions">
                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn-sm" style="background: #3b82f6; color: white; text-decoration: none;">👁 View</a>
                    <a href="{{ asset('storage/' . $item->file_path) }}" download class="btn-sm" style="background: #10b981; color: white; text-decoration: none;">⬇ Download</a>
                </div>
            </div>
            @endif

            <div class="form-group">
                <label for="file">{{ $item->file_path ? 'Replace File (optional)' : 'Upload File' }}</label>
                <label class="upload-box" id="upload-box" style="display:block; border:2px dashed #e5e7eb; border-radius:8px; padding:28px; text-align:center; cursor:pointer; margin-top:8px; transition: all 0.3s ease;">
                    <div id="upload-icon" style="font-size:24px; color:#6b7280;">📁</div>
                    <div id="upload-text" style="margin-top:8px; font-weight:600;">{{ $item->file_path ? 'Upload new file to replace current one' : 'Upload unit outline file' }}</div>
                    <div style="font-size:12px; color:#9ca3af; margin-top:6px;">PDF, DOC, DOCX, TXT — AI will use this for auto-grading</div>
                    <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.txt" onchange="handleFileUpload(this)" style="display:none">
                </label>
                <div id="file-info" style="margin-top: 8px; color: #10b981; font-size: 13px;"></div>
                @error('file')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control"
                          placeholder="Enter the content description...">{{ old('description', $item->content) }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Unit Outline</button>
            </div>
        </form>
    </div>
</div>

<script>
function handleFileUpload(input) {
    const fileInfo = document.getElementById('file-info');
    const uploadBox = document.getElementById('upload-box');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        fileInfo.textContent = `✓ New file selected: ${file.name} (${(file.size / 1024).toFixed(2)} KB) - This will replace the current file when you save.`;
        uploadBox.style.borderColor = '#10b981';
        uploadBox.style.background = '#f0fdf4';
    } else {
        fileInfo.textContent = '';
        uploadBox.style.borderColor = '#e5e7eb';
        uploadBox.style.background = 'transparent';
    }
}
</script>
@endsection
