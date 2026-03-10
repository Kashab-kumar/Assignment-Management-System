@extends('layouts.student')

@section('title', 'Assignments')
@section('page-title', 'Assignments & Homework')

@section('content')
<style>
    .assignments-grid { display: grid; gap: 20px; }
    .assignment-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.2s; }
    .assignment-card:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
    .assignment-card h3 { color: #333; margin-bottom: 10px; font-size: 20px; }
    .assignment-card .meta { color: #666; font-size: 14px; margin-bottom: 10px; padding: 10px; background: #f8f9fa; border-radius: 4px; }
    .assignment-card .description { color: #555; margin-bottom: 15px; line-height: 1.6; }
    .btn { padding: 10px 20px; background: #27ae60; color: white; text-decoration: none; border-radius: 4px; display: inline-block; font-size: 14px; }
    .btn:hover { background: #229954; }
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; margin-left: 10px; font-weight: bold; }
    .badge-assignment { background: #2196F3; color: white; }
    .badge-homework { background: #FF9800; color: white; }
    .empty-state { text-align: center; padding: 60px 20px; background: white; border-radius: 8px; }
    .empty-state svg { width: 100px; height: 100px; fill: #ddd; margin-bottom: 20px; }
    .empty-state h3 { color: #666; margin-bottom: 10px; }
    .due-soon { color: #e74c3c; font-weight: bold; }
    .pagination { margin-top: 20px; display: flex; justify-content: center; }
</style>

<div class="assignments-grid">
    @forelse($assignments as $assignment)
    <div class="assignment-card">
        <h3>
            {{ $assignment->title }}
            <span class="badge badge-{{ $assignment->type }}">{{ ucfirst($assignment->type) }}</span>
        </h3>
        <div class="meta">
            <div style="margin-bottom: 5px;">
                📅 <strong>Due:</strong> 
                @if($assignment->due_date->isPast())
                    <span style="color: #e74c3c;">{{ $assignment->due_date->format('F d, Y') }} (Overdue)</span>
                @elseif($assignment->due_date->diffInDays() <= 3)
                    <span class="due-soon">{{ $assignment->due_date->format('F d, Y') }} (Due Soon!)</span>
                @else
                    {{ $assignment->due_date->format('F d, Y') }}
                @endif
            </div>
            <div>📊 <strong>Max Score:</strong> {{ $assignment->max_score }} points</div>
        </div>
        <div class="description">{{ Str::limit($assignment->description, 150) }}</div>
        <a href="{{ route('assignments.show', $assignment) }}" class="btn">View Details & Submit</a>
    </div>
    @empty
    <div class="empty-state">
        <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
        <h3>No Assignments Available</h3>
        <p>Your teacher hasn't posted any assignments yet. Check back later!</p>
    </div>
    @endforelse
</div>

@if($assignments->hasPages())
<div class="pagination">
    {{ $assignments->links() }}
</div>
@endif
@endsection
