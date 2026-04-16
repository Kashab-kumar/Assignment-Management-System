@extends('layouts.teacher')

@section('title', $module->title)
@section('page-title', $module->title)

@section('content')
<style>
    .module-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .module-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 32px;
        color: white;
    }

    .module-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .module-course {
        font-size: 18px;
        opacity: 0.9;
        margin-bottom: 16px;
    }

    .module-description {
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 24px;
    }

    .module-weightage {
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
    }

    .module-outline {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 32px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .outline-title {
        font-size: 20px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 16px;
    }

    .outline-content {
        color: #4b5563;
        line-height: 1.6;
        white-space: pre-wrap;
    }

    .activities-section {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 32px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 24px;
        font-weight: 600;
        color: #1f2937;
    }

    .activities-grid {
        display: grid;
        gap: 20px;
    }

    .activity-card {
        background: #f9fafb;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .activity-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .activity-header {
        display: flex;
        align-items: center;
        margin-bottom: 16px;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        font-size: 18px;
        color: white;
    }

    .activity-icon.assignment {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .activity-icon.exam {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .activity-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .activity-type {
        font-size: 12px;
        color: #6b7280;
        text-transform: uppercase;
        font-weight: 600;
    }

    .activity-description {
        color: #4b5563;
        margin-bottom: 16px;
        line-height: 1.5;
    }

    .activity-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .activity-weightage {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .activity-date {
        font-size: 14px;
        color: #6b7280;
    }

    .activity-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-active {
        background: #dcfce7;
        color: #16a34a;
    }

    .status-due-soon {
        background: #fef3c7;
        color: #d97706;
    }

    .status-overdue {
        background: #fee2e2;
        color: #dc2626;
    }

    .status-upcoming {
        background: #dbeafe;
        color: #2563eb;
    }

    .status-completed {
        background: #e5e7eb;
        color: #6b7280;
    }

    .activity-actions {
        display: flex;
        gap: 12px;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #4b5563;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .empty-activities {
        text-align: center;
        padding: 40px;
        color: #6b7280;
    }

    .empty-activities-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .module-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 14px;
        color: #6b7280;
    }

    @media (max-width: 768px) {
        .module-stats {
            grid-template-columns: 1fr;
        }
        
        .activity-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
    }
</style>

<div class="module-container">
    <div class="module-header">
        <h1 class="module-title">{{ $module->title }}</h1>
        <div class="module-course">Course: {{ $module->course->name }}</div>
        <p class="module-description">{{ $module->description }}</p>
        <div class="module-weightage">Weightage: {{ $module->weightage ?? 0 }}%</div>
    </div>

    <div class="module-stats">
        <div class="stat-card">
            <div class="stat-value">{{ $module->assignments->count() }}</div>
            <div class="stat-label">Assignments</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $module->exams->count() }}</div>
            <div class="stat-label">Exams</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $module->course->students->count() }}</div>
            <div class="stat-label">Students</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $module->weightage ?? 0 }}%</div>
            <div class="stat-label">Module Weightage</div>
        </div>
    </div>

    @if($module->model_unit_outline)
        <div class="module-outline">
            <h3 class="outline-title">Model Unit Outline</h3>
            <div class="outline-content">{{ $module->model_unit_outline }}</div>
        </div>
    @endif

    <div class="activities-section">
        <div class="section-header">
            <h2 class="section-title">Module Activities</h2>
            <div>
                <a href="{{ route('teacher.assignments.create', ['module_id' => $module->id]) }}" class="btn btn-primary">+ Assignment</a>
                <a href="{{ route('teacher.exams.create', ['module_id' => $module->id]) }}" class="btn btn-success">+ Exam</a>
            </div>
        </div>

        @if($activities->count() > 0)
            <div class="activities-grid">
                @foreach($activities as $activity)
                    <div class="activity-card">
                        <div class="activity-header">
                            <div class="activity-icon {{ $activity['type'] }}">
                                {{ $activity['type'] == 'assignment' ? 'A' : 'E' }}
                            </div>
                            <div>
                                <div class="activity-title">{{ $activity['title'] }}</div>
                                <div class="activity-type">{{ $activity['type'] }}</div>
                            </div>
                        </div>
                        
                        <div class="activity-description">
                            {{ Str::limit($activity['description'], 150) }}
                        </div>
                        
                        <div class="activity-meta">
                            <div class="activity-weightage">Weightage: {{ $activity['weightage'] }}%</div>
                            <div class="activity-date">
                                @if($activity['type'] == 'assignment')
                                    Due: {{ $activity['due_date'] ? \Carbon\Carbon::parse($activity['due_date'])->format('M d, Y') : 'No due date' }}
                                @else
                                    Date: {{ $activity['exam_date'] ? \Carbon\Carbon::parse($activity['exam_date'])->format('M d, Y') : 'No date set' }}
                                @endif
                            </div>
                        </div>
                        
                        <div class="activity-status status-{{ $activity['status'] }}">
                            {{ ucfirst(str_replace('_', ' ', $activity['status'])) }}
                        </div>
                        
                        <div class="activity-actions">
                            @if($activity['type'] == 'assignment')
                                <a href="{{ route('teacher.assignments.show', $activity['id']) }}" class="btn btn-primary">View</a>
                                <a href="{{ route('teacher.assignments.edit', $activity['id']) }}" class="btn btn-secondary">Edit</a>
                                <a href="{{ route('teacher.assignments.grading', $activity['id']) }}" class="btn btn-success">Grade</a>
                            @else
                                <a href="{{ route('teacher.exams.show', $activity['id']) }}" class="btn btn-primary">View</a>
                                <a href="{{ route('teacher.exams.edit', $activity['id']) }}" class="btn btn-secondary">Edit</a>
                                <a href="{{ route('teacher.exams.results', $activity['id']) }}" class="btn btn-success">Results</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-activities">
                <div class="empty-activities-icon">{$module->title[0]}</div>
                <h3>No Activities Yet</h3>
                <p>Create assignments and exams for this module to get started</p>
                <div>
                    <a href="{{ route('teacher.assignments.create', ['module_id' => $module->id]) }}" class="btn btn-primary">Create Assignment</a>
                    <a href="{{ route('teacher.exams.create', ['module_id' => $module->id]) }}" class="btn btn-success">Create Exam</a>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activity card interactions
        const activityCards = document.querySelectorAll('.activity-card');
        
        activityCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endsection
