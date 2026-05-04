@extends('layouts.teacher')

@section('title', $assignment->title)
@section('page-title', $assignment->title)

@section('content')
<style>
    .card { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .card h2 { margin-bottom: 15px; color: #333; font-size: 20px; }
    .meta { color: #666; margin-bottom: 15px; padding: 15px; background: #f8f9fa; border-radius: 4px; }
    .meta-item { display: inline-block; margin-right: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f8f8; font-weight: bold; }
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
    .badge-pending { background: #FFC107; color: #000; }
    .badge-graded { background: #4CAF50; color: white; }
    .btn { padding: 6px 12px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px; display: inline-block; border: none; cursor: pointer; font-size: 14px; }
    .btn:hover { background: #1976D2; }
    .btn-back { background: #666; padding: 10px 20px; }
    .btn-back:hover { background: #555; }
    .grade-form { display: inline-flex; align-items: center; gap: 5px; }
    .grade-input { width: 60px; padding: 4px 8px; border: 1px solid #ddd; border-radius: 4px; }
    .alert-success { padding: 15px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px; }
    .empty-state { text-align: center; padding: 40px; color: #666; }
    .score-display { font-weight: bold; color: #4CAF50; }
    .file-link { color: #2196F3; text-decoration: none; }
    .file-link:hover { text-decoration: underline; }
    .instruction-card { margin-top: 12px; padding: 14px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; }
</style>

@if(session('success'))
<div class="alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <h2>Assignment Details</h2>
    <div class="meta">
        <span class="meta-item"><strong>Type:</strong> {{ ucfirst($assignment->type) }}</span>
        <span class="meta-item"><strong>Due Date:</strong> {{ $assignment->due_date->format('F d, Y') }}</span>
        <span class="meta-item"><strong>Max Score:</strong> {{ $assignment->max_score }} points</span>
        <span class="meta-item"><strong>Total Submissions:</strong> {{ $submissions->count() }}</span>
    </div>
    <p style="line-height: 1.6; color: #555;">{{ $assignment->description }}</p>

    @if($assignment->instruction_file_path)
        <div class="instruction-card">
            <strong>Instruction File:</strong>
            <a class="file-link" href="{{ asset('storage/' . $assignment->instruction_file_path) }}" target="_blank" rel="noopener">
                {{ $assignment->instruction_file_name ?? basename($assignment->instruction_file_path) }}
            </a>
        </div>
    @endif
</div>

<div class="card">
    <h2>Student Submissions</h2>

    @if($submissions->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Enrollment No.</th>
                <th>Submitted At</th>
                <th>Status</th>
                <th>Score</th>
                <th>Content</th>
                <th>File</th>
            </tr>
        </thead>
        <tbody>
            @foreach($submissions as $submission)
            <tr>
                <td><strong>{{ $submission->student->user->name }}</strong></td>
                <td>{{ $submission->student->student_id }}</td>
                <td>{{ $submission->submitted_at->format('M d, Y h:i A') }}</td>
                <td><span class="badge badge-{{ $submission->status }}">{{ ucfirst($submission->status) }}</span></td>
                <td>
                    @if($submission->status === 'graded')
                        <span class="score-display">{{ $submission->score }}/{{ $assignment->max_score }}</span>
                    @else
                        <form action="{{ route('teacher.submissions.grade', $submission) }}" method="POST" class="grade-form">
                            @csrf
                            <input type="number" name="score" class="grade-input" min="0" max="{{ $assignment->max_score }}" placeholder="0" required>
                            <button type="submit" class="btn">Grade</button>
                        </form>
                    @endif
                </td>
                <td>
                    @if($submission->content)
                        <details>
                            <summary style="cursor: pointer; color: #2196F3;">View Content</summary>
                            <div style="margin-top: 10px; padding: 10px; background: #f8f9fa; border-radius: 4px; max-width: 300px; word-wrap: break-word;">
                                {{ Str::limit($submission->content, 200) }}
                            </div>
                        </details>
                    @else
                        <span style="color: #999;">No text content</span>
                    @endif
                </td>
                <td>
                    @if($submission->file_path)
                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="file-link">📎 Download</a>
                    @else
                        <span style="color: #999;">No file</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <svg viewBox="0 0 24 24" style="width: 60px; height: 60px; fill: #ddd; margin-bottom: 15px;"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
        <h3>No Submissions Yet</h3>
        <p>Students haven't submitted their work yet.</p>
    </div>
    @endif
</div>

<a href="{{ route('teacher.assignments.index') }}" class="btn btn-back">← Back to Assignments</a>
@endsection
