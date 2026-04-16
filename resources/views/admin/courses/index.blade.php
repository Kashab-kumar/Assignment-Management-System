@extends('layouts.admin')

@section('title', 'Courses')
@section('page-title', 'Courses Management')

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
        position: relative;
    }

    .header-actions {
        position: absolute;
        top: 32px;
        right: 32px;
        display: flex;
        gap: 12px;
        align-items: center;
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

    .btn-add {
        background: white;
        color: #667eea;
        text-decoration: none;
        border-radius: 12px;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-add-module {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        text-decoration: none;
        border-radius: 12px;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
        margin-left: 12px;
    }

    .btn-add-module:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(16, 185, 129, 0.4);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        background: #f3f4f6;
    }

    .courses-filters {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 32px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .filter-select {
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        background: white;
        color: #374151;
        transition: border-color 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 8px;
    }

    .btn-filter {
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-clear {
        background: #f3f4f6;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }

    .btn-clear:hover {
        background: #e5e7eb;
        border-color: #9ca3af;
    }

    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 24px;
    }

    .course-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }

    .course-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .course-header {
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .course-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        margin-bottom: 12px;
    }

    .course-title {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .course-code {
        font-size: 12px;
        color: #6b7280;
        background: #f3f4f6;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
        font-family: monospace;
    }

    .course-body {
        padding: 20px;
    }

    .course-meta {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 16px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #4b5563;
    }

    .meta-item::before {
        content: "📍";
        font-size: 12px;
    }

    .meta-item.category::before {
        content: "📂";
    }

    .meta-item.class::before {
        content: "🏫";
    }

    .course-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .stat-item {
        background: #f9fafb;
        padding: 12px;
        border-radius: 8px;
        text-align: center;
    }

    .stat-value {
        font-size: 18px;
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

    .course-description {
        font-size: 14px;
        color: #4b5563;
        line-height: 1.6;
        margin-bottom: 20px;
        min-height: 60px;
    }

    .course-footer {
        padding: 20px;
        border-top: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .course-actions {
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
        color: #6b7280;
        border: 1px solid #d1d5db;
    }

    .btn-secondary:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
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
            text-align: center;
        }
        
        .courses-header h1 {
            font-size: 24px;
        }
        
        .btn-add {
            position: static;
            margin-top: 16px;
            display: inline-block;
        }
        
        .filters-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="courses-container">
    <div class="courses-header">
        <h1>Courses Management</h1>
        <p>Manage and monitor all courses in the system</p>
        <div class="header-actions">
            <a href="{{ route('admin.courses.create') }}" class="btn-add">+ Add New Course</a>
            <a href="{{ route('teacher.modules.create') }}" class="btn-add-module">+ Add Module</a>
        </div>
    </div>

    <div class="courses-filters">
        <form method="GET" class="filters-grid">
            <div class="filter-group">
                <label for="category_name">Category</label>
                <select id="category_name" name="category_name" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categoryOptions as $cat)
                        <option value="{{ $cat }}" {{ $selectedCategory === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label for="class_name">Class</label>
                <select id="class_name" name="class_name" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Classes</option>
                    @foreach($classOptions as $cls)
                        <option value="{{ $cls }}" {{ $selectedClass === $cls ? 'selected' : '' }}>{{ $cls }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label>&nbsp;</label>
                <div class="filter-actions">
                    @if($selectedCategory || $selectedClass)
                        <a href="{{ route('admin.courses.index') }}" class="btn-filter btn-clear">Clear Filters</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    @if($courses->count() > 0)
        <div class="courses-grid">
            @foreach($courses as $course)
                <div class="course-card">
                    <div class="course-header">
                        <div class="course-icon">📚</div>
                        <div class="course-title">{{ $course->name }}</div>
                        <div class="course-code">{{ $course->code ?? 'COURSE-' . $course->id }}</div>
                    </div>
                    
                    <div class="course-body">
                        <div class="course-meta">
                            <div class="meta-item category">{{ $course->category_name ?: 'Uncategorized' }}</div>
                            <div class="meta-item class">{{ $course->class_name ?: 'Unassigned' }}</div>
                        </div>
                        
                        <div class="course-stats">
                            <div class="stat-item">
                                <div class="stat-value">{{ $course->students_count ?? 0 }}</div>
                                <div class="stat-label">Students</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $course->modules_count ?? 0 }}</div>
                                <div class="stat-label">Modules</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $course->assignments_count ?? 0 }}</div>
                                <div class="stat-label">Assignments</div>
                            </div>
                        </div>
                        
                        <div class="course-description">
                            {{ $course->description ?: 'No description available for this course.' }}
                        </div>
                    </div>
                    
                    <div class="course-footer">
                        <div class="course-actions">
                            <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-primary">Manage</a>
                            <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-secondary">Edit</a>
                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this course?')">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <h3>No Courses Found</h3>
            <p>No courses match your current filters. Try adjusting your search criteria or <a href="{{ route('admin.courses.create') }}" style="color: #667eea;">create a new course</a>.</p>
        </div>
    @endif
</div>
@endsection
