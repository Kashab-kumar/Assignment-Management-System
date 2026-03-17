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

    @forelse($answerSheets as $answers)
        @php
            $firstAnswer = $answers->first();
            $student = $firstAnswer?->student;
        @endphp
        <div class="answer-sheet">
            <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:10px;">
                <div>
                    <strong>{{ $student?->name }}</strong>
                    <div style="color:#334155;">{{ $student?->student_id }}</div>
                </div>
                <div style="color:#334155;">{{ $answers->count() }} answer{{ $answers->count() === 1 ? '' : 's' }} submitted</div>
            </div>

            @foreach($answers->sortBy('question.position') as $answer)
                <div style="margin-top:12px;">
                    <div style="font-weight:700; margin-bottom:6px;">Question {{ $answer->question?->position }}: {{ $answer->question?->question_text }}</div>
                    <div class="answer-body">{{ $answer->answer_text }}</div>
                </div>
            @endforeach
        </div>
    @empty
        <p style="color:#334155; margin:0;">No student answers have been submitted yet.</p>
    @endforelse
</div>

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
