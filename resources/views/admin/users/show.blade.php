@extends('layouts.admin')

@section('title', $user->name)
@section('page-title', $user->name)

@section('content')
<style>
    .user-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 30px;
    }
    
    .user-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .role-badge {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .role-admin { background: #9C27B0; color: white; }
    .role-teacher { background: #2196F3; color: white; }
    .role-student { background: #4CAF50; color: white; }
    
    .info-grid {
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
        font-weight: 600;
        color: #333;
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

<div class="user-container">
    <div class="user-header">
        <div>
            <h1 style="margin: 0 0 10px 0; color: #333;">{{ $user->name }}</h1>
            <p style="margin: 0; color: #666;">User ID: {{ $user->id }}</p>
        </div>
        <div>
            <span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
        </div>
    </div>
    
    <div class="info-grid">
        <div class="info-card">
            <h4>Email</h4>
            <p>{{ $user->email }}</p>
        </div>
        
        <div class="info-card">
            <h4>Role</h4>
            <p>{{ ucfirst($user->role) }}</p>
        </div>
        
        <div class="info-card">
            <h4>Account Created</h4>
            <p>{{ $user->created_at->format('F d, Y') }}</p>
        </div>
        
        <div class="info-card">
            <h4>Last Updated</h4>
            <p>{{ $user->updated_at->format('F d, Y') }}</p>
        </div>
    </div>
    
    @if($user->student)
    <div style="margin-top: 20px; padding: 20px; background: #e8f5e9; border-radius: 4px;">
        <h3 style="margin-top: 0; color: #2e7d32;">Student Information</h3>
        <p><strong>Student ID:</strong> {{ $user->student->student_id }}</p>
        <p><strong>Course:</strong> {{ $user->student->course->name ?? 'Not assigned' }}</p>
    </div>
    @endif
    
    @if($user->teacher)
    <div style="margin-top: 20px; padding: 20px; background: #e3f2fd; border-radius: 4px;">
        <h3 style="margin-top: 0; color: #1565c0;">Teacher Information</h3>
        <p><strong>Teacher ID:</strong> {{ $user->teacher->teacher_id }}</p>
        <p><strong>Subject:</strong> {{ $user->teacher->subject }}</p>
    </div>
    @endif
    
    <div style="margin-top: 30px;">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-edit">Edit User</a>
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-delete" onclick="return confirm('Delete this user?')">Delete User</button>
        </form>
        <a href="{{ route('admin.users.index') }}" class="btn btn-back">← Back to Users</a>
    </div>
</div>
@endsection