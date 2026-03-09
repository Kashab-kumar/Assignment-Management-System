<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: #4CAF50; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .nav { margin-bottom: 20px; }
        .nav a { padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px; }
        .assignments-grid { display: grid; gap: 20px; }
        .assignment-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .assignment-card h3 { color: #333; margin-bottom: 10px; }
        .assignment-card .meta { color: #666; font-size: 14px; margin-bottom: 10px; }
        .assignment-card .description { color: #555; margin-bottom: 15px; }
        .btn { padding: 8px 16px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px; display: inline-block; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; margin-left: 10px; }
        .badge-assignment { background: #2196F3; color: white; }
        .badge-homework { background: #FF9800; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Assignments & Homework</h1>
        </div>

        <div class="nav">
            <a href="{{ route('dashboard') }}">← Back to Dashboard</a>
        </div>

        <div class="assignments-grid">
            @forelse($assignments as $assignment)
            <div class="assignment-card">
                <h3>
                    {{ $assignment->title }}
                    <span class="badge badge-{{ $assignment->type }}">{{ ucfirst($assignment->type) }}</span>
                </h3>
                <div class="meta">
                    Due: {{ $assignment->due_date->format('F d, Y') }} | 
                    Max Score: {{ $assignment->max_score }} points
                </div>
                <div class="description">{{ Str::limit($assignment->description, 150) }}</div>
                <a href="{{ route('assignments.show', $assignment) }}" class="btn">View Details</a>
            </div>
            @empty
            <p>No assignments available yet.</p>
            @endforelse
        </div>

        <div style="margin-top: 20px;">
            {{ $assignments->links() }}
        </div>
    </div>
</body>
</html>
