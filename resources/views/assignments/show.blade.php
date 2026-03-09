<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $assignment->title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        .header { background: #4CAF50; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .nav { margin-bottom: 20px; }
        .nav a { padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px; }
        .card { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card h2 { margin-bottom: 15px; color: #333; }
        .meta { color: #666; margin-bottom: 15px; }
        .description { line-height: 1.6; color: #555; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; min-height: 150px; }
        .form-group input[type="file"] { width: 100%; padding: 10px; }
        .btn { padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #45a049; }
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-info { background: #d1ecf1; color: #0c5460; }
        .submission-info { background: #f8f9fa; padding: 15px; border-radius: 4px; margin-top: 15px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .badge-pending { background: #FFC107; color: white; }
        .badge-graded { background: #4CAF50; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $assignment->title }}</h1>
        </div>

        <div class="nav">
            <a href="{{ route('assignments.index') }}">← Back to Assignments</a>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="card">
            <h2>Assignment Details</h2>
            <div class="meta">
                <strong>Type:</strong> {{ ucfirst($assignment->type) }} | 
                <strong>Due Date:</strong> {{ $assignment->due_date->format('F d, Y') }} | 
                <strong>Max Score:</strong> {{ $assignment->max_score }} points
            </div>
            <div class="description">
                <strong>Description:</strong><br>
                {{ $assignment->description }}
            </div>
        </div>

        @if($submission)
        <div class="card">
            <h2>Your Submission</h2>
            <div class="submission-info">
                <p><strong>Status:</strong> <span class="badge badge-{{ $submission->status }}">{{ ucfirst($submission->status) }}</span></p>
                <p><strong>Submitted:</strong> {{ $submission->submitted_at->format('F d, Y h:i A') }}</p>
                @if($submission->score)
                <p><strong>Score:</strong> {{ $submission->score }}/{{ $assignment->max_score }}</p>
                @endif
                @if($submission->content)
                <p><strong>Content:</strong><br>{{ $submission->content }}</p>
                @endif
                @if($submission->file_path)
                <p><strong>File:</strong> <a href="{{ Storage::url($submission->file_path) }}" target="_blank">Download</a></p>
                @endif
            </div>
        </div>
        @else
        <div class="card">
            <h2>Submit Your Work</h2>
            <form action="{{ route('submissions.store', $assignment) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="content">Your Answer (Optional)</label>
                    <textarea name="content" id="content" placeholder="Type your answer here..."></textarea>
                </div>
                <div class="form-group">
                    <label for="file">Upload File (Optional)</label>
                    <input type="file" name="file" id="file">
                    <small>Max file size: 10MB</small>
                </div>
                <button type="submit" class="btn">Submit Assignment</button>
            </form>
        </div>
        @endif
    </div>
</body>
</html>
