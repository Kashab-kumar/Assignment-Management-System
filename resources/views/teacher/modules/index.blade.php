@extends('layouts.teacher')

@section('title', 'Module Management')
@section('page-title', 'Module Management')

@section('content')
<style>
    .modules-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .modules-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 32px;
        color: white;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-text h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .header-text p {
        font-size: 16px;
        opacity: 0.9;
    }

    
    .modules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .module-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .module-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    .module-header {
        display: flex;
        align-items: center;
        margin-bottom: 16px;
    }

    .module-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        color: white;
        font-size: 20px;
    }

    .module-title {
        font-size: 20px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .module-course {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .module-description {
        color: #4b5563;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .module-framework {
        background: linear-gradient(135deg, #fef3c7 0%, #fbbf24 100%);
        border: 3px solid #f59e0b;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(245, 158, 11, 0.1);
    }

    .framework-header {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e2e8f0;
    }

    .framework-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
        margin-right: 12px;
    }

    .framework-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
    }

    .framework-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 12px;
    }

    .framework-item {
        display: flex;
        flex-direction: column;
    }

    .framework-label {
        font-size: 12px;
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .framework-value {
        font-size: 14px;
        color: #374151;
        font-weight: 500;
    }

    .framework-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 12px;
        border-top: 1px solid #e2e8f0;
    }

    .framework-status {
        background: #dcfce7;
        color: #166534;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .framework-progress {
        width: 120px;
        height: 6px;
        background: #e5e7eb;
        border-radius: 3px;
        overflow: hidden;
    }

    .framework-progress-bar {
        height: 100%;
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    .module-activities {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .activities-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .activities-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
    }

    .activities-actions {
        display: flex;
        gap: 12px;
    }

    .activity-section {
        margin-bottom: 20px;
    }

    .activity-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .activity-section-header h4 {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .activity-count {
        background: #10b981;
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .activity-list {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .activity-item {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 12px;
        transition: transform 0.3s ease;
    }

    .activity-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .activity-icon {
        font-size: 16px;
        margin-bottom: 8px;
    }

    .activity-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .activity-title {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
        flex: 1;
    }

    .activity-meta {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .activity-type {
        font-size: 12px;
        color: #6b7280;
        background: #f3f4f6;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .activity-weightage {
        font-size: 12px;
        color: #10b981;
        background: #dcfce7;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .activity-action {
        display: flex;
        gap: 8px;
    }

    .view-all {
        text-align: center;
        margin-top: 12px;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-sm:hover {
        transform: translateY(-1px);
    }

    .btn-outline {
        background: transparent;
        border: 1px solid #667eea;
        color: #667eea;
    }

    .btn-outline:hover {
        background: #667eea;
        color: white;
    }

    .module-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .stat-item {
        text-align: center;
        padding: 12px;
        background: #f9fafb;
        border-radius: 8px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
    }

    .stat-label {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }

    .module-weightage {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 16px;
    }

    .module-actions {
        display: flex;
        gap: 12px;
    }

    .btn {
        padding: 10px 20px;
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

    .add-module-btn {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
    }

    .add-module-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 16px;
    }

    .empty-state h3 {
        font-size: 24px;
        margin-bottom: 8px;
        color: #4b5563;
    }

    .empty-state p {
        margin-bottom: 24px;
    }

    @media (max-width: 768px) {
        .modules-grid {
            grid-template-columns: 1fr;
        }
        
        .module-stats {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="modules-container">
    <div class="modules-header">
        <div class="header-content">
            <div class="header-text">
                <h1>Module Management</h1>
                <p>Manage your course modules, assignments, and activities</p>
            </div>
        </div>
    </div>

    @if($modules->count() > 0)
        <div class="modules-grid">
            @foreach($modules as $module)
                <div class="module-card">
                    <div class="module-header">
                        <div class="module-icon">{{ substr($module->title, 0, 1) }}</div>
                        <div>
                            <div class="module-title">{{ $module->title }}</div>
                            <div class="module-course">{{ $module->course->name ?? 'General Course' }}</div>
                        </div>
                    </div>
                    
                    <div class="module-description">
                        {{ Str::limit($module->description, 150) }}
                    </div>
                    
                    <div class="module-activities">
                        <div class="activities-header">
                            <div class="activities-title">Activities</div>
                            <div class="activities-actions">
                                <a href="{{ route('teacher.assignments.create', ['module_id' => $module->id]) }}" class="btn btn-success">+ Assignment</a>
                                <a href="{{ route('teacher.exams.create', ['module_id' => $module->id, 'mode' => 'exam']) }}" class="btn btn-info">+ Exam</a>
                                <a href="{{ route('teacher.exams.create', ['module_id' => $module->id, 'mode' => 'quiz']) }}" class="btn btn-warning">+ Quiz</a>
                                <a href="{{ route('teacher.exams.create', ['module_id' => $module->id, 'mode' => 'test']) }}" class="btn btn-secondary">+ Test</a>
                            </div>
                        </div>
                        
                        @if($module->assignments->count() > 0)
                            <div class="activity-section">
                                <div class="activity-section-header">
                                    <h4>Assignments</h4>
                                    <span class="activity-count">{{ $module->assignments->count() }} Active</span>
                                </div>
                                <div class="activity-list">
                                    @foreach($module->assignments->take(5) as $assignment)
                                        <div class="activity-item">
                                            <div class="activity-icon">📝</div>
                                            <div class="activity-details">
                                                <div class="activity-title">{{ $assignment->title }}</div>
                                                <div class="activity-meta">
                                                    <span class="activity-type">{{ ucfirst($assignment->type ?? 'Assignment') }}</span>
                                                    <span class="activity-weightage">{{ $assignment->weightage ?? 0 }}%</span>
                                                </div>
                                            </div>
                                            <div class="activity-action">
                                                <a href="{{ route('teacher.assignments.show', $assignment) }}" class="btn btn-sm">View</a>
                                                <a href="{{ route('teacher.assignments.edit', $assignment) }}" class="btn btn-sm btn-secondary">Edit</a>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($module->assignments->count() > 5)
                                        <div class="view-all">
                                            <a href="{{ route('teacher.modules.show', $module) }}" class="btn btn-outline">View All {{ $module->assignments->count() }} Assignments</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        @if($module->exams->count() > 0)
                            <div class="activity-section">
                                <div class="activity-section-header">
                                    <h4>Exams</h4>
                                    <span class="activity-count">{{ $module->exams->count() }} Active</span>
                                </div>
                                <div class="activity-list">
                                    @foreach($module->exams->take(5) as $exam)
                                        <div class="activity-item">
                                            <div class="activity-icon">📋</div>
                                            <div class="activity-details">
                                                <div class="activity-title">{{ $exam->title }}</div>
                                                <div class="activity-meta">
                                                    <span class="activity-type">{{ ucfirst($exam->type ?? 'Exam') }}</span>
                                                    <span class="activity-weightage">{{ $exam->weightage ?? 0 }}%</span>
                                                </div>
                                            </div>
                                            <div class="activity-action">
                                                <a href="{{ route('teacher.exams.show', $exam) }}" class="btn btn-sm">View</a>
                                                <a href="{{ route('teacher.exams.edit', $exam) }}" class="btn btn-sm btn-secondary">Edit</a>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($module->exams->count() > 5)
                                        <div class="view-all">
                                            <a href="{{ route('teacher.modules.show', $module) }}" class="btn btn-outline">View All {{ $module->exams->count() }} Exams</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        @if($module->lesson_count > 0)
                            <div class="activity-section">
                                <div class="activity-section-header">
                                    <h4>Lessons</h4>
                                    <span class="activity-count">{{ $module->lesson_count }} Active</span>
                                </div>
                                <div class="activity-list">
                                    @for($i = 1; $i <= min($module->lesson_count, 5); $i++)
                                        <div class="activity-item">
                                            <div class="activity-icon">📚</div>
                                            <div class="activity-details">
                                                <div class="activity-title">Lesson {{ $i }}</div>
                                                <div class="activity-meta">
                                                    <span class="activity-type">Unit Content</span>
                                                    <span class="activity-weightage">Lesson Plan</span>
                                                </div>
                                            </div>
                                        @endfor
                                    @if($module->lesson_count > 5)
                                        <div class="view-all">
                                            <a href="{{ route('teacher.modules.show', $module) }}" class="btn btn-outline">View All {{ $module->lesson_count }} Lessons</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="module-footer">
                        <div class="module-weightage">
                            Weightage: {{ $module->weightage ?? 0 }}%
                        </div>
                        
                        <div class="module-actions">
                            <a href="{{ route('teacher.modules.show', $module) }}" class="btn btn-primary">Manage Module</a>
                            <a href="{{ route('teacher.modules.edit', $module) }}" class="btn btn-secondary">Edit Module</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <h3>No Modules Yet</h3>
            <p>Start by creating your first module to manage course activities and assignments</p>
            <a href="{{ route('teacher.modules.create') }}" class="btn btn-primary">Create Module</a>
        </div>
    @endif
</div>


<script>
    // Add any interactive JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // Module card interactions
        const moduleCards = document.querySelectorAll('.module-card');
        
        moduleCards.forEach(card => {
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
