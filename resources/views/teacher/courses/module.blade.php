@extends('layouts.teacher')

@section('title', $module->title)
@section('page-title', 'Module Workspace')

@section('content')
<style>
    .wrap { display: grid; gap: 14px; }
    .panel { background: #fff; border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; padding: 16px; }
    .header h2 { margin: 0; color: #111827; }
    .header p { margin: 6px 0 0; color: #475569; }
    .chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
    .chip { background: #eef2ff; color: #3730a3; border-radius: 999px; padding: 4px 10px; font-size: 12px; }
    .breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; font-size: 14px; }
    .breadcrumb a { color: #7c3aed; text-decoration: none; transition: color 0.2s; }
    .breadcrumb a:hover { color: #5b21b6; text-decoration: underline; }
    .breadcrumb span { color: #6b7280; }
    .cards-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-top: 16px; }
    .card-link { text-decoration: none; display: block; }
    .card { background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%); color: white; padding: 32px; border-radius: 16px; cursor: pointer; transition: all 0.3s; }
    .card:hover { transform: translateY(-4px) scale(1.02); box-shadow: 0 12px 24px rgba(124,58,237,0.3); }
    .card h3 { margin: 0 0 8px; font-size: 24px; font-weight: 700; }
    .card .count { font-size: 48px; font-weight: 800; }
    .card .icon { font-size: 32px; opacity: 0.8; }
    @media (max-width: 768px) { .cards-grid { grid-template-columns: 1fr; } }

    /* Unit Outline Styles */
    .section-title { font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 12px; }
    .items-list { display: grid; gap: 10px; }
    .item-card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; display: flex; justify-content: space-between; align-items: center; transition: all 0.2s; }
    .item-card:hover { background: #f3f4f6; border-color: #d1d5db; }
    .item-info { flex: 1; }
    .item-title { font-size: 14px; font-weight: 600; color: #111827; margin-bottom: 4px; }
    .item-meta { display: flex; align-items: center; gap: 8px; }
    .item-type { font-size: 11px; padding: 2px 8px; border-radius: 999px; font-weight: 500; }
    .item-type.video { background: #dbeafe; color: #1e40af; }
    .item-type.note { background: #dcfce7; color: #166534; }
    .item-type.quiz { background: #fef3c7; color: #92400e; }
    .item-type.test { background: #fce7f3; color: #9d174d; }
    .item-type.other { background: #e5e7eb; color: #374151; }
    .item-actions { display: flex; gap: 6px; }
    .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
    .btn-edit { background: #3b82f6; color: white; }
    .btn-edit:hover { background: #2563eb; }
    .btn-delete { background: #ef4444; color: white; }
    .btn-delete:hover { background: #dc2626; }
    .btn-add { background: #7c3aed; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-block; font-weight: 500; margin-top: 12px; }
    .btn-add:hover { background: #6d28d9; }
    .empty-state { text-align: center; padding: 24px; color: #6b7280; font-size: 14px; }
</style>

<div class="wrap">
    <div class="panel header">
        <nav class="breadcrumb">
            <a href="{{ route('teacher.courses.index') }}">Courses</a>
            <span>/</span>
            <a href="{{ route('teacher.courses.show', $course) }}">{{ $course->name }}</a>
            <span>/</span>
            <span>{{ $module->title }}</span>
        </nav>
        <h2>{{ $module->title }}</h2>
        <p>{{ $course->name }} ({{ $course->code }})</p>
        <p>{{ $module->description ?: 'No module description provided.' }}</p>
        <div class="chips">
            <span class="chip">{{ $module->lesson_count }} lessons</span>
            <span class="chip">{{ $module->assignment_count }} assignments</span>
            <span class="chip">{{ $module->quiz_count }} quizzes</span>
            <span class="chip">Assigned Teacher: {{ $module->teacher?->name ?? 'Any assigned course teacher' }}</span>
        </div>
    </div>

    <div class="cards-grid">
        <a href="{{ route('teacher.assignments.index', ['course_id' => $course->id, 'module_id' => $module->id]) }}" class="card-link">
            <div class="card">
                <h3>Assignments</h3>
                <div class="count">{{ $assignments->count() }}</div>
            </div>
        </a>
        <a href="{{ route('teacher.exams.index', ['course_id' => $course->id, 'module_id' => $module->id]) }}" class="card-link">
            <div class="card">
                <h3>Exams</h3>
                <div class="count">{{ $exams->count() }}</div>
            </div>
        </a>
    </div>
    <div style="margin-top: 20px; display: flex; gap: 12px;">
        <a href="{{ route('teacher.assignments.create', ['course_id' => $course->id, 'module_id' => $module->id]) }}" style="padding: 10px 20px; background: #10b981; color: white; text-decoration: none; border-radius: 8px; font-weight: 500;">+ Create Assignment</a>
        <a href="{{ route('teacher.exams.create', ['course_id' => $course->id, 'module_id' => $module->id]) }}" style="padding: 10px 20px; background: #7c3aed; color: white; text-decoration: none; border-radius: 8px; font-weight: 500;">+ Create Exam</a>
    </div>

    <div class="panel" style="margin-top: 20px;">
        <h3 class="section-title">Unit Outline</h3>
        @if($module->items && $module->items->count() > 0)
            <div class="items-list">
                @foreach($module->items as $item)
                    <div class="item-card">
                        <div class="item-info">
                            <div class="item-title">{{ $item->title }}</div>
                            <div class="item-meta">
                                <span class="item-type {{ $item->type }}">{{ ucfirst($item->type) }}</span>
                                @if($item->file_name)
                                    <span style="font-size: 11px; color: #10b981;">📎 {{ $item->file_name }}</span>
                                @endif
                                @if($item->creator)
                                    <span style="font-size: 11px; color: #6b7280;">by {{ $item->creator->name }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="item-actions">
                            @if($item->file_path)
                                <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn-sm" style="background: #10b981; color: white; text-decoration: none;">Download</a>
                            @endif
                            <button class="btn-sm btn-edit">Edit</button>
                            <button class="btn-sm btn-delete" onclick="return confirm('Delete this item?')">Delete</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">No content has been added to this unit yet.</div>
        @endif
        <a href="{{ route('teacher.courses.modules.items.create', [$course, $module]) }}" class="btn-add">+ Add Content</a>
    </div>
</div>
@endsection
