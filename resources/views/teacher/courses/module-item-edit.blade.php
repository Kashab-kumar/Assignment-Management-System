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
        background: linear-gradient(135deg, #111827 0%, #000000 100%);
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

        @php
            $criteriaRows = old('criteria', $item->grading_criteria ?? []);
            if (!is_array($criteriaRows) || empty($criteriaRows)) {
                $criteriaRows = [[
                    'topic' => '',
                    'marks' => '',
                    'weight' => '',
                ]];
            }
            $chapterTotalWeight = old('chapter_total_weight', data_get($item->ai_options, 'chapter_total_weight', ''));
        @endphp

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

            <div class="form-group">
                <label>Tasks, Marks & Weightage</label>
                <div style="overflow-x:auto; border:1px solid #e5e7eb; border-radius:8px;">
                    <table style="width:100%; border-collapse:collapse; min-width:760px;">
                        <thead>
                            <tr style="background:#f9fafb;">
                                <th style="padding:10px; border-bottom:1px solid #e5e7eb; text-align:left;">Task</th>
                                <th style="padding:10px; border-bottom:1px solid #e5e7eb; text-align:left; width:140px;">Marks</th>
                                <th style="padding:10px; border-bottom:1px solid #e5e7eb; text-align:left; width:160px;">Weightage</th>
                                <th style="padding:10px; border-bottom:1px solid #e5e7eb; text-align:left; width:90px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="criteria-rows">
                            @foreach($criteriaRows as $index => $criterion)
                                <tr class="criteria-row">
                                    <td style="padding:10px; border-bottom:1px solid #e5e7eb;">
                                        <input type="text" name="criteria[{{ $index }}][topic]" class="form-control criterion-topic" value="{{ old('criteria.' . $index . '.topic', $criterion['topic'] ?? $criterion['description'] ?? $criterion['name'] ?? '') }}" placeholder="e.g., Assignment on Network">
                                    </td>
                                    <td style="padding:10px; border-bottom:1px solid #e5e7eb;">
                                        <input type="number" name="criteria[{{ $index }}][marks]" class="form-control criterion-marks" value="{{ old('criteria.' . $index . '.marks', $criterion['marks'] ?? '') }}" min="0" step="0.01" placeholder="e.g., 15">
                                    </td>
                                    <td style="padding:10px; border-bottom:1px solid #e5e7eb;">
                                        <input type="number" name="criteria[{{ $index }}][weight]" class="form-control criterion-weight" value="{{ old('criteria.' . $index . '.weight', $criterion['weight'] ?? '') }}" min="0" step="0.01" placeholder="e.g., 10">
                                    </td>
                                    <td style="padding:10px; border-bottom:1px solid #e5e7eb;">
                                        <button type="button" class="btn-sm btn-secondary remove-criteria-btn" style="background:#ef4444; color:#fff;">Remove</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="display:flex; align-items:center; gap:12px; margin-top:12px; flex-wrap:wrap;">
                    <button type="button" id="add-criteria-btn" class="btn-sm" style="background:#7c3aed; color:#fff;">+ Add Task</button>
                    <div style="display:flex; align-items:center; gap:8px; margin-left:auto;">
                        <label for="chapter_total_weight" style="margin-bottom:0; font-weight:600;">Chapter Total Weightage</label>
                        <input type="number" id="chapter_total_weight" name="chapter_total_weight" class="form-control" style="width:140px;" value="{{ $chapterTotalWeight }}" min="0" max="100" step="0.01" placeholder="100">
                    </div>
                </div>
                @error('criteria')
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

(function () {
    const rowsContainer = document.getElementById('criteria-rows');
    const addButton = document.getElementById('add-criteria-btn');
    const totalInput = document.getElementById('chapter_total_weight');

    function updateNames() {
        const rows = rowsContainer.querySelectorAll('.criteria-row');
        rows.forEach((row, index) => {
            const topic = row.querySelector('.criterion-topic');
            const marks = row.querySelector('.criterion-marks');
            const weight = row.querySelector('.criterion-weight');
            if (topic) topic.name = `criteria[${index}][topic]`;
            if (marks) marks.name = `criteria[${index}][marks]`;
            if (weight) weight.name = `criteria[${index}][weight]`;
        });
    }

    function updateTotal() {
        if (!totalInput) return;
        const total = Array.from(rowsContainer.querySelectorAll('.criterion-weight'))
            .reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);
        totalInput.value = total.toFixed(2).replace(/\.00$/, '');
    }

    if (addButton && rowsContainer) {
        addButton.addEventListener('click', function () {
            const row = document.createElement('tr');
            row.className = 'criteria-row';
            row.innerHTML = `
                <td style="padding:10px; border-bottom:1px solid #e5e7eb;">
                    <input type="text" class="form-control criterion-topic" placeholder="e.g., Assignment on Network">
                </td>
                <td style="padding:10px; border-bottom:1px solid #e5e7eb;">
                    <input type="number" class="form-control criterion-marks" min="0" step="0.01" placeholder="e.g., 15">
                </td>
                <td style="padding:10px; border-bottom:1px solid #e5e7eb;">
                    <input type="number" class="form-control criterion-weight" min="0" step="0.01" placeholder="e.g., 10">
                </td>
                <td style="padding:10px; border-bottom:1px solid #e5e7eb;">
                    <button type="button" class="btn-sm btn-secondary remove-criteria-btn" style="background:#ef4444; color:#fff;">Remove</button>
                </td>`;
            rowsContainer.appendChild(row);
            updateNames();
            updateTotal();
        });

        rowsContainer.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-criteria-btn')) {
                const row = event.target.closest('.criteria-row');
                if (row && rowsContainer.querySelectorAll('.criteria-row').length > 1) {
                    row.remove();
                    updateNames();
                    updateTotal();
                }
            }
        });

        rowsContainer.addEventListener('input', function (event) {
            if (event.target.classList.contains('criterion-weight')) {
                updateTotal();
            }
        });
    }

    updateNames();
    updateTotal();
})();
</script>
@endsection
