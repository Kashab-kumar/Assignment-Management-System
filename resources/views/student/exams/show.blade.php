@extends('layouts.student')

@section('title', $exam->title)
@section('page-title', $exam->title)

@section('content')
<style>
    .assessment-shell { max-width: 980px; margin: 0 auto; }
    .card { background: #1e2235; border: 1px solid rgba(255,255,255,0.06); border-radius: 14px; padding: 22px; margin-bottom: 18px; }
    .meta-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin-top: 16px; }
    .meta-item { border-radius: 10px; padding: 14px; background: rgba(0,0,0,0.18); border: 1px solid rgba(255,255,255,0.06); }
    .meta-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 5px; }
    .meta-value { color: #f8fafc; font-weight: 600; }
    .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .badge-exam { background: rgba(124,58,237,0.18); color: #a78bfa; border: 1px solid rgba(124,58,237,0.32); }
    .badge-quiz { background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.25); }
    .badge-test { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.28); }
    .question-list { display: grid; gap: 14px; }
    .question-card { border-radius: 12px; padding: 18px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); }
    .question-head { display: flex; justify-content: space-between; gap: 12px; align-items: center; margin-bottom: 10px; }
    .question-number { color: #f8fafc; font-weight: 700; }
    .question-points { color: #94a3b8; font-size: 13px; }
    .question-text { color: #cbd5e1; line-height: 1.7; margin-bottom: 14px; white-space: pre-wrap; }
    .answer-input, .answer-textarea { width: 100%; border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; background: rgba(0,0,0,0.2); color: #f8fafc; padding: 12px; }
    .answer-textarea { min-height: 180px; resize: vertical; }
    .actions { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-top: 18px; }
    .btn { display: inline-block; padding: 11px 18px; border-radius: 10px; text-decoration: none; border: 0; cursor: pointer; }
    .btn-primary { background: #7c3aed; color: #fff; }
    .btn-secondary { background: rgba(255,255,255,0.08); color: #e2e8f0; }
    .notice { padding: 12px 14px; border-radius: 10px; margin-bottom: 16px; }
    .notice-success { background: rgba(16,185,129,0.15); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.25); }
    .notice-error { background: rgba(239,68,68,0.16); color: #fca5a5; border: 1px solid rgba(239,68,68,0.25); }
    .readonly-note { color: #94a3b8; margin-top: 14px; }

    @media (max-width: 900px) {
        .meta-grid { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 640px) {
        .meta-grid { grid-template-columns: 1fr; }
    }
</style>

@php
    $typeLabels = ['exam' => 'Exam', 'quiz' => 'Quiz', 'test' => 'Test'];
    $typeLabel = $typeLabels[$exam->type] ?? ucfirst($exam->type);
    $examStartsAt = $exam->exam_date->copy()->startOfDay();
    if ($exam->exam_time) {
        [$hours, $minutes] = array_pad(explode(':', $exam->exam_time), 2, 0);
        $examStartsAt = $exam->exam_date->copy()->setTime((int) $hours, (int) $minutes, 0);
    }
    $canAnswerNow = now()->greaterThanOrEqualTo($examStartsAt);
    $isLive = now()->isSameDay($examStartsAt);
    $startTimeText = $exam->exam_time
        ? \Illuminate\Support\Carbon::createFromFormat('H:i:s', strlen($exam->exam_time) === 5 ? $exam->exam_time . ':00' : $exam->exam_time)->format('h:i A')
        : '12:00 AM';
    $hasSubmittedAnswers = $answers->isNotEmpty();
@endphp

<div class="assessment-shell">
    @if(session('success'))
        <div class="notice notice-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="notice notice-error">
            <strong>Please fix the following:</strong>
            <ul style="margin:8px 0 0 18px; color:inherit;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div style="display:flex; justify-content:space-between; gap:14px; align-items:flex-start; flex-wrap:wrap;">
            <div>
                <a href="{{ route('student.exams.index') }}" class="btn btn-secondary" style="margin-bottom:12px;">← Back to Assessments</a>
                <h2 style="margin:0 0 8px 0; color:#f8fafc;">{{ $exam->title }}</h2>
                <div style="color:#94a3b8; font-size:18px;">{{ $exam->course?->name ?? 'General Course' }}</div>
            </div>
            <span class="badge badge-{{ $exam->type }}">{{ $typeLabel }}</span>
        </div>

        <div class="meta-grid">
            <div class="meta-item">
                <div class="meta-label">Date</div>
                <div class="meta-value">{{ $exam->exam_date->format('M d, Y') }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Start Time</div>
                <div class="meta-value">{{ $startTimeText }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Questions</div>
                <div class="meta-value">{{ $exam->questions->count() }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Total Marks</div>
                <div class="meta-value">{{ $exam->max_score }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Score</div>
                <div class="meta-value">{{ $existingResult ? ($existingResult->score . '/' . $exam->max_score) : 'Not graded yet' }}</div>
            </div>
        </div>

        <p style="margin:16px 0 0 0; color:#cbd5e1; line-height:1.7;">{{ $exam->description ?: 'No description has been provided for this assessment yet.' }}</p>
    </div>

    <div class="card">
        <h3 style="margin:0 0 14px 0; color:#f8fafc;">Answer Sheet</h3>

        @if($exam->questions->isEmpty())
            <p style="margin:0; color:#94a3b8;">Your teacher has not added any questions yet.</p>
        @else
            <form method="POST" action="{{ route('student.exams.submit', $exam) }}">
                @csrf

                <div class="question-list">
                    @foreach($exam->questions as $question)
                        @php
                            $savedAnswer = old('answers.' . $question->id, $answers->get($question->id)?->answer_text);
                            $isLongAnswer = $question->question_type === 'long_answer';
                        @endphp
                        <div class="question-card">
                            <div class="question-head">
                                <div class="question-number">Question {{ $question->position }}</div>
                                <div class="question-points">{{ $question->points }} point{{ $question->points === 1 ? '' : 's' }} · {{ $isLongAnswer ? 'Long answer' : 'Short answer' }}</div>
                            </div>
                            <div class="question-text">{{ $question->question_text }}</div>

                            @if($isLongAnswer)
                                <textarea
                                    name="answers[{{ $question->id }}]"
                                    class="answer-textarea"
                                    placeholder="Type your answer here..."
                                    {{ ($canAnswerNow && !$hasSubmittedAnswers) ? '' : 'readonly' }}
                                >{{ $savedAnswer }}</textarea>
                            @else
                                <input
                                    type="text"
                                    name="answers[{{ $question->id }}]"
                                    class="answer-input"
                                    value="{{ $savedAnswer }}"
                                    placeholder="Type your answer here..."
                                    {{ ($canAnswerNow && !$hasSubmittedAnswers) ? '' : 'readonly' }}
                                >
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="actions">
                    <div class="readonly-note">
                        @if($hasSubmittedAnswers)
                            You already submitted this assessment. Answers are now read-only.
                        @elseif($canAnswerNow)
                            You can submit or update your answers for this assessment.
                        @else
                            This assessment has not started yet. Answer fields will open on the scheduled date and time.
                        @endif
                    </div>

                    @if($canAnswerNow && !$hasSubmittedAnswers)
                        <button type="submit" class="btn btn-primary">Submit Answers</button>
                    @endif
                </div>
            </form>
        @endif
    </div>
</div>
@endsection