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

    {{-- System Overview cards removed per request --}}

    <div class="reports-section">
        <h3>Recent User Activity</h3>
        <x-ui.table>
            <x-slot name="head">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </x-slot>

            @foreach($recentUsers as $user)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                    @if($user->student)
                        <div class="text-xs text-gray-500">Student ID: {{ $user->student->student_id }}</div>
                    @elseif($user->teacher)
                        <div class="text-xs text-gray-500">Teacher</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($user->role) }}</span></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">● Active</td>
            </tr>
            @endforeach
        </x-ui.table>
    </div>

    <div class="reports-section">
        <h3>Recent Submissions</h3>
        <x-ui.table>
            <x-slot name="head">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                </tr>
            </x-slot>

            @foreach($recentSubmissions as $submission)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $submission->student->user->name ?? 'Unknown' }}</div>
                    <div class="text-xs text-gray-500">{{ $submission->student->student_id ?? 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $submission->assignment->title }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $submission->submitted_at->format('M d, Y h:i A') }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $submission->status == 'graded' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($submission->status) }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    @if($submission->score)
                        {{ $submission->score }}/{{ $submission->assignment->max_score }}
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </x-ui.table>
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
