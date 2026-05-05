@extends('layouts.teacher')

@section('title', $exam->title)
@section('page-title', $exam->title)

@section('content')
<style>
    .card {
        --text-strong: #0f172a;
        --text-muted: #334155;
        background: #fff;
        color: var(--text-strong);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; color: var(--text-strong); }
    th { background: #f8f8f8; }
    .meta-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; }
    .meta-item { padding: 12px; border-radius: 8px; background: #f8fafc; color: var(--text-strong); }
    .meta-item strong { display: block; margin-bottom: 4px; color: #475569; font-size: 12px; text-transform: uppercase; }
    .question-list { display: grid; gap: 12px; }
    .question-item { border: 1px solid #e5e7eb; border-radius: 10px; padding: 14px; background: #f8fafc; color: var(--text-strong); }
    .question-meta { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 8px; }
    .badge { display: inline-block; padding: 4px 8px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .badge-exam { background: #ede7f6; color: #5e35b1; }
    .badge-quiz { background: #fff3e0; color: #f57c00; }
    .badge-test { background: #fee2e2; color: #b91c1c; }
    .badge-answer { background: #e0f2fe; color: #0369a1; }
    .answer-sheet { border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px; margin-bottom: 14px; color: var(--text-strong); }
    .answer-body { margin-top: 10px; padding: 12px; background: #f8fafc; border-radius: 8px; white-space: pre-wrap; color: var(--text-strong); }
    label { color: var(--text-strong); }
    input, select { color: var(--text-strong); background: #fff; }
    input::placeholder { color: var(--text-muted); }

    @media (max-width: 900px) {
        .meta-grid { grid-template-columns: 1fr 1fr; }
    }
</style>

@php
    $typeLabels = ['exam' => 'Exam', 'quiz' => 'Quiz', 'test' => 'Test'];
@endphp

@if(session('success'))
    <div style="padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 16px;">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div style="padding: 12px; background: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 16px;">{{ $errors->first() }}</div>
@endif

<div class="card">
    <div style="display:flex; justify-content:flex-end; margin-bottom: 10px;">
        <a href="{{ route('teacher.exams.edit', $exam) }}" style="padding:8px 14px; background:#4CAF50; color:#fff; border-radius:4px; text-decoration:none;">Edit Assessment</a>
    </div>
    <h2 style="margin-bottom: 16px;">Assessment Details</h2>
    <div class="meta-grid">
        <div class="meta-item">
            <strong>Type</strong>
            <span class="badge badge-{{ $exam->type }}">{{ $typeLabels[$exam->type] ?? ucfirst($exam->type) }}</span>
        </div>
        <div class="meta-item">
            <strong>Date</strong>
            <span>{{ $exam->exam_date->format('F d, Y') }}</span>
        </div>
        <div class="meta-item">
            <strong>Max Score</strong>
            <span>{{ $exam->max_score }}</span>
        </div>
        <div class="meta-item">
            <strong>Questions</strong>
            <span>{{ $exam->questions->count() }}</span>
        </div>
    </div>
    <p style="margin-top:16px;"><strong>Description:</strong> {{ $exam->description ?: 'No description' }}</p>
</div>

<div class="card">
    <h2 style="margin-bottom: 12px;">Questions</h2>

    <div class="question-list">
        @foreach($exam->questions as $question)
            <div class="question-item">
                <div class="question-meta">
                    <span class="badge badge-answer">Question {{ $question->position }}</span>
                    <span class="badge badge-answer">{{ $question->question_type === 'long_answer' ? 'Long Answer' : 'Short Answer' }}</span>
                    <span class="badge badge-answer">{{ $question->points }} Point{{ $question->points === 1 ? '' : 's' }}</span>
                </div>
                <div>{{ $question->question_text }}</div>
            </div>
        @endforeach
    </div>
</div>

<div class="card">
    <h2 style="margin-bottom: 12px;">Add / Update Student Score</h2>
    <form method="POST" action="{{ route('teacher.exams.results.upsert', $exam) }}" style="display: grid; grid-template-columns: 1fr 120px 1fr auto; gap: 10px; align-items: end;">
        @csrf
        <div>
            <label style="display:block; margin-bottom:4px;">Student</label>
            <select name="student_id" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                <option value="">Select student</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->student_id }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block; margin-bottom:4px;">Score</label>
            <input type="number" name="score" min="0" max="{{ $exam->max_score }}" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
        </div>
        <div>
            <label style="display:block; margin-bottom:4px;">Remarks</label>
            <input name="remarks" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;" placeholder="Optional remarks">
        </div>
        <button type="submit" style="padding:9px 14px; border:0; border-radius:4px; background:#2196F3; color:#fff; cursor:pointer;">Save</button>
    </form>
</div>

<div class="card">
    <h2 style="margin-bottom: 12px;">Submitted Answers</h2>

    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #e5e7eb; background:#f8fafc;">Student</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #e5e7eb; background:#f8fafc;">Student ID</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #e5e7eb; background:#f8fafc;">Answers</th>
                <th style="padding:10px; text-align:left; border-bottom:2px solid #e5e7eb; background:#f8fafc;">Status</th>
                <th style="padding:10px; text-align:center; border-bottom:2px solid #e5e7eb; background:#f8fafc;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($answerSheets as $answers)
                @php
                    $firstAnswer = $answers->first();
                    $student = $firstAnswer?->student;
                    $unverifiedCount = $answers->filter(fn($a) => $a->is_correct === null)->count();
                    $correctCount = $answers->filter(fn($a) => $a->is_correct === true)->count();
                    $incorrectCount = $answers->filter(fn($a) => $a->is_correct === false)->count();
                    $studentId = 'student-' . $student?->id . '-' . md5($exam->id);
                @endphp
                <tr style="border-bottom:1px solid #e5e7eb;">
                    <td style="padding:12px; color:var(--text-strong);">{{ $student?->name }}</td>
                    <td style="padding:12px; color:var(--text-strong);">{{ $student?->student_id }}</td>
                    <td style="padding:12px; color:var(--text-strong);">{{ $answers->count() }}/{{ $exam->questions->count() }}</td>
                    <td style="padding:12px;">
                        @if($unverifiedCount > 0)
                            <span style="color:#ea580c; font-weight:700;">{{ $unverifiedCount }} Unverified</span>
                        @elseif($incorrectCount > 0)
                            <span style="color:#dc2626; font-weight:700;">{{ $incorrectCount }} Incorrect</span>
                        @else
                            <span style="color:#16a34a; font-weight:700;">All Verified</span>
                        @endif
                    </td>
                    <td style="padding:12px; text-align:center;">
                        <button type="button" onclick="toggleAnswers('{{ $studentId }}')" style="padding:6px 12px; background:#2196F3; color:#fff; border:0; border-radius:4px; cursor:pointer; font-size:14px;">
                            View
                        </button>
                    </td>
                </tr>
                <tr style="display:none;" id="details-{{ $studentId }}">
                    <td colspan="5" style="padding:20px; background:#f8fafc; border-bottom:1px solid #e5e7eb;">
                        <div style="margin-bottom:16px;">
                            <h4 style="margin:0 0 12px 0; color:var(--text-strong);">Answers for {{ $student?->name }}</h4>
                            @foreach($answers->sortBy('question.position') as $answer)
                                <div style="margin-bottom:16px; padding:12px; background:#fff; border-radius:8px; border:1px solid #e5e7eb; display:flex; gap:12px; align-items:flex-start;">
                                    <div style="flex:1;">
                                        <div style="font-weight:700; margin-bottom:8px; color:var(--text-strong);">Question {{ $answer->question?->position }}: {{ $answer->question?->question_text }}</div>
                                        <div style="padding:10px; background:#f8fafc; border-radius:6px; border-left:3px solid #2196F3; white-space:pre-wrap; color:var(--text-strong);">{{ $answer->answer_text }}</div>
                                    </div>
                                    <div style="width:220px; flex-shrink:0;">
                                        <div style="margin-bottom:10px;">
                                            <div style="font-size:12px; color:#6b7280; font-weight:700; margin-bottom:4px;">CORRECTNESS</div>
                                            <div>
                                                @if($answer->is_correct === true)
                                                    <span style="color:#16a34a; font-weight:700;">✓ Correct</span>
                                                @elseif($answer->is_correct === false)
                                                    <span style="color:#dc2626; font-weight:700;">✗ Incorrect</span>
                                                @else
                                                    <span style="color:#6b7280; font-weight:700;">○ Unverified</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div style="display:flex; gap:4px; flex-direction:column;">
                                            <form method="POST" action="{{ route('teacher.exam-answers.verify', $answer) }}" style="margin:0;">
                                                @csrf
                                                <input type="hidden" name="is_correct" value="1">
                                                <button type="submit" style="width:100%; padding:6px 8px; background:#10b981; color:#fff; border:0; border-radius:4px; cursor:pointer; font-weight:600; font-size:12px;">✓ Mark Correct</button>
                                            </form>
                                            <form method="POST" action="{{ route('teacher.exam-answers.verify', $answer) }}" style="margin:0;">
                                                @csrf
                                                <input type="hidden" name="is_correct" value="0">
                                                <button type="submit" style="width:100%; padding:6px 8px; background:#ef4444; color:#fff; border:0; border-radius:4px; cursor:pointer; font-weight:600; font-size:12px;">✗ Mark Incorrect</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="padding:12px; text-align:center; color:#6b7280;">No student answers have been submitted yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function toggleAnswers(studentId) {
        const detailRow = document.getElementById('details-' + studentId);
        if (detailRow) {
            const isHidden = detailRow.style.display === 'none';
            detailRow.style.display = isHidden ? 'table-row' : 'none';
        }
    }
</script>

<div class="card">
    <h2 style="margin-bottom: 12px;">Assessment Results</h2>
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Student ID</th>
                <th>Score</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($results as $result)
                <tr>
                    <td>{{ $result->student->name }}</td>
                    <td>{{ $result->student->student_id }}</td>
                    <td>{{ $result->score }}/{{ $exam->max_score }}</td>
                    <td>{{ $result->remarks ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No assessment results yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
