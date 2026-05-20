@extends('layouts.teacher')

@section('title', 'Add Unit Outline')
@section('page-title', 'Add Unit Outline')

@section('content')
<style>
    .container {
        max-width: 1280px;
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
        padding: 24px;
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

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .ai-section {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 2px solid #7c3aed;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
    }

    .ai-section h3 {
        margin: 0 0 16px;
        color: #5b21b6;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ai-section h3::before {
        content: "🤖";
    }

    .ai-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #7c3aed;
    }

    .checkbox-group label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
        font-size: 13px;
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

    .btn-ai {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .btn-ai:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
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

    .unit-outline-block {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
        background: #ffffff;
        margin-bottom: 24px;
    }

    /* Ensure top border appears when multiple tbody.unit-outline-block are adjacent */
    .unit-outline-block + .unit-outline-block tr:first-child td,
    .sheet-table tbody + tbody tr:first-child td {
        border-top: 1px solid #d1d5db !important;
    }

    /* Make sure the left chapter cell keeps the pale background like the first block */
    .unit-outline-block .chapter-cell { background: #f8fafc; }

    .sheet-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        background: #ffffff;
    }

    .sheet-table th,
    .sheet-table td {
        border: 1px solid #d1d5db;
        vertical-align: middle;
        padding: 0;
        min-height: 40px;
    }

    .sheet-table thead th {
        background: #f3f4f6;
        color: #111827;
        font-size: 13px;
        font-weight: 700;
        text-align: left;
        padding: 12px;
        border-color: #9ca3af;
    }

    .sheet-cell {
        padding: 10px;
        background: #ffffff;
        height: 100%;
    }

    .sheet-cell-title {
        font-size: 12px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 6px;
    }

    .sheet-total-cell {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        min-height: 100%;
        font-weight: 600;
        color: #111827;
        font-size: 14px;
        text-align: center;
    }

    .sheet-upload-cell {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100%;
        padding: 10px;
    }

    .sheet-upload-box {
        width: 100%;
        border: 1px dashed #d1d5db;
        border-radius: 6px;
        padding: 12px 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fafafa;
    }

    .sheet-upload-box:hover {
        background: #f3f4f6;
        border-color: #7c3aed;
    }

    .sheet-upload-box .upload-title {
        margin-top: 4px;
        font-size: 12px;
        font-weight: 600;
        color: #111827;
    }

    .sheet-upload-box .upload-subtitle {
        margin-top: 2px;
        font-size: 10px;
        color: #6b7280;
    }

    .file-preview-card {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 10px;
        padding: 10px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }

    .file-preview-row {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .file-preview-icon {
        font-size: 20px;
        flex-shrink: 0;
    }

    .file-preview-info {
        flex: 1;
        min-width: 0;
    }

    .file-preview-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 12px;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .file-preview-meta {
        font-size: 10px;
        color: #6b7280;
        margin-top: 2px;
    }

    .file-preview-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .file-preview-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        border-radius: 4px;
        border: 1px solid transparent;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
    }

    .file-preview-link-open {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .file-preview-link-download {
        background: #ecfccb;
        color: #3f6212;
    }

    .upload-success {
        font-size: 11px;
        font-weight: 600;
        color: #059669;
    }

    .sheet-input,
    .sheet-select {
        width: 100%;
        border: none;
        outline: none;
        background: transparent;
        font-size: 13px;
        padding: 8px;
        box-sizing: border-box;
        font-family: inherit;
    }

    .sheet-input {
        min-height: 36px;
    }

    .sheet-topic {
        min-height: 36px;
    }

    .sheet-number {
        text-align: center;
    }

    .task-actions {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
        margin-top: 6px;
    }

    .task-action-btn {
        border: 1px solid #d1d5db;
        background: #f8fafc;
        color: #111827;
        border-radius: 4px;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .task-action-btn:hover {
        background: #e5e7eb;
        border-color: #9ca3af;
    }

    .criterion-task-box {
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .criterion-task-box .criterion-topic {
        flex: 1;
        min-width: 0;
    }

    .criterion-remove-btn {
        flex-shrink: 0;
        width: 28px;
        height: 28px;
        line-height: 1;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        background: #fff7f7;
        color: #dc2626;
        cursor: pointer;
        font-size: 16px;
        font-weight: 700;
    }

    .criterion-remove-btn:hover {
        background: #fee2e2;
        border-color: #fca5a5;
    }

    .sheet-row {
        height: auto;
    }

    .sheet-summary-row td {
        background: #f9fafb;
        font-weight: 700;
        font-size: 13px;
        padding: 12px;
    }

    .sheet-summary-label {
        text-align: left;
    }

    .sheet-summary-value {
        text-align: center;
    }

    .sheet-compact-note {
        margin-top: 4px;
        font-size: 10px;
        color: #6b7280;
    }

    .teacher-only-note {
        margin-top: 4px;
        font-size: 11px;
        color: #6b7280;
    }

    .sheet-note {
        font-size: 11px;
        color: #6b7280;
        margin-top: 4px;
    }

    .chapter-header-cell {
        background: #f0f4f8;
        font-weight: 700;
        color: #1f2937;
    }

    .outline-title {
        width: 100%;
        min-height: 68px;
        resize: none;
        overflow: hidden;
        white-space: normal;
        word-wrap: break-word;
        word-break: break-word;
        line-height: 1.4;
        box-sizing: border-box;
    }

    .outline-title::-webkit-scrollbar {
        display: none;
    }

    /* Hide the purple duplicate Add Assessment button inside each chapter block */
    .unit-outline-block .btn.btn-primary.add-criterion-btn {
        display: none !important;
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
</style>

<div class="container">
    <div class="header">
        <h1>Add Unit Outline</h1>
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
            <span>Add Unit Outline</span>
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

        <form method="POST" action="{{ route('teacher.courses.modules.items.store', [$course, $module]) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="unit_outline">

            <div id="unit-outline-blocks">
                <table class="sheet-table" id="unit-outline-table">
                    <colgroup>
                        <col style="width: 20%;">
                        <col style="width: 30%;">
                        <col style="width: 10%;">
                        <col style="width: 10%;">
                        <col style="width: 12%;">
                        <col style="width: 18%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Chapter/unit</th>
                            <th>Tasks</th>
                            <th>Marks</th>
                            <th>Weightage</th>
                            <th>Total Weightage</th>
                            <th>Question bank</th>
                        </tr>
                    </thead>
                    <tbody class="unit-outline-block" data-outline-index="0" id="criteria-list-0">
                        <tr class="criterion-row sheet-row" data-index="0">
                               <td class="chapter-cell chapter-header-cell" rowspan="1">
                                <div class="sheet-cell">
                                    <div class="sheet-cell-title">Chapter/unit title</div>
                                    <textarea id="outline-title-0" name="outlines[0][title]" class="outline-title sheet-input"
                                        placeholder="e.g., Chapter 1" required>{{ old('outlines.0.title', '') }}</textarea>
                                    <div class="sheet-compact-note">Chapter identifier</div>
                                    @error('title')
                                        <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </td>
                            <td>
                                <input type="text" name="outlines[0][criteria][0][topic]" class="criterion-topic sheet-input sheet-topic" placeholder="Task title" value="{{ old('outlines.0.criteria.0.topic', '') }}">
                                <div class="task-actions">
                                    <button type="button" class="task-action-btn task-add-btn" data-type="task" data-topic="Task" data-weight="10">+ Add Task</button>
                                </div>
                            </td>
                            <td>
                                <input type="number" name="outlines[0][criteria][0][marks]" class="criterion-marks sheet-input sheet-number" value="{{ old('outlines.0.criteria.0.marks', '') }}" min="0" step="0.01" placeholder="e.g., 15">
                            </td>
                            <td>
                                <input type="number" name="outlines[0][criteria][0][weight]" class="criterion-weight sheet-input sheet-number" value="{{ old('outlines.0.criteria.0.weight', '') }}" min="0" step="0.01" placeholder="e.g., 10%">
                            </td>
                                <td class="total-weight-cell chapter-header-cell" rowspan="1">
                                <div class="sheet-cell">
                                    <div class="sheet-cell-title">Total Weightage</div>
                                    <input type="number" id="outline-chapter-total-weight-0" name="outlines[0][chapter_total_weight]" class="outline-total-weight sheet-input sheet-number"
                                        value="{{ old('outlines.0.chapter_total_weight', old('chapter_total_weight', '')) }}" min="0" max="100" step="0.01"
                                        placeholder="e.g., 100">
                                    <div id="total-weight-0" class="sheet-total-cell" style="font-size: 16px; margin-top: 4px;">0%</div>
                                    <div id="weight-remaining-0" class="sheet-compact-note" style="text-align:center;">Remaining: 0%</div>
                                    <div id="total-weight-warning-0" style="display:none; margin-top:6px; color:#dc2626; font-size:11px; font-weight:600; text-align:center;"></div>
                                </div>
                            </td>
                                <td class="question-bank-cell chapter-header-cell" rowspan="1">
                                <div class="sheet-cell">
                                    <div class="sheet-cell-title">Question bank</div>
                                    <label class="sheet-upload-box" id="upload-box-0">
                                        <div id="upload-icon-0" style="font-size:20px; color:#6b7280;">📁</div>
                                        <div id="upload-text-0" class="upload-title">File upload</div>
                                        <div class="upload-subtitle">PDF, DOC, DOCX, TXT</div>
                                        <input type="file" id="outline-file-0" name="outlines[0][file]" accept=".pdf,.doc,.docx,.txt" onchange="handleFileUpload(this, 0)" style="display:none">
                                    </label>
                                    <div class="teacher-only-note">Teacher only</div>

                                    <div id="file-preview-section-0" class="file-preview-card" style="display:none;">
                                        <div class="file-preview-row">
                                            <div id="file-icon-0" class="file-preview-icon">📄</div>
                                            <div class="file-preview-info">
                                                <div id="file-name-0" class="file-preview-name"></div>
                                                <div id="file-size-0" class="file-preview-meta"></div>
                                            </div>
                                        </div>
                                        <div class="file-preview-actions">
                                            <a id="preview-btn-0" class="file-preview-link file-preview-link-open" href="#" target="_blank" rel="noopener noreferrer">Open</a>
                                            <a id="download-btn-0" class="file-preview-link file-preview-link-download" href="#" download>Download</a>
                                            <button type="button" onclick="removeFile(0)" class="file-preview-link" style="background:#fee2e2;color:#dc2626;">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody id="sheet-summary-body">
                        <tr class="sheet-summary-row">
                            <td class="sheet-summary-label">Total</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td id="overall-total-weight" class="sheet-summary-value">0%</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div style="display:flex; gap:12px; justify-content:flex-start; margin-bottom:16px;">
                <button type="button" id="add-outline-btn" class="btn btn-outline" onclick="cloneOutlineBlock()">+ Add Another Chapter/Unit</button>
            </div>
            </div>
            <div class="form-actions">
                <a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Unit Outlines</button>
            </div>
        </form>
    </div>
</div>

<script>
const uploadedFiles = {};
const uploadedFileUrls = {};

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function getFileTypeFromName(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    const types = {
        'pdf': 'PDF Document',
        'doc': 'Word Document',
        'docx': 'Word Document',
        'txt': 'Text File'
    };
    return types[ext] || 'Unknown File';
}

function getOutlineBlock(index) {
    return document.querySelector('.unit-outline-block[data-outline-index="' + index + '"]');
}

function getOutlineIndexFromElement(element) {
    const block = element.closest('.unit-outline-block');
    return block ? parseInt(block.dataset.outlineIndex, 10) : 0;
}

function buildChapterSection(index, values = {}) {
    const title = values.title || '';
    const chapterTotalWeight = values.chapter_total_weight ?? '';
    const topic0 = values.topic0 || '';
    const topic1 = values.topic1 || '';
    const topic2 = values.topic2 || '';
    const marks0 = values.marks0 ?? '';
    const marks1 = values.marks1 ?? '';
    const marks2 = values.marks2 ?? '';
    const weight0 = values.weight0 ?? '';
    const weight1 = values.weight1 ?? '';
    const weight2 = values.weight2 ?? '';

    return `
        <tbody class="unit-outline-block" data-outline-index="${index}" id="criteria-list-${index}">
            <tr class="criterion-row sheet-row" data-index="0">
                <td class="chapter-cell chapter-header-cell" rowspan="1">
                    <div class="sheet-cell">
                        <div class="sheet-cell-title">Chapter/unit title</div>
                        <textarea id="outline-title-${index}" name="outlines[${index}][title]" class="outline-title sheet-input"
                            placeholder="e.g., Chapter ${index + 1}" required>${String(title).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\"/g, '&quot;').replace(/'/g, '&#039;')}</textarea>
                        <div class="sheet-compact-note">Chapter identifier</div>
                    </div>
                </td>
                <td>
                    <input type="text" name="outlines[${index}][criteria][0][topic]" class="criterion-topic sheet-input sheet-topic" placeholder="Task title" value="${String(topic0).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\"/g, '&quot;').replace(/'/g, '&#039;')}">
                    <div class="task-actions">
                        <button type="button" class="task-action-btn task-add-btn" data-type="task" data-topic="Task" data-weight="10">+ Add Task</button>
                    </div>
                </td>
                <td>
                    <input type="number" name="outlines[${index}][criteria][0][marks]" class="criterion-marks sheet-input sheet-number" value="${marks0}" min="0" step="0.01" placeholder="e.g., 15">
                </td>
                <td>
                    <input type="number" name="outlines[${index}][criteria][0][weight]" class="criterion-weight sheet-input sheet-number" value="${weight0}" min="0" step="0.01" placeholder="e.g., 10%">
                </td>
                <td class="total-weight-cell chapter-header-cell" rowspan="1">
                    <div class="sheet-cell">
                        <div class="sheet-cell-title">Total Weightage</div>
                        <input type="number" id="outline-chapter-total-weight-${index}" name="outlines[${index}][chapter_total_weight]" class="outline-total-weight sheet-input sheet-number"
                            value="${chapterTotalWeight}" min="0" max="100" step="0.01"
                            placeholder="e.g., 100">
                        <div id="total-weight-${index}" class="sheet-total-cell" style="font-size: 16px; margin-top: 4px;">0%</div>
                        <div id="weight-remaining-${index}" class="sheet-compact-note" style="text-align:center;">Remaining: 0%</div>
                        <div id="total-weight-warning-${index}" style="display:none; margin-top:6px; color:#dc2626; font-size:11px; font-weight:600; text-align:center;"></div>
                    </div>
                </td>
                <td class="question-bank-cell chapter-header-cell" rowspan="1">
                    <div class="sheet-cell">
                        <div class="sheet-cell-title">Question bank</div>
                        <label class="sheet-upload-box" id="upload-box-${index}">
                            <div id="upload-icon-${index}" style="font-size:20px; color:#6b7280;">📁</div>
                            <div id="upload-text-${index}" class="upload-title">File upload</div>
                            <div class="upload-subtitle">PDF, DOC, DOCX, TXT</div>
                            <input type="file" id="outline-file-${index}" name="outlines[${index}][file]" accept=".pdf,.doc,.docx,.txt" onchange="handleFileUpload(this, ${index})" style="display:none">
                        </label>
                        <div class="teacher-only-note">Teacher only</div>

                        <div id="file-preview-section-${index}" class="file-preview-card" style="display:none;">
                            <div class="file-preview-row">
                                <div id="file-icon-${index}" class="file-preview-icon">📄</div>
                                <div class="file-preview-info">
                                    <div id="file-name-${index}" class="file-preview-name"></div>
                                    <div id="file-size-${index}" class="file-preview-meta"></div>
                                </div>
                            </div>
                            <div class="file-preview-actions">
                                <a id="preview-btn-${index}" class="file-preview-link file-preview-link-open" href="#" target="_blank" rel="noopener noreferrer">Open</a>
                                <a id="download-btn-${index}" class="file-preview-link file-preview-link-download" href="#" download>Download</a>
                                <button type="button" onclick="removeFile(${index})" class="file-preview-link" style="background:#fee2e2;color:#dc2626;">Remove</button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            ${renderDefaultCriteriaRows(index)}
        </tbody>`;
}

function renderDefaultCriteriaRows(index) {
    return `
        <tr class="criterion-row sheet-row" data-index="1">
            <td>
                <div class="criterion-task-box">
                    <input type="text" name="outlines[${index}][criteria][1][topic]" class="criterion-topic sheet-input sheet-topic" placeholder="Add a task title" value="">
                    <button type="button" class="criterion-remove-btn remove-criterion" aria-label="Remove task">×</button>
                </div>
            </td>
            <td><input type="number" name="outlines[${index}][criteria][1][marks]" class="criterion-marks sheet-input sheet-number" value="" min="0" step="0.01" placeholder="Marks"></td>
            <td><input type="number" name="outlines[${index}][criteria][1][weight]" class="criterion-weight sheet-input sheet-number" value="" min="0" step="0.01" placeholder="Weight"></td>
        </tr>`;
}

function syncSheetRowspans(block) {
    const rows = block.querySelectorAll('.criterion-row');
    const rowCount = Math.max(rows.length, 1);

    const chapterCell = block.querySelector('.chapter-cell');
    const totalCell = block.querySelector('.total-weight-cell');
    const questionCell = block.querySelector('.question-bank-cell');

    if (chapterCell) chapterCell.rowSpan = rowCount;
    if (totalCell) totalCell.rowSpan = rowCount;
    if (questionCell) questionCell.rowSpan = rowCount;
}

function updateTotal(block) {
    const weights = Array.from(block.querySelectorAll('.criterion-weight')).map(input => parseFloat(input.value) || 0);
    const total = weights.reduce((sum, value) => sum + value, 0);
    const index = block.dataset.outlineIndex;
    const chapterWeightInput = block.querySelector('.outline-total-weight');
    const totalEl = block.querySelector('#total-weight-' + index);
    if (totalEl) {
        totalEl.textContent = total + '%';
        totalEl.style.color = total > 100 ? '#dc2626' : '#111827';
    }

    if (chapterWeightInput) {
        chapterWeightInput.value = total;
    }

    const warning = block.querySelector('#total-weight-warning-' + index);
    const remainingLabel = block.querySelector('#weight-remaining-' + index);
    if (warning) {
        if (total > 100) {
            warning.textContent = 'Over by ' + (total - 100) + '%';
            warning.style.display = 'block';
        } else {
            warning.textContent = '';
            warning.style.display = 'none';
        }
    }

    if (remainingLabel) {
        const remaining = 100 - total;
        remainingLabel.textContent = remaining >= 0 ? ('Remaining: ' + remaining + '%') : ('Over by: ' + Math.abs(remaining) + '%');
    }

    syncSheetRowspans(block);

    return total;
}

function updateOverallTotal() {
    const blocks = Array.from(document.querySelectorAll('.unit-outline-block'));
    const overallTotal = blocks.reduce((sum, block) => sum + updateTotal(block), 0);
    const overallCell = document.getElementById('overall-total-weight');

    if (overallCell) {
        overallCell.textContent = overallTotal + '%';
        overallCell.style.color = overallTotal > 100 ? '#dc2626' : '#111827';
        overallCell.style.fontWeight = overallTotal > 100 ? '700' : '600';
    }

    return overallTotal;
}

function serializeCriteria(block) {
    const index = block.dataset.outlineIndex;
    const rows = Array.from(block.querySelectorAll('.criterion-row'));
    const items = rows.map(row => ({
        assessment_type: row.querySelector('.criterion-type') ? row.querySelector('.criterion-type').value : '',
        topic: row.querySelector('.criterion-topic') ? row.querySelector('.criterion-topic').value : '',
        marks: parseFloat(row.querySelector('.criterion-marks')?.value) || 0,
        weight: parseFloat(row.querySelector('.criterion-weight')?.value) || 0,
        name: row.querySelector('.criterion-type') ? row.querySelector('.criterion-type').value : '',
        description: row.querySelector('.criterion-topic') ? row.querySelector('.criterion-topic').value : ''
    }));

    const input = block.querySelector('.grading-criteria-input');
    if (input) input.value = JSON.stringify(items);
    return items;
}

function serializeGradeScale(block) {
    const input = block.querySelector('.grade-scale-input');
    if (input) input.value = JSON.stringify({});
}

function serializeAiOptions(block) {
    const chapterWeightInput = block.querySelector('.outline-total-weight');
    const chapterWeight = chapterWeightInput ? (parseFloat(chapterWeightInput.value) || 0) : 0;
    const input = block.querySelector('.ai-options-input');
    if (input) {
        input.value = JSON.stringify({ chapter_total_weight: chapterWeight });
    }
}

function appendCriterionRow(block, type, topic, weight) {
    const index = block.dataset.outlineIndex;
    const list = block;
    const rowIndex = list.querySelectorAll('.criterion-row').length;
    const row = document.createElement('tr');
    row.className = 'criterion-row sheet-row';
    row.dataset.index = rowIndex;
    row.innerHTML = `<td><div class="criterion-task-box"><input type="text" name="outlines[${index}][criteria][${rowIndex}][topic]" class="criterion-topic sheet-input sheet-topic" placeholder="Topic" value="${String(topic || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\"/g, '&quot;').replace(/'/g, '&#039;')}"><button type="button" class="criterion-remove-btn remove-criterion" aria-label="Remove task">×</button></div></td><td><input type="number" name="outlines[${index}][criteria][${rowIndex}][marks]" class="criterion-marks sheet-input sheet-number" value="0" min="0" step="0.01" placeholder="Marks"></td><td><input type="number" name="outlines[${index}][criteria][${rowIndex}][weight]" class="criterion-weight sheet-input sheet-number" value="${parseFloat(weight) || 0}" min="0" step="0.01" placeholder="Weight"></td>`;
    list.appendChild(row);
    updateTotal(block);
    serializeCriteria(block);
}

function taskDefaults(type) {
    if (type === 'test') {
        return { topic: 'Unit test or quiz', marks: 10, weight: 15 };
    }

    if (type === 'exam') {
        return { topic: 'Midterm or final exam', marks: 10, weight: 5 };
    }

    return { topic: 'Homework, classwork, or submissions', marks: 15, weight: 10 };
}

function handleFileUpload(input, index) {
    const block = getOutlineBlock(index);
    if (!block) return;

    const previewSection = block.querySelector('#file-preview-section-' + index);
    const uploadBox = block.querySelector('#upload-box-' + index);

    if (input.files && input.files[0]) {
        uploadedFiles[index] = input.files[0];
        if (uploadedFileUrls[index]) {
            URL.revokeObjectURL(uploadedFileUrls[index]);
        }
        uploadedFileUrls[index] = URL.createObjectURL(uploadedFiles[index]);

        block.querySelector('#file-name-' + index).textContent = uploadedFiles[index].name;
        block.querySelector('#file-size-' + index).textContent = formatFileSize(uploadedFiles[index].size);

        const fileIcon = block.querySelector('#file-icon-' + index);
        const ext = uploadedFiles[index].name.split('.').pop().toLowerCase();
        if (ext === 'pdf') fileIcon.textContent = '📕';
        else if (['doc', 'docx'].includes(ext)) fileIcon.textContent = '📘';
        else if (ext === 'txt') fileIcon.textContent = '📝';
        else fileIcon.textContent = '📄';

        const previewBtn = block.querySelector('#preview-btn-' + index);
        const downloadBtn = block.querySelector('#download-btn-' + index);
        if (previewBtn) previewBtn.href = uploadedFileUrls[index];
        if (downloadBtn) downloadBtn.href = uploadedFileUrls[index];

        if (uploadBox) {
            uploadBox.style.display = 'none';
        }
        previewSection.style.display = 'block';
    } else {
        removeFile(index);
    }
}

function removeFile(index) {
    delete uploadedFiles[index];
    if (uploadedFileUrls[index]) {
        URL.revokeObjectURL(uploadedFileUrls[index]);
        delete uploadedFileUrls[index];
    }

    const block = getOutlineBlock(index);
    if (!block) return;

    const input = block.querySelector('#outline-file-' + index);
    if (input) input.value = '';

    const previewSection = block.querySelector('#file-preview-section-' + index);
    const uploadBox = block.querySelector('#upload-box-' + index);

    previewSection.style.display = 'none';
    if (uploadBox) {
        uploadBox.style.display = 'flex';
    }
    uploadBox.style.borderColor = '#e5e7eb';
    uploadBox.style.background = 'transparent';
    block.querySelector('#upload-icon-' + index).textContent = '📁';
    block.querySelector('#upload-text-' + index).textContent = 'Upload unit outline file';
}

function previewFile(index) {
    const file = uploadedFiles[index];
    if (!file) return;

    const fileURL = uploadedFileUrls[index] || URL.createObjectURL(file);
    window.open(fileURL, '_blank', 'noopener');
}

function syncOutlineTitle(block) {
    const index = block.dataset.outlineIndex;
    const titleInput = block.querySelector('.outline-title');
    const display = block.querySelector('#chapter-title-display-' + index);
    if (titleInput && display) {
        display.textContent = titleInput.value || 'Untitled';
    }
}

function autoResizeTitleField(field) {
    if (!field) return;
    field.style.height = 'auto';
    field.style.height = field.scrollHeight + 'px';
}

function cloneOutlineBlock() {
    const table = document.getElementById('unit-outline-table');
    const summaryBody = document.getElementById('sheet-summary-body');
    const blocks = Array.from(document.querySelectorAll('.unit-outline-block'));
    const newIndex = blocks.length;
    const html = buildChapterSection(newIndex);
    const parserTable = document.createElement('table');
    parserTable.innerHTML = html.trim();
    const node = parserTable.querySelector('tbody.unit-outline-block');

    if (!table || !node) {
        return;
    }

    if (summaryBody) {
        table.insertBefore(node, summaryBody);
    } else {
        table.appendChild(node);
    }

    // Force visible top border on the newly inserted block's first row cells
    try {
        Array.from(node.querySelectorAll('tr:first-child td')).forEach(td => {
            td.style.borderTop = '1px solid #d1d5db';
        });
        const chapterCell = node.querySelector('.chapter-cell');
        if (chapterCell) chapterCell.style.background = '#f8fafc';
    } catch (e) {
        // ignore
    }

    updateTotal(node);
    serializeCriteria(node);
    serializeGradeScale(node);
    serializeAiOptions(node);
    syncOutlineTitle(node);
    autoResizeTitleField(node.querySelector('.outline-title'));
    updateOverallTotal();
}

(function(){
    document.addEventListener('click', function(e){
        if (e.target && e.target.classList && e.target.classList.contains('task-add-btn')) {
            const block = e.target.closest('.unit-outline-block');
            const defaults = taskDefaults(e.target.dataset.type || 'assignment');
            appendCriterionRow(block, e.target.dataset.type || 'assignment', defaults.topic, defaults.weight);

                    const rows = block.querySelectorAll('.criterion-row');
                    const lastRow = rows[rows.length - 1];
            if (lastRow) {
                const topicInput = lastRow.querySelector('.criterion-topic');
                const marksInput = lastRow.querySelector('.criterion-marks');
                const weightInput = lastRow.querySelector('.criterion-weight');
                if (topicInput) topicInput.value = defaults.topic;
                if (marksInput) marksInput.value = defaults.marks;
                if (weightInput) weightInput.value = defaults.weight;
                serializeCriteria(block);
                updateTotal(block);
                updateOverallTotal();
            }
            return;
        }

        if (e.target && e.target.classList && e.target.classList.contains('preset-criterion-btn')) {
            const block = e.target.closest('.unit-outline-block');
            appendCriterionRow(block, e.target.dataset.type || 'assignment', e.target.dataset.topic || '', parseFloat(e.target.dataset.weight) || 0);
            updateOverallTotal();
            return;
        }

        if (e.target && e.target.classList && e.target.classList.contains('add-criterion-btn')) {
            const block = e.target.closest('.unit-outline-block');
            const typeInput = block.querySelector('.new-criterion-type');
            const topicInput = block.querySelector('.new-criterion-topic');
            const weightInput = block.querySelector('.new-criterion-weight');
            appendCriterionRow(block, typeInput ? typeInput.value : 'assignment', topicInput ? topicInput.value.trim() : '', weightInput ? (parseFloat(weightInput.value) || 0) : 0);
            if (topicInput) topicInput.value = '';
            if (weightInput) weightInput.value = '10';
            updateOverallTotal();
            return;
        }

        if (e.target && e.target.classList && e.target.classList.contains('remove-criterion')) {
            const row = e.target.closest('.criterion-row');
            const block = e.target.closest('.unit-outline-block');
            if (row && block) {
                row.remove();
                updateTotal(block);
                serializeCriteria(block);
                updateOverallTotal();
            }
        }
    });

    document.addEventListener('input', function(e){
        const block = e.target.closest ? e.target.closest('.unit-outline-block') : null;
        if (!block) return;

        if (e.target.classList && (e.target.classList.contains('criterion-weight') || e.target.classList.contains('criterion-type') || e.target.classList.contains('criterion-topic'))) {
            updateTotal(block);
            serializeCriteria(block);
            updateOverallTotal();
        }

        if (e.target.classList && e.target.classList.contains('criterion-marks')) {
            serializeCriteria(block);
        }

        if (e.target.classList && e.target.classList.contains('outline-title')) {
            syncOutlineTitle(block);
            autoResizeTitleField(e.target);
            autoResizeTitleField(e.target);
        }

        if (e.target.classList && e.target.classList.contains('outline-total-weight')) {
            updateTotal(block);
            serializeAiOptions(block);
            updateOverallTotal();
        }
    });

    document.addEventListener('change', function(e){
        const block = e.target.closest ? e.target.closest('.unit-outline-block') : null;
        if (!block) return;

        if (e.target.classList && e.target.classList.contains('criterion-type')) {
            serializeCriteria(block);
            updateOverallTotal();
        }

        if (e.target.classList && e.target.classList.contains('criterion-marks')) {
            serializeCriteria(block);
        }

        if (e.target.classList && e.target.classList.contains('outline-total-weight')) {
            updateTotal(block);
            serializeAiOptions(block);
            updateOverallTotal();
        }
    });

    document.addEventListener('DOMContentLoaded', function(){
        Array.from(document.querySelectorAll('.unit-outline-block')).forEach(block => {
            updateTotal(block);
            serializeCriteria(block);
            serializeGradeScale(block);
            serializeAiOptions(block);
            syncOutlineTitle(block);
            syncSheetRowspans(block);
            autoResizeTitleField(block.querySelector('.outline-title'));
        });
        updateOverallTotal();
        const form = document.querySelector('form[method="POST"][enctype]');
        if (form) {
            form.addEventListener('submit', function(e){
                Array.from(document.querySelectorAll('.unit-outline-block')).forEach(block => {
                    updateTotal(block);
                    serializeCriteria(block);
                    serializeGradeScale(block);
                    serializeAiOptions(block);
                });
                updateOverallTotal();
            });
        }
    });
})();
</script>
@endsection
