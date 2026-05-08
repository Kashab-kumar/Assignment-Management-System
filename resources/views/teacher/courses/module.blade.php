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
    .cards-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; margin-top: 16px; }
    @media (max-width: 768px) { .cards-grid { grid-template-columns: 1fr; } }

    /* Modern Card Styles */
    .card-wrapper {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .card-link {
        text-decoration: none;
        display: block;
    }
    .card {
        background: white;
        border: 2px solid #e5e7eb;
        padding: 32px;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        min-height: 140px;
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.1);
        border-color: #7c3aed;
    }
    .card-label {
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .card-count {
        font-size: 42px;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 4px;
    }
    .card-sublabel {
        font-size: 14px;
        color: #9ca3af;
    }
    .card-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .card-icon.assignments { background: #dcfce7; }
    .card-icon.exams { background: #ede9fe; }

    .btn-create {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 1px dashed #d1d5db;
        background: white;
        color: #6b7280;
    }
    .btn-create:hover {
        border-color: #10b981;
        color: #10b981;
        background: #f0fdf4;
    }
    .btn-create.exam:hover {
        border-color: #7c3aed;
        color: #7c3aed;
        background: #faf5ff;
    }

    /* Unit Outline Styles */
    .section-title { font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 12px; }
    .items-list { display: grid; gap: 10px; }
    .item-card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; display: flex; justify-content: space-between; align-items: center; transition: all 0.2s; }
    .item-card:hover { background: #f3f4f6; border-color: #d1d5db; }
    .item-info { flex: 1; }
    .item-title { font-weight: 600; font-size: 15px; color: #111827; margin-bottom: 6px; }
    .item-title a:hover { color: #7c3aed; text-decoration: underline; }
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
        <!-- Assignments Card -->
        <div class="card-wrapper">
            <a href="{{ route('teacher.assignments.index', ['course_id' => $course->id, 'module_id' => $module->id]) }}" class="card-link">
                <div class="card">
                    <div class="card-label">Assignments</div>
                    <div class="card-count">{{ $assignments->count() }}</div>
                    <div class="card-sublabel">Manage assignments</div>
                </div>
            </a>
            <a href="{{ route('teacher.assignments.create', ['course_id' => $course->id, 'module_id' => $module->id]) }}" class="btn-create">
                <span>+</span> Create Assignment
            </a>
        </div>

        <!-- Exams Card -->
        <div class="card-wrapper">
            <a href="{{ route('teacher.exams.index', ['course_id' => $course->id, 'module_id' => $module->id]) }}" class="card-link">
                <div class="card">
                    <div class="card-label">Exams</div>
                    <div class="card-count">{{ $exams->count() }}</div>
                    <div class="card-sublabel">Manage exams & quizzes</div>
                </div>
            </a>
            <a href="{{ route('teacher.exams.create', ['course_id' => $course->id, 'module_id' => $module->id]) }}" class="btn-create exam">
                <span>+</span> Create Exam
            </a>
        </div>
    </div>

    <div class="panel" style="margin-top: 20px;">
        <h3 class="section-title">Unit Outline</h3>
        @if($module->items && $module->items->count() > 0)
            <div class="items-list">
                @foreach($module->items as $item)
                    <div class="item-card">
                        <div class="item-info">
                            <div class="item-title"><a href="{{ route('teacher.courses.modules.items.show', [$course, $module, $item]) }}" style="color: #111827; text-decoration: none;">{{ $item->title }}</a></div>
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
                                <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn-sm" style="background: #3b82f6; color: white; text-decoration: none;">👁 View</a>
                            @endif
                            <a href="{{ route('teacher.courses.modules.items.edit', [$course, $module, $item]) }}" class="btn-sm btn-edit">Edit</a>
                            <form method="POST" action="{{ route('teacher.courses.modules.items.destroy', [$course, $module, $item]) }}" style="display: inline;" onsubmit="return confirm('Delete this unit outline? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sm btn-delete">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">No content has been added to this unit yet.</div>
        @endif
        <a href="{{ route('teacher.courses.modules.items.create', [$course, $module]) }}" class="btn-add">+ Add Unit Outline</a>
    </div>
</div>
@endsection
