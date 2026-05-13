@extends('layouts.teacher')

@section('title', 'Add Unit Outline')
@section('page-title', 'Add Unit Outline')

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
            <div class="unit-outline-block" data-outline-index="0" style="background:#f8fafc; border:1px solid #e5e7eb; border-radius:12px; padding:20px; margin-bottom:24px;">
                <div class="outline-heading" data-outline-heading style="font-weight:700; font-size:16px; margin-bottom:14px; color:#1f2937;">Chapter/Unit 1</div>
                <div class="form-group">
                    <label for="outline-title-0">Chapter/Unit Title <span class="required">*</span></label>
                          <input type="text" id="outline-title-0" name="outlines[0][title]" class="form-control outline-title"
                              value="{{ old('outlines.0.title', old('title')) }}"
                           placeholder="e.g., Introduction to Photosynthesis" required>
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="outline-chapter-total-weight-0">Chapter/Unit Total Weight (%)</label>
                          <input type="number" id="outline-chapter-total-weight-0" name="outlines[0][chapter_total_weight]" class="form-control outline-total-weight"
                              value="{{ old('outlines.0.chapter_total_weight', old('chapter_total_weight', '100')) }}" min="0" max="100" step="0.01"
                           placeholder="e.g., 20">
                    <small style="display:block; margin-top:6px; color:#6b7280; font-size:12px;">This creates the actual Unit record for the module. The assessment rows below will be linked to it automatically.</small>
                </div>

                <div class="form-group">
                    <label for="outline-description-0">Learning objectives & description</label>
                    <textarea id="outline-description-0" name="outlines[0][description]" class="form-control outline-description"
                              placeholder="Describe what students should achieve, what topics this chapter covers, and any special instructions...">{{ old('outlines.0.description', old('description')) }}</textarea>
                    @error('description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="outline-file-0">Upload unit outline file</label>
                    <label class="upload-box" id="upload-box-0" style="display:block; border:2px dashed #e5e7eb; border-radius:8px; padding:28px; text-align:center; cursor:pointer; margin-top:8px; transition: all 0.3s ease;">
                        <div id="upload-icon-0" style="font-size:24px; color:#6b7280;">📁</div>
                        <div id="upload-text-0" style="margin-top:8px; font-weight:600;">Upload unit outline file</div>
                        <div style="font-size:12px; color:#9ca3af; margin-top:6px;">PDF, DOC, DOCX, TXT — AI will use this for auto-grading</div>
                        <input type="file" id="outline-file-0" name="outlines[0][file]" accept=".pdf,.doc,.docx,.txt" onchange="handleFileUpload(this, 0)" style="display:none">
                    </label>

                    <!-- File Preview Section -->
                    <div id="file-preview-section-0" style="display:none; margin-top: 16px; padding: 16px; background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px;">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                            <div id="file-icon-0" style="font-size: 32px;">📄</div>
                            <div style="flex: 1;">
                                <div id="file-name-0" style="font-weight: 600; color: #1f2937; word-break: break-all;"></div>
                                <div id="file-size-0" style="font-size: 12px; color: #6b7280; margin-top: 2px;"></div>
                                <div id="file-type-0" style="font-size: 11px; color: #9ca3af; margin-top: 2px;"></div>
                            </div>
                            <button type="button" onclick="removeFile(0)" style="background: #fee2e2; color: #dc2626; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 500;">Remove</button>
                        </div>

                        <!-- Preview Actions -->
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <button type="button" id="preview-btn-0" onclick="previewFile(0)" style="background: #dbeafe; color: #1d4ed8; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 6px;">
                                👁️ Open in Browser
                            </button>
                        </div>

                        <!-- Quick Text Preview (for TXT files only) -->
                        <div id="text-preview-0" style="display:none; margin-top: 12px; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; background: white;">
                            <div style="background: #f3f4f6; padding: 8px 12px; font-size: 12px; color: #4b5563; font-weight: 500; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                                <span>Text Preview (first 1000 characters)</span>
                                <button type="button" onclick="previewFile(0)" style="background: none; border: none; color: #1d4ed8; cursor: pointer; font-size: 12px; text-decoration: underline;">Open full file →</button>
                            </div>
                            <pre id="text-content-0" style="width: 100%; height: 200px; margin: 0; padding: 12px; overflow: auto; font-size: 13px; line-height: 1.5; color: #374151; background: #fafafa; white-space: pre-wrap; word-wrap: break-word;"></pre>
                        </div>

                        <div id="preview-error-0" style="display:none; margin-top: 12px; padding: 12px; background: #fee2e2; color: #dc2626; border-radius: 6px; font-size: 13px;"></div>
                    </div>
                </div>

                <div style="margin-top:20px; margin-bottom:4px;">
                    <div style="font-weight:700; font-size:16px; margin-bottom:10px; color:#1f2937;">Assessments for <span id="chapter-title-display-0">{{ old('title') ?: 'Untitled' }}</span></div>
                    <div style="font-size:12px; color:#6b7280; margin-bottom:14px;">Add the Assignment, Test, and Exam rows that belong to this chapter/unit. These assessments are saved to the same Unit record created above.</div>
                </div>
                <div style="background:#fff; border:1px solid #e5e7eb; padding:16px; border-radius:8px;">
                    <div style="font-size:12px; color:#6b7280; margin-bottom:10px;">Keep this section inside the chapter flow so the teacher adds the chapter and its assessments together.</div>
                    <div id="criteria-list-0" class="criteria-list">
                        <div class="criterion-row" data-index="0" style="display:grid; grid-template-columns: 1fr 1fr 100px 40px; gap:10px; margin-bottom:10px; align-items:center;">
                            <select name="outlines[0][criteria][0][type]" class="criterion-type form-control" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                                <option value="assignment" @selected(old('outlines.0.criteria.0.type', old('criteria.0.type', 'assignment')) === 'assignment')>Assignment</option>
                                <option value="test" @selected(old('outlines.0.criteria.0.type', old('criteria.0.type')) === 'test')>Test</option>
                                <option value="exam" @selected(old('outlines.0.criteria.0.type', old('criteria.0.type')) === 'exam')>Exam</option>
                            </select>
                            <input type="text" name="outlines[0][criteria][0][topic]" class="criterion-topic form-control" placeholder="Topic" value="{{ old('outlines.0.criteria.0.topic', old('criteria.0.topic', 'Homework, classwork, or submissions')) }}" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                            <input type="number" name="outlines[0][criteria][0][weight]" class="criterion-weight form-control" value="{{ old('outlines.0.criteria.0.weight', old('criteria.0.weight', '40')) }}" min="0" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                            <button type="button" class="btn btn-outline remove-criterion" style="padding:8px 12px;">×</button>
                        </div>
                        <div class="criterion-row" data-index="1" style="display:grid; grid-template-columns: 1fr 1fr 100px 40px; gap:10px; margin-bottom:10px; align-items:center;">
                            <select name="outlines[0][criteria][1][type]" class="criterion-type form-control" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                                <option value="assignment" @selected(old('outlines.0.criteria.1.type', old('criteria.1.type')) === 'assignment')>Assignment</option>
                                <option value="test" @selected(old('outlines.0.criteria.1.type', old('criteria.1.type', 'test')) === 'test')>Test</option>
                                <option value="exam" @selected(old('outlines.0.criteria.1.type', old('criteria.1.type')) === 'exam')>Exam</option>
                            </select>
                            <input type="text" name="outlines[0][criteria][1][topic]" class="criterion-topic form-control" placeholder="Topic" value="{{ old('outlines.0.criteria.1.topic', old('criteria.1.topic', 'Unit test or quiz')) }}" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                            <input type="number" name="outlines[0][criteria][1][weight]" class="criterion-weight form-control" value="{{ old('outlines.0.criteria.1.weight', old('criteria.1.weight', '30')) }}" min="0" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                            <button type="button" class="btn btn-outline remove-criterion" style="padding:8px 12px;">×</button>
                        </div>
                        <div class="criterion-row" data-index="2" style="display:grid; grid-template-columns: 1fr 1fr 100px 40px; gap:10px; margin-bottom:10px; align-items:center;">
                            <select name="outlines[0][criteria][2][type]" class="criterion-type form-control" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                                <option value="assignment" @selected(old('outlines.0.criteria.2.type', old('criteria.2.type')) === 'assignment')>Assignment</option>
                                <option value="test" @selected(old('outlines.0.criteria.2.type', old('criteria.2.type')) === 'test')>Test</option>
                                <option value="exam" @selected(old('outlines.0.criteria.2.type', old('criteria.2.type', 'exam')) === 'exam')>Exam</option>
                            </select>
                            <input type="text" name="outlines[0][criteria][2][topic]" class="criterion-topic form-control" placeholder="Topic" value="{{ old('outlines.0.criteria.2.topic', old('criteria.2.topic', 'Midterm or final exam')) }}" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                            <input type="number" name="outlines[0][criteria][2][weight]" class="criterion-weight form-control" value="{{ old('outlines.0.criteria.2.weight', old('criteria.2.weight', '30')) }}" min="0" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                            <button type="button" class="btn btn-outline remove-criterion" style="padding:8px 12px;">×</button>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns: 1fr 1fr 100px 40px; gap:10px; margin-top:10px; align-items:center;">
                        <select id="new-criterion-type-0" class="form-control new-criterion-type" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                            <option value="assignment">Assignment</option>
                            <option value="test">Test</option>
                            <option value="exam">Exam</option>
                        </select>
                        <input id="new-criterion-topic-0" placeholder="Topic (optional)" class="form-control new-criterion-topic" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                        <input id="new-criterion-weight-0" type="number" min="0" placeholder="Weight" value="10" class="form-control new-criterion-weight" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                        <div></div>
                    </div>
                    <div style="display:flex; gap:8px; margin-top:12px; align-items:center;">
                        <button type="button" class="btn btn-outline preset-criterion-btn" data-type="assignment" data-topic="Homework, classwork, or submissions" data-weight="40">+ Assignment</button>
                        <button type="button" class="btn btn-outline preset-criterion-btn" data-type="test" data-topic="Unit test or quiz" data-weight="30">+ Test</button>
                        <button type="button" class="btn btn-outline preset-criterion-btn" data-type="exam" data-topic="Midterm or final exam" data-weight="30">+ Exam</button>
                        <!-- Purple "+ Add Assessment" removed (duplicate); use preset buttons or the smaller add button instead -->
                        <span style="margin-left:auto; font-weight:600; color:#4b5563;">Total weight: <span id="total-weight-0">100</span>%</span>
                    </div>
                    <div style="margin-top:8px;">
                        <div id="weight-progress-0" style="width:100%; height:12px; background:#eef2ff; border-radius:8px; overflow:hidden;">
                            <div id="weight-progress-fill-0" style="width:100%; height:100%; background:#f97316; transition: width 200ms ease, background 200ms ease;"></div>
                        </div>
                        <div id="weight-remaining-0" style="margin-top:6px; font-size:12px; color:#6b7280;">Remaining: 0%</div>
                    </div>
                    <div id="total-weight-warning-0" style="display:none; margin-top:8px; color:#b91c1c; font-size:12px; font-weight:600;">Assessment total must match chapter/unit total weight before saving.</div>
                </div>

            </div>

            <input type="hidden" name="outlines[0][grading_criteria]" class="grading-criteria-input">
            <input type="hidden" name="outlines[0][grade_scale]" class="grade-scale-input">
            <input type="hidden" name="outlines[0][ai_options]" class="ai-options-input">

            </div>

            <div style="display:flex; gap:12px; justify-content:flex-start; margin-bottom:16px;">
                <button type="button" id="add-outline-btn" class="btn btn-outline" onclick="cloneOutlineBlock()">+ Add Another Chapter/Unit</button>
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
const fileContents = {};

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

function renderDefaultCriteriaRows(index) {
    return `
        <div class="criterion-row" data-index="0" style="display:grid; grid-template-columns: 1fr 1fr 100px 40px; gap:10px; margin-bottom:10px; align-items:center;">
            <select name="outlines[${index}][criteria][0][type]" class="criterion-type form-control" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                <option value="assignment" selected>Assignment</option>
                <option value="test">Test</option>
                <option value="exam">Exam</option>
            </select>
            <input type="text" name="outlines[${index}][criteria][0][topic]" class="criterion-topic form-control" placeholder="Topic" value="Homework, classwork, or submissions" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
            <input type="number" name="outlines[${index}][criteria][0][weight]" class="criterion-weight form-control" value="40" min="0" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
            <button type="button" class="btn btn-outline remove-criterion" style="padding:8px 12px;">×</button>
        </div>
        <div class="criterion-row" data-index="1" style="display:grid; grid-template-columns: 1fr 1fr 100px 40px; gap:10px; margin-bottom:10px; align-items:center;">
            <select name="outlines[${index}][criteria][1][type]" class="criterion-type form-control" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                <option value="assignment">Assignment</option>
                <option value="test" selected>Test</option>
                <option value="exam">Exam</option>
            </select>
            <input type="text" name="outlines[${index}][criteria][1][topic]" class="criterion-topic form-control" placeholder="Topic" value="Unit test or quiz" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
            <input type="number" name="outlines[${index}][criteria][1][weight]" class="criterion-weight form-control" value="30" min="0" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
            <button type="button" class="btn btn-outline remove-criterion" style="padding:8px 12px;">×</button>
        </div>
        <div class="criterion-row" data-index="2" style="display:grid; grid-template-columns: 1fr 1fr 100px 40px; gap:10px; margin-bottom:10px; align-items:center;">
            <select name="outlines[${index}][criteria][2][type]" class="criterion-type form-control" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
                <option value="assignment">Assignment</option>
                <option value="test">Test</option>
                <option value="exam" selected>Exam</option>
            </select>
            <input type="text" name="outlines[${index}][criteria][2][topic]" class="criterion-topic form-control" placeholder="Topic" value="Midterm or final exam" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
            <input type="number" name="outlines[${index}][criteria][2][weight]" class="criterion-weight form-control" value="30" min="0" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;">
            <button type="button" class="btn btn-outline remove-criterion" style="padding:8px 12px;">×</button>
        </div>`;
}

function updateTotal(block) {
    const weights = Array.from(block.querySelectorAll('.criterion-weight')).map(input => parseFloat(input.value) || 0);
    const total = weights.reduce((sum, value) => sum + value, 0);
    const index = block.dataset.outlineIndex;
    const totalEl = block.querySelector('#total-weight-' + index);
    if (totalEl) totalEl.textContent = total;

    const warning = block.querySelector('#total-weight-warning-' + index);
    const chapterWeightInput = block.querySelector('.outline-total-weight');
    const chapterWeight = chapterWeightInput ? (parseFloat(chapterWeightInput.value) || 0) : 0;
    const expected = chapterWeight > 0 ? chapterWeight : 100;

    if (warning) {
        warning.textContent = 'Assessment total must be exactly ' + expected + '%. Current total: ' + total + '%.';
        warning.style.display = total === expected ? 'none' : 'block';
    }

    const progressFill = block.querySelector('#weight-progress-fill-' + index);
    const remainingLabel = block.querySelector('#weight-remaining-' + index);
    if (progressFill && remainingLabel) {
        const pct = expected > 0 ? Math.max(0, Math.min(100, (total / expected) * 100)) : 0;
        progressFill.style.width = pct + '%';
        const remaining = Math.round((expected - total) * 100) / 100;
        remainingLabel.textContent = remaining >= 0 ? ('Remaining: ' + remaining + '%') : ('Over by: ' + Math.abs(remaining) + '%');
        if (total === expected) {
            progressFill.style.background = '#10b981';
        } else if (total < expected) {
            progressFill.style.background = '#f97316';
        } else {
            progressFill.style.background = '#ef4444';
        }
    }

    return total;
}

function serializeCriteria(block) {
    const index = block.dataset.outlineIndex;
    const rows = Array.from(block.querySelectorAll('.criterion-row'));
    const items = rows.map(row => ({
        assessment_type: row.querySelector('.criterion-type') ? row.querySelector('.criterion-type').value : '',
        topic: row.querySelector('.criterion-topic') ? row.querySelector('.criterion-topic').value : '',
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
    const list = block.querySelector('.criteria-list');
    const rowIndex = list.children.length;
    const row = document.createElement('div');
    row.className = 'criterion-row';
    row.dataset.index = rowIndex;
    row.style.display = 'grid';
    row.style.gridTemplateColumns = '1fr 1fr 100px 40px';
    row.style.gap = '10px';
    row.style.marginBottom = '10px';
    row.style.alignItems = 'center';
    row.innerHTML = `<select name="outlines[${index}][criteria][${rowIndex}][type]" class="criterion-type form-control" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;"><option value="assignment" ${type === 'assignment' ? 'selected' : ''}>Assignment</option><option value="test" ${type === 'test' ? 'selected' : ''}>Test</option><option value="exam" ${type === 'exam' ? 'selected' : ''}>Exam</option></select><input type="text" name="outlines[${index}][criteria][${rowIndex}][topic]" class="criterion-topic form-control" placeholder="Topic" value="${String(topic || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\"/g, '&quot;').replace(/'/g, '&#039;')}" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;"><input type="number" name="outlines[${index}][criteria][${rowIndex}][weight]" class="criterion-weight form-control" value="${parseFloat(weight) || 0}" min="0" style="padding:10px; border:1px solid #d1d5db; border-radius:6px;"><button type="button" class="btn btn-outline remove-criterion" style="padding:8px 12px;">×</button>`;
    list.appendChild(row);
    updateTotal(block);
    serializeCriteria(block);
}

function handleFileUpload(input, index) {
    const block = getOutlineBlock(index);
    if (!block) return;

    const previewSection = block.querySelector('#file-preview-section-' + index);
    const uploadBox = block.querySelector('#upload-box-' + index);

    if (input.files && input.files[0]) {
        uploadedFiles[index] = input.files[0];
        fileContents[index] = null;

        block.querySelector('#file-name-' + index).textContent = uploadedFiles[index].name;
        block.querySelector('#file-size-' + index).textContent = formatFileSize(uploadedFiles[index].size);
        block.querySelector('#file-type-' + index).textContent = uploadedFiles[index].type || getFileTypeFromName(uploadedFiles[index].name);

        const fileIcon = block.querySelector('#file-icon-' + index);
        const ext = uploadedFiles[index].name.split('.').pop().toLowerCase();
        if (ext === 'pdf') fileIcon.textContent = '📕';
        else if (['doc', 'docx'].includes(ext)) fileIcon.textContent = '📘';
        else if (ext === 'txt') fileIcon.textContent = '📝';
        else fileIcon.textContent = '📄';

        previewSection.style.display = 'block';
        uploadBox.style.borderColor = '#10b981';
        uploadBox.style.background = '#f0fdf4';
        block.querySelector('#upload-icon-' + index).textContent = '✅';
        block.querySelector('#upload-text-' + index).textContent = 'File uploaded successfully';

        block.querySelector('#text-preview-' + index).style.display = 'none';
        block.querySelector('#text-content-' + index).textContent = '';
        block.querySelector('#preview-error-' + index).style.display = 'none';
    } else {
        removeFile(index);
    }
}

function removeFile(index) {
    delete uploadedFiles[index];
    delete fileContents[index];

    const block = getOutlineBlock(index);
    if (!block) return;

    const input = block.querySelector('#outline-file-' + index);
    if (input) input.value = '';

    const previewSection = block.querySelector('#file-preview-section-' + index);
    const uploadBox = block.querySelector('#upload-box-' + index);

    previewSection.style.display = 'none';
    uploadBox.style.borderColor = '#e5e7eb';
    uploadBox.style.background = 'transparent';
    block.querySelector('#upload-icon-' + index).textContent = '📁';
    block.querySelector('#upload-text-' + index).textContent = 'Upload unit outline file';
    block.querySelector('#text-preview-' + index).style.display = 'none';
    block.querySelector('#text-content-' + index).textContent = '';
    block.querySelector('#preview-error-' + index).style.display = 'none';
}

function previewFile(index) {
    const file = uploadedFiles[index];
    if (!file) return;

    const block = getOutlineBlock(index);
    const ext = file.name.split('.').pop().toLowerCase();
    block.querySelector('#preview-error-' + index).style.display = 'none';

    const fileURL = URL.createObjectURL(file);
    window.open(fileURL, '_blank');

    if (ext === 'txt') {
        const reader = new FileReader();
        reader.onload = function(e) {
            fileContents[index] = e.target.result;
            const previewText = fileContents[index].length > 1000
                ? fileContents[index].substring(0, 1000) + '\n\n[... Click "Open in Browser" to see full file ...]'
                : fileContents[index];
            block.querySelector('#text-content-' + index).textContent = previewText;
            block.querySelector('#text-preview-' + index).style.display = 'block';
        };
        reader.readAsText(file);
    }
}

function syncOutlineTitle(block) {
    const index = block.dataset.outlineIndex;
    const titleInput = block.querySelector('.outline-title');
    const display = block.querySelector('#chapter-title-display-' + index);
    if (titleInput && display) {
        display.textContent = titleInput.value || 'Untitled';
    }
}

function cloneOutlineBlock() {
    // Prefer using a prebuilt template (if available) so new blocks match exactly
    try {
        if (window.unitOutlineTemplate) {
            const blocks = Array.from(document.querySelectorAll('.unit-outline-block'));
            const newIndex = blocks.length;
            const html = window.unitOutlineTemplate.replace(/__INDEX__/g, newIndex);
            const container = document.createElement('div');
            container.innerHTML = html;
            const node = container.firstElementChild;
            if (node) {
                node.dataset.outlineIndex = newIndex;
                document.getElementById('unit-outline-blocks').appendChild(node);
                updateTotal(node);
                serializeCriteria(node);
                serializeGradeScale(node);
                serializeAiOptions(node);
                syncOutlineTitle(node);
                return;
            }
        }
    } catch (e) {
        console.warn('Template clone failed, falling back to DOM clone', e);
    }

    // Last-resort: clone the last existing block and fix ids/names
    const blocks = Array.from(document.querySelectorAll('.unit-outline-block'));
    const lastBlock = blocks[blocks.length - 1];
    const newIndex = blocks.length;
    const clone = lastBlock.cloneNode(true);
    const oldIndex = parseInt(lastBlock.dataset.outlineIndex, 10);

    clone.dataset.outlineIndex = newIndex;
    clone.style.marginTop = '24px';

    clone.querySelectorAll('[id]').forEach(element => {
        element.id = element.id.replace(new RegExp('-' + oldIndex + '$'), '-' + newIndex);
    });

    clone.querySelectorAll('[name]').forEach(element => {
        element.name = element.name.replace(`outlines[${oldIndex}]`, `outlines[${newIndex}]`);
    });

    clone.querySelectorAll('[for]').forEach(element => {
        element.htmlFor = element.htmlFor.replace(new RegExp('-' + oldIndex + '$'), '-' + newIndex);
    });

    const heading = clone.querySelector('[data-outline-heading]');
    if (heading) heading.textContent = 'Chapter/Unit ' + (newIndex + 1);

    clone.querySelectorAll('.outline-title').forEach(el => el.value = '');
    clone.querySelectorAll('.outline-total-weight').forEach(el => el.value = '100');
    clone.querySelectorAll('.outline-description').forEach(el => el.value = '');
    clone.querySelectorAll('.grading-criteria-input').forEach(el => el.value = '');
    clone.querySelectorAll('.grade-scale-input').forEach(el => el.value = '');
    clone.querySelectorAll('.ai-options-input').forEach(el => el.value = '');

    const fileInput = clone.querySelector('input[type="file"]');
    if (fileInput) {
        fileInput.value = '';
        fileInput.setAttribute('name', `outlines[${newIndex}][file]`);
        fileInput.setAttribute('onchange', `handleFileUpload(this, ${newIndex})`);
    }

    const removeButton = clone.querySelector('[onclick^="removeFile"]');
    if (removeButton) removeButton.setAttribute('onclick', `removeFile(${newIndex})`);

    const previewButton = clone.querySelector('[onclick^="previewFile"]');
    if (previewButton) previewButton.setAttribute('onclick', `previewFile(${newIndex})`);

    const previewTextButton = clone.querySelector('#text-preview-' + newIndex + ' button');
    if (previewTextButton) previewTextButton.setAttribute('onclick', `previewFile(${newIndex})`);

    const uploadIcon = clone.querySelector('#upload-icon-' + newIndex);
    if (uploadIcon) uploadIcon.textContent = '📁';
    const uploadText = clone.querySelector('#upload-text-' + newIndex);
    if (uploadText) uploadText.textContent = 'Upload unit outline file';
    const filePreview = clone.querySelector('#file-preview-section-' + newIndex);
    if (filePreview) filePreview.style.display = 'none';
    const textPreview = clone.querySelector('#text-preview-' + newIndex);
    if (textPreview) textPreview.style.display = 'none';
    const textContent = clone.querySelector('#text-content-' + newIndex);
    if (textContent) textContent.textContent = '';
    const previewErr = clone.querySelector('#preview-error-' + newIndex);
    if (previewErr) previewErr.style.display = 'none';

    const criteriaList = clone.querySelector('#criteria-list-' + newIndex);
    if (criteriaList) criteriaList.innerHTML = renderDefaultCriteriaRows(newIndex);
    const newType = clone.querySelector('#new-criterion-type-' + newIndex);
    if (newType) newType.value = 'assignment';
    const newTopic = clone.querySelector('#new-criterion-topic-' + newIndex);
    if (newTopic) newTopic.value = '';
    const newWeight = clone.querySelector('#new-criterion-weight-' + newIndex);
    if (newWeight) newWeight.value = '10';

    document.getElementById('unit-outline-blocks').appendChild(clone);
    syncOutlineTitle(clone);
    updateTotal(clone);
    serializeCriteria(clone);
    serializeGradeScale(clone);
    serializeAiOptions(clone);
}

(function(){
    document.addEventListener('click', function(e){
        if (e.target && e.target.classList && e.target.classList.contains('preset-criterion-btn')) {
            const block = e.target.closest('.unit-outline-block');
            appendCriterionRow(block, e.target.dataset.type || 'assignment', e.target.dataset.topic || '', parseFloat(e.target.dataset.weight) || 0);
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
            return;
        }

        if (e.target && e.target.classList && e.target.classList.contains('remove-criterion')) {
            const row = e.target.closest('.criterion-row');
            const block = e.target.closest('.unit-outline-block');
            if (row && block) {
                row.remove();
                updateTotal(block);
                serializeCriteria(block);
            }
        }
    });

    document.addEventListener('input', function(e){
        const block = e.target.closest ? e.target.closest('.unit-outline-block') : null;
        if (!block) return;

        if (e.target.classList && (e.target.classList.contains('criterion-weight') || e.target.classList.contains('criterion-type') || e.target.classList.contains('criterion-topic'))) {
            updateTotal(block);
            serializeCriteria(block);
        }

        if (e.target.classList && e.target.classList.contains('outline-title')) {
            syncOutlineTitle(block);
        }

        if (e.target.classList && e.target.classList.contains('outline-total-weight')) {
            updateTotal(block);
            serializeAiOptions(block);
        }
    });

    document.addEventListener('change', function(e){
        const block = e.target.closest ? e.target.closest('.unit-outline-block') : null;
        if (!block) return;

        if (e.target.classList && e.target.classList.contains('criterion-type')) {
            serializeCriteria(block);
        }

        if (e.target.classList && e.target.classList.contains('outline-total-weight')) {
            updateTotal(block);
            serializeAiOptions(block);
        }
    });

    document.addEventListener('DOMContentLoaded', function(){
        Array.from(document.querySelectorAll('.unit-outline-block')).forEach(block => {
            updateTotal(block);
            serializeCriteria(block);
            serializeGradeScale(block);
            serializeAiOptions(block);
            syncOutlineTitle(block);
        });

        // Build a robust HTML template from the first block so cloned blocks match exactly
        try {
            const first = document.querySelector('.unit-outline-block');
            if (first) {
                // capture outerHTML and replace the initial index markers with a placeholder
                let html = first.outerHTML;
                html = html.replace(/\[0\]/g, '[__INDEX__]');
                html = html.replace(/-0(["'\s>])/g, '-__INDEX__$1');
                // store template for cloning
                window.unitOutlineTemplate = html;
            }
        } catch (e) {
            console.warn('Could not build outline template', e);
        }

        const form = document.querySelector('form[method="POST"][enctype]');
        if (form) {
            form.addEventListener('submit', function(e){
                let valid = true;
                Array.from(document.querySelectorAll('.unit-outline-block')).forEach(block => {
                    const chapterWeightInput = block.querySelector('.outline-total-weight');
                    const expected = chapterWeightInput ? (parseFloat(chapterWeightInput.value) || 0) : 100;
                    const total = updateTotal(block);
                    serializeCriteria(block);
                    serializeGradeScale(block);
                    serializeAiOptions(block);
                    if (total !== expected) {
                        valid = false;
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    alert('Each chapter/unit assessment total must match its chapter/unit total weight before saving.');
                }
            });
        }
    });
})();
</script>
@endsection
