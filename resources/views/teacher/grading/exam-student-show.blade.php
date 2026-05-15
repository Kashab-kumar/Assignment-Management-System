@extends('layouts.teacher')

@section('title', 'Grade Exam Student')
@section('page-title', 'Grade Exam Student')

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
    .answer-list { display: grid; gap: 12px; margin-top: 18px; }
    .answer-item { border: 1px solid #e5e7eb; border-radius: 14px; padding: 14px; background: #f8fafc; }
    .answer-meta { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 8px; }
    .badge { display: inline-flex; align-items: center; padding: 5px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; background: #dbeafe; color: #1d4ed8; }
    .answer-text { color: #0f172a; white-space: pre-wrap; line-height: 1.7; }
    .grading-panel { position: sticky; top: 18px; }
    @media (max-width: 980px) { .grading-panel { position: static; } }
    label { display: block; margin-bottom: 6px; color: #334155; font-size: 13px; font-weight: 700; }
    input, select, textarea { width: 100%; border: 1px solid #dbe3ee; border-radius: 10px; padding: 11px 12px; font-size: 14px; color: #0f172a; background: #fff; }
    textarea { min-height: 120px; resize: vertical; }
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
    <h1>{{ $exam->title }}</h1>
    <p>{{ $student->name }} - {{ $student->student_id ?? 'No ID' }}</p>
    <div class="nav-links">
        <a href="{{ route('teacher.grading.exams.submissions', $exam) }}" class="btn btn-secondary">Back to Exam Submissions</a>
        @if($previousStudent)
            <a href="{{ route('teacher.grading.exam-students.show', [$exam, $previousStudent]) }}" class="btn btn-secondary">Previous Student</a>
        @endif
        @if($nextStudent)
            <a href="{{ route('teacher.grading.exam-students.show', [$exam, $nextStudent]) }}" class="btn btn-secondary">Next Student</a>
        @endif
    </div>
</div>

<div class="layout">
    <div class="panel">
        <h2 style="margin-top:0; color:#0f172a;">Student Submission</h2>
        <div class="meta-grid">
            <div class="meta-box"><strong>Student</strong>{{ $student->name }}</div>
            <div class="meta-box"><strong>Student ID</strong>{{ $student->student_id ?? '-' }}</div>
            <div class="meta-box"><strong>Questions Submitted</strong>{{ $answers->count() }}</div>
            <div class="meta-box"><strong>Status</strong>{{ $result ? 'Graded' : ($answers->count() ? 'Submitted' : 'Missing') }}</div>
        </div>

        <div class="answer-list">
            @forelse($answers as $answer)
                <div class="answer-item">
                    <div class="answer-meta">
                        <span class="badge">Question {{ $answer->question?->position ?? '-' }}</span>
                        <span class="badge">{{ $answer->question?->question_type ?? 'Answer' }}</span>
                    </div>
                    <div style="font-weight:700; color:#0f172a; margin-bottom:8px;">{{ $answer->question?->question_text }}</div>
                    <div class="answer-text">{{ $answer->answer_text ?: 'No answer submitted.' }}</div>
                </div>
            @empty
                <div class="answer-item">No answers submitted for this exam.</div>
            @endforelse
        </div>
    </div>

    <div class="panel grading-panel">
        <h2 style="margin-top:0; color:#0f172a;">Grading Panel</h2>
        <form method="POST" action="{{ route('teacher.grading.exam-students.update', [$exam, $student]) }}" style="display:grid; gap:14px;">
            @csrf
            <div>
                <label for="score">Marks</label>
                <input id="score" type="number" name="score" min="0" max="{{ $exam->max_score }}" value="{{ old('score', $result->score ?? '') }}" required>
            </div>
            <div>
                <label for="grade">Grade</label>
                <select id="grade" name="grade">
                    @php $gradeValue = old('grade', $result->grade ?? ''); @endphp
                    @foreach(['A+','A','A-','B+','B','B-','C+','C','D','F'] as $gradeOption)
                        <option value="{{ $gradeOption }}" @selected($gradeValue === $gradeOption)>{{ $gradeOption }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="feedback">Feedback</label>
                <textarea id="feedback" name="feedback" placeholder="Add comments for the student...">{{ old('feedback', $result->feedback ?? $result->remarks ?? '') }}</textarea>
            </div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" name="publish_action" value="draft" class="btn btn-secondary">Save Draft</button>
                <button type="submit" name="publish_action" value="publish" class="btn btn-success">Publish Grade</button>
            </div>
        </form>
    </div>
</div>
@endsection
