@extends('layouts.admin')

@section('title', 'Teachers')
@section('page-title', 'Teachers Management')

@section('content')
<style>
    .teachers-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 20px;
    }
    
    .teachers-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }
    
    .teachers-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .teachers-table th {
        background: #f8f9fa;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }
    
    .teachers-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }
    
    .teachers-table tr:hover {
        background: #f8f9fa;
    }
    
    .teacher-id {
        font-family: monospace;
        background: #f0f0f0;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 12px;
    }
    
    .subject-badge {
        background: #2196F3;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
    
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
    .btn-invite { background: #03A9F4; color: white; }
</style>

<div class="teachers-container">
    <div class="teachers-header">
        <h2 style="margin: 0; color: #333;">All Teachers ({{ $teachers->total() }})</h2>
        <div class="header-actions">
            <a href="{{ route('admin.invitations.create', ['role' => 'teacher']) }}" class="btn btn-invite">+ Generate Invite Link</a>
            <a href="{{ route('admin.teachers.create') }}" class="btn btn-add">+ Add New Teacher</a>
        </div>
    </div>
    
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif
    
    @if($teachers->count() > 0)
        <table class="teachers-table">
            <thead>
                <tr>
                    <th>Teacher ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teachers as $teacher)
                <tr>
                    <td><span class="teacher-id">{{ $teacher->teacher_id }}</span></td>
                    <td>
                        <strong>{{ $teacher->name }}</strong>
                        @if($teacher->user)
                            <div style="font-size: 12px; color: #666;">User ID: {{ $teacher->user->id }}</div>
                        @endif
                    </td>
                    <td>{{ $teacher->email }}</td>
                    <td><span class="subject-badge">{{ $teacher->subject }}</span></td>
                    <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.teachers.show', $teacher) }}" class="btn btn-view">View</a>
                        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-edit">Edit</a>
                        <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Delete this teacher?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="margin-top: 20px;">
            {{ $teachers->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <svg viewBox="0 0 24 24" style="width: 60px; height: 60px; fill: #ddd; margin-bottom: 15px;">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            <h3>No Teachers Found</h3>
            <p>Add your first teacher to get started.</p>
            <a href="{{ route('admin.teachers.create') }}" class="btn btn-add" style="margin-top: 10px;">+ Add First Teacher</a>
        </div>
    @endif
</div>
@endsection