@extends('layouts.admin')

@section('title', 'All Users')
@section('page-title', 'All Users')

@section('content')
<style>
    .users-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 20px;
    }
    
    .users-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .users-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .users-table th {
        background: #f8f9fa;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }
    
    .users-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }
    
    .users-table tr:hover {
        background: #f8f9fa;
    }
    
    .role-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
    }
    
    .role-admin { background: #9C27B0; color: white; }
    .role-teacher { background: #2196F3; color: white; }
    .role-student { background: #4CAF50; color: white; }
    
    .status-active { color: #4CAF50; }
    .status-inactive { color: #f44336; }
    
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
</style>

<div class="users-container">
    <div class="users-header">
        <h2 style="margin: 0; color: #333;">All Users ({{ $users->total() }})</h2>
        <a href="#" class="btn" style="background: #9C27B0; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none;">
            + Add New User
        </a>
    </div>
    
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif
    
    @if($users->count() > 0)
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <strong>{{ $user->name }}</strong>
                        @if($user->student)
                            <div style="font-size: 12px; color: #666;">Student ID: {{ $user->student->student_id ?? 'N/A' }}</div>
                        @elseif($user->teacher)
                            <div style="font-size: 12px; color: #666;">Teacher</div>
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td>
                        <span class="status-active">● Active</span>
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-view">View</a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-edit">Edit</a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Delete this user?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <p>No users found.</p>
        </div>
    @endif
</div>
@endsection