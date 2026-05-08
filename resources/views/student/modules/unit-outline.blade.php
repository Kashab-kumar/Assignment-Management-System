@extends('layouts.student')

@section('title', $module->title . ' - Unit Outline')
@section('page-title', 'Unit Outline Details')

@section('content')
<style>
    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 32px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
        color: #2459ff;
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px;
        padding: 40px;
        margin-bottom: 32px;
    }

    .header h1 {
        margin: 0 0 12px;
        font-size: 32px;
        font-weight: 700;
    }

    .header p {
        margin: 0;
        opacity: 0.95;
        font-size: 16px;
    }

    .header-meta {
        display: flex;
        gap: 20px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .meta-item {
        background: rgba(255,255,255,0.2);
        padding: 10px 18px;
        border-radius: 24px;
        font-size: 14px;
        font-weight: 500;
    }

    .outline-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .outline-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .outline-title {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 8px;
    }

    .outline-type {
        background: #dbeafe;
        color: #1e40af;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .outline-creator {
        color: #64748b;
        font-size: 14px;
        margin-top: 8px;
    }

    .btn-view {
        background: #3b82f6;
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        background: #2563eb;
        transform: translateY(-2px);
    }

    .description-box {
        background: #f9fafb;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        color: #374151;
        line-height: 1.7;
        font-size: 15px;
    }

    .file-section {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .file-card {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .file-icon {
        font-size: 40px;
    }

    .file-info {
        flex: 1;
    }

    .file-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 16px;
    }

    .file-meta {
        font-size: 13px;
        color: #6b7280;
        margin-top: 4px;
    }

    .file-actions {
        display: flex;
        gap: 8px;
    }

    .btn-sm {
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary-sm {
        background: #3b82f6;
        color: white;
    }

    .btn-primary-sm:hover {
        background: #2563eb;
    }

    .btn-secondary-sm {
        background: #10b981;
        color: white;
    }

    .btn-secondary-sm:hover {
        background: #059669;
    }

    .section-title {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0 0 16px;
    }

    .grade-scale {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .grade-item {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .grade-item:hover {
        border-color: #667eea;
        transform: translateY(-2px);
    }

    .grade-letter {
        font-size: 28px;
        font-weight: 800;
        color: #667eea;
    }

    .grade-range {
        font-size: 13px;
        color: #6b7280;
        margin-top: 4px;
    }

    .criteria-list {
        display: grid;
        gap: 12px;
    }

    .criteria-item {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .criteria-info h4 {
        margin: 0 0 4px;
        font-size: 15px;
        color: #0f172a;
    }

    .criteria-info p {
        margin: 0;
        font-size: 13px;
        color: #6b7280;
    }

    .criteria-weight {
        background: #dbeafe;
        color: #1e40af;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 700;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .actions-bar {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        padding: 14px 28px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-outline {
        background: transparent;
        color: #6b7280;
        border: 2px solid #d1d5db;
    }

    .btn-outline:hover {
        background: #f3f4f6;
    }

    .btn-continue {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        margin-left: auto;
    }

    .btn-continue:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
</style>

<div class="container">
    <a href="{{ route('student.courses.show', $course->id) }}" class="back-link">
        ← Back to {{ $course->name }}
    </a>

    <div class="header">
        <h1>{{ $module->title }}</h1>
        <p>{{ $module->description ?: 'Unit Outline Details' }}</p>
        <div class="header-meta">
            <span class="meta-item">📚 {{ $module->items->count() }} Materials</span>
            <span class="meta-item">👨‍🏫 {{ $module->teacher?->name ?? 'Course Instructor' }}</span>
        </div>
    </div>

    @if($module->items && $module->items->count() > 0)
        @foreach($module->items as $item)
            <div class="outline-card">
                <div class="outline-header">
                    <div>
                        <h2 class="outline-title">{{ $item->title }}</h2>
                        <span class="outline-type">{{ ucfirst(str_replace('_', ' ', $item->type)) }}</span>
                        @if($item->creator)
                            <div class="outline-creator">Created by {{ $item->creator->name }} on {{ $item->created_at->format('M d, Y') }}</div>
                        @endif
                    </div>
                    @if($item->file_path)
                        <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn-view">👁 View File</a>
                    @endif
                </div>

                @if($item->content)
                    <div class="description-box">
                        {{ $item->content }}
                    </div>
                @endif

                @if($item->file_path)
                    <div class="file-section">
                        <div class="section-title">Attached File</div>
                        <div class="file-card">
                            <div class="file-icon">
                                @if($item->file_type == 'pdf') 📕
                                @elseif(in_array($item->file_type, ['doc', 'docx'])) 📘
                                @elseif($item->file_type == 'txt') 📝
                                @else 📄
                                @endif
                            </div>
                            <div class="file-info">
                                <div class="file-name">{{ $item->file_name }}</div>
                                <div class="file-meta">{{ strtoupper($item->file_type) }} file</div>
                            </div>
                            <div class="file-actions">
                                <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn-sm btn-primary-sm">Open</a>
                                <a href="{{ asset('storage/' . $item->file_path) }}" download class="btn-sm btn-secondary-sm">Download</a>
                            </div>
                        </div>
                    </div>
                @endif

                @if($item->grade_scale)
                    <div style="margin-bottom: 20px;">
                        <div class="section-title">Grade Scale</div>
                        <div class="grade-scale">
                            @foreach(['A', 'B', 'C', 'D', 'E', 'F'] as $grade)
                                @if(isset($item->grade_scale[strtolower($grade) . '_min']) && isset($item->grade_scale[strtolower($grade) . '_max']))
                                    <div class="grade-item">
                                        <div class="grade-letter">{{ $grade }}</div>
                                        <div class="grade-range">{{ $item->grade_scale[strtolower($grade) . '_min'] }}-{{ $item->grade_scale[strtolower($grade) . '_max'] }}%</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($item->grading_criteria)
                    <div>
                        <div class="section-title">Grading Criteria</div>
                        <div class="criteria-list">
                            @foreach($item->grading_criteria as $criterion)
                                <div class="criteria-item">
                                    <div class="criteria-info">
                                        <h4>{{ $criterion['name'] ?? 'Criterion' }}</h4>
                                        @if(isset($criterion['description']) && $criterion['description'])
                                            <p>{{ $criterion['description'] }}</p>
                                        @endif
                                    </div>
                                    <div class="criteria-weight">{{ $criterion['weight'] ?? 0 }}%</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endforeach

        <div class="actions-bar">
            <a href="{{ route('student.courses.show', $course->id) }}" class="btn btn-outline">← Back to Course</a>
            <a href="{{ route('student.modules.show', $module->id) }}" class="btn btn-continue">Continue to Module Workspace →</a>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <h3>No Unit Outline Materials</h3>
            <p>No materials have been added to this module yet.</p>
        </div>
    @endif
</div>
@endsection
