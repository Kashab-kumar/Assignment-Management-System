@extends('layouts.admin')

@section('title', 'Edit Student')
@section('page-title', 'Edit Student: ' . $student->name)

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
    
    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
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
</style>

<div class="form-container">
    <h2 style="margin-top: 0; color: #333;">Edit Student</h2>
    
    <form action="{{ route('admin.students.update', $student) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $student->name) }}" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $student->email) }}" required>
        </div>
        
        <div class="form-group">
            <label for="course_id">Course</label>
            <select id="course_id" name="course_id" required>
                <option value="">Select Course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ $student->course_id == $course->id ? 'selected' : '' }}>
                        {{ $course->name }} ({{ $course->code }})
                    </option>
                @endforeach
            </select>
        </div>
        
        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">Update Student</button>
            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection