@extends('layouts.admin')

@section('title', 'System Reports')
@section('page-title', 'System Reports & Analytics')

@section('content')
<style>
    .reports-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 20px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        margin: 0 auto 15px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-users { background: #9C27B0; color: white; }
    .stat-teachers { background: #2196F3; color: white; }
    .stat-students { background: #4CAF50; color: white; }
    .stat-courses { background: #FF9800; color: white; }
    .stat-assignments { background: #795548; color: white; }
    .stat-submissions { background: #607D8B; color: white; }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #333;
        margin: 10px 0;
    }

    .stat-label {
        color: #666;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .reports-section {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .reports-section h3 {
        color: #333;
        margin-bottom: 20px;
    }

    .reports-table {
        width: 100%;
        border-collapse: collapse;
    }

    .reports-table th {
        background: #f8f9fa;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }

    .reports-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }

    .reports-table tr:hover {
        background: #f8f9fa;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        margin-right: 10px;
    }

    .btn-primary { background: #9C27B0; color: white; }
    .btn-secondary { background: #666; color: white; }

    .export-options {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 4px;
        margin-top: 30px;
    }
</style>

<div class="reports-container">
    <h2 style="color: #333; margin-bottom: 20px;">System Overview</h2>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-users">👥</div>
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-label">Total Users</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-teachers">👨‍🏫</div>
            <div class="stat-value">{{ $totalTeachers }}</div>
            <div class="stat-label">Teachers</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-students">👨‍🎓</div>
            <div class="stat-value">{{ $totalStudents }}</div>
            <div class="stat-label">Students</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-courses">📚</div>
            <div class="stat-value">{{ $totalCourses }}</div>
            <div class="stat-label">Courses</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-assignments">📄</div>
            <div class="stat-value">{{ $totalAssignments }}</div>
            <div class="stat-label">Assignments</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-submissions">📤</div>
            <div class="stat-value">{{ $totalSubmissions }}</div>
            <div class="stat-label">Submissions</div>
        </div>
    </div>

    <div class="reports-section">
        <h3>Recent User Activity</h3>
        <table class="reports-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Joined</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentUsers as $user)
                <tr>
                    <td>
                        <strong>{{ $user->name }}</strong>
                        @if($user->student)
                            <div style="font-size: 12px; color: #666;">Student ID: {{ $user->student->student_id }}</div>
                        @elseif($user->teacher)
                            <div style="font-size: 12px; color: #666;">Teacher</div>
                        @endif
                    </td>
                    <td>
                        <span style="padding: 4px 8px; background: #f0f0f0; border-radius: 4px; font-size: 12px;">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td><span style="color: #4CAF50;">● Active</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="reports-section">
        <h3>Recent Submissions</h3>
        <table class="reports-table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Assignment</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentSubmissions as $submission)
                <tr>
                    <td>
                        <strong>{{ $submission->student->user->name ?? 'Unknown' }}</strong>
                        <div style="font-size: 12px; color: #666;">{{ $submission->student->student_id ?? 'N/A' }}</div>
                    </td>
                    <td>{{ $submission->assignment->title }}</td>
                    <td>{{ $submission->submitted_at->format('M d, Y h:i A') }}</td>
                    <td>
                        <span style="padding: 4px 8px; background: {{ $submission->status == 'graded' ? '#4CAF50' : '#FFC107' }}; color: white; border-radius: 4px; font-size: 12px;">
                            {{ ucfirst($submission->status) }}
                        </span>
                    </td>
                    <td>
                        @if($submission->score)
                            {{ $submission->score }}/{{ $submission->assignment->max_score }}
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="export-options">
        <h3 style="margin-top: 0;">Export Reports</h3>
        <form action="{{ route('admin.reports.export') }}" method="POST">
            @csrf
            <div style="display: flex; gap: 10px; align-items: center;">
                <select name="type" style="padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                    <option value="users">Users Report</option>
                    <option value="students">Students Report</option>
                    <option value="teachers">Teachers Report</option>
                    <option value="courses">Courses Report</option>
                    <option value="assignments">Assignments Report</option>
                </select>
                <button type="submit" class="btn btn-primary">Export as CSV</button>
                <a href="{{ route('admin.reports.users') }}" class="btn btn-secondary">View Users Report</a>
                <a href="{{ route('admin.reports.academic') }}" class="btn btn-secondary">View Academic Report</a>
            </div>
        </form>
    </div>
</div>
@endsection
