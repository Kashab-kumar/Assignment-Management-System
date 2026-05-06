@extends('layouts.admin')

@section('title', 'Academic Report')
@section('page-title', 'Academic Report')

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

    .section {
        margin-bottom: 40px;
    }

    .section h3 {
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
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
        <h2 style="margin: 0; color: #333;">Academic Report</h2>
        <div>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-back">← Back to Reports</a>
        </div>
    </div>

    <div class="section">
        <h3>Assignments</h3>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Due Date</th>
                    <th>Max Score</th>
                    <th>Submissions</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->title }}</td>
                    <td>{{ ucfirst($assignment->type) }}</td>
                    <td>{{ $assignment->due_date->format('d/m/Y') }}</td>
                    <td>{{ $assignment->max_score }}</td>
                    <td>{{ $assignment->submissions_count }}</td>
                    <td>{{ $assignment->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $assignments->links() }}
        </div>
    </div>

    <div class="section">
        <h3>Courses</h3>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Status</th>
                    <th>Students</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                <tr>
                    <td><strong>{{ $course->code }}</strong></td>
                    <td>{{ $course->name }}</td>
                    <td>
                        @if($course->is_active)
                            <span style="color: #4CAF50;">● Active</span>
                        @else
                            <span style="color: #f44336;">● Inactive</span>
                        @endif
                    </td>
                    <td>{{ $course->students_count }}</td>
                    <td>{{ $course->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $courses->links() }}
        </div>
    </div>
</div>
@endsection
