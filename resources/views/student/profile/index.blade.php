@extends('layouts.student')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<style>
    .grid { display:grid; grid-template-columns: 1fr 1fr; gap:16px; }
    .card {
        background: #1e2235;
        padding: 22px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.06);
    }
    .card h2 { color: #f1f5f9; }
    .row {
        margin-bottom: 12px;
        padding: 10px 12px;
        background: rgba(0,0,0,0.14);
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.04);
    }
    .row:last-child { margin-bottom: 0; }
    .label { color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em; }
    .value { font-size: 16px; font-weight: 700; color: #e2e8f0; margin-top: 2px; }
    .full-width { grid-column: 1 / -1; }
    .modules-list { display: grid; gap: 10px; }
    .module-item { padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08); background: rgba(0,0,0,0.14); }
    .module-title { font-size: 14px; font-weight: 700; color: #f1f5f9; }
    .module-desc { color: #94a3b8; font-size: 13px; margin-top: 4px; }
    .module-meta { display: flex; gap: 6px; margin-top: 8px; flex-wrap: wrap; }
    .module-chip { font-size: 11px; color: #cbd5e1; background: rgba(124,58,237,0.18); border: 1px solid rgba(124,58,237,0.3); padding: 3px 8px; border-radius: 999px; }
    .module-content-list { display: grid; gap: 8px; margin-top: 10px; }
    .module-content-item { padding: 10px; border-radius: 8px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); }
    .module-content-head { display: flex; justify-content: space-between; gap: 8px; align-items: center; }
    .module-content-title { color: #f8fafc; font-size: 13px; font-weight: 700; }
    .module-content-type { font-size: 11px; color: #cbd5e1; background: rgba(124,58,237,0.18); border: 1px solid rgba(124,58,237,0.3); padding: 3px 8px; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.05em; }
    .module-content-body { margin-top: 6px; color: #94a3b8; font-size: 13px; line-height: 1.5; white-space: pre-line; }
    @media (max-width: 900px) { .grid { grid-template-columns: 1fr; } }
</style>

<div class="grid">
    <div class="card">
        <h2 style="margin-bottom:12px;">Personal Information</h2>
        <div class="row"><div class="label">Name</div><div class="value">{{ $student->name }}</div></div>
        <div class="row"><div class="label">Email</div><div class="value">{{ $student->email }}</div></div>
        <div class="row"><div class="label">Student ID</div><div class="value">{{ $student->student_id }}</div></div>
        <div class="row"><div class="label">Course</div><div class="value">{{ $student->course?->name ?? 'Not assigned' }}</div></div>
    </div>

    <div class="card">
        <h2 style="margin-bottom:12px;">Academic Snapshot</h2>
        <div class="row"><div class="label">Total Submissions</div><div class="value">{{ $submissionCount }}</div></div>
        <div class="row"><div class="label">Graded Submissions</div><div class="value">{{ $gradedSubmissionCount }}</div></div>
        <div class="row"><div class="label">Exam Results</div><div class="value">{{ $examResultCount }}</div></div>
        <div class="row"><div class="label">Average Score</div><div class="value">{{ number_format($student->getAverageScore(), 2) }}</div></div>
    </div>

    <div class="card full-width">
        <h2 style="margin-bottom:12px;">Course Modules</h2>
        @if($courseModules->isEmpty())
            <div class="row"><div class="value" style="font-size:14px;">No modules are available for your course yet.</div></div>
        @else
            <div class="modules-list">
                @foreach($courseModules as $module)
                    @php
                        $typeLabels = [
                            'unit_outline' => 'Unit Outline',
                            'quiz' => 'Quiz',
                            'test' => 'Test',
                            'note' => 'Note',
                        ];
                    @endphp
                    <div class="module-item">
                        <div class="module-title">{{ $module->title }}</div>
                        @if($module->description)
                            <div class="module-desc">{{ $module->description }}</div>
                        @endif
                        <div class="module-meta">
                            <span class="module-chip">{{ $module->lesson_count }} lessons</span>
                            <span class="module-chip">{{ $module->assignment_count }} assignments</span>
                            <span class="module-chip">{{ $module->quiz_count }} quizzes</span>
                        </div>
                        @if($moduleItemsEnabled && $module->items->isNotEmpty())
                            <div class="module-content-list">
                                @foreach($module->items as $item)
                                    <div class="module-content-item">
                                        <div class="module-content-head">
                                            <div class="module-content-title">{{ $item->title }}</div>
                                            <span class="module-content-type">{{ $typeLabels[$item->type] ?? ucfirst(str_replace('_', ' ', $item->type)) }}</span>
                                        </div>
                                        @if($item->content)
                                            <div class="module-content-body">{{ $item->content }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
