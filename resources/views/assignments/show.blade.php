@extends('layouts.student')

@section('title', $assignment->title)
@section('page-title', 'Assignments')

@section('content')
<style>
    .tabs { display: flex; gap: 4px; margin-bottom: 24px; border-bottom: 1px solid rgba(255,255,255,0.08); }
    .tab-link {
        padding: 10px 18px;
        font-size: 14px;
        font-weight: 500;
        color: #94a3b8;
        text-decoration: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        transition: color 0.2s;
    }
    .tab-link:hover { color: #e2e8f0; }
    .tab-link.active { color: #a78bfa; border-bottom-color: #7c3aed; }

    .card {
        background: #1e2235;
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 12px;
        padding: 28px;
        margin-bottom: 4px;
    }

    .assignment-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
    .assignment-title { font-size: 20px; font-weight: 700; color: #f1f5f9; }

    .assignment-meta { display: flex; gap: 20px; flex-wrap: wrap; padding-top: 14px; border-top: 1px solid rgba(255,255,255,0.07); margin-bottom: 0; }
    .meta-item { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #94a3b8; }
    .meta-item svg { width: 15px; height: 15px; fill: currentColor; flex-shrink: 0; }

    .badge { padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; display: inline-block; }
    .badge-not-submitted { background: rgba(245,158,11,0.18); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
    .badge-submitted    { background: rgba(59,130,246,0.15);  color: #60a5fa; border: 1px solid rgba(59,130,246,0.25); }
    .badge-graded       { background: rgba(16,185,129,0.15);  color: #10b981; border: 1px solid rgba(16,185,129,0.25); }
    .badge-overdue      { background: rgba(239,68,68,0.15);   color: #ef4444; border: 1px solid rgba(239,68,68,0.25); }

    .instructions-box {
        margin-top: 20px;
        padding: 18px 20px;
        background: rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 8px;
    }
    .instructions-box .label { font-size: 13px; font-weight: 600; color: #94a3b8; margin-bottom: 10px; }
    .instructions-box p { font-size: 14px; color: #cbd5e1; line-height: 1.7; }

    .submit-section { padding: 0 28px 28px; }
    .submit-section h3 { font-size: 16px; font-weight: 600; color: #f1f5f9; margin: 0 0 16px; padding-top: 20px; }

    .drop-zone {
        border: 2px dashed rgba(124,58,237,0.4);
        border-radius: 10px;
        padding: 44px 20px;
        text-align: center;
        background: rgba(124,58,237,0.04);
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        position: relative;
    }
    .drop-zone:hover, .drop-zone.dragover { border-color: #7c3aed; background: rgba(124,58,237,0.08); }
    .drop-zone svg { width: 40px; height: 40px; fill: #7c3aed; margin-bottom: 12px; }
    .drop-zone p { color: #94a3b8; font-size: 14px; margin-bottom: 4px; }
    .drop-zone small { color: #64748b; font-size: 12px; }
    .drop-zone input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .file-selected { font-size: 13px; color: #a78bfa; margin-top: 8px; display: none; }

    .comments-section { margin-top: 20px; }
    .comments-section label { font-size: 13px; font-weight: 600; color: #94a3b8; display: block; margin-bottom: 10px; }
    .comments-section textarea {
        width: 100%;
        padding: 14px 16px;
        background: rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: #cbd5e1;
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
        min-height: 110px;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }
    .comments-section textarea::placeholder { color: #475569; }
    .comments-section textarea:focus { outline: none; border-color: #7c3aed; }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 20px 28px 28px;
        border-top: 1px solid rgba(255,255,255,0.06);
        margin-top: 4px;
    }
    .btn-cancel {
        padding: 10px 22px;
        background: transparent;
        color: #94a3b8;
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-cancel:hover { background: rgba(255,255,255,0.06); color: #e2e8f0; }
    .btn-submit {
        padding: 10px 22px;
        background: #7c3aed;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background 0.2s;
    }
    .btn-submit:hover { background: #6d28d9; }
    .btn-submit svg { width: 16px; height: 16px; fill: currentColor; }

    .alert { padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
    .alert-success { background: rgba(16,185,129,0.12); color: #10b981; border: 1px solid rgba(16,185,129,0.25); }
    .alert-warning { background: rgba(245,158,11,0.12); color: #f59e0b; border: 1px solid rgba(245,158,11,0.25); }

    .submission-box {
        background: rgba(16,185,129,0.07);
        border: 1px solid rgba(16,185,129,0.2);
        border-radius: 10px;
        padding: 22px 24px;
        margin-top: 20px;
    }
    .submission-box p { font-size: 14px; color: #cbd5e1; margin-bottom: 10px; }
    .submission-box p:last-child { margin-bottom: 0; }
    .score-display { font-size: 28px; font-weight: 700; color: #10b981; }
    .file-link { color: #7c3aed; text-decoration: none; }
    .file-link:hover { text-decoration: underline; }
    .content-preview {
        margin-top: 12px;
        padding: 12px 14px;
        background: rgba(0,0,0,0.2);
        border-radius: 6px;
        font-size: 13px;
        color: #94a3b8;
        white-space: pre-wrap;
    }
</style>

{{-- Tab navigation --}}
<div class="tabs">
    <a href="{{ route('student.assignments.index', ['tab' => 'pending']) }}" class="tab-link {{ !$submission ? 'active' : '' }}">
        Pending ({{ $pendingCount }})
    </a>
    <a href="{{ route('student.assignments.index', ['tab' => 'submitted']) }}" class="tab-link {{ ($submission && $submission->status !== 'graded') ? 'active' : '' }}">
        Submitted ({{ $submittedCount }})
    </a>
    <a href="{{ route('student.assignments.index', ['tab' => 'graded']) }}" class="tab-link {{ ($submission && $submission->status === 'graded') ? 'active' : '' }}">
        Graded ({{ $gradedCount }})
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($assignment->due_date->isPast() && !$submission)
<div class="alert alert-warning">This assignment is overdue. Late submissions may not be accepted.</div>
@endif

{{-- Assignment details card --}}
<div class="card">
    <div class="assignment-header">
        <div class="assignment-title">{{ $assignment->title }}</div>
        @if($submission)
            @if($submission->status === 'graded')
                <span class="badge badge-graded">Graded</span>
            @else
                <span class="badge badge-submitted">Submitted</span>
            @endif
        @elseif($assignment->due_date->isPast())
            <span class="badge badge-overdue">Overdue</span>
        @else
            <span class="badge badge-not-submitted">Not Submitted</span>
        @endif
    </div>

    <div class="assignment-meta">
        @if($assignment->course)
        <span class="meta-item">
            <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/></svg>
            {{ $assignment->course->name }}
        </span>
        @endif
        <span class="meta-item">
            <svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5C3.89 4 3 4.9 3 6v14c0 1.1.89 2 2 2h14a2 2 0 0 0 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>
            Due: {{ $assignment->due_date->format('M d, Y') }}
        </span>
        <span class="meta-item">
            <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14l-5-5 1.41-1.41L12 14.17l7.59-7.59L21 8l-9 9z"/></svg>
            {{ $assignment->max_score }} Points
        </span>
    </div>

    @if($assignment->description)
    <div class="instructions-box">
        <div class="label">Instructions:</div>
        <p>{{ $assignment->description }}</p>
    </div>
    @endif

    @if($submission)
    {{-- Submitted / Graded state --}}
    <div class="submission-box">
        <p><strong style="color:#94a3b8;">Status:</strong>
            @if($submission->status === 'graded')
                <span style="color:#10b981;">Graded</span>
            @else
                <span style="color:#60a5fa;">Submitted – Pending Review</span>
            @endif
        </p>
        <p><strong style="color:#94a3b8;">Submitted On:</strong> {{ $submission->submitted_at->format('M d, Y h:i A') }}</p>

        @if($submission->status === 'graded' && $submission->score !== null)
        <p>
            <strong style="color:#94a3b8;">Your Score:</strong>
            <span class="score-display">{{ $submission->score }}/{{ $assignment->max_score }}</span>
            <span style="color:#64748b; font-size:14px;"> ({{ round(($submission->score / $assignment->max_score) * 100, 1) }}%)</span>
        </p>
        @endif

        @if($submission->content)
        <p><strong style="color:#94a3b8;">Your Answer:</strong></p>
        <div class="content-preview">{{ $submission->content }}</div>
        @endif

        @if($submission->file_path)
        <p style="margin-top:14px;">
            <strong style="color:#94a3b8;">Attached File:</strong>
            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="file-link">
                &#128206; Download Your Submission
            </a>
        </p>
        @endif
    </div>
    @endif
</div>

@if(!$submission)
{{-- Submission form --}}
<form action="{{ route('student.submissions.store', $assignment) }}" method="POST" enctype="multipart/form-data" id="submitForm">
    @csrf
    <div class="card" style="padding-bottom: 0; border-bottom-left-radius: 0; border-bottom-right-radius: 0; border-bottom: none; margin-bottom: 0;">
        <div class="submit-section" style="padding: 0;">
            <h3>Submit Your Work</h3>

            <div class="drop-zone" id="dropZone">
                <svg viewBox="0 0 24 24"><path d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/></svg>
                <p>Click to browse or drag and drop your file here</p>
                <small>Supported formats: PDF, DOCX, PPTX, TXT, JPG, PNG (Max: 10MB)</small>
                <div class="file-selected" id="fileSelected"></div>
                <input type="file" name="file" id="fileInput" accept=".pdf,.doc,.docx,.pptx,.ppt,.txt,.jpg,.jpeg,.png">
            </div>

            <div class="comments-section">
                <label for="content">Additional Comments (Optional)</label>
                <textarea name="content" id="content" placeholder="Add any notes for your instructor here...">{{ old('content') }}</textarea>
            </div>
        </div>
    </div>

    <div class="form-actions" style="background:#1e2235; border-radius: 0 0 12px 12px; border: 1px solid rgba(255,255,255,0.06); border-top: none;">
        <a href="{{ route('student.assignments.index') }}" class="btn-cancel">Cancel</a>
        <button type="submit" class="btn-submit">
            <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            Submit Assignment
        </button>
    </div>
</form>

<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileSelected = document.getElementById('fileSelected');

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            fileSelected.style.display = 'block';
            fileSelected.textContent = '✓ ' + fileInput.files[0].name;
        }
    });

    dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('dragover'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            fileSelected.style.display = 'block';
            fileSelected.textContent = '✓ ' + e.dataTransfer.files[0].name;
        }
    });
</script>
@endif
@endsection
