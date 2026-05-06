@extends('layouts.admin')

@section('title', $student->name)
@section('page-title', $student->name)

@section('content')
<style>
    .student-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 30px;
    }

    .student-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .student-id {
        font-family: monospace;
        background: #f0f0f0;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 14px;
        color: #666;
    }

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

<div class="student-container">
    <div class="student-header">
        <div>
            <h1 style="margin: 0 0 10px 0; color: #333;">{{ $student->name }}</h1>
            <div class="student-id">{{ $student->student_id }}</div>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-card">
            <h4>Email</h4>
            <p>{{ $student->email }}</p>
        </div>

        <div class="info-card">
            <h4>Student ID</h4>
            <p>{{ $student->student_id }}</p>
        </div>

        <div class="info-card">
            <h4>Course</h4>
            <p>{{ $student->course->name ?? 'Not assigned' }}</p>
        </div>

        <div class="info-card">
            <h4>Account Created</h4>
            <p>{{ $student->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <div style="margin-top: 30px;">
        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-edit">Edit Student</a>
        <form action="{{ route('admin.students.destroy', $student) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-delete" onclick="return confirm('Delete this student?')">Delete Student</button>
        </form>
        <a href="{{ route('admin.students.index') }}" class="btn btn-back">← Back to Students</a>
    </div>
</div>
@endsection
