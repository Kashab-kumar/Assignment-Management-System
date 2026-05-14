@extends('layouts.teacher')

@section('title', $module->title)
@section('page-title', 'Module Workspace')

@section('content')
<style>
    .wrap { display: grid; gap: 14px; }
    .panel { background: #fff; border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; padding: 16px; }
    .header h2 { margin: 0; color: #111827; }
    .header p { margin: 6px 0 0; color: #475569; }
    .chips { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
    .chip { background: #eef2ff; color: #3730a3; border-radius: 999px; padding: 4px 10px; font-size: 12px; }
    .breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; font-size: 14px; }
    .breadcrumb a { color: #7c3aed; text-decoration: none; transition: color 0.2s; }
    .breadcrumb a:hover { color: #5b21b6; text-decoration: underline; }
    .breadcrumb span { color: #6b7280; }
    .cards-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; margin-top: 16px; }
    @media (max-width: 1100px) { .cards-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 768px) { .cards-grid { grid-template-columns: 1fr; } }

    /* Modern Card Styles */
    .card-wrapper {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .card-link {
        text-decoration: none;
        display: block;
    }
    .card {
        background: white;
        border: 2px solid #e5e7eb;
        padding: 32px;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        min-height: 140px;
    }

    .card-toggle {
        width: 100%;
        text-align: left;
        font: inherit;
    }

    .card.active {
        border-color: #7c3aed;
        box-shadow: 0 12px 28px rgba(124,58,237,0.15);
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.1);
        border-color: #7c3aed;
    }
    .card-label {
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .card-count {
        font-size: 42px;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 4px;
    }
    .card-sublabel {
        font-size: 14px;
        color: #9ca3af;
    }
    .card-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .card-icon.assignments { background: #dcfce7; }
    .card-icon.exams { background: #ede9fe; }

    .btn-create {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 1px dashed #d1d5db;
        background: white;
        color: #6b7280;
    }
    .btn-create:hover {
        border-color: #10b981;
        color: #10b981;
        background: #f0fdf4;
    }
    .btn-create.exam:hover {
        border-color: #7c3aed;
        color: #7c3aed;
        background: #faf5ff;
    }

    /* Unit Outline Styles */
    .section-title { font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 12px; }
    .items-list { display: grid; gap: 10px; }
    .item-card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; display: flex; justify-content: space-between; align-items: center; transition: all 0.2s; }
    .item-card:hover { background: #f3f4f6; border-color: #d1d5db; }
    .item-info { flex: 1; }
    .item-title { font-weight: 600; font-size: 15px; color: #111827; margin-bottom: 6px; }
    .item-title a:hover { color: #7c3aed; text-decoration: underline; }
    .item-meta { display: flex; align-items: center; gap: 8px; }
    .item-type { font-size: 11px; padding: 2px 8px; border-radius: 999px; font-weight: 500; }
    .item-type.video { background: #dbeafe; color: #1e40af; }
    .item-type.note { background: #dcfce7; color: #166534; }
    .item-type.quiz { background: #fef3c7; color: #92400e; }
    .item-type.test { background: #fce7f3; color: #9d174d; }
    .item-type.other { background: #e5e7eb; color: #374151; }
    .item-actions { display: flex; gap: 6px; }
    .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
    .btn-edit { background: #3b82f6; color: white; }
    .btn-edit:hover { background: #2563eb; }
    .btn-delete { background: #ef4444; color: white; }
    .btn-delete:hover { background: #dc2626; }
    .btn-add { background: #7c3aed; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-block; font-weight: 500; margin-top: 12px; }
    .btn-add:hover { background: #6d28d9; }
    .empty-state { text-align: center; padding: 24px; color: #6b7280; font-size: 14px; }

    .outline-table-wrap { overflow-x: auto; border: 1px solid #d1d5db; border-radius: 10px; }
    .outline-table { width: 100%; border-collapse: collapse; min-width: 980px; }
    .outline-table th,
    .outline-table td {
        border: 1px solid #111827;
        padding: 8px;
        vertical-align: top;
        font-size: 14px;
    }
    .outline-table th {
        background: #f3f4f6;
        color: #111827;
        text-align: left;
        font-weight: 700;
    }
    .outline-chapter-cell { min-width: 200px; }
    .outline-chapter-title { font-weight: 700; color: #111827; margin-bottom: 6px; word-break: break-word; }
    .outline-row-actions { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 8px; }
    .outline-task { color: #111827; word-break: break-word; }
    .outline-number { text-align: right; color: #111827; white-space: nowrap; }
    .outline-check { text-align: center; font-weight: 700; white-space: nowrap; }
    .outline-check.done { color: #16a34a; }
    .outline-check.not-done { color: #dc2626; }
    .outline-total-cell,
    .outline-file-cell { text-align: center; vertical-align: middle; }
    .outline-total-value { font-weight: 700; color: #111827; }
    .outline-file-link { color: #2563eb; text-decoration: none; font-weight: 600; word-break: break-word; display: inline-block; }
    .outline-file-link:hover { text-decoration: underline; }
    .outline-summary-row td { background: #f9fafb; font-weight: 700; }
    .outline-summary-row td:last-child { text-align: right; }

    .unit-outline-panel-hidden { display: none; }
</style>

<div class="wrap">
    <div class="panel header">
        <nav class="breadcrumb">
            <a href="{{ route('teacher.courses.index') }}">Courses</a>
            <span>/</span>
            <a href="{{ route('teacher.courses.show', $course) }}">{{ $course->name }}</a>
            <span>/</span>
            <span>{{ $module->title }}</span>
        </nav>
        <h2>{{ $module->title }}</h2>
        <p>{{ $course->name }} ({{ $course->code }})</p>
        <p>{{ $module->description ?: 'No module description provided.' }}</p>
        <div class="chips">
            <span class="chip">{{ $module->lesson_count }} lessons</span>
            <span class="chip">{{ $module->assignment_count }} assignments</span>
            <span class="chip">{{ $module->quiz_count }} quizzes</span>
            <span class="chip">Assigned Teacher: {{ $module->teacher?->name ?? 'Any assigned course teacher' }}</span>
        </div>
    </div>

    @php
        $unitOutlineItems = ($module->items ?? collect())->where('type', 'unit_outline')->values();
        $overallChapterWeight = 0;
    @endphp

    <div class="cards-grid">
        <!-- Assignments Card -->
        <div class="card-wrapper">
            <a href="{{ route('teacher.assignments.index', ['course_id' => $course->id, 'module_id' => $module->id]) }}" class="card-link">
                <div class="card">
                    <div class="card-label">Assignments</div>
                    <div class="card-count">{{ $assignments->count() }}</div>
                    <div class="card-sublabel">Manage assignments</div>
                </div>
            </a>
            <a href="{{ route('teacher.assignments.create', ['course_id' => $course->id, 'module_id' => $module->id]) }}" class="btn-create">
                <span>+</span> Create Assignment
            </a>
        </div>

        <!-- Exams Card -->
        <div class="card-wrapper">
            <a href="{{ route('teacher.exams.index', ['course_id' => $course->id, 'module_id' => $module->id]) }}" class="card-link">
                <div class="card">
                    <div class="card-label">Exams</div>
                    <div class="card-count">{{ $exams->count() }}</div>
                    <div class="card-sublabel">Manage exams & quizzes</div>
                </div>
            </a>
            <a href="{{ route('teacher.exams.create', ['course_id' => $course->id, 'module_id' => $module->id]) }}" class="btn-create exam">
                <span>+</span> Create Exam
            </a>
        </div>

        <!-- Unit Outline Card -->
        <div class="card-wrapper">
            <button type="button" id="unit-outline-card" class="card card-toggle" onclick="toggleUnitOutlinePanel()" aria-expanded="false" aria-controls="unit-outline-panel">
                <div class="card-label">Unit Outline</div>
                <div class="card-count">{{ $unitOutlineItems->count() }}</div>
                <div class="card-sublabel">View chapter table</div>
            </button>
            <a href="{{ route('teacher.courses.modules.items.create', [$course, $module]) }}" class="btn-create">
                <span>+</span> Add Unit Outline
            </a>
        </div>
    </div>

    <div id="unit-outline-panel" class="panel unit-outline-panel-hidden" style="margin-top: 20px;">
        <h3 class="section-title">Unit Outline</h3>

        @if($unitOutlineItems->count() > 0)
            <div class="outline-table-wrap">
                <table class="outline-table">
                    <thead>
                        <tr>
                            <th>Chapter/unit</th>
                            <th>Tasks</th>
                            <th>Marks</th>
                            <th>Weightage</th>
                            <th>Check list</th>
                            <th>Total Weightage</th>
                            <th>Question bank</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unitOutlineItems as $item)
                            @php
                                $criteria = collect($item->grading_criteria ?? [])->values();
                                if ($criteria->isEmpty()) {
                                    $criteria = collect([[
                                        'topic' => '-',
                                        'marks' => '-',
                                        'weight' => 0,
                                    ]]);
                                }

                                $criteriaCount = max($criteria->count(), 1);
                                $criteriaWeightTotal = $criteria->sum(fn ($criterion) => (float) ($criterion['weight'] ?? 0));
                                $chapterWeight = (float) data_get($item->ai_options, 'chapter_total_weight', $criteriaWeightTotal);
                                $overallChapterWeight += $chapterWeight;
                            @endphp

                            @foreach($criteria as $rowIndex => $criterion)
                                @php
                                    $topic = trim((string) ($criterion['topic'] ?? $criterion['name'] ?? '-'));
                                    $marks = $criterion['marks'] ?? '-';
                                    $weight = (float) ($criterion['weight'] ?? 0);
                                    $isDone = $topic !== '' && $topic !== '-' && is_numeric($marks) && (float) $marks > 0 && $weight > 0;
                                @endphp
                                <tr>
                                    @if($rowIndex === 0)
                                        <td class="outline-chapter-cell" rowspan="{{ $criteriaCount }}">
                                            <div class="outline-chapter-title">{{ $item->title }}</div>
                                            <div class="outline-row-actions">
                                                <a href="{{ route('teacher.courses.modules.items.show', [$course, $module, $item]) }}" class="btn-sm" style="background:#3b82f6;color:#fff;text-decoration:none;">View</a>
                                                <a href="{{ route('teacher.courses.modules.items.edit', [$course, $module, $item]) }}" class="btn-sm btn-edit">Edit</a>
                                                <form method="POST" action="{{ route('teacher.courses.modules.items.destroy', [$course, $module, $item]) }}" style="display:inline;" onsubmit="return confirm('Delete this unit outline? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-sm btn-delete">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif

                                    <td class="outline-task">{{ $topic }}</td>
                                    <td class="outline-number">{{ $marks }}</td>
                                    <td class="outline-number">{{ rtrim(rtrim(number_format($weight, 2, '.', ''), '0'), '.') }}%</td>
                                    <td class="outline-check {{ $isDone ? 'done' : 'not-done' }}">{{ $isDone ? 'Done' : 'Not Done' }}</td>

                                    @if($rowIndex === 0)
                                        <td class="outline-total-cell" rowspan="{{ $criteriaCount }}">
                                            <div class="outline-total-value">{{ rtrim(rtrim(number_format($chapterWeight, 2, '.', ''), '0'), '.') }}%</div>
                                        </td>
                                        <td class="outline-file-cell" rowspan="{{ $criteriaCount }}">
                                            @if($item->file_path)
                                                <a class="outline-file-link" href="{{ asset('storage/' . $item->file_path) }}" target="_blank" rel="noopener noreferrer">{{ $item->file_name ?: 'File upload' }}</a>
                                            @else
                                                <span style="color:#6b7280;">File upload</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach

                        <tr class="outline-summary-row">
                            <td>Total</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ rtrim(rtrim(number_format($overallChapterWeight, 2, '.', ''), '0'), '.') }}%</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">No content has been added to this unit yet.</div>
        @endif

        <a href="{{ route('teacher.courses.modules.items.create', [$course, $module]) }}" class="btn-add">+ Add Unit Outline</a>
    </div>
</div>

<script>
function toggleUnitOutlinePanel() {
    const panel = document.getElementById('unit-outline-panel');
    const card = document.getElementById('unit-outline-card');
    if (!panel || !card) {
        return;
    }

    const isHidden = panel.classList.contains('unit-outline-panel-hidden');
    if (isHidden) {
        panel.classList.remove('unit-outline-panel-hidden');
        card.classList.add('active');
        card.setAttribute('aria-expanded', 'true');
        panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        panel.classList.add('unit-outline-panel-hidden');
        card.classList.remove('active');
        card.setAttribute('aria-expanded', 'false');
    }
}
</script>
@endsection
