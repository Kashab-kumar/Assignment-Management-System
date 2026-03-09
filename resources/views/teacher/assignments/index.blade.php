<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Assignments</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #2196F3; color: white; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header-content { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; }
        .nav { display: flex; gap: 20px; }
        .nav a { color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; }
        .nav a:hover { background: rgba(255,255,255,0.2); }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .section { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f8f8; font-weight: bold; }
        .btn { padding: 8px 16px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px; display: inline-block; }
        .btn:hover { background: #1976D2; }
        .btn-success { background: #4CAF50; }
        .btn-success:hover { background: #45a049; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .badge-assignment { background: #2196F3; color: white; }
        .badge-homework { background: #FF9800; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Manage Assignments</h1>
            <div class="nav">
                <a href="{{ route('teacher.dashboard') }}">Dashboard</a>
                <a href="{{ route('teacher.assignments.index') }}">Assignments</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="section">
            <div class="section-header">
                <h2>All Assignments</h2>
                <a href="{{ route('teacher.assignments.create') }}" class="btn btn-success">Create New Assignment</a>
            </div>

            @if(session('success'))
            <div style="padding: 15px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
            @endif

            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Due Date</th>
                        <th>Max Score</th>
                        <th>Submissions</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                    <tr>
                        <td>{{ $assignment->title }}</td>
                        <td><span class="badge badge-{{ $assignment->type }}">{{ ucfirst($assignment->type) }}</span></td>
                        <td>{{ $assignment->due_date->format('M d, Y') }}</td>
                        <td>{{ $assignment->max_score }}</td>
                        <td>{{ $assignment->submissions_count }}</td>
                        <td>
                            <a href="{{ route('teacher.assignments.show', $assignment) }}" class="btn">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6">No assignments created yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
