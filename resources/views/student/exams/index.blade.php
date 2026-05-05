@extends('layouts.student')

@section('title', $module ? $module->title . ' - Exams' : 'Tests & Exams')
@section('page-title', $module ? $module->title . ' Exams' : 'Tests & Exams')

@section('content')
<style>
    .page-shell {
        display: grid;
        gap: 18px;
    }

    .hero {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .hero-title {
        font-size: 28px;
        line-height: 1.1;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 6px;
    }

    .hero-subtitle {
        color: #94a3b8;
        font-size: 15px;
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.06);
        border-radius: 14px;
        padding: 18px 18px 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 8px 30px rgba(15, 23, 42, 0.04);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
    }

    .stat-icon svg {
        width: 18px;
        height: 18px;
    }

    .stat-icon.total { background: #ede9fe; color: #7c3aed; }
    .stat-icon.upcoming { background: #fef3c7; color: #f59e0b; }
    .stat-icon.completed { background: #dcfce7; color: #16a34a; }
    .stat-icon.average { background: #dbeafe; color: #2563eb; }

    .stat-label {
        color: #94a3b8;
        font-size: 12px;
        margin-bottom: 2px;
    }

    .stat-value {
        color: #0f172a;
        font-size: 20px;
        line-height: 1.1;
        font-weight: 800;
    }

    .schedule-card {
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.06);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(15, 23, 42, 0.05);
    }

    .schedule-head {
        padding: 18px 20px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .schedule-head-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
    }

    .schedule-title {
        margin: 0 0 4px;
        color: #0f172a;
        font-size: 18px;
        font-weight: 800;
    }

    .schedule-subtitle {
        margin: 0;
        color: #94a3b8;
        font-size: 13px;
    }

    .tabs {
        display: flex;
        gap: 28px;
        margin-top: 14px;
    }

    .tab-link {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 0 0 14px;
        color: #94a3b8;
        text-decoration: none;
        font-size: 15px;
        font-weight: 700;
        transition: color 0.2s ease;
    }

    .tab-link:hover {
        color: #4f46e5;
    }

    .tab-link.active {
        color: #4f46e5;
    }

    .tab-link.active::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 3px;
        border-radius: 999px;
        background: #4f46e5;
    }

    .schedule-body {
        padding: 14px 20px 20px;
    }

    .filter-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 14px;
    }

    .filter-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 32px;
        padding: 0 14px;
        border-radius: 10px;
        background: #f8fafc;
        color: #64748b;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .filter-btn:hover {
        background: #eef2ff;
        border-color: #c7d2fe;
        color: #4f46e5;
    }

    .filter-btn.active {
        background: #6d5efc;
        color: #ffffff;
        border-color: #6d5efc;
        box-shadow: 0 8px 18px rgba(109, 94, 252, 0.22);
    }

    .table-shell {
        overflow-x: auto;
        border-radius: 12px;
    }

    .assessment-table {
        width: 100%;
        min-width: 840px;
        border-collapse: collapse;
    }

    .assessment-table thead th {
        padding: 12px 10px;
        text-align: left;
        font-size: 11px;
        letter-spacing: 0.08em;
        color: #94a3b8;
        font-weight: 700;
        border-bottom: 1px solid #e5e7eb;
    }

    .assessment-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s ease;
    }

    .assessment-table tbody tr:hover {
        background: #f8fafc;
    }

    .assessment-table tbody tr.is-active {
        background: #f8f7ff;
    }

    .assessment-table td {
        padding: 16px 10px;
        color: #334155;
        vertical-align: middle;
        font-size: 14px;
    }

    .assessment-cell {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 220px;
    }

    .assessment-arrow {
        color: #cbd5e1;
        width: 16px;
        flex: 0 0 auto;
    }

    .assessment-name {
        color: #0f172a;
        font-weight: 700;
        line-height: 1.3;
    }

    .assessment-course {
        margin-top: 4px;
        color: #94a3b8;
        font-size: 12px;
    }

    .meta-stack {
        display: grid;
        gap: 4px;
    }

    .pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.02em;
        white-space: nowrap;
        width: fit-content;
    }

    .pill-live { background: #dcfce7; color: #16a34a; }
    .pill-soon { background: #fef3c7; color: #d97706; }
    .pill-date { background: #e2e8f0; color: #475569; }
    .pill-type { background: #eef2ff; color: #4f46e5; }

    .table-empty {
        padding: 32px 10px;
        text-align: center;
        color: #94a3b8;
    }

    .detail-panel {
        margin-top: 18px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 18px;
    }

    .detail-panel-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }

    .detail-title {
        margin: 0 0 5px;
        font-size: 22px;
        line-height: 1.2;
        color: #0f172a;
        font-weight: 800;
    }

    .detail-course {
        color: #64748b;
        font-size: 16px;
        margin: 0;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 14px;
    }

    .detail-stat {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px;
    }

    .detail-stat-label {
        color: #94a3b8;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 4px;
    }

    .detail-stat-value {
        color: #0f172a;
        font-weight: 700;
        font-size: 14px;
    }

    .detail-description {
        color: #475569;
        line-height: 1.75;
        margin: 0 0 14px;
    }

    .rule-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: grid;
        gap: 8px;
    }

    .rule-list li {
        color: #334155;
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .rule-list li::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #4f46e5;
        margin-top: 8px;
        flex: 0 0 auto;
    }

    .detail-actions {
        margin-top: 16px;
        display: flex;
        justify-content: flex-end;
    }

    .btn-begin {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #4f46e5;
        color: #ffffff;
        text-decoration: none;
        padding: 11px 18px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        border: 0;
    }

    .btn-begin:hover {
        background: #4338ca;
    }

    .btn-begin.is-disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        pointer-events: none;
    }

    .empty {
        padding: 30px 10px;
        text-align: center;
        color: #94a3b8;
    }

    @media (max-width: 1024px) {
        .stats-grid,
        .detail-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 720px) {
        .hero-title {
            font-size: 24px;
        }

        .stats-grid,
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .tabs {
            gap: 18px;
        }
    }
</style>

@php
    $student = auth()->user()->student;

    if (!$student) {
        return redirect()->route('dashboard')
            ->withErrors(['error' => 'Student profile not found. Please contact administrator.']);
    }

    $studentCourseId = Schema::hasColumn('students', 'course_id') ? $student->course_id : null;
    $activeFilter = request()->query('filter', 'all');
    $activeTab = request()->query('tab', 'upcoming');

    // Filter exams by type
    $allExams = \App\Models\Exam::with([
            'results' => function ($query) use ($student) {
                $query->where('student_id', $student->id);
            },
            'course',
            'answers' => function ($query) use ($student) {
                $query->where('student_id', $student->id);
            },
        ])
            ->withCount('questions')
            ->when(
                Schema::hasColumn('students', 'course_id'),
                function ($query) use ($studentCourseId) {
                    if ($studentCourseId) {
                        $query->where(function ($inner) use ($studentCourseId) {
                            $inner->whereNull('course_id')
                                ->orWhere('course_id', $studentCourseId);
                        });
                    } else {
                        $query->whereNull('course_id');
                    }
                }
            )
            ->latest('exam_date')
            ->get();

    // Apply type filter
    $filteredExams = $allExams;
    if ($activeFilter !== 'all') {
        $filteredExams = $allExams->where('type', $activeFilter);
    }

    $today = now()->startOfDay();

    $upcomingExams = $filteredExams
        ->filter(fn ($exam) => $exam->exam_date->greaterThanOrEqualTo($today))
        ->sortBy('exam_date')
        ->values();

    $completedExams = $filteredExams
        ->filter(fn ($exam) => $exam->exam_date->lessThan($today))
        ->sortByDesc('exam_date')
        ->values();

    $gradedExams = $filteredExams->filter(fn ($exam) => $exam->results->isNotEmpty());
    $averageScore = $gradedExams->count()
        ? round($gradedExams->avg(function ($exam) {
            $result = $exam->results->first();

            if (!$result || (int) $exam->max_score <= 0) {
                return 0;
            }

            return (($result->score ?? 0) / $exam->max_score) * 100;
        }))
        : 0;

    $activeList = $activeTab === 'completed' ? $completedExams : $upcomingExams;

    $selectedExamId = (int) request()->query('exam', 0);
    $selectedExam = $activeList->firstWhere('id', $selectedExamId)
        ?? $activeList->first()
        ?? $filteredExams->first();

    $typeLabels = ['exam' => 'Exam', 'quiz' => 'Quiz', 'test' => 'Test'];
    $totalAssessments = $filteredExams->count();
    $upcomingCount = $upcomingExams->count();
    $completedCount = $completedExams->count();
@endphp

<div class="page-shell">
    <div class="hero">
        <div>
            <h1 class="hero-title">Tests &amp; Exams</h1>
            <p class="hero-subtitle">Track your upcoming assessments and review past performance</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"></path><path d="M8 8h8M8 12h8M8 16h5"></path></svg>
            </div>
            <div>
                <div class="stat-label">Total Assessments</div>
                <div class="stat-value">{{ $totalAssessments }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon upcoming">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4M16 2v4M3 10h18"></path><rect x="3" y="5" width="18" height="17" rx="2"></rect><path d="M8 14h4v4H8z"></path></svg>
            </div>
            <div>
                <div class="stat-label">Upcoming</div>
                <div class="stat-value">{{ $upcomingCount }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon completed">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m20 6-11 11-5-5"></path></svg>
            </div>
            <div>
                <div class="stat-label">Completed</div>
                <div class="stat-value">{{ $completedCount }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon average">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V5"></path><path d="M4 19h16"></path><path d="M8 15v-4"></path><path d="M12 15V8"></path><path d="M16 15v-6"></path></svg>
            </div>
            <div>
                <div class="stat-label">Average Score</div>
                <div class="stat-value">{{ $averageScore }}%</div>
            </div>
        </div>
    </div>

    <div class="schedule-card">
        <div class="schedule-head">
            <div class="schedule-head-top">
                <div>
                    <h2 class="schedule-title">Assessment Schedule</h2>
                    <p class="schedule-subtitle">Upcoming and completed assessments</p>
                </div>
            </div>

            <div class="tabs">
                <a href="{{ route('student.exams.index') }}?filter={{ $activeFilter }}&tab=upcoming" class="tab-link {{ $activeTab === 'upcoming' ? 'active' : '' }}">Upcoming ({{ $upcomingCount }})</a>
                <a href="{{ route('student.exams.index') }}?filter={{ $activeFilter }}&tab=completed" class="tab-link {{ $activeTab === 'completed' ? 'active' : '' }}">Completed ({{ $completedCount }})</a>
            </div>
        </div>

        <div class="schedule-body">
            <div class="filter-buttons">
                <a href="{{ route('student.exams.index') }}?filter=all&tab={{ $activeTab }}" class="filter-btn {{ $activeFilter === 'all' ? 'active' : '' }}">All</a>
                <a href="{{ route('student.exams.index') }}?filter=exam&tab={{ $activeTab }}" class="filter-btn {{ $activeFilter === 'exam' ? 'active' : '' }}">Exam</a>
                <a href="{{ route('student.exams.index') }}?filter=quiz&tab={{ $activeTab }}" class="filter-btn {{ $activeFilter === 'quiz' ? 'active' : '' }}">Quiz</a>
                <a href="{{ route('student.exams.index') }}?filter=test&tab={{ $activeTab }}" class="filter-btn {{ $activeFilter === 'test' ? 'active' : '' }}">Test</a>
            </div>

            <div class="table-shell">
                <table class="assessment-table">
                    <thead>
                        <tr>
                            <th>Assessment</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Duration</th>
                            <th>Type</th>
                            <th>Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeList as $exam)
                            @php
                                $isSelected = $selectedExam && $selectedExam->id === $exam->id;
                                $daysAway = now()->startOfDay()->diffInDays($exam->exam_date, false);
                                $durationMinutes = data_get($exam, 'duration_minutes', 90);
                                $typeLabel = $typeLabels[$exam->type] ?? ucfirst($exam->type);
                                $rowUrl = route('student.exams.index') . '?filter=' . $activeFilter . '&tab=' . $activeTab . '&exam=' . $exam->id;
                            @endphp
                            <tr class="{{ $isSelected ? 'is-active' : '' }}">
                                <td>
                                    <a href="{{ $rowUrl }}" class="assessment-cell" style="text-decoration:none;">
                                        <span class="assessment-arrow">⌄</span>
                                        <span>
                                            <div class="assessment-name">{{ $exam->title }}</div>
                                            <div class="assessment-course">{{ $exam->course?->name ?? 'General Course' }}</div>
                                        </span>
                                    </a>
                                </td>
                                <td>{{ $exam->course?->name ?? 'General Course' }}</td>
                                <td>{{ $exam->exam_date->format('Y-m-d') }}</td>
                                <td>{{ $exam->exam_time ? \Illuminate\Support\Carbon::createFromFormat('H:i:s', strlen($exam->exam_time) === 5 ? $exam->exam_time . ':00' : $exam->exam_time)->format('h:i A') : '12:00 AM' }}</td>
                                <td>{{ $durationMinutes }}m</td>
                                <td><span class="pill pill-type">{{ $typeLabel }}</span></td>
                                <td>{{ $exam->max_score }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="table-empty">No assessments match the current filters.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
