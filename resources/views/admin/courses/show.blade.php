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
    .modules-grid { display: grid; gap: 12px; margin-top: 12px; }
    .module-card-link { text-decoration: none; display: block; }
    .module-card { background: rgba(0,0,0,0.14); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 14px; cursor: pointer; transition: all 0.3s ease; }
    .module-card-link:hover .module-card { background: rgba(124,58,237,0.15); border-color: rgba(124,58,237,0.4); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(124,58,237,0.2); }
    .module-head { display: flex; justify-content: space-between; gap: 8px; align-items: center; }
    .module-title { font-size: 15px; font-weight: 700; color: #000000; }
    .module-order { font-size: 11px; color: #000000; background: rgba(148,163,184,0.16); padding: 3px 8px; border-radius: 999px; }
    .module-desc { color: #000000; font-size: 13px; margin-top: 6px; line-height: 1.5; }
    .module-tags { display: flex; gap: 6px; margin-top: 8px; flex-wrap: wrap; }
    .module-tag { font-size: 11px; color: #000000; background: rgba(124,58,237,0.18); border: 1px solid rgba(124,58,237,0.3); padding: 3px 8px; border-radius: 999px; }
    .module-actions { display: flex; gap: 8px; margin-top: 12px; }
    .module-form { margin-top: 12px; display: grid; gap: 10px; background: rgba(0,0,0,0.14); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 14px; }
    .module-form input, .module-form textarea { width: 100%; }
    .module-form-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; }
    .module-items { display: grid; gap: 8px; margin-top: 12px; }
    .module-item-card { border-radius: 8px; border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.04); padding: 12px; }
    .module-item-head { display: flex; justify-content: space-between; gap: 8px; align-items: center; }
    .module-item-title { font-size: 14px; font-weight: 700; color: #000000; }
    .module-item-type { font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: #000000; background: rgba(124,58,237,0.18); border: 1px solid rgba(124,58,237,0.3); padding: 3px 8px; border-radius: 999px; }
    .module-item-content { margin-top: 8px; color: #000000; white-space: pre-line; line-height: 1.55; }
    .module-item-meta { margin-top: 8px; color: #000000; font-size: 12px; }
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
                            <div class="module-head">
                                <div class="module-title">{{ $module->title }}</div>
                                <span class="module-order">Module {{ $module->position }}</span>
                            </div>
                            @if($module->description)
                                <div class="module-desc">{{ $module->description }}</div>
                            @endif
                            <div class="module-tags">
                                <span class="module-tag">{{ $module->lesson_count }} lessons</span>
                                <span class="module-tag">{{ $module->assignment_count }} assignments</span>
                                <span class="module-tag">{{ $module->quiz_count }} quizzes</span>
                            </div>
                            <div class="module-desc" style="margin-top: 10px; margin-bottom: 2px;">
                                Teacher: <strong>{{ $module->teacher?->name ?? 'Not assigned' }}</strong>
                            </div>
                            <div class="module-actions">
                                <form action="{{ route('admin.courses.modules.update-teacher', [$course, $module]) }}" method="POST" style="display: flex; gap: 8px; align-items: center; flex: 1;">
                                    @csrf
                                    @method('PUT')
                                    <select name="teacher_id" style="padding: 6px; border-radius: 4px; border: 1px solid #ddd; flex: 1;">
                                        <option value="">Assign Teacher</option>
                                        @foreach($availableTeachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ $module->teacher_id == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}{{ $teacher->teacher_id ? ' (' . $teacher->teacher_id . ')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-edit" style="padding: 6px 12px; font-size: 12px;">Update</button>
                                </form>
                                <form action="{{ route('admin.courses.modules.destroy', [$course, $module]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Delete this module?')">Delete</button>
                                </form>
                            </div>

                            @if($moduleItemsEnabled)
                                @if($module->items->isEmpty())
                                    <div class="module-desc" style="margin-top: 12px;">No teacher content has been added for this module yet.</div>
                                @else
                                    <div class="module-items">
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
@endsection
