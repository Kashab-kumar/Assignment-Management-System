@extends('layouts.admin')

@section('title', 'Courses')
@section('page-title', 'Courses Management')

@section('content')
<style>
    .courses-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 20px;
    }

    .courses-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-form {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-select {
        min-width: 220px;
        padding: 8px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .tree {
        border: 1px solid #ececf3;
        border-radius: 10px;
        overflow: hidden;
    }

    .tree details {
        border-bottom: 1px solid #ececf3;
        background: #fff;
    }

    .tree details:last-child {
        border-bottom: none;
    }

    .tree summary {
        list-style: none;
        cursor: pointer;
    }

    .tree summary::-webkit-details-marker {
        display: none;
    }

    .category-summary,
    .class-summary {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 18px;
        font-weight: 700;
        color: #142a68;
    }

    .category-summary {
        background: #f6f7fb;
        font-size: 24px;
    }

    .class-summary {
        padding-left: 36px;
        font-size: 17px;
        background: #fcfcfe;
    }

    .tree-caret {
        width: 0;
        height: 0;
        border-left: 6px solid #8d91a7;
        border-top: 5px solid transparent;
        border-bottom: 5px solid transparent;
        transition: transform .2s ease;
    }

    details[open] > summary .tree-caret {
        transform: rotate(90deg);
    }

    .course-list {
        padding: 0 18px 12px 54px;
    }

    .course-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 14px;
        align-items: center;
        padding: 12px 0;
        border-top: 1px solid #efeff5;
    }

    .course-row:first-child {
        border-top: none;
    }

    .course-code {
        font-family: monospace;
        background: #f0f0f0;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        color: #666;
    }

    .course-name {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 4px 0 8px;
    }

    .course-description {
        color: #666;
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .course-meta {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 10px;
    }

    .students-count {
        background: #2196F3;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-active { background: #4CAF50; color: white; }
    .status-inactive { background: #f44336; color: white; }

    .btn {
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 13px;
        margin-right: 5px;
    }

    .btn-view { background: #4CAF50; color: white; }
    .btn-edit { background: #2196F3; color: white; }
    .btn-delete { background: #f44336; color: white; }
    .btn-add { background: #9C27B0; color: white; padding: 8px 16px; }

    .course-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }
</style>

<div class="courses-container">
    <div class="courses-header">
        <div>
            <h2 style="margin: 0; color: #333;">Course Categories ({{ $courses->count() }})</h2>
            <p style="margin-top: 6px; color: #666;">Browse courses as Category → Class → Subject</p>
            @if($selectedCategory || $selectedClass)
                <p style="margin-top: 6px; color: #666;">
                    Filter:
                    @if($selectedCategory)<strong>Category: {{ $selectedCategory }}</strong>@endif
                    @if($selectedCategory && $selectedClass) | @endif
                    @if($selectedClass)<strong>Class: {{ $selectedClass }}</strong>@endif
                </p>
            @endif
        </div>

        <div class="filter-form">
            <form method="GET" action="{{ route('admin.courses.index') }}" class="filter-form">
                <select name="category_name" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categoryOptions as $categoryName)
                        <option value="{{ $categoryName }}" {{ $selectedCategory === $categoryName ? 'selected' : '' }}>{{ $categoryName }}</option>
                    @endforeach
                </select>
                <select name="class_name" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Classes</option>
                    @foreach($classOptions as $className)
                        <option value="{{ $className }}" {{ $selectedClass === $className ? 'selected' : '' }}>{{ $className }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-add">+ Add New Course</a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    @if($courses->count() > 0)
        <div class="tree">
            @foreach($courseTree as $categoryName => $classes)
                <details open>
                    <summary class="category-summary">
                        <span class="tree-caret"></span>
                        <span>{{ $categoryName }}</span>
                    </summary>

                    @foreach($classes as $className => $classCourses)
                        <details>
                            <summary class="class-summary">
                                <span class="tree-caret"></span>
                                <span>{{ $className }}</span>
                            </summary>

                            <div class="course-list">
                                @foreach($classCourses as $course)
                                    <div class="course-row">
                                        <div>
                                            <div class="course-code">{{ $course->code }}</div>
                                            <div class="course-name">{{ $course->name }}</div>
                                            @if($course->description)
                                                <div class="course-description">{{ Str::limit($course->description, 140) }}</div>
                                            @endif
                                            <div class="course-meta">
                                                <span class="students-count">{{ $course->students_count }} student(s)</span>
                                                <span class="status-badge status-{{ $course->is_active ? 'active' : 'inactive' }}">
                                                    {{ $course->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="course-actions">
                                            <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-view">View</a>
                                            <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-edit">Edit</a>
                                            <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-delete" onclick="return confirm('Delete this course?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    @endforeach
                </details>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <svg viewBox="0 0 24 24" style="width: 60px; height: 60px; fill: #ddd; margin-bottom: 15px;">
                <path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/>
            </svg>
            <h3>No Courses Found</h3>
            <p>Create your first category/class/course entry to get started.</p>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-add" style="margin-top: 10px;">+ Create First Course</a>
        </div>
    @endif
</div>
@endsection
