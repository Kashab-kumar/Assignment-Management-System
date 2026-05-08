@extends('layouts.admin')

@section('title', $course->name)
@section('page-title', $course->name)

@section('content')
<style>
    .course-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 30px;
    }

    .course-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .course-code {
        font-family: monospace;
        background: #f0f0f0;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 14px;
        color: #666;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-active { background: #4CAF50; color: white; }
    .status-inactive { background: #f44336; color: white; }

    .course-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 30px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 4px;
    }

    .students-section {
        margin-top: 30px;
    }

    .students-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .students-table th {
        background: #f8f9fa;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #555;
        border-bottom: 2px solid #dee2e6;
    }

    .students-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }

    .students-table tr:hover {
        background: #f8f9fa;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        margin-right: 10px;
    }

    .btn-edit { background: #2196F3; color: white; }
    .btn-delete { background: #f44336; color: white; }
    .btn-back { background: #666; color: white; }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #666;
    }
    /* Modern Module Cards - Similar to Student/Teacher View */
    .modules-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; margin-top: 20px; }
    @media (max-width: 768px) { .modules-grid { grid-template-columns: 1fr; } }

    .module-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .module-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
        border-color: #7c3aed;
    }

    .module-card-header {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        padding: 24px;
        border-bottom: 1px solid #e5e7eb;
    }
    .module-icon {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin-bottom: 16px;
    }
    .module-title {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 4px;
    }
    .module-position {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .module-card-body {
        padding: 20px 24px;
    }
    .module-desc {
        color: #4b5563;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 16px;
        min-height: 44px;
    }

    .module-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 16px;
    }
    .stat-box {
        background: #f9fafb;
        padding: 14px;
        border-radius: 10px;
        text-align: center;
    }
    .stat-value {
        font-size: 22px;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 4px;
    }
    .stat-label {
        font-size: 11px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .module-footer {
        padding: 16px 24px 24px;
        border-top: 1px solid #f3f4f6;
    }
    .module-teacher {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 16px;
    }
    .module-teacher strong {
        color: #1f2937;
    }

    .module-actions {
        display: flex;
        gap: 10px;
    }
    .btn-module {
        flex: 1;
        padding: 12px 16px;
        border-radius: 10px;
        text-decoration: none;
        text-align: center;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    .btn-module-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    .btn-module-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Legacy styles for other sections */
    .module-form { margin-top: 12px; display: grid; gap: 10px; background: #f8f9fa; border: 1px solid #e5e7eb; border-radius: 10px; padding: 14px; }
    .module-form input, .module-form textarea { width: 100%; }
    .module-form-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; }
    .module-items { display: grid; gap: 8px; margin-top: 12px; }
    .module-item-card { border-radius: 8px; border: 1px solid #e5e7eb; background: #f9fafb; padding: 12px; }
    .module-item-head { display: flex; justify-content: space-between; gap: 8px; align-items: center; }
    .module-item-title { font-size: 14px; font-weight: 700; color: #1f2937; }
    .module-item-type { font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: #7c3aed; background: #ede9fe; border: 1px solid #ddd6fe; padding: 3px 8px; border-radius: 999px; }
    .module-item-content { margin-top: 8px; color: #4b5563; white-space: pre-line; line-height: 1.55; }
    .module-item-meta { margin-top: 8px; color: #6b7280; font-size: 12px; }
    @media (max-width: 900px) { .module-form-grid { grid-template-columns: 1fr; } }
</style>

<div class="course-container">
    @if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
    @endif

    <div class="course-header">
        <div>
            <h1 style="margin: 0 0 10px 0; color: #333;">{{ $course->name }}</h1>
            <div class="course-code">{{ $course->code }}</div>
            <div style="margin-top: 10px; color: #666;">Category: <strong>{{ $course->category_name ?: 'Uncategorized' }}</strong></div>
            <div style="margin-top: 10px; color: #666;">Class: <strong>{{ $course->class_name ?: 'Unassigned' }}</strong></div>
        </div>
        <div>
            <span class="status-badge status-{{ $course->is_active ? 'active' : 'inactive' }}">
                {{ $course->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>

    @if($course->description)
    <div class="course-description">
        {{ $course->description }}
    </div>
    @endif

    <div style="margin-top: 20px;">
        <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-edit">Edit Course</a>
        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-delete" onclick="return confirm('Delete this course?')">Delete Course</button>
        </form>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-back">← Back to Courses</a>
    </div>

    <div class="students-section">
        <h3>Course Modules ({{ $modulesEnabled ? $course->modules->count() : 0 }})</h3>

        @if(!$modulesEnabled)
            <div class="empty-state">Run `php artisan migrate` to enable course modules.</div>
        @else
            @if($course->modules->isEmpty())
                <div class="empty-state">No modules added for this course yet.</div>
            @else
                <div class="modules-grid">
                    @foreach($course->modules as $module)
                        @php
                            $typeLabels = [
                                'unit_outline' => 'Unit Outline',
                                'quiz' => 'Quiz',
                                'test' => 'Test',
                                'note' => 'Note',
                            ];
                        @endphp
                        <div class="module-card">
                            <div class="module-card-header">
                                <div class="module-icon">📖</div>
                                <div class="module-title">{{ $module->title }}</div>
                                <div class="module-position">Module {{ $module->position }}</div>
                            </div>

                            <div class="module-card-body">
                                <div class="module-desc">
                                    {{ $module->description ?: 'No description available for this module.' }}
                                </div>
                                <div class="module-stats">
                                    <div class="stat-box">
                                        <div class="stat-value">{{ $module->items?->count() ?? $module->lesson_count ?? 0 }}</div>
                                        <div class="stat-label">Items</div>
                                    </div>
                                    <div class="stat-box">
                                        <div class="stat-value">{{ $module->quiz_count ?? 0 }}</div>
                                        <div class="stat-label">Quizzes</div>
                                    </div>
                                </div>
                            </div>

                            <div class="module-footer">
                                <div class="module-teacher">
                                    👨‍🏫 Teacher: <strong>{{ $module->teacher?->name ?? 'Not assigned' }}</strong>
                                </div>
                                <div class="module-actions">
                                    <button type="button" class="btn-module btn-module-primary" onclick="toggleModuleContent('module-content-{{ $module->id }}')">View Content</button>
                                </div>
                            </div>

                            <!-- Expandable content section -->
                            <div id="module-content-{{ $module->id }}" style="display: none; padding: 0 24px 24px; border-top: 1px solid #f3f4f6;">
                                @if($moduleItemsEnabled)
                                    @if($module->items->isEmpty())
                                        <div class="module-desc" style="margin-top: 16px; padding: 16px; background: #f9fafb; border-radius: 8px;">
                                            No teacher content has been added for this module yet.
                                        </div>
                                    @else
                                        <div class="module-items" style="margin-top: 16px;">
                                            @foreach($module->items as $item)
                                                <div class="module-item-card">
                                                    <div class="module-item-head">
                                                        <div class="module-item-title">{{ $item->title }}</div>
                                                        <span class="module-item-type">{{ $typeLabels[$item->type] ?? ucfirst(str_replace('_', ' ', $item->type)) }}</span>
                                                    </div>
                                                    @if($item->content)
                                                        <div class="module-item-content">{{ $item->content }}</div>
                                                    @endif
                                                    <div class="module-item-meta">
                                                        Added by {{ $item->creator?->name ?? 'Teacher' }} on {{ $item->created_at->format('M d, Y') }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.courses.modules.store', $course) }}" class="module-form">
                @csrf
                <h4 style="margin:0;">Add Module</h4>
                <input type="text" name="title" placeholder="Module title" required>
                <textarea name="description" rows="3" placeholder="Short description (optional)"></textarea>
                <select name="teacher_id">
                    <option value="">No specific teacher</option>
                    @foreach($availableTeachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}{{ $teacher->teacher_id ? ' (' . $teacher->teacher_id . ')' : '' }}</option>
                    @endforeach
                </select>
                <div class="module-form-grid">
                    <input type="number" name="lesson_count" min="0" value="0" placeholder="Lessons">
                    <input type="number" name="assignment_count" min="0" value="0" placeholder="Assignments">
                    <input type="number" name="quiz_count" min="0" value="0" placeholder="Quizzes">
                </div>
                <div>
                    <button type="submit" class="btn btn-edit">Add Module</button>
                </div>
            </form>
        @endif
    </div>

    <div class="students-section">
        <h3>Enrolled Students ({{ $course->students->count() }})</h3>

        @if($course->students->count() > 0)
        <table class="students-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @foreach($course->students as $student)
                <tr>
                    <td><strong>{{ $student->student_id }}</strong></td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <p>No students enrolled in this course yet.</p>
        </div>
        @endif
    </div>

    @if(!empty($course->class_name))
    <div class="students-section">
        <h3>Other Subjects/Courses In {{ $course->class_name }} ({{ $relatedCourses->count() }})</h3>

        @if($relatedCourses->count() > 0)
        <table class="students-table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($relatedCourses as $related)
                <tr>
                    <td>{{ $related->category_name ?: 'Uncategorized' }}</td>
                    <td><strong>{{ $related->code }}</strong></td>
                    <td>{{ $related->name }}</td>
                    <td><a href="{{ route('admin.courses.show', $related) }}" class="btn btn-edit" style="padding: 6px 10px; font-size: 12px;">View</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <p>No other subjects/courses found for this class yet.</p>
        </div>
        @endif
    </div>
    @endif
</div>

<script>
function toggleModuleContent(id) {
    const element = document.getElementById(id);
    if (element.style.display === 'none') {
        element.style.display = 'block';
    } else {
        element.style.display = 'none';
    }
}
</script>
@endsection
