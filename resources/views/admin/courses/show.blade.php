@extends('layouts.admin')

@section('title', $course->name)
@section('page-title', $course->name)

@section('content')
<style>
    .course-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 30px;
    }
    
    .course-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .course-code {
        font-family: monospace;
        background: #f0f0f0;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 14px;
        color: #666;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .status-active { background: #4CAF50; color: white; }
    .status-inactive { background: #f44336; color: white; }
    
    .course-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 30px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 4px;
    }
    
    .students-section {
        margin-top: 30px;
    }
    
    .students-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    
    .students-table th {
        background: #f8f9fa;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }
    
    .students-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }
    
    .students-table tr:hover {
        background: #f8f9fa;
    }
    
    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        margin-right: 10px;
    }
    
    .btn-edit { background: #2196F3; color: white; }
    .btn-delete { background: #f44336; color: white; }
    .btn-back { background: #666; color: white; }
    
    .empty-state {
        text-align: center;
        padding: 40px;
        color: #666;
    }
</style>

<div class="course-container">
    @if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
    @endif
    
    <div class="course-header">
        <div>
            <h1 style="margin: 0 0 10px 0; color: #333;">{{ $course->name }}</h1>
            <div class="course-code">{{ $course->code }}</div>
        </div>
        <div>
            <span class="status-badge status-{{ $course->is_active ? 'active' : 'inactive' }}">
                {{ $course->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>
    
    @if($course->description)
    <div class="course-description">
        {{ $course->description }}
    </div>
    @endif
    
    <div style="margin-top: 20px;">
        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-edit">Edit Course</a>
        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-delete" onclick="return confirm('Delete this course?')">Delete Course</button>
        </form>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-back">← Back to Courses</a>
    </div>
    
    <div class="students-section">
        <h3>Enrolled Students ({{ $course->students->count() }})</h3>
        
        @if($course->students->count() > 0)
        <table class="students-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @foreach($course->students as $student)
                <tr>
                    <td><strong>{{ $student->student_id }}</strong></td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <p>No students enrolled in this course yet.</p>
        </div>
        @endif
    </div>
</div>
@endsection