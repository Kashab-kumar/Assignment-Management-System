@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<style>
    .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: #1e2235; padding: 22px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06); }
    .stat-card h3 { color: #94a3b8; font-size: 13px; margin-bottom: 10px; }
    .stat-card .value { font-size: 34px; font-weight: 700; color: #7c3aed; }
    .section { background: #1e2235; padding: 22px; border-radius: 12px; margin-bottom: 20px; border: 1px solid rgba(255,255,255,0.06); }
    .section h2 { margin-bottom: 15px; color: #f1f5f9; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.06); }
    th { background: rgba(0,0,0,0.12); color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em; }
    td { color: #cbd5e1; }
    tr:last-child td { border-bottom: none; }
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; color: white; }
    .badge-admin { background: #7c3aed; }
    .badge-teacher { background: #3b82f6; }
    .badge-student { background: #10b981; }
    .empty { color: #64748b; text-align: center; }
</style>

<div class="stats">
    <div class="stat-card">
        <h3>Total Users</h3>
        <div class="value">{{ $totalUsers }}</div>
    </div>
    <div class="stat-card">
        <h3>Total Students</h3>
        <div class="value">{{ $totalStudents }}</div>
    </div>
    <div class="stat-card">
        <h3>Total Teachers</h3>
        <div class="value">{{ $totalTeachers }}</div>
    </div>
    <div class="stat-card">
        <h3>Total Assignments</h3>
        <div class="value">{{ $totalAssignments }}</div>
    </div>
</div>

<div class="section">
    <h2>Recent Users</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Registered</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentUsers as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td><span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                <td>{{ $user->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="empty">No users yet</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
