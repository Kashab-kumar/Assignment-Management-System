@extends('layouts.teacher')

@section('title', 'Grade Submission')
@section('page-title', 'Grade Submission')

@section('content')
<style>
    .layout { display: grid; grid-template-columns: 1.3fr .9fr; gap: 18px; }
    @media (max-width: 980px) { .layout { grid-template-columns: 1fr; } }
    .panel { background: #fff; border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 18px; padding: 20px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05); }
    .hero { background: linear-gradient(135deg, #0f172a 0%, #111827 100%); color: #fff; }
    .hero h1 { margin: 0; font-size: 28px; }
    .hero p { margin: 8px 0 0; color: #cbd5e1; }
    .nav-links { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 14px; }
    .btn { display: inline-flex; align-items: center; justify-content: center; padding: 11px 16px; border-radius: 10px; text-decoration: none; border: 0; font-weight: 700; cursor: pointer; }
    .btn-primary { background: #2563eb; color: #fff; }
    .btn-secondary { background: #e2e8f0; color: #0f172a; }
    .btn-success { background: #16a34a; color: #fff; }
    .meta-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin-top: 16px; }
    .meta-box { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px; }
    .meta-box strong { display: block; margin-bottom: 6px; color: #475569; font-size: 12px; text-transform: uppercase; }
    .answer-box { margin-top: 18px; border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden; background: #f8fafc; }
    .answer-head { padding: 12px 14px; background: #eff6ff; border-bottom: 1px solid #dbeafe; font-weight: 700; color: #1d4ed8; }
    .answer-body { padding: 14px; color: #0f172a; white-space: pre-wrap; line-height: 1.7; }
    .preview-img, .preview-frame { width: 100%; border: 0; border-radius: 12px; }
    .preview-img { max-height: 420px; object-fit: contain; background: #fff; }
    .grading-panel { position: sticky; top: 18px; }
    @media (max-width: 980px) { .grading-panel { position: static; } }
    label { display: block; margin-bottom: 6px; color: #334155; font-size: 13px; font-weight: 700; }
    input, select, textarea { width: 100%; border: 1px solid #dbe3ee; border-radius: 10px; padding: 11px 12px; font-size: 14px; color: #0f172a; background: #fff; }
    textarea { min-height: 120px; resize: vertical; }
    .rubric-list { display: grid; gap: 10px; margin-top: 14px; }
    .rubric-item { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px; }
    .rubric-item strong { display: block; margin-bottom: 4px; color: #0f172a; }
    .muted { color: #64748b; font-size: 13px; }
    .alert { padding: 12px 14px; border-radius: 12px; margin-bottom: 16px; }
    .alert-success { background: #dcfce7; color: #166534; }
    .alert-error { background: #fee2e2; color: #991b1b; }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<div class="panel hero" style="margin-bottom:18px;">
    <h1>{{ $submission->assignment->title }}</h1>
    <p>{{ $submission->student->name }} - {{ $submission->student->student_id ?? 'No ID' }}</p>
    <div class="nav-links">
        <a href="{{ route('teacher.grading.assignments.submissions', $submission->assignment) }}" class="btn btn-secondary">Back to Submissions</a>
        @if($previousSubmission)
            <a href="{{ route('teacher.grading.submissions.show', $previousSubmission) }}" class="btn btn-secondary">Previous Student</a>
        @endif
        @if($nextSubmission)
            <a href="{{ route('teacher.grading.submissions.show', $nextSubmission) }}" class="btn btn-secondary">Next Student</a>
        @endif
    </div>
</div>

<div class="layout">
    <div class="panel">
        <h2 style="margin-top:0; color:#0f172a;">Submission Details</h2>
        <div class="meta-grid">
            <div class="meta-box"><strong>Student</strong>{{ $submission->student->name }}</div>
            <div class="meta-box"><strong>Student ID</strong>{{ $submission->student->student_id ?? '-' }}</div>
            <div class="meta-box"><strong>Submitted At</strong>{{ optional($submission->submitted_at)->format('d M Y h:i A') ?? '-' }}</div>
            <div class="meta-box"><strong>Status</strong>{{ ucfirst($submission->status ?? 'pending') }}</div>
        </div>

        @if($submission->content)
            <div class="answer-box">
                <div class="answer-head">Written Answer</div>
                <div class="answer-body">{{ $submission->content }}</div>
            </div>
        @endif

        @if($submission->file_path)
            <div class="answer-box">
                <div class="answer-head">Uploaded File</div>
                <div class="answer-body">
                    @php $fileUrl = asset('storage/' . $submission->file_path); $ext = strtolower(pathinfo($submission->file_path, PATHINFO_EXTENSION)); @endphp
                    @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']))
                        <img src="{{ $fileUrl }}" alt="Submission preview" class="preview-img">
                    @elseif($ext === 'pdf')
                        <iframe src="{{ $fileUrl }}" class="preview-frame" style="height:420px;"></iframe>
                    @else
                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-primary">Download File</a>
                    @endif
                </div>
            </div>
        @endif

        <div class="rubric-list">
            <h3 style="margin:0; color:#0f172a;">Rubric</h3>
            @forelse($submission->assignment->unit?->grading_criteria ?? [] as $criterion)
                <div class="rubric-item">
                    <strong>{{ $criterion['topic'] ?? $criterion['name'] ?? 'Criterion' }}</strong>
                    <div class="muted">{{ $criterion['description'] ?? 'No rubric description available.' }}</div>
                </div>
            @empty
                <div class="rubric-item">
                    <strong>General grading guidance</strong>
                    <div class="muted">Grade based on the assignment instructions, correctness, and completeness.</div>
                </div>
            @endforelse
        </div>
    </div>

    <div class="panel grading-panel">
        <h2 style="margin-top:0; color:#0f172a;">Grading Panel</h2>
        <form method="POST" action="{{ route('teacher.grading.submissions.update', $submission) }}" id="gradingForm" style="display:grid; gap:14px;">
            @csrf
            <div>
                <label for="score">Marks</label>
                <input id="score" type="number" name="score" min="0" max="{{ $submission->assignment->max_score }}" value="{{ old('score', $submission->score ?? '') }}" required>
            </div>
            <div>
                <label for="grade">Grade</label>
                @php $gradeValue = old('grade', $submission->grade ?? ''); @endphp
                <select id="grade" name="grade">
                    @foreach(['A+','A','A-','B+','B','B-','C+','C','D','F'] as $gradeOption)
                        <option value="{{ $gradeOption }}" @selected($gradeValue === $gradeOption)>{{ $gradeOption }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="feedback">Feedback</label>
                <textarea id="feedback" name="feedback" placeholder="Add feedback for the student...">{{ old('feedback', $submission->feedback ?? '') }}</textarea>
            </div>
            <div class="muted">Saved grades update the submission status automatically when published.</div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" name="publish_action" value="draft" class="btn btn-secondary">Save Draft</button>
                <button type="submit" name="publish_action" value="publish" class="btn btn-success">Publish Grade</button>
            </div>
        </form>
    </div>
</div>
@endsection
