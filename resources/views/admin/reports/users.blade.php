@extends('layouts.admin')

@section('title', 'Users Report')
@section('page-title', 'Users Report')

@section('content')
<style>
    .report-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 30px;
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
    }

    .report-table th {
        background: #f8f9fa;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }

    .report-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }

    .report-table tr:hover {
        background: #f8f9fa;
    }

    .role-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .role-admin { background: #9C27B0; color: white; }
    .role-teacher { background: #2196F3; color: white; }
    .role-student { background: #4CAF50; color: white; }

    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        margin-right: 10px;
    }

    .btn-back { background: #666; color: white; }
    .btn-export { background: #4CAF50; color: white; }
</style>

<div class="report-container">
    <div class="report-header">
        <h2 style="margin: 0; color: #333;">Users Report</h2>
        <div>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-back">← Back to Reports</a>
            <form action="{{ route('admin.reports.export') }}" method="POST" style="display: inline;">
                @csrf
                <input type="hidden" name="type" value="users">
                <button type="submit" class="btn btn-export">Export as CSV</button>
            </form>
        </div>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Profile ID</th>
                <th>Joined</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>
                    <strong>{{ $user->name }}</strong>
                    @if($user->student)
                        <div style="font-size: 12px; color: #666;">Student</div>
                    @elseif($user->teacher)
                        <div style="font-size: 12px; color: #666;">Teacher</div>
                    @endif
                </td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                </td>
                <td>
                    @if($user->student)
                        {{ $user->student->student_id }}
                    @elseif($user->teacher)
                        {{ $user->teacher->teacher_id }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                <td><span style="color: #4CAF50;">● Active</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>
@endsection
