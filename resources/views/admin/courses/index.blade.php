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
    }
    
    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .course-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        transition: all 0.3s ease;
    }
    
    .course-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .course-card.active {
        border-left: 4px solid #4CAF50;
    }
    
    .course-card.inactive {
        border-left: 4px solid #f44336;
        opacity: 0.8;
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
        margin: 10px 0;
    }
    
    .course-description {
        color: #666;
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 15px;
    }
    
    .course-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
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
</style>

<div class="courses-container">
    <div class="courses-header">
        <h2 style="margin: 0; color: #333;">All Courses ({{ $courses->total() }})</h2>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-add">+ Add New Course</a>
    </div>
    
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif
    
    @if($courses->count() > 0)
        <div class="courses-grid">
            @foreach($courses as $course)
            <div class="course-card {{ $course->is_active ? 'active' : 'inactive' }}">
                <div class="course-code">{{ $course->code }}</div>
                <div class="course-name">{{ $course->name }}</div>
                
                @if($course->description)
                <div class="course-description">
                    {{ Str::limit($course->description, 100) }}
                </div>
                @endif
                
                <div class="course-meta">
                    <div>
                        <span class="students-count">{{ $course->students_count }} student(s)</span>
                    </div>
                    <div>
                        <span class="status-badge status-{{ $course->is_active ? 'active' : 'inactive' }}">
                            {{ $course->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                
                <div style="margin-top: 15px; display: flex; gap: 5px;">
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
        
        <div style="margin-top: 20px;">
            {{ $courses->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <svg viewBox="0 0 24 24" style="width: 60px; height: 60px; fill: #ddd; margin-bottom: 15px;">
                <path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/>
            </svg>
            <h3>No Courses Found</h3>
            <p>Create your first course to get started.</p>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-add" style="margin-top: 10px;">+ Create First Course</a>
        </div>
    @endif
</div>
@endsection