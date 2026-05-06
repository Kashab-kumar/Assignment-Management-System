@extends('layouts.student')

@section('title', $exam->title)
@section('page-title', $exam->title)

@section('content')
<style>
    .assessment-shell { max-width: 980px; margin: 0 auto; }
    .card { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 22px; margin-bottom: 18px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .meta-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin-top: 16px; }
    .meta-item { border-radius: 10px; padding: 14px; background: #f8fafc; border: 1px solid #e5e7eb; }
    .meta-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 5px; }
    .meta-value { color: #1f2937; font-weight: 600; }
    .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .badge-exam { background: #ede9fe; color: #7c3aed; border: 1px solid #c4b5fd; }
    .badge-quiz { background: #fef3c7; color: #d97706; border: 1px solid #fcd34d; }
    .badge-test { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
    .question-list { display: grid; gap: 14px; }
    .question-card { border-radius: 12px; padding: 18px; background: #f8fafc; border: 1px solid #e5e7eb; }
    .question-head { display: flex; justify-content: space-between; gap: 12px; align-items: center; margin-bottom: 10px; }
    .question-number { color: #7c3aed; font-weight: 700; }
    .question-points { color: #64748b; font-size: 13px; }
    .question-text { color: #1f2937; line-height: 1.7; margin-bottom: 14px; white-space: pre-wrap; font-size: 15px; }
    .answer-input, .answer-textarea { width: 100%; border: 1px solid #d1d5db; border-radius: 10px; background: #ffffff; color: #1f2937; padding: 12px; font-size: 15px; transition: border-color 0.2s, box-shadow 0.2s; }
    .answer-input:focus, .answer-textarea:focus { outline: none; border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,0.1); }
    .answer-textarea { min-height: 200px; resize: vertical; font-family: inherit; line-height: 1.6; }
    .answer-input { height: 48px; }
    .actions { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-top: 18px; }
    .btn { display: inline-block; padding: 11px 18px; border-radius: 10px; text-decoration: none; border: 0; cursor: pointer; font-weight: 500; transition: background 0.2s; }
    .btn-primary { background: #7c3aed; color: #fff; }
    .btn-primary:hover { background: #6d28d9; }
    .btn-secondary { background: #f1f5f9; color: #475569; }
    .btn-secondary:hover { background: #e2e8f0; }
    .notice { padding: 12px 14px; border-radius: 10px; margin-bottom: 16px; }
    .notice-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .notice-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .readonly-note { color: #64748b; margin-top: 14px; font-size: 14px; }
    .review-grid { display: grid; gap: 14px; }
    .review-card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; background: #f8fafc; }
    .review-row { display: grid; gap: 10px; margin-top: 12px; }
    .review-item { padding: 12px 14px; border-radius: 10px; background: #ffffff; border: 1px solid #e5e7eb; }
    .review-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 6px; font-weight: 700; }
    .review-value { color: #1f2937; font-size: 15px; line-height: 1.6; white-space: pre-wrap; }
    .review-correct { border-color: #86efac; background: #f0fdf4; }
    .review-wrong { border-color: #fca5a5; background: #fef2f2; }

    /* Multiple choice options */
    .mcq-options { display: grid; gap: 10px; margin-top: 12px; }
    .mcq-option { display: flex; align-items: center; gap: 10px; padding: 12px 14px; background: #ffffff; border: 2px solid #e5e7eb; border-radius: 10px; cursor: pointer; transition: all 0.2s; }
    .mcq-option:hover { border-color: #7c3aed; background: #f5f3ff; }
    .mcq-option input[type="radio"] { width: 18px; height: 18px; accent-color: #7c3aed; cursor: pointer; }
    .mcq-option label { cursor: pointer; flex: 1; color: #1f2937; font-size: 15px; }
    .mcq-option.selected { border-color: #7c3aed; background: #ede9fe; }

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
    $canReviewAnswers = $hasSubmittedAnswers || $existingResult !== null || now()->greaterThan($examStartsAt->copy()->addMinutes($exam->duration_minutes));
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
                <a href="{{ route('student.modules.show', $exam->module_id) }}?tab=exams" class="btn btn-secondary" style="margin-bottom:12px;">← Back to Exams</a>
                <h2 style="margin:0 0 8px 0; color:#1f2937;">{{ $exam->title }}</h2>
                <div style="color: #64748b; font-size:18px;">{{ $exam->course?->name ?? 'General Course' }}</div>
            </div>
            <span class="badge badge-{{ $exam->type }}">{{ $typeLabel }}</span>
        </div>

        <div class="meta-grid">
            <div class="meta-item">
                <div class="meta-label">Date</div>
                <div class="meta-value">{{ $exam->exam_date->format('d/m/Y') }}</div>
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

        <p style="margin:16px 0 0 0; color: #4b5563; line-height:1.7;">{{ $exam->description ?: 'No description has been provided for this assessment yet.' }}</p>
    </div>

    <div class="card">
        <h3 style="margin:0 0 14px 0; color:#1f2937;">Answer Sheet</h3>

        @if($exam->questions->isEmpty())
            <p style="margin:0; color: #64748b;">Your teacher has not added any questions yet.</p>
        @else
            <form id="examSubmitForm" method="POST" action="{{ route('student.exams.submit', $exam) }}" onsubmit="handleExamSubmit(event)">
                @csrf

                <div class="question-list">
                    @foreach($exam->questions as $question)
                        @php
                            $savedAnswer = old('answers.' . $question->id, $answers->get($question->id)?->answer_text);
                            $isLongAnswer = $question->question_type === 'long_answer';
                            $isMultipleChoice = $question->question_type === 'multiple_choice';

                            // Parse multiple choice options if available
                            $options = [];
                            $correctAnswer = '';
                            if ($isMultipleChoice && !empty($question->answer_key)) {
                                // New format: correct_answer|option1|option2|option3...
                                $parts = array_map('trim', explode('|', $question->answer_key));
                                if (count($parts) > 1) {
                                    $correctAnswer = $parts[0];
                                    $options = array_slice($parts, 1);
                                } else {
                                    // Old format: option1|option2|option3... (first is correct)
                                    $options = $parts;
                                    $correctAnswer = $parts[0] ?? '';
                                }
                            }
                        @endphp
                        <div class="question-card">
                            <div class="question-head">
                                <div class="question-number">Question {{ $question->position }}</div>
                                <div class="question-points">{{ $question->points }} point{{ $question->points === 1 ? '' : 's' }} · {{ $isLongAnswer ? 'Long answer' : ($isMultipleChoice ? 'Multiple choice' : 'Short answer') }}</div>
                            </div>
                            <div class="question-text">{{ $question->question_text }}</div>

                            @if($isMultipleChoice && !empty($options))
                                <div class="mcq-options">
                                    @foreach($options as $index => $option)
                                        <label class="mcq-option {{ $savedAnswer === $option ? 'selected' : '' }}">
                                            <input
                                                type="radio"
                                                name="answers[{{ $question->id }}]"
                                                value="{{ $option }}"
                                                {{ ($canAnswerNow && !$hasSubmittedAnswers) ? '' : 'disabled' }}
                                                {{ $savedAnswer === $option ? 'checked' : '' }}
                                            >
                                            <span>{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif($isLongAnswer)
                                <textarea
                                    name="answers[{{ $question->id }}]"
                                    class="answer-textarea"
                                    placeholder="Type your answer here... (Press Enter for new line)"
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

    @if($canReviewAnswers)
    <div class="card">
        <h3 style="margin:0 0 14px 0; color:#1f2937;">Review Answers</h3>

        <div class="review-grid">
            @foreach($exam->questions as $question)
                @php
                    $studentAnswer = $answers->get($question->id)?->answer_text;
                    $isMultipleChoice = $question->question_type === 'multiple_choice';
                    $correctAnswer = trim((string) ($question->answer_key ?? ''));
                    $displayCorrectAnswer = $correctAnswer;
                    $options = [];

                    if ($isMultipleChoice && $correctAnswer !== '') {
                        $parts = array_map('trim', explode('|', $correctAnswer));
                        if (count($parts) > 1) {
                            $displayCorrectAnswer = $parts[0] ?? '';
                            $options = array_slice($parts, 1);
                        }
                    }

                    $isCorrect = $answers->get($question->id)?->is_correct;
                @endphp

                <div class="review-card">
                    <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:flex-start;">
                        <div>
                            <div class="question-number">Question {{ $question->position }}</div>
                            <div class="question-points" style="margin-top:6px;">{{ $question->points }} point{{ $question->points === 1 ? '' : 's' }}</div>
                        </div>
                        @if($isCorrect === true)
                            <span class="badge" style="background:#dcfce7; color:#166534; border:1px solid #86efac;">Correct</span>
                        @elseif($isCorrect === false)
                            <span class="badge" style="background:#fee2e2; color:#991b1b; border:1px solid #fca5a5;">Incorrect</span>
                        @endif
                    </div>

                    <div class="question-text" style="margin-top:12px; margin-bottom:0;">{{ $question->question_text }}</div>

                    <div class="review-row">
                        <div class="review-item {{ $isCorrect === true ? 'review-correct' : ($isCorrect === false ? 'review-wrong' : '') }}">
                            <div class="review-label">Your Answer</div>
                            <div class="review-value">{{ filled($studentAnswer) ? $studentAnswer : 'No answer submitted' }}</div>
                        </div>

                        <div class="review-item review-correct">
                            <div class="review-label">Correct Answer</div>
                            <div class="review-value">
                                @if($isMultipleChoice && !empty($options))
                                    {{ $displayCorrectAnswer }}
                                @else
                                    {{ $displayCorrectAnswer !== '' ? $displayCorrectAnswer : 'No answer key provided' }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
    async function handleExamSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('examSubmitForm');
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                // Use window.location.replace() instead of navigate() to avoid adding to history
                // This prevents the exam detail page from appearing in browser back button
                window.location.replace('{{ route("student.modules.show", $exam->module_id) }}?tab=exams&success=submitted');
            } else {
                // If there's an error, do a normal form submission (adds to history)
                form.submit();
            }
        } catch (error) {
            console.error('Submission error:', error);
            // On error, do a normal form submission
            form.submit();
        }
    }

    // Also replace history on page load to prevent back button issues
    document.addEventListener('DOMContentLoaded', function() {
        window.history.replaceState(
            { page: 'exam_detail', exam_id: {{ $exam->id }} },
            document.title,
            window.location.href
        );
    });
</script>
@endsection
