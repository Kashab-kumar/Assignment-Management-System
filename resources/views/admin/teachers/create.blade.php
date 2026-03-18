@extends('layouts.admin')

@section('title', 'Add Teacher')
@section('page-title', 'Add New Teacher')

@section('content')
<style>
    .form-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 30px;
        max-width: 600px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
    }
    
    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .course-list {
        border: 1px solid #ddd;
        border-radius: 6px;
        max-height: 220px;
        overflow-y: auto;
        padding: 8px;
        background: #fafafa;
    }

    .course-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 4px;
        font-size: 14px;
    }

    .course-item input[type="checkbox"] {
        width: auto;
    }
    
    .form-group small {
        color: #666;
        font-size: 12px;
        display: block;
        margin-top: 5px;
    }
    
    .btn {
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        border: none;
        cursor: pointer;
    }
    
    .btn-primary {
        background: #9C27B0;
        color: white;
    }
    
    .btn-secondary {
        background: #666;
        color: white;
        margin-left: 10px;
    }
    
    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        padding: 12px;
        border-radius: 4px;
        margin-bottom: 20px;
        border: 1px solid #f5c6cb;
    }
</style>

<div class="form-container">
    <h2 style="margin-top: 0; color: #333;">Add New Teacher</h2>
    
    @if($errors->any())
    <div class="alert-danger">
        <strong>Please fix the following errors:</strong>
        <ul style="margin: 10px 0 0 20px;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <form action="{{ route('admin.teachers.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name">Full Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g., John Smith">
        </div>
        
        <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="teacher@example.com">
        </div>
        
        <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" id="password" name="password" required>
            <small>Minimum 8 characters</small>
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Confirm Password *</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
        
        <div class="form-group">
            <label for="teacher_id">Teacher ID *</label>
            <input type="text" id="teacher_id" name="teacher_id" value="{{ old('teacher_id') }}" required placeholder="e.g., TCH001">
            <small>Unique identifier for the teacher</small>
        </div>
        
        <div class="form-group">
            <label for="subject">Subject *</label>
            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required placeholder="e.g., Mathematics">
        </div>

        <div class="form-group">
            <label>Assigned Modules / Courses</label>
            <div class="course-list">
                @forelse($courses as $course)
                    <label class="course-item">
                        <input type="checkbox" name="course_ids[]" value="{{ $course->id }}" {{ in_array($course->id, old('course_ids', [])) ? 'checked' : '' }}>
                        <span>{{ $course->category_name ?: 'Uncategorized' }} / {{ $course->class_name ?: 'Unassigned' }} / {{ $course->name }}</span>
                    </label>
                @empty
                    <p style="padding: 6px; color: #666;">No courses available.</p>
                @endforelse
            </div>
            <small>Only selected modules/courses will be visible to this teacher.</small>
        </div>
        
        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">Create Teacher Account</button>
            <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection