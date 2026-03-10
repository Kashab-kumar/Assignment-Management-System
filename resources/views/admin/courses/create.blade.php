@extends('layouts.admin')

@section('title', 'Create Course')
@section('page-title', 'Create New Course')

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
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .form-group textarea {
        min-height: 100px;
        resize: vertical;
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
    <h2 style="margin-top: 0; color: #333;">Create New Course</h2>
    
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
    
    <form action="{{ route('admin.courses.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="name">Course Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g., Introduction to Programming">
            <small>The full name of the course</small>
        </div>
        
        <div class="form-group">
            <label for="code">Course Code *</label>
            <input type="text" id="code" name="code" value="{{ old('code') }}" required placeholder="e.g., CS101">
            <small>Unique course code (e.g., CS101, MATH201)</small>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" placeholder="Course description...">{{ old('description') }}</textarea>
            <small>Optional course description</small>
        </div>
        
        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">Create Course</button>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection