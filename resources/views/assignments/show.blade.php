@extends('layouts.student')

@section('title', $assignment->title)
@section('page-title', $assignment->title)

@section('content')
<style>
    .card { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .card h2 { margin-bottom: 15px; color: #333; font-size: 20px; }
    .meta { color: #666; margin-bottom: 15px; padding: 15px; background: #f8f9fa; border-radius: 4px; }
    .meta-item { display: block; margin-bottom: 8px; }
    .description { line-height: 1.6; color: #555; margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 4px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
    .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; min-height: 150px; font-family: Arial, sans-serif; resize: vertical; }
    .form-group input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
    .form-group small { color: #666; font-size: 12px; }
    .btn { padding: 12px 24px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; text-decoration: none; display: inline-block; }
    .btn:hover { background: #229954; }
    .btn-back { background: #666; padding: 10px 20px; font-size: 14px; }
    .btn-back:hover { background: #555; }
    .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
    .alert-success { background: #d4edda; color: #155724; }
    .alert-info { background: #d1ecf1; color: #0c5460; }
    .alert-warning { background: #fff3cd; color: #856404; }
    .submission-info { background: #e8f5e9; padding: 20px; border-radius: 4px; border-left: 4px solid #4CAF50; }
    .submission-info p { margin-bottom: 10px; }
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
    .badge-pending { background: #FFC107; color: #000; }
    .badge-graded { background: #4CAF50; color: white; }
    .badge-assignment { background: #2196F3; color: white; }
    .badge-homework { background: #FF9800; color: white; }
    .score-display { font-size: 24px; font-weight: bold; color: #4CAF50; }
    .file-preview { padding: 10px; background: #f8f9fa; border-radius: 4px; margin-top: 10px; }
</style>

@if(session('success'))
<div class="alert alert-success">
    ✓ {{ session('success') }}
</div>
@endif

@if($assignment->due_date->isPast() && !$submission)
<div class="alert alert-warning">
    ⚠️ This assignment is overdue. Late submissions may not be accepted.
</div>
@endif

<div class="card">
    <h2>Assignment Details</h2>
    <div class="meta">
        <span class="meta-item">
            <strong>Type:</strong> 
            <span class="badge badge-{{ $assignment->type }}">{{ ucfirst($assignment->type) }}</span>
        </span>
        <span class="meta-item">
            <strong>Due Date:</strong> 
            @if($assignment->due_date->isPast())
                <span style="color: #e74c3c;">{{ $assignment->due_date->format('F d, Y') }} (Overdue)</span>
            @elseif($assignment->due_date->diffInDays() <= 3)
                <span style="color: #e74c3c;">{{ $assignment->due_date->format('F d, Y') }} (Due in {{ $assignment->due_date->diffInDays() }} days)</span>
            @else
                {{ $assignment->due_date->format('F d, Y') }}
            @endif
        </span>
        <span class="meta-item"><strong>Maximum Score:</strong> {{ $assignment->max_score }} points</span>
    </div>
    <div class="description">
        <strong>Instructions:</strong><br>
        {{ $assignment->description }}
    </div>
</div>

@if($submission)
<div class="card">
    <h2>Your Submission</h2>
    <div class="submission-info">
        <p><strong>Status:</strong> <span class="badge badge-{{ $submission->status }}">{{ ucfirst($submission->status) }}</span></p>
        <p><strong>Submitted On:</strong> {{ $submission->submitted_at->format('F d, Y h:i A') }}</p>
        
        @if($submission->status === 'graded' && $submission->score !== null)
        <p><strong>Your Score:</strong> <span class="score-display">{{ $submission->score }}/{{ $assignment->max_score }}</span></p>
        <p><strong>Percentage:</strong> {{ round(($submission->score / $assignment->max_score) * 100, 2) }}%</p>
        @else
        <p style="color: #666;"><em>Your submission is pending review by the teacher.</em></p>
        @endif
        
        @if($submission->content)
        <div style="margin-top: 15px;">
            <strong>Your Answer:</strong>
            <div class="file-preview">{{ $submission->content }}</div>
        </div>
        @endif
        
        @if($submission->file_path)
        <p style="margin-top: 15px;">
            <strong>Attached File:</strong> 
            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" style="color: #27ae60; text-decoration: none;">
                📎 Download Your Submission
            </a>
        </p>
        @endif
    </div>
</div>
@else
<div class="card">
    <h2>Submit Your Work</h2>
    
    @if($assignment->due_date->isPast())
    <div class="alert alert-warning" style="margin-bottom: 20px;">
        This assignment is past the due date. Please contact your teacher if you need to submit late.
    </div>
    @endif
    
    <form action="{{ route('submissions.store', $assignment) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label for="content">Your Answer (Optional)</label>
            <textarea name="content" id="content" placeholder="Type your answer here...">{{ old('content') }}</textarea>
            <small>You can type your answer directly here</small>
        </div>
        
        <div class="form-group">
            <label for="file">Upload File (Optional)</label>
            <input type="file" name="file" id="file" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
            <small>Accepted formats: PDF, DOC, DOCX, TXT, JPG, PNG (Max: 10MB)</small>
        </div>
        
        <div class="alert alert-info" style="margin-top: 20px;">
            <strong>Note:</strong> You must provide either a text answer or upload a file (or both) to submit.
        </div>
        
        <div style="margin-top: 20px;">
            <button type="submit" class="btn">Submit Assignment</button>
        </div>
    </form>
</div>
@endif

<a href="{{ route('assignments.index') }}" class="btn btn-back">← Back to Assignments</a>
@endsection
