@extends('layouts.teacher')

@section('title', $assignment->title . ' Submissions')
@section('page-title', $assignment->title . ' Submissions')

@section('content')
<style>
    .page-shell { display: grid; gap: 18px; }
    .panel { background: #fff; border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 18px; padding: 20px; box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05); }
    .hero { background: linear-gradient(135deg, #0f172a 0%, #111827 100%); color: #fff; }
    .hero h1 { margin: 0; font-size: 28px; }
    .hero p { margin: 8px 0 0; color: #cbd5e1; }
    .meta { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px; }
    .meta span { background: rgba(255,255,255,0.08); padding: 6px 10px; border-radius: 999px; font-size: 13px; }
    .toolbar { display: grid; grid-template-columns: 1.4fr .8fr auto; gap: 12px; align-items: end; }
    @media (max-width: 900px) { .toolbar { grid-template-columns: 1fr; } }
    label { display: block; margin-bottom: 6px; color: #334155; font-size: 13px; font-weight: 700; }
    input, select { width: 100%; border: 1px solid #dbe3ee; border-radius: 10px; padding: 11px 12px; font-size: 14px; color: #0f172a; background: #fff; }
    .btn { display: inline-flex; align-items: center; justify-content: center; padding: 11px 16px; border-radius: 10px; border: 0; text-decoration: none; font-weight: 700; cursor: pointer; }
    .btn-primary { background: #2563eb; color: #fff; }
    .btn-secondary { background: #e2e8f0; color: #0f172a; }
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 1100px; }
    th, td { padding: 14px 12px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: top; }
    th { background: #f8fafc; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; }
    tr:hover td { background: #f8fafc; }
    .badge { display: inline-flex; align-items: center; padding: 5px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
    .badge-submitted { background: #dbeafe; color: #1d4ed8; }
    .badge-late { background: #fef3c7; color: #b45309; }
    .badge-missing { background: #fee2e2; color: #991b1b; }
    .badge-graded { background: #dcfce7; color: #166534; }
    .student { font-weight: 700; color: #0f172a; }
    .meta-small { color: #475569; font-size: 13px; }
    .file-link, .text-link { color: #2563eb; text-decoration: none; font-weight: 700; }
    .empty { text-align: center; padding: 50px 20px; color: #64748b; }
    .pagination { margin-top: 18px; }
</style>

<div class="page-shell">
    <div class="panel hero">
        <div style="display:flex; justify-content:space-between; gap:16px; align-items:flex-start; flex-wrap:wrap;">
            <div>
                <h1>{{ $assignment->title }}</h1>
                <p>Student submissions for this assignment.</p>
                <div class="meta">
                    <span>Due {{ optional($assignment->due_date)->format('d M Y') ?? '-' }}</span>
                    <span>Max Score {{ $assignment->max_score }}</span>
                    <span>{{ $assignment->module?->title ?? 'General' }}</span>
                </div>
            </div>
            <a href="{{ route('teacher.courses.assignment-grading', $assignment->course) }}" class="btn btn-secondary">Back to Grading</a>
        </div>
    </div>

    <div class="panel">
        <form method="GET" class="toolbar">
            <div>
                <label for="search">Search student</label>
                <input id="search" name="search" value="{{ $search }}" placeholder="Search by student name or ID">
            </div>
            <div>
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="all" @selected($statusFilter === 'all')>All</option>
                    <option value="submitted" @selected($statusFilter === 'submitted')>Submitted</option>
                    <option value="late" @selected($statusFilter === 'late')>Late</option>
                    <option value="graded" @selected($statusFilter === 'graded')>Graded</option>
                    <option value="missing" @selected($statusFilter === 'missing')>Missing</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Apply</button>
                <a href="{{ route('teacher.grading.assignments.submissions', $assignment) }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="panel table-wrap">
        @if($submissions->count())
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Submission Time</th>
                        <th>Status</th>
                        <th>Marks Obtained</th>
                        <th>Grade Button</th>
                        <th>File / Answer</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $row)
                        @php
                            $student = $row['student'];
                            $submission = $row['submission'];
                        @endphp
                        <tr>
                            <td class="student">{{ $student->name }}</td>
                            <td class="meta-small">{{ $student->student_id ?? '-' }}</td>
                            <td class="meta-small">{{ optional($row['submitted_at'])->format('d M Y h:i A') ?? '-' }}</td>
                            <td>
                                @php
                                    $statusClass = match($row['status']) {
                                        'late' => 'badge-late',
                                        'graded' => 'badge-graded',
                                        'missing' => 'badge-missing',
                                        default => 'badge-submitted',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($row['status']) }}</span>
                            </td>
                            <td class="meta-small">
                                @if($submission)
                                    {{ $row['marks'] ?? 0 }}/{{ $assignment->max_score }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($submission)
                                    <a href="{{ route('teacher.grading.submissions.show', $submission) }}" class="btn btn-primary">Grade</a>
                                @else
                                    <span class="meta-small">No submission</span>
                                @endif
                            </td>
                            <td>
                                @if($submission?->file_path)
                                    <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="file-link">Download file</a>
                                @elseif($submission?->content)
                                    <a href="{{ route('teacher.grading.submissions.show', $submission) }}" class="text-link">Open answer</a>
                                @else
                                    <span class="meta-small">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination">{{ $submissions->links() }}</div>
        @else
            <div class="empty">
                <h3 style="margin:0 0 8px; color:#0f172a;">No matching student submissions</h3>
                <p style="margin:0;">Try a different search or filter.</p>
            </div>
        @endif
    </div>
</div>
@endsection
