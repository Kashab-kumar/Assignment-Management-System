@extends('layouts.admin')

@section('title', $teacher->name)
@section('page-title', $teacher->name)

@section('content')
<style>
    .teacher-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 30px;
    }
    
    .teacher-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .teacher-id {
        font-family: monospace;
        background: #f0f0f0;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 14px;
        color: #666;
    }
    
    .subject-badge {
        background: #2196F3;
        color: white;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .teacher-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .info-card {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 4px;
    }
    
    .info-card h4 {
        margin: 0 0 10px 0;
        color: #666;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .info-card p {
        margin: 0;
        font-size: 18px;
        color: #333;
        font-weight: 600;
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
</style>

<div class="teacher-container">
    @if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
    @endif
    
    <div class="teacher-header">
        <div>
            <h1 style="margin: 0 0 10px 0; color: #333;">{{ $teacher->name }}</h1>
            <div class="teacher-id">{{ $teacher->teacher_id }}</div>
        </div>
        <div>
            <span class="subject-badge">{{ $teacher->subject }}</span>
        </div>
    </div>
    
    <div class="teacher-info">
        <div class="info-card">
            <h4>Email</h4>
            <p>{{ $teacher->email }}</p>
        </div>
        
        <div class="info-card">
            <h4>User ID</h4>
            <p>{{ $teacher->user->id ?? 'N/A' }}</p>
        </div>
        
        <div class="info-card">
            <h4>Account Created</h4>
            <p>{{ $teacher->created_at->format('F d, Y') }}</p>
        </div>
        
        <div class="info-card">
            <h4>Last Updated</h4>
            <p>{{ $teacher->updated_at->format('F d, Y') }}</p>
        </div>
    </div>
    
    <div style="margin-top: 20px;">
        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-edit">Edit Teacher</a>
        <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-delete" onclick="return confirm('Delete this teacher?')">Delete Teacher</button>
        </form>
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-back">← Back to Teachers</a>
    </div>
</div>
@endsection