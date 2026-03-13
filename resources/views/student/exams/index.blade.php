@extends('layouts.student')

@section('title', 'Exams')
@section('page-title', 'Exams & Quizzes')

@section('content')
<style>
    .exam-layout {
        display: grid;
        grid-template-columns: 330px 1fr;
        gap: 18px;
        min-height: 72vh;
    }

    .panel {
        background: #1e2235;
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 12px;
        overflow: hidden;
    }

    .tabs {
        display: flex;
        gap: 8px;
        padding: 14px 16px 0;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .tab-link {
        color: #94a3b8;
        text-decoration: none;
        padding: 10px 2px;
        font-size: 14px;
        font-weight: 600;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
    }
    .tab-link.active {
        color: #a78bfa;
        border-bottom-color: #7c3aed;
    }

    .exam-list { max-height: calc(72vh - 52px); overflow-y: auto; }
    .exam-item {
        display: block;
        padding: 16px;
        text-decoration: none;
        color: inherit;
        border-left: 2px solid transparent;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        transition: background 0.2s;
    }
    .exam-item:hover { background: rgba(255,255,255,0.03); }
    .exam-item.active {
        background: rgba(124,58,237,0.14);
        border-left-color: #7c3aed;
    }
    .exam-item:last-child { border-bottom: none; }

    .exam-item-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 6px;
    }
    .exam-item-title { font-size: 19px; font-weight: 700; color: #f1f5f9; margin-bottom: 2px; }
    .exam-item-course { font-size: 13px; color: #94a3b8; }
    .exam-meta { display: flex; gap: 14px; margin-top: 10px; font-size: 12px; color: #64748b; }

    .pill {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.03em;
        white-space: nowrap;
    }
    .pill-live { background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3); }
    .pill-soon { background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
    .pill-date { background: rgba(148,163,184,0.12); color: #94a3b8; border: 1px solid rgba(148,163,184,0.25); }

    .detail { display: flex; flex-direction: column; min-height: 72vh; }
    .detail-head { padding: 28px 30px 20px; border-bottom: 1px solid rgba(255,255,255,0.06); }
    .detail-head-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 8px;
    }
    .detail-title { font-size: 40px; line-height: 1.1; font-weight: 700; color: #f1f5f9; }
    .detail-course { font-size: 23px; color: #94a3b8; }

    .stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(120px, 1fr));
        gap: 10px;
        margin-top: 18px;
        background: rgba(0,0,0,0.15);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 10px;
        padding: 14px;
    }
    .stat-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; margin-bottom: 3px; }
    .stat-value { color: #e2e8f0; font-weight: 600; }

    .detail-body { padding: 22px 30px; color: #cbd5e1; flex: 1; }
    .detail-body h3 { font-size: 22px; color: #f1f5f9; margin-bottom: 10px; }
    .detail-body p { line-height: 1.7; margin-bottom: 18px; }

    .rule-list { list-style: none; margin: 0; padding: 0; }
    .rule-list li { margin-bottom: 10px; color: #94a3b8; }
    .rule-list li:before {
        content: "✓";
        color: #7c3aed;
        font-weight: 700;
        margin-right: 10px;
    }

    .detail-footer {
        padding: 18px 30px;
        border-top: 1px solid rgba(255,255,255,0.06);
        display: flex;
        justify-content: flex-end;
    }
    .btn-begin {
        background: #7c3aed;
        color: #fff;
        text-decoration: none;
        padding: 11px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
    }
    .btn-begin:hover { background: #6d28d9; }

    .empty {
        padding: 30px;
        text-align: center;
        color: #64748b;
    }

    @media (max-width: 980px) {
        .exam-layout { grid-template-columns: 1fr; }
        .exam-list { max-height: none; }
        .detail-title { font-size: 28px; }
        .detail-course { font-size: 18px; }
    }
</style>

@php
    $activeList = $activeTab === 'completed' ? $completedExams : $upcomingExams;
@endphp

<div class="exam-layout">
    <aside class="panel">
        <div class="tabs">
            <a href="{{ route('student.exams.index', ['tab' => 'upcoming']) }}" class="tab-link {{ $activeTab === 'upcoming' ? 'active' : '' }}">Upcoming ({{ $upcomingExams->count() }})</a>
            <a href="{{ route('student.exams.index', ['tab' => 'completed']) }}" class="tab-link {{ $activeTab === 'completed' ? 'active' : '' }}">Completed ({{ $completedExams->count() }})</a>
        </div>

        <div class="exam-list">
            @forelse($activeList as $exam)
                @php
                    $isSelected = $selectedExam && $selectedExam->id === $exam->id;
                    $daysAway = now()->startOfDay()->diffInDays($exam->exam_date, false);
                    $durationMinutes = data_get($exam, 'duration_minutes', 90);
                @endphp
                <a href="{{ route('student.exams.index', ['tab' => $activeTab, 'exam' => $exam->id]) }}" class="exam-item {{ $isSelected ? 'active' : '' }}">
                    <div class="exam-item-top">
                        <div>
                            <div class="exam-item-title">{{ $exam->title }}</div>
                            <div class="exam-item-course">{{ $exam->course?->name ?? 'General Course' }}</div>
                        </div>
                        @if($daysAway === 0)
                            <span class="pill pill-live">LIVE</span>
                        @elseif($daysAway > 0 && $daysAway <= 2)
                            <span class="pill pill-soon">IN {{ $daysAway }} DAY{{ $daysAway > 1 ? 'S' : '' }}</span>
                        @else
                            <span class="pill pill-date">{{ $exam->exam_date->format('M d') }}</span>
                        @endif
                    </div>
                    <div class="exam-meta">
                        <span>{{ $exam->exam_date->format('M d, Y') }}</span>
                        <span>{{ $durationMinutes }}m</span>
                    </div>
                </a>
            @empty
                <div class="empty">No exams in this tab.</div>
            @endforelse
        </div>
    </aside>

    <section class="panel detail">
        @if($selectedExam)
            @php
                $selectedResult = $selectedExam->results->first();
                $durationMinutes = data_get($selectedExam, 'duration_minutes', 90);
            @endphp
            <div class="detail-head">
                <div class="detail-head-top">
                    <div>
                        <div class="detail-title">{{ $selectedExam->title }}</div>
                        <div class="detail-course">{{ $selectedExam->course?->name ?? 'General Course' }}</div>
                    </div>
                    @if(now()->startOfDay()->equalTo($selectedExam->exam_date))
                        <span class="pill pill-live">LIVE NOW</span>
                    @elseif($selectedExam->exam_date->isFuture())
                        <span class="pill pill-soon">UPCOMING</span>
                    @else
                        <span class="pill pill-date">COMPLETED</span>
                    @endif
                </div>

                <div class="stats">
                    <div>
                        <div class="stat-label">Date</div>
                        <div class="stat-value">{{ $selectedExam->exam_date->format('M d, Y') }}</div>
                    </div>
                    <div>
                        <div class="stat-label">Duration</div>
                        <div class="stat-value">{{ $durationMinutes }} Minutes</div>
                    </div>
                    <div>
                        <div class="stat-label">Total Marks</div>
                        <div class="stat-value">{{ $selectedExam->max_score }} Points</div>
                    </div>
                </div>
            </div>

            <div class="detail-body">
                <h3>Description</h3>
                <p>{{ $selectedExam->description ?: 'No description has been provided for this exam yet.' }}</p>

                <h3>Exam Rules & Requirements</h3>
                <ul class="rule-list">
                    <li>Keep your exam session active from start to finish.</li>
                    <li>No external resources unless your teacher explicitly allows them.</li>
                    <li>Submit before the deadline shown in your exam instructions.</li>
                    @if($selectedResult)
                        <li>Your recorded score: {{ $selectedResult->score }}/{{ $selectedExam->max_score }}.</li>
                    @else
                        <li>You have no graded result for this exam yet.</li>
                    @endif
                </ul>
            </div>

            <div class="detail-footer">
                @if($selectedExam->exam_date->isFuture())
                    <a href="javascript:void(0)" class="btn-begin" aria-disabled="true">Exam Not Started</a>
                @elseif($selectedExam->exam_date->isPast())
                    <a href="javascript:void(0)" class="btn-begin" aria-disabled="true">Exam Closed</a>
                @else
                    <a href="javascript:void(0)" class="btn-begin">Begin Examination</a>
                @endif
            </div>
        @else
            <div class="empty">No exam selected.</div>
        @endif
    </section>
</div>
@endsection
