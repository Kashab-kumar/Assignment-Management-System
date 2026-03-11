@extends('layouts.teacher')

@section('title', 'Submissions')
@section('page-title', 'Submissions')

@section('content')
<style>
    .section { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f8f8; font-weight: bold; }
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
    .badge-pending { background: #FFC107; color: #000; }
    .badge-graded { background: #4CAF50; color: white; }
</style>

<div class="section">
    <h2 style="margin-bottom: 16px;">All Student Submissions</h2>

    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Student ID</th>
                <th>Assignment</th>
                <th>Submitted</th>
                <th>Status</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
            @forelse($submissions as $submission)
                <tr>
                    <td>{{ $submission->student->name }}</td>
                    <td>{{ $submission->student->student_id }}</td>
                    <td>
                        <a href="{{ route('teacher.assignments.show', $submission->assignment) }}" style="color: #2196F3; text-decoration: none;">
                            {{ $submission->assignment->title }}
                        </a>
                    </td>
                    <td>{{ $submission->submitted_at->format('M d, Y h:i A') }}</td>
                    <td><span class="badge badge-{{ $submission->status }}">{{ ucfirst($submission->status) }}</span></td>
                    <td>{{ $submission->score ?? '-' }}/{{ $submission->assignment->max_score }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No submissions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
