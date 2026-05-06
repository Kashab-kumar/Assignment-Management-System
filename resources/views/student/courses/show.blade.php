@extends('layouts.student')

@section('title', $course->name)
@section('page-title', $course->name)

@section('content')
<style>
    .course-view-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .course-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 32px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .course-header-content h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .course-header-meta {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .meta-item {
        font-size: 14px;
        opacity: 0.95;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .meta-label {
        font-weight: 600;
        opacity: 0.9;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-2px);
    }

    .modules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .module-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        display: flex;
        flex-direction: column;
    }

    .module-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .module-card-header {
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        padding: 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .module-icon {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        margin-bottom: 12px;
    }

    .module-title {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .module-position {
        font-size: 12px;
        color: #9ca3af;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .module-body {
        padding: 20px;
        flex: 1;
    }

    .module-description {
        font-size: 14px;
        color: #4b5563;
        line-height: 1.6;
        margin-bottom: 16px;
        min-height: 60px;
    }

    .module-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .stat-item {
        background: #f9fafb;
        padding: 12px;
        border-radius: 8px;
        text-align: center;
    }

    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .module-footer {
        padding: 20px;
        border-top: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .module-progress {
        margin-bottom: 16px;
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 12px;
        color: #6b7280;
        font-weight: 600;
    }

    .progress-bar {
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .module-actions {
        display: flex;
        gap: 8px;
    }

    .btn {
        flex: 1;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        text-align: center;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .btn-secondary:hover {
        background: #f3f4f6;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #4b5563;
    }

    .empty-state p {
        font-size: 14px;
        color: #6b7280;
    }

    @media (max-width: 768px) {
        .modules-grid {
            grid-template-columns: 1fr;
        }

        .course-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .course-header h1 {
            font-size: 24px;
        }

        .course-header-meta {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>

<div class="course-view-container">
    <a href="{{ route('student.modules.index') }}" class="back-button">← Back to Courses</a>

    <div class="course-header">
        <div class="course-header-content">
            <h1>{{ $course->name }}</h1>
            <div class="course-header-meta">
                @if($course->code)
                    <div class="meta-item">
                        <span class="meta-label">Code:</span>
                        <span>{{ $course->code }}</span>
                    </div>
                @endif
                @if($teachers->count() > 0)
                    <div class="meta-item">
                        <span class="meta-label">Instructors:</span>
                        <span>{{ $teachers->implode(', ') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($modules->count() > 0)
        <div class="modules-grid">
            @foreach($modules as $module)
                <div class="module-card">
                    <div class="module-card-header">
                        <div class="module-icon">📖</div>
                        <div class="module-title">{{ $module['title'] }}</div>
                        <div class="module-position">Module {{ $module['position'] ?? 'N/A' }}</div>
                    </div>

                    <div class="module-body">
                        <div class="module-description">
                            {{ $module['description'] ?: 'No description available for this module.' }}
                        </div>

                        <div class="module-stats">
                            <div class="stat-item">
                                <div class="stat-value">{{ $module['item_count'] ?? $module['lesson_count'] ?? 0 }}</div>
                                <div class="stat-label">Items</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $module['quiz_count'] ?? 0 }}</div>
                                <div class="stat-label">Quizzes</div>
                            </div>
                        </div>
                    </div>

                    <div class="module-footer">
                        <div class="module-progress">
                            <div class="progress-label">
                                <span>Progress</span>
                                <span>{{ $module['completion_percentage'] ?? 0 }}%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $module['completion_percentage'] ?? 0 }}%"></div>
                            </div>
                        </div>

                        <div class="module-actions">
                            <a href="{{ route('student.modules.show', $module['id']) }}" class="btn btn-primary">Continue</a>
                            <a href="{{ route('student.modules.show', $module['id']) }}" class="btn btn-secondary">Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <h3>No Modules Available</h3>
            <p>This course doesn't have any modules yet. Check back later for updates.</p>
        </div>
    @endif
</div>
@endsection
