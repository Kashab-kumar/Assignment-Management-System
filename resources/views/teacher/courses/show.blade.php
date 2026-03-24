@extends('layouts.teacher')

@section('title', $course->name)
@section('page-title', $course->name)

@section('content')
<style>
    .course-container { background: #ffffff; border-radius: 12px; border: 1px solid rgba(0,0,0,0.06); padding: 30px; }
    .course-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid rgba(0,0,0,0.08); }
    .course-code { font-family: monospace; background: rgba(148,163,184,0.08); padding: 4px 8px; border-radius: 6px; font-size: 14px; color: #64748b; }
    .status-badge { padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; }
    .status-active { background: rgba(16,185,129,0.18); border: 1px solid rgba(16,185,129,0.3); color: #10b981; }
    .status-inactive { background: rgba(239,68,68,0.18); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; }
    .course-description { color: #475569; line-height: 1.6; margin-bottom: 30px; padding: 20px; background: rgba(0,0,0,0.02); border-radius: 8px; border: 1px solid rgba(0,0,0,0.08); }
    .students-section { margin-top: 30px; }
    .students-section h3 { color: #1f2937; }
    .students-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .students-table th { background: rgba(0,0,0,0.05); padding: 12px 15px; text-align: left; font-weight: 600; color: #64748b; border-bottom: 1px solid rgba(0,0,0,0.08); font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em; }
    .students-table td { padding: 12px 15px; border-bottom: 1px solid rgba(0,0,0,0.06); color: #475569; }
    .students-table tr:hover { background: rgba(124,58,237,0.03); }
    .btn { padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 14px; margin-right: 10px; }
    .btn-back { background: rgba(0,0,0,0.05); color: #1f2937; }
    .btn-invite { background: #3b82f6; color: white; }
    .btn-assign { background: #10b981; color: white; }
    .btn-quiz { background: #f59e0b; color: white; }
    .btn-test { background: #ef4444; color: white; }
    .btn-exam { background: #7c3aed; color: white; }
    .stats-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; margin: 16px 0 22px; }
    .stat-card { background: rgba(0,0,0,0.14); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 14px; }
    .stat-card h4 { margin: 0 0 6px 0; color: #000000; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em; }
    .stat-card p { margin: 0; font-size: 24px; font-weight: 700; color: #7c3aed; }
    .empty-state { text-align: center; padding: 40px; color: #000000; }
    .related-list { list-style: none; margin-top: 10px; padding: 0; background: rgba(0,0,0,0.14); border-radius: 8px; border: 1px solid rgba(255,255,255,0.08); }
    .related-list li { padding: 10px 12px; border-bottom: 1px solid rgba(255,255,255,0.08); }
    .related-list li:last-child { border-bottom: none; }
    .modules-grid { display: grid; gap: 12px; margin-top: 12px; }
    .module-card { background: rgba(0,0,0,0.14); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 14px; }
    .module-head { display: flex; justify-content: space-between; gap: 8px; align-items: center; }
    .module-title { font-size: 15px; font-weight: 700; color: #000000; }
    .module-order { font-size: 11px; color: #000000; background: rgba(148,163,184,0.16); padding: 3px 8px; border-radius: 999px; }
    .module-desc { color: #000000; font-size: 13px; margin-top: 6px; line-height: 1.5; }
    .module-tags { display: flex; gap: 6px; margin-top: 8px; flex-wrap: wrap; }
    .module-tag { font-size: 11px; color: #000000; background: rgba(124,58,237,0.18); border: 1px solid rgba(124,58,237,0.3); padding: 3px 8px; border-radius: 999px; }
    .module-form { margin-top: 12px; display: grid; gap: 10px; background: rgba(0,0,0,0.14); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 14px; }
    .module-form input, .module-form textarea { width: 100%; }
    .module-form select { width: 100%; }
    .module-items { display: grid; gap: 8px; margin-top: 12px; }
    .module-item-card { border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); background: rgba(255,255,255,0.03); padding: 12px; }
    .module-item-head { display: flex; justify-content: space-between; gap: 8px; align-items: center; }
    .module-item-title { color: #f8fafc; font-weight: 600; }
    .module-item-type { font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: #000000; background: rgba(124,58,237,0.2); border: 1px solid rgba(124,58,237,0.32); padding: 3px 8px; border-radius: 999px; }
    .module-item-content { margin-top: 8px; color: #000000; line-height: 1.55; white-space: pre-line; }
    .module-item-meta { margin-top: 8px; color: #64748b; font-size: 12px; }
</style>

<div class="course-container">
    <div class="course-header">
        <div>
            <h1 style="margin: 0 0 10px 0; color: #000000;">{{ $course->name }}</h1>
            <span class="course-code">{{ $course->code }}</span>
            <div style="margin-top: 10px; color: #000000;">Category: <strong>{{ $course->category_name ?: 'Uncategorized' }}</strong></div>
            <div style="margin-top: 5px; color: #000000;">Class: <strong>{{ $course->class_name ?: 'Unassigned' }}</strong></div>
        </div>
        <span class="status-badge status-{{ $course->is_active ? 'active' : 'inactive' }}">
            {{ $course->is_active ? 'Active' : 'Inactive' }}
        </span>
    </div>

    @if($course->description)
    <div class="course-description">{{ $course->description }}</div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <h4>Assignments</h4>
            <p>{{ $course->assignments_count }}</p>
        </div>
        <div class="stat-card">
            <h4>Exams / Quizzes / Tests</h4>
            <p>{{ $course->exams_count }}</p>
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <a href="{{ route('teacher.courses.index') }}" class="btn btn-back">← Back to Courses</a>
        <a href="{{ route('teacher.assignments.create', ['course_id' => $course->id]) }}" class="btn btn-assign">Give Assignment</a>
        <a href="{{ route('teacher.exams.create', ['course_id' => $course->id, 'mode' => 'quiz']) }}" class="btn btn-quiz">Create Quiz</a>
        <a href="{{ route('teacher.exams.create', ['course_id' => $course->id, 'mode' => 'test']) }}" class="btn btn-test">Create Test</a>
        <a href="{{ route('teacher.exams.create', ['course_id' => $course->id]) }}" class="btn btn-exam">Create Exam</a>
        <a href="{{ route('teacher.students.index') }}" class="btn btn-invite">Invite Students</a>
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
            <a href="{{ route('teacher.students.index') }}" class="btn btn-invite" style="margin-top:12px; display:inline-block;">Invite Students</a>
        </div>
        @endif
    </div>

    <div class="students-section">
        <h3>Course Modules ({{ $modulesEnabled ? $course->modules->count() : 0 }})</h3>

        @if(!$modulesEnabled)
            <div class="empty-state">Run `php artisan migrate` to enable course modules.</div>
        @else
            @if($course->modules->isEmpty())
                <div class="empty-state">No modules added for this course yet. Admin needs to create the module structure first.</div>
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

                                <form method="POST" action="{{ route('teacher.courses.modules.items.store', [$course, $module]) }}" class="module-form">
                                    @csrf
                                    <h4 style="margin:0;">Add Module Content</h4>
                                    <select name="type" required>
                                        <option value="">Select content type</option>
                                        <option value="unit_outline">Unit Outline</option>
                                        <option value="quiz">Quiz</option>
                                        <option value="test">Test</option>
                                        <option value="note">Note</option>
                                    </select>
                                    <input type="text" name="title" placeholder="Title" required>
                                    <textarea name="description" rows="4" placeholder="Details, instructions, or note text"></textarea>
                                    <div>
                                        <button type="submit" class="btn btn-exam">Add Content</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>

    @if($relatedCourses->count() > 0)
    <div class="students-section">
        <h3>Other Courses in {{ $course->class_name }} ({{ $relatedCourses->count() }})</h3>
        <ul class="related-list">
            @foreach($relatedCourses as $related)
            <li>
                <a href="{{ route('teacher.courses.show', $related) }}" style="color:#a78bfa; text-decoration:none;">
                    {{ $related->name }}
                </a>
                <span style="color: #000000; font-size:13px; margin-left:8px;">{{ $related->category_name }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
