@extends('layouts.teacher')

@section('title', $course->name)
@section('page-title', $course->name)

@section('content')
<style>
    .course-container { background: #ffffff; border-radius: 12px; border: 1px solid rgba(0,0,0,0.06); padding: 30px; }
    .course-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid rgba(0,0,0,0.08); }
    .course-code { font-family: monospace; background: rgba(148,163,184,0.08); padding: 4px 8px; border-radius: 6px; font-size: 14px; color: #64748b; }
    .status-badge { padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; }
    .status-active { background: rgba(16,185,129,0.18); border: 1px solid rgba(16,185,129,0.3); color: #10b981; }
    .status-inactive { background: rgba(239,68,68,0.18); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; }
    .course-description { color: #475569; line-height: 1.6; margin-bottom: 30px; padding: 20px; background: rgba(0,0,0,0.02); border-radius: 8px; border: 1px solid rgba(0,0,0,0.08); }
    .students-section { margin-top: 30px; }
    .students-section h3 { color: #1f2937; }
    .students-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .students-table th { background: rgba(0,0,0,0.05); padding: 12px 15px; text-align: left; font-weight: 600; color: #64748b; border-bottom: 1px solid rgba(0,0,0,0.08); font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em; }
    .students-table td { padding: 12px 15px; border-bottom: 1px solid rgba(0,0,0,0.06); color: #475569; }
    .students-table tr:hover { background: rgba(124,58,237,0.03); }
    .btn { padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 14px; margin-right: 10px; cursor: pointer; transition: all 0.2s; display: inline-block; font-weight: 500; }
    .btn:hover { transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .btn:active { transform: translateY(0); }
    .btn-back { background: rgba(0,0,0,0.05); color: #1f2937; }
    .btn-back:hover { background: rgba(0,0,0,0.1); }
    .btn-invite { background: #3b82f6; color: white; }
    .btn-invite:hover { background: #2563eb; }
    .btn-assign { background: #10b981; color: white; }
    .btn-assign:hover { background: #059669; }
    .btn-quiz { background: #f59e0b; color: white; }
    .btn-quiz:hover { background: #d97706; }
    .btn-test { background: #ef4444; color: white; }
    .btn-test:hover { background: #dc2626; }
    .btn-exam { background: #7c3aed; color: white; }
    .btn-exam:hover { background: #6d28d9; }
    .stats-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin: 16px 0 22px; }
    .stat-card { background: rgba(0,0,0,0.14); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 14px; cursor: pointer; transition: all 0.3s; text-decoration: none; display: block; }
    .stat-card:hover { background: rgba(124,58,237,0.25); border-color: rgba(124,58,237,0.6); transform: translateY(-3px) scale(1.02); box-shadow: 0 8px 20px rgba(124,58,237,0.3); }
    .stat-card:hover h4 { text-decoration: underline; color: #7c3aed; }
    .stat-card h4 { margin: 0 0 6px 0; color: #000000; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em; transition: color 0.3s; }
    .stat-card p { margin: 0; font-size: 24px; font-weight: 700; color: #7c3aed; }
    .empty-state { text-align: center; padding: 40px; color: #000000; }
    .related-list { list-style: none; margin-top: 10px; padding: 0; background: rgba(0,0,0,0.14); border-radius: 8px; border: 1px solid rgba(255,255,255,0.08); }
    .related-list li { padding: 10px 12px; border-bottom: 1px solid rgba(255,255,255,0.08); cursor: pointer; transition: background 0.2s; }
    .related-list li:hover { background: rgba(124,58,237,0.1); }
    .related-list li a { cursor: pointer; transition: color 0.2s; }
    .related-list li a:hover { color: #7c3aed !important; text-decoration: underline; }
    .related-list li:last-child { border-bottom: none; }
    /* Modern Module Cards - Similar to Student View */
    .modules-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; margin-top: 20px; }
    @media (max-width: 768px) { .modules-grid { grid-template-columns: 1fr; } }

    .module-card-link { text-decoration: none; display: block; }
    .module-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .module-card-link:hover .module-card {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
        border-color: #7c3aed;
    }

    .module-card-header {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        padding: 24px;
        border-bottom: 1px solid #e5e7eb;
    }
    .module-icon {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin-bottom: 16px;
    }
    .module-title {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 4px;
    }
    .module-position {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .module-card-body {
        padding: 20px 24px;
    }
    .module-desc {
        color: #4b5563;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 16px;
        min-height: 44px;
    }

    .module-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 16px;
    }
    .stat-box {
        background: #f9fafb;
        padding: 14px;
        border-radius: 10px;
        text-align: center;
    }
    .stat-value {
        font-size: 22px;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 4px;
    }
    .stat-label {
        font-size: 11px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .module-footer {
        padding: 16px 24px 24px;
        border-top: 1px solid #f3f4f6;
    }
    .module-teacher {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 16px;
    }
    .module-teacher strong {
        color: #1f2937;
    }

    .module-actions {
        display: flex;
        gap: 10px;
    }
    .btn-module {
        flex: 1;
        padding: 12px 16px;
        border-radius: 10px;
        text-decoration: none;
        text-align: center;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    .btn-module-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    .btn-module-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    .btn-module-secondary {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
    }
    .btn-module-secondary:hover {
        background: #f3f4f6;
    }
    .module-form { margin-top: 12px; display: grid; gap: 10px; background: rgba(0,0,0,0.14); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 14px; }
    .module-form input, .module-form textarea { width: 100%; }
    .module-form select { width: 100%; }
    .module-items { display: grid; gap: 8px; margin-top: 12px; }
    .module-item-card { border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); background: rgba(255,255,255,0.03); padding: 12px; }
    .module-item-head { display: flex; justify-content: space-between; gap: 8px; align-items: center; }
    .module-item-title { color: #f8fafc; font-weight: 600; }
    .module-item-type { font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: #000000; background: rgba(124,58,237,0.2); border: 1px solid rgba(124,58,237,0.32); padding: 3px 8px; border-radius: 999px; }
    .module-item-content { margin-top: 8px; color: #000000; line-height: 1.55; white-space: pre-line; }
    .module-item-meta { margin-top: 8px; color: #64748b; font-size: 12px; }
</style>

<div class="course-container">
    @if($errors->has('error'))
        <div style="background:#fee2e2; color:#991b1b; border:1px solid #fecaca; padding:10px 12px; border-radius:8px; margin-bottom:14px;">
            {{ $errors->first('error') }}
        </div>
    @endif

    <div class="course-header">
        <div>
            <h1 style="margin: 0 0 10px 0; color: #000000;">{{ $course->name }}</h1>
            <span class="course-code">{{ $course->code }}</span>
            <div style="margin-top: 10px; color: #000000;">Category: <strong>{{ $course->category_name ?: 'Uncategorized' }}</strong></div>
            <div style="margin-top: 5px; color: #000000;">Class: <strong>{{ $course->class_name ?: 'Unassigned' }}</strong></div>
        </div>
        <span class="status-badge status-{{ $course->is_active ? 'active' : 'inactive' }}">
            {{ $course->is_active ? 'Active' : 'Inactive' }}
        </span>
    </div>

    @if($course->description)
    <div class="course-description">{{ $course->description }}</div>
    @endif

    <div class="students-section">
        <h3>Course Modules ({{ $modulesEnabled ? $course->modules->count() : 0 }})</h3>

        @if(!$modulesEnabled)
            <div class="empty-state">Run `php artisan migrate` to enable course modules.</div>
        @else
            @if($course->modules->isEmpty())
                <div class="empty-state">No modules added for this course yet. Admin needs to create the module structure first.</div>
            @else
                <div class="modules-grid">
                    @foreach($course->modules as $module)
                        <div class="module-card">
                            <div class="module-card-header">
                                <div class="module-icon">📖</div>
                                <div class="module-title">{{ $module->title }}</div>
                                <div class="module-position">Module {{ $module->position }}</div>
                            </div>

                            <div class="module-card-body">
                                <div class="module-desc">
                                    {{ $module->description ?: 'No description available for this module.' }}
                                </div>
                                <div class="module-stats">
                                    <div class="stat-box">
                                        <div class="stat-value">{{ $module->items?->count() ?? $module->lesson_count ?? 0 }}</div>
                                        <div class="stat-label">Items</div>
                                    </div>
                                    <div class="stat-box">
                                        <div class="stat-value">{{ $module->quiz_count ?? 0 }}</div>
                                        <div class="stat-label">Quizzes</div>
                                    </div>
                                </div>
                            </div>

                            <div class="module-footer">
                                <div class="module-teacher">
                                    👨‍🏫 Teacher: <strong>{{ $module->teacher?->name ?? 'Any assigned' }}</strong>
                                </div>
                                <div class="module-actions">
                                    <a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}" class="btn-module btn-module-primary">Continue</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
