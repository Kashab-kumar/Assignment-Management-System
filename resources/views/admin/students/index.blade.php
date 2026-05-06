@extends('layouts.admin')

@section('title', 'Students')
@section('page-title', 'Students Management')

@section('content')
<style>
    .students-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 20px;
    }

    .students-header {
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

    .students-table {
        width: 100%;
        border-collapse: collapse;
    }

    .students-table th {
        background: #f8f9fa;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }

    .students-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }

    .students-table tr:hover {
        background: #f8f9fa;
    }

    .student-id {
        font-family: monospace;
        background: #f0f0f0;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 12px;
    }

    .course-badge {
        background: #4CAF50;
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

    .filters {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .filter-group {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .filter-group select {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: white;
    }
</style>

<div class="students-container">
    <div class="students-header">
        <h2 style="margin: 0; color: #333;">All Students ({{ $students->total() }})</h2>
        <div class="header-actions">
            <a href="{{ route('admin.invitations.create', ['role' => 'student']) }}" class="btn btn-invite">+ Generate Invite Link</a>
            <a href="{{ route('admin.students.create') }}" class="btn btn-add">+ Add New Student</a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    <div class="filters">
        <form method="GET" action="{{ route('admin.students.index') }}">
            <div class="filter-group">
                <select name="course_id">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} ({{ $course->code }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn" style="background: #666; color: white;">Filter</button>
                <a href="{{ route('admin.students.index') }}" class="btn" style="background: #999; color: white;">Reset</a>
            </div>
        </form>
    </div>

    @if($students->count() > 0)
        <table class="students-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td><span class="student-id">{{ $student->student_id }}</span></td>
                    <td>
                        <strong>{{ $student->name }}</strong>
                        @if($student->user)
                            <div style="font-size: 12px; color: #666;">User ID: {{ $student->user->id }}</div>
                        @endif
                    </td>
                    <td>{{ $student->email }}</td>
                    <td>
                        @if($student->course)
                            <span class="course-badge">{{ $student->course->name }}</span>
                        @else
                            <span style="color: #999; font-size: 12px;">Not Assigned</span>
                        @endif
                    </td>
                    <td>{{ $student->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-view">View</a>
                        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-edit">Edit</a>
                        <form action="{{ route('admin.students.destroy', $student) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Delete this student?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $students->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <svg viewBox="0 0 24 24" style="width: 60px; height: 60px; fill: #ddd; margin-bottom: 15px;">
                <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/>
            </svg>
            <h3>No Students Found</h3>
            <p>Add your first student to get started.</p>
            <a href="{{ route('admin.students.create') }}" class="btn btn-add" style="margin-top: 10px;">+ Add First Student</a>
        </div>
    @endif
</div>
@endsection
