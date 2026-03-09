<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Assignment</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #2196F3; color: white; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header-content { max-width: 900px; margin: 0 auto; }
        .container { max-width: 900px; margin: 20px auto; padding: 0 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .form-group textarea { min-height: 150px; resize: vertical; }
        .btn { padding: 12px 24px; background: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #1976D2; }
        .btn-secondary { background: #666; margin-left: 10px; text-decoration: none; display: inline-block; }
        .btn-secondary:hover { background: #555; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Create New Assignment</h1>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <form action="{{ route('teacher.assignments.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required>{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="type">Type</label>
                    <select id="type" name="type" required>
                        <option value="assignment" {{ old('type') == 'assignment' ? 'selected' : '' }}>Assignment</option>
                        <option value="homework" {{ old('type') == 'homework' ? 'selected' : '' }}>Homework</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                </div>

                <div class="form-group">
                    <label for="max_score">Maximum Score</label>
                    <input type="number" id="max_score" name="max_score" value="{{ old('max_score', 100) }}" min="1" required>
                </div>

                <button type="submit" class="btn">Create Assignment</button>
                <a href="{{ route('teacher.assignments.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
