@extends('layouts.teacher')

@section('title', 'My Students')
@section('page-title', 'My Students')

@section('content')
<style>
    .section { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f8f8; }
    .form-row { display: grid; grid-template-columns: 1fr auto; gap: 12px; align-items: end; }
    .form-group label { display: block; margin-bottom: 6px; font-weight: bold; }
    .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
    .btn { padding: 10px 16px; background: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
    .badge-active { background: #4CAF50; color: white; }
    .badge-used { background: #666; color: white; }
    .badge-expired { background: #f44336; color: white; }
</style>

<div class="section">
    <h2 style="margin-bottom: 16px;">Invite Student To A Course</h2>

    @if(session('success'))
        <div style="padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 16px;">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div style="padding: 12px; background: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 16px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('teacher.students.invitations.store') }}">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label for="course_id">Select Course</label>
                <select id="course_id" name="course_id" required>
                    <option value="">Choose a course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->category_name ?: 'Uncategorized' }} / {{ $course->class_name ?: 'Unassigned' }} / {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="max_uses">Max Uses <small style="color:#888;">(leave blank for unlimited)</small></label>
                <input type="number" id="max_uses" name="max_uses" min="1" value="{{ old('max_uses') }}" placeholder="e.g. 30" style="width:120px;">
            </div>
            <button type="submit" class="btn">Generate Invitation</button>
        </div>
    </form>
</div>

<div class="section">
    <h2 style="margin-bottom: 16px;">Student Directory</h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Student ID</th>
                <th>Email</th>
                <th>Course</th>
                <th>Submissions</th>
                <th>Exam Results</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->student_id }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->course?->name ?? ($student->class ?? '-') }}</td>
                    <td>{{ $student->submissions_count }}</td>
                    <td>{{ $student->exam_results_count }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No students found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="section">
    <h2 style="margin-bottom: 16px;">Recent Student Invitations</h2>

    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Class</th>
                <th>Course</th>
                <th>Status</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($studentInvitations as $invitation)
                <tr>
                    <td>{{ $invitation->course?->category_name ?: 'Uncategorized' }}</td>
                    <td>{{ $invitation->course?->class_name ?: 'Unassigned' }}</td>
                    <td>{{ $invitation->course?->name ?: '-' }}</td>
                    <td>
                        @if($invitation->used)
                            <span class="badge badge-used">Used</span>
                        @elseif($invitation->isExpired())
                            <span class="badge badge-expired">Expired</span>
                        @else
                            <span class="badge badge-active">Active</span>
                        @endif
                    </td>
                    <td>{{ $invitation->created_at->format('d/m/Y') }}</td>
                    <td><a href="{{ route('teacher.students.invitations.show', $invitation) }}" style="color:#2196F3; text-decoration:none;">View Link</a></td>
                </tr>
            @empty
                <tr><td colspan="6">No student invitations yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
