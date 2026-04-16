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
    }

    .module-description {
        color: #4b5563;
        margin-bottom: 20px;
        line-height: 1.6;
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
            <div class="header-actions">
                <a href="{{ route('teacher.modules.create') }}" class="btn btn-primary">+ Add Module</a>
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
                        {{ Str::limit($module->description, 100) }}
                    </div>
                    
                    <div class="module-weightage">
                        Weightage: {{ $module->weightage ?? 0 }}%
                    </div>
                    
                    <div class="module-stats">
                        <div class="stat-item">
                            <div class="stat-value">{{ $module->assignments->count() }}</div>
                            <div class="stat-label">Assignments</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $module->exams->count() }}</div>
                            <div class="stat-label">Exams</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $module->lesson_count ?? 0 }}</div>
                            <div class="stat-label">Lessons</div>
                        </div>
                    </div>
                    
                    <div class="module-actions">
                        <a href="{{ route('teacher.modules.show', $module) }}" class="btn btn-primary">Manage</a>
                        <a href="{{ route('teacher.modules.edit', $module) }}" class="btn btn-secondary">Edit</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">{$module->title[0]}</div>
            <h3>No Modules Yet</h3>
            <p>Start by creating your first module to manage course activities</p>
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
