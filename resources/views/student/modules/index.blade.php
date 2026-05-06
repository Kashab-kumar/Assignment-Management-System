@extends('layouts.student')

@section('title', 'Courses')
@section('page-title', 'My Courses')

@section('content')
<style>
    .courses-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .courses-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 32px;
        color: white;
        text-align: center;
    }

    .courses-header h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .courses-header p {
        font-size: 16px;
        opacity: 0.9;
    }

    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 24px;
    }

    .course-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }

    .course-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .course-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 24px;
        color: white;
    }

    .course-card-title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .course-card-code {
        font-size: 13px;
        opacity: 0.9;
        margin-bottom: 4px;
    }

    .course-card-body {
        padding: 20px;
        flex: 1;
    }

    .course-description {
        font-size: 14px;
        color: #4b5563;
        line-height: 1.6;
        margin-bottom: 16px;
        min-height: 50px;
    }

    .course-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .info-item {
        font-size: 13px;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-label {
        font-weight: 600;
        color: #1f2937;
    }

    .course-card-footer {
        padding: 16px 20px;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        text-align: center;
    }

    .btn-view {
        display: inline-block;
        padding: 10px 20px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        width: 100%;
        text-align: center;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
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
        .courses-grid {
            grid-template-columns: 1fr;
        }

        .courses-header {
            padding: 24px;
        }

        .courses-header h1 {
            font-size: 24px;
        }
    }
</style>

<div class="courses-container">
    <div class="courses-header">
        <h1>My Courses</h1>
        <p>Select a course to view its modules and content</p>
    </div>

    @if($courses->count() > 0)
        <div class="courses-grid">
            @foreach($courses as $course)
                <a href="{{ route('student.courses.show', $course['id']) }}" class="course-card">
                    <div class="course-card-header">
                        <div class="course-card-code">{{ $course['code'] ?? 'Course' }}</div>
                        <div class="course-card-title">{{ $course['name'] }}</div>
                    </div>

                    <div class="course-card-body">
                        <div class="course-description">
                            {{ $course['description'] ?: 'No description available.' }}
                        </div>

                        <div class="course-info">
                            @if($course['teachers']->count() > 0)
                                <div class="info-item">
                                    <span class="info-label">Instructors:</span>
                                    <span>{{ $course['teachers']->implode(', ') }}</span>
                                </div>
                            @endif
                            @if($course['category_name'])
                                <div class="info-item">
                                    <span class="info-label">Category:</span>
                                    <span>{{ $course['category_name'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="course-card-footer">
                        <div class="btn-view">View Modules →</div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <h3>No Courses Available</h3>
            <p>You don't have any courses assigned yet. Check back later or contact your instructor.</p>
        </div>
    @endif
</div>
@endsection
