@extends('layouts.teacher')

@section('title', 'Submissions')
@section('page-title', 'Submissions')

@section('content')
<style>
    .section { background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 14px 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f8f8; font-weight: 600; font-size: 13px; }
    tr:hover { background: #f9f9f9; }
    .badge { padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; }
    .badge-pending { background: #FFC107; color: #000; }
    .badge-graded { background: #4CAF50; color: white; }
    .btn-grade { background: #2196F3; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: 500; transition: background 0.2s; }
    .btn-grade:hover { background: #1976D2; }
    .score-input { width: 70px; padding: 6px 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; }
    .grade-form { display: flex; gap: 8px; align-items: center; }
    .score-display { font-weight: 600; color: #4CAF50; }
    .empty-state { text-align: center; padding: 40px; color: #999; }
    .assignment-link { color: #2196F3; text-decoration: none; font-weight: 500; }
    .assignment-link:hover { text-decoration: underline; }
    .student-name { font-weight: 600; color: #1f2937; }
    .file-link { color: #2196F3; text-decoration: none; font-weight: 500; }
    .file-link:hover { text-decoration: underline; }
    .file-name { display: block; max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .assignment-link { display: inline-block; max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
</style>

<div class="section">
    @if($assignment)
        <div style="margin-bottom: 15px;">
            <a href="{{ route('teacher.assignments.show', $assignment) }}" style="color: #7c3aed; text-decoration: none; font-size: 14px;">← Back to Assignment</a>
        </div>
    @endif
    <h2 style="margin-bottom: 20px; color: #1f2937;">{{ $pageTitle }}</h2>

    @if($submissions->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Assignment</th>
                <th>Submitted</th>
                <th>File</th>
                <th>Status</th>
                <th>Score</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($submissions as $submission)
                <tr>
                    <td class="student-name">{{ $submission->student->name }}</td>
                    <td>
                        <a href="{{ route('teacher.assignments.show', $submission->assignment) }}" class="assignment-link" title="{{ $submission->assignment->title }}">
                            {{ Str::limit($submission->assignment->title, 30) }}
                        </a>
                    </td>
                    <td style="font-size: 13px;">{{ $submission->submitted_at->format('M d, Y h:i A') }}</td>
                    <td>
                        @if($submission->file_path)
                            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" rel="noopener" class="file-link" title="Open submitted file">
                                <span class="file-name">{{ basename($submission->file_path) }}</span>
                            </a>
                        @else
                            <span style="color: #999;">No file</span>
                        @endif
                    </td>
                    <td><span class="badge badge-{{ $submission->status }}">{{ ucfirst($submission->status) }}</span></td>
                    <td>
                        @if($submission->status === 'graded')
                            <span class="score-display">{{ $submission->score }}/{{ $submission->assignment->max_score }}</span>
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('teacher.submissions.grade', $submission) }}" method="POST" class="grade-form">
                            @csrf
                            <input type="number" name="score" class="score-input" min="0" max="{{ $submission->assignment->max_score }}" placeholder="Score" value="{{ $submission->score }}" required>
                            <button type="submit" class="btn-grade">{{ $submission->status === 'graded' ? 'Update' : 'Grade' }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <h3>No Submissions Yet</h3>
        <p>Students haven't submitted their work yet.</p>
    </div>
    @endif
</div>
@endsection
