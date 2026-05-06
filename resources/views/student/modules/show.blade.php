@extends('layouts.student')

@section('title', $module->title)
@section('page-title', 'Module Workspace')

@section('content')
<style>
    .module-container { width: 100%; padding: 32px; }
    .back-link { display: inline-block; margin-bottom: 28px; color: #2459ff; text-decoration: none; font-size: 16px; font-weight: 500; }
    .back-link:hover { text-decoration: underline; }
    .module-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 16px; padding: 40px; margin-bottom: 32px; }
    .module-header h1 { margin: 0 0 12px; font-size: 42px; font-weight: 700; line-height: 1.2; }
    .module-header p { margin: 6px 0; opacity: 0.95; font-size: 17px; line-height: 1.5; }
    .module-meta { display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px; }
    .meta-item { background: rgba(255,255,255,0.2); padding: 10px 18px; border-radius: 24px; font-size: 15px; font-weight: 500; }

    .tabs-container { background: white; border: 1px solid #dbe2ec; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.07); }
    .tabs-header { display: flex; border-bottom: 3px solid #e5e7eb; background: #f8f9fb; }
    .tab-btn { flex: 1; padding: 18px 20px; border: none; background: none; cursor: pointer; font-size: 16px; font-weight: 600; color: #64748b; transition: all 0.3s; border-bottom: 4px solid transparent; margin-bottom: -3px; }
    .tab-btn:hover { color: #2459ff; background: #f0f5ff; }
    .tab-btn.active { color: #2459ff; border-bottom-color: #2459ff; background: white; }

    .tabs-content { padding: 40px; }
    .tab-pane { display: none; }
    .tab-pane.active { display: block; }
    .tab-pane.active { animation: fadeIn 0.3s; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    .tabs-content h3 { font-size: 24px; margin-bottom: 24px; color: #0f172a; }

    .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 28px; }
    .metric { border: 1px solid #e5e7eb; border-radius: 12px; padding: 28px; background: #fafbfc; }
    .metric h4 { margin: 0 0 14px; font-size: 14px; color: #64748b; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
    .metric .value { font-size: 48px; font-weight: 700; color: #0f172a; }

    .list-item { border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin-bottom: 12px; }
    .list-item h4 { margin: 0 0 6px; color: #0f172a; font-size: 16px; font-weight: 600; }
    .list-item p { margin: 0; color: #64748b; font-size: 14px; }

    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 16px; border-bottom: 1px solid #e5e7eb; text-align: left; font-size: 15px; }
    th { font-weight: 700; color: #64748b; text-transform: uppercase; background: #f8f9fb; font-size: 13px; letter-spacing: 0.5px; }
    td { color: #0f172a; font-weight: 500; }

    .empty-state { text-align: center; padding: 60px 20px; color: #64748b; }
    .empty-state-icon { font-size: 64px; margin-bottom: 16px; opacity: 0.5; }
    .empty-state p { font-size: 17px; }

    .btn { display: inline-block; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-size: 15px; font-weight: 600; transition: all 0.3s; }
    .btn-primary { background: #2459ff; color: white; }
    .btn-primary:hover { background: #1e47cc; transform: translateY(-2px); }
    .btn-secondary { background: #f3f4f6; color: #0f172a; border: 1px solid #d1d5db; }

    @media (max-width: 768px) {
        .grid { grid-template-columns: 1fr; }
        .module-container { padding: 20px; }
        .module-header { padding: 28px; }
        .module-header h1 { font-size: 32px; }
        .tabs-content { padding: 24px; }
        .tabs-header { flex-wrap: wrap; }
        .tab-btn { flex: 0 1 calc(50% - 8px); font-size: 14px; padding: 14px; }
    }
</style>

<div class="module-container">
    <a href="{{ route('student.courses.show', $module->course_id) }}" class="back-link">← Back to Course</a>

    <div class="module-header">
        <h1>{{ $module->title }}</h1>
        <p>{{ $module->course->name }} ({{ $module->course->code }})</p>
        <p>{{ $module->description ?: 'No module description provided.' }}</p>
        <div class="module-meta">
            <span class="meta-item">📚 {{ $module->lesson_count }} items</span>
            <span class="meta-item">📝 {{ $module->assignment_count }} assignments</span>
            <span class="meta-item">🧪 {{ $module->quiz_count }} quizzes</span>
            <span class="meta-item">👨‍🏫 {{ $module->teacher?->name ?? 'Course teacher' }}</span>
        </div>
    </div>

    <div class="tabs-container">
        <div class="tabs-header">
            <button class="tab-btn active" onclick="switchTab(event, 'overview')">Overview</button>
            <button class="tab-btn" onclick="switchTab(event, 'assignments')">Assignments ({{ $assignments->count() }})</button>
            <button class="tab-btn" onclick="switchTab(event, 'exams')">Exams ({{ $exams->count() }})</button>
            <button class="tab-btn" onclick="switchTab(event, 'activity')">Recent Activity</button>
        </div>

        <div class="tabs-content">
            <!-- Overview Tab -->
            <div id="overview" class="tab-pane active">
                <h3 style="margin-top: 0;">Your Progress</h3>
                <div class="grid">
                    <div class="metric">
                        <h4>Assignment Average</h4>
                        <div class="value">{{ number_format($assignmentAverage, 1) }}%</div>
                    </div>
                    <div class="metric">
                        <h4>Exam Average</h4>
                        <div class="value">{{ number_format($examAverage, 1) }}%</div>
                    </div>
                    <div class="metric">
                        <h4>Overall Average</h4>
                        <div class="value">{{ number_format($overallAverage, 1) }}%</div>
                    </div>
                    <div class="metric">
                        <h4>Graded Records</h4>
                        <div class="value">{{ $gradedAssignments->count() + $examResults->count() }}</div>
                    </div>
                </div>

                <h3>Recent Grades</h3>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
                    <div>
                        <h4 style="margin: 0 0 18px; color: #0f172a; font-size: 18px; font-weight: 600;">Recent Assignment Grades</h4>
                        @if($gradedAssignments->count() > 0)
                            <table>
                                <thead><tr><th>Title</th><th>Score</th></tr></thead>
                                <tbody>
                                    @foreach($gradedAssignments->take(4) as $submission)
                                        <tr>
                                            <td>{{ Str::limit($submission->assignment->title, 25) }}</td>
                                            <td><strong>{{ $submission->score }}/{{ $submission->assignment->max_score }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state" style="padding: 30px;">
                                <p>No graded assignments yet.</p>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h4 style="margin: 0 0 18px; color: #0f172a; font-size: 18px; font-weight: 600;">Recent Exam Grades</h4>
                        @if($examResults->count() > 0)
                            <table>
                                <thead><tr><th>Title</th><th>Score</th></tr></thead>
                                <tbody>
                                    @foreach($examResults->take(4) as $result)
                                        <tr>
                                            <td>{{ Str::limit($result->exam->title, 25) }}</td>
                                            <td><strong>{{ $result->score }}/{{ $result->exam->max_score }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state" style="padding: 30px;">
                                <p>No exam grades yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assignments Tab -->
            <div id="assignments" class="tab-pane">
                <h3 style="margin-top: 0;">Assignments for {{ $module->title }}</h3>
                @if($assignments->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignments as $assignment)
                                @php
                                    $submission = $assignment->submissions->first();
                                    $status = $submission ? ($submission->status === 'graded' ? 'Graded' : 'Submitted') : 'Not Submitted';
                                    $statusColor = $submission ? ($submission->status === 'graded' ? '#10b981' : '#f59e0b') : '#ef4444';
                                @endphp
                                <tr>
                                    <td><strong>{{ $assignment->title }}</strong></td>
                                    <td><span style="background: #e0ecff; color: #2459ff; padding: 6px 12px; border-radius: 6px; font-size: 14px; font-weight: 600;">{{ ucfirst($assignment->type) }}</span></td>
                                    <td>{{ $assignment->due_date?->format('d/m/Y') ?: '-' }}</td>
                                    <td><span style="background: {{ str_contains($statusColor, '#') ? $statusColor : '#f3f4f6' }}; color: white; padding: 6px 12px; border-radius: 6px; font-size: 14px; font-weight: 600;">{{ $status }}</span></td>
                                    <td>
                                        <a href="{{ route('student.assignments.show', $assignment) }}" class="btn btn-secondary" style="padding: 8px 16px; font-size: 14px;">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">📝</div>
                        <p>No assignments for this module yet.</p>
                    </div>
                @endif
            </div>

            <!-- Exams Tab -->
            <div id="exams" class="tab-pane">
                <h3 style="margin-top: 0;">Exams for {{ $module->title }}</h3>
                @if($exams->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Exam Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exams as $exam)
                                @php
                                    $result = $exam->results->first();
                                    $status = $result ? 'Completed' : 'Not Taken';
                                @endphp
                                <tr>
                                    <td><strong>{{ $exam->title }}</strong></td>
                                    <td><span style="background: #f3e8ff; color: #7c3aed; padding: 6px 12px; border-radius: 6px; font-size: 14px; font-weight: 600;">{{ ucfirst($exam->type) }}</span></td>
                                    <td>{{ $exam->exam_date?->format('d/m/Y') ?: '-' }}</td>
                                    <td><span style="background: {{ $result ? '#10b981' : '#d1d5db' }}; color: white; padding: 6px 12px; border-radius: 6px; font-size: 14px; font-weight: 600;">{{ $status }}</span></td>
                                    <td>
                                        <a href="{{ route('student.exams.show', $exam) }}" class="btn btn-secondary" style="padding: 8px 16px; font-size: 14px;">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">🧪</div>
                        <p>No exams for this module yet.</p>
                    </div>
                @endif
            </div>

            <!-- Activity Tab -->
            <div id="activity" class="tab-pane">
                <h3 style="margin-top: 0;">Recent Activity</h3>
                @if($recents->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        @foreach($recents as $recent)
                            <div class="list-item">
                                <h4>{{ $recent['title'] }}</h4>
                                <p>{{ $recent['subtitle'] }}</p>
                                <p style="font-size: 13px; color: #9ca3af; margin-top: 6px;">{{ $recent['date']->format('d/m/Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">📅</div>
                        <p>No recent activity for this module yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(event, tabName) {
        if (event) {
            event.preventDefault();
        }

        // Hide all tab panes
        const panes = document.querySelectorAll('.tab-pane');
        panes.forEach(pane => pane.classList.remove('active'));

        // Remove active class from all buttons
        const buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => btn.classList.remove('active'));

        // Show the selected tab pane
        document.getElementById(tabName).classList.add('active');

        // Add active class to the clicked button
        const targetBtn = event ? event.target : document.querySelector(`button[onclick*="'${tabName}'"]`);
        if (targetBtn) {
            targetBtn.classList.add('active');
        }

        // Update URL without reloading
        const url = new URL(window.location.href);
        url.searchParams.set('tab', tabName);
        window.history.replaceState({}, '', url);
    }

    // Check for tab query parameter on page load
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');

        if (tabParam && ['overview', 'assignments', 'exams', 'activity'].includes(tabParam)) {
            switchTab(null, tabParam);
        }

        // Show success message if submitted
        if (urlParams.get('success') === 'submitted') {
            const successDiv = document.createElement('div');
            successDiv.className = 'notice notice-success';
            successDiv.textContent = 'Exam submitted successfully!';
            successDiv.style.cssText = 'padding: 12px 14px; border-radius: 10px; margin-bottom: 16px; background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7;';

            const container = document.querySelector('.module-container');
            if (container) {
                container.insertBefore(successDiv, container.children[1]);
            }
        }
    });
</script>
@endsection
