@extends('layouts.student')

@section('title', $module ? $module->title . ' - Assignments' : 'Assignments')
@section('page-title', $module ? $module->title . ' Assignments' : 'Assignments')

@section('content')
<style>
    .tabs { display: flex; gap: 4px; margin-bottom: 24px; border-bottom: 1px solid rgba(255,255,255,0.08); }
    .tab-link {
        padding: 10px 18px;
        font-size: 14px;
        font-weight: 500;
        color: #000000;
        text-decoration: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        transition: color 0.2s;
    }
    .tab-link:hover { color: #1f2937; }
    .tab-link.active { color: #a78bfa; border-bottom-color: #7c3aed; }

    .assignments-list { display: flex; flex-direction: column; gap: 16px; }

    .assignment-card {
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.06);
        border-radius: 12px;
        padding: 22px 24px;
        transition: border-color 0.2s;
    }
    .assignment-card:hover { border-color: rgba(124,58,237,0.3); }

    .assignment-card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
    .assignment-title { font-size: 18px; font-weight: 600; color: #1f2937; }

    .assignment-meta { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 14px; }
    .meta-item { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #000000; }
    .meta-item svg { width: 15px; height: 15px; fill: currentColor; flex-shrink: 0; }

    .badge { padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; display: inline-block; }
    .badge-not-submitted { background: rgba(245,158,11,0.18); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
    .badge-submitted    { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.25); }
    .badge-graded       { background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.25); }
    .badge-overdue      { background: rgba(239,68,68,0.15);  color: #ef4444; border: 1px solid rgba(239,68,68,0.25); }

    .btn {
        padding: 8px 18px;
        background: #7c3aed;
        color: white;
        text-decoration: none;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 500;
        display: inline-block;
        transition: background 0.2s;
    }
    .btn:hover { background: #6d28d9; }

    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state svg { width: 64px; height: 64px; fill: #334155; margin-bottom: 16px; }
    .empty-state h3 { color: #64748b; font-size: 16px; margin-bottom: 6px; }
    .empty-state p  { color: #475569; font-size: 14px; }
</style>

@if($module)
    <div style="margin-bottom: 20px;">
        <a href="{{ route('student.modules.show', $module) }}" style="color: #7c3aed; text-decoration: none; font-weight: 500;">
            ← Back to {{ $module->title }}
        </a>
    </div>
@endif

<div class="tabs">
    <a href="{{ route('student.assignments.index', array_filter(['module_id' => $moduleId, 'tab' => 'pending'])) }}" class="tab-link {{ $activeTab === 'pending' ? 'active' : '' }}">
        Pending ({{ $pending->count() }})
    </a>
    <a href="{{ route('student.assignments.index', array_filter(['module_id' => $moduleId, 'tab' => 'submitted'])) }}" class="tab-link {{ $activeTab === 'submitted' ? 'active' : '' }}">
        Submitted ({{ $submitted->count() }})
    </a>
    <a href="{{ route('student.assignments.index', array_filter(['module_id' => $moduleId, 'tab' => 'graded'])) }}" class="tab-link {{ $activeTab === 'graded' ? 'active' : '' }}">
        Graded ({{ $graded->count() }})
    </a>
</div>

@php
    $list = $activeTab === 'graded' ? $graded : ($activeTab === 'submitted' ? $submitted : $pending);
@endphp

<div class="assignments-list">
    @forelse($list as $assignment)
    @php($sub = $assignment->submissions->first())
    <div class="assignment-card">
        <div class="assignment-card-header">
            <div class="assignment-title">{{ $assignment->title }}</div>
            @if($sub)
                @if($sub->status === 'graded')
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
                @if($assignment->due_date->isPast() && !$sub)
                    <span style="color:#ef4444;"> (Overdue)</span>
                @elseif(!$sub && $assignment->due_date->diffInDays() <= 3)
                    <span style="color:#f59e0b;"> (Due Soon)</span>
                @endif
            </span>
            <span class="meta-item">
                <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14l-5-5 1.41-1.41L12 14.17l7.59-7.59L21 8l-9 9z"/></svg>
                {{ $assignment->max_score }} Points
            </span>
            @if($sub && $sub->status === 'graded' && $sub->score !== null)
            <span class="meta-item" style="color:#10b981;">
                Score: {{ $sub->score }}/{{ $assignment->max_score }}
            </span>
            @endif
        </div>

        <a href="{{ route('student.assignments.show', $assignment) }}" class="btn">
            {{ $sub ? 'View Submission' : 'View & Submit' }}
        </a>
    </div>
    @empty
    <div class="empty-state">
        <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
        <h3>No {{ ucfirst($activeTab) }} Assignments</h3>
        <p>
            @if($activeTab === 'pending') You're all caught up! No pending assignments. @endif
            @if($activeTab === 'submitted') No assignments submitted yet. @endif
            @if($activeTab === 'graded') No graded assignments yet. @endif
        </p>
    </div>
    @endforelse
</div>
@endsection
