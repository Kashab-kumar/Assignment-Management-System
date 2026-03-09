<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $assignment->title }} - Submissions</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #2196F3; color: white; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header-content { max-width: 1200px; margin: 0 auto; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card h2 { margin-bottom: 15px; color: #333; }
        .meta { color: #666; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f8f8; font-weight: bold; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .badge-pending { background: #FFC107; color: white; }
        .badge-graded { background: #4CAF50; color: white; }
        .btn { padding: 6px 12px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px; display: inline-block; border: none; cursor: pointer; font-size: 14px; }
        .btn:hover { background: #1976D2; }
        .grade-form { display: inline-block; }
        .grade-input { width: 60px; padding: 4px; border: 1px solid #ddd; border-radius: 4px; }
        .alert-success { padding: 15px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>{{ $assignment->title }}</h1>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <h2>Assignment Details</h2>
            <div class="meta">
                <strong>Type:</strong> {{ ucfirst($assignment->type) }} | 
                <strong>Due Date:</strong> {{ $assignment->due_date->format('F d, Y') }} | 
                <strong>Max Score:</strong> {{ $assignment->max_score }}
            </div>
            <p>{{ $assignment->description }}</p>
        </div>

        <div class="card">
            <h2>Student Submissions ({{ $submissions->count() }})</h2>
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th>Score</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                    <tr>
                        <td>{{ $submission->student->student_id }}</td>
                        <td>{{ $submission->student->name }}</td>
                        <td>{{ $submission->submitted_at->format('M d, Y h:i A') }}</td>
                        <td><span class="badge badge-{{ $submission->status }}">{{ ucfirst($submission->status) }}</span></td>
                        <td>
                            @if($submission->status === 'graded')
                                {{ $submission->score }}/{{ $assignment->max_score }}
                            @else
                                <form action="{{ route('teacher.submissions.grade', $submission) }}" method="POST" class="grade-form">
                                    @csrf
                                    <input type="number" name="score" class="grade-input" min="0" max="{{ $assignment->max_score }}" required>
                                    <button type="submit" class="btn">Grade</button>
                                </form>
                            @endif
                        </td>
                        <td>
                            @if($submission->file_path)
                                <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="btn">View File</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6">No submissions yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <a href="{{ route('teacher.assignments.index') }}" class="btn">← Back to Assignments</a>
    </div>
</body>
</html>
