@extends('layouts.teacher')

@section('title', $module->title . ' - Unit Outline')
@section('page-title', 'Unit Outline')

@section('content')
<style>
    .panel { background: #fff; border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; padding: 16px; }
    .section-title { font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 12px; }
    .outline-table-wrap { overflow-x: auto; border: 1px solid #d1d5db; border-radius: 10px; }
    .outline-table { width: 100%; border-collapse: collapse; min-width: 980px; }
    .outline-table th,
    .outline-table td { border: 1px solid #111827; padding: 8px; vertical-align: top; font-size: 14px; }
    .outline-table th { background: #f3f4f6; color: #111827; text-align: left; font-weight: 700; }
    .outline-chapter-cell { min-width: 200px; }
    .outline-chapter-title { font-weight: 700; color: #111827; margin-bottom: 6px; word-break: break-word; }
    .outline-row-actions { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 8px; }
    .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; transition: all 0.2s; }
    .btn-edit { background: #3b82f6; color: white; }
    .btn-delete { background: #ef4444; color: white; }
    .outline-total-value { font-weight: 700; color: #111827; }
    .checklist-state { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; }
    .checklist-state.done { color: #16a34a; }
    .checklist-state.pending { color: #dc2626; }
    .checklist-source { display: inline-block; margin-top: 6px; font-size: 12px; color: #6b7280; }
    .checklist-actions { display: flex; flex-wrap: wrap; gap: 6px; justify-content: center; margin-top: 8px; }
    .checklist-actions form { display: inline; }
    .checklist-btn { padding: 4px 8px; border-radius: 6px; border: none; cursor: pointer; font-size: 12px; font-weight: 600; }
    .checklist-btn.done { background: #16a34a; color: white; }
    .checklist-btn.pending { background: #dc2626; color: white; }
    .checklist-btn.auto { background: #e5e7eb; color: #111827; }
</style>

<div class="panel">
    <nav style="margin-bottom:12px;">
        <a href="{{ route('teacher.courses.show', $course) }}">{{ $course->name }}</a> / <span>{{ $module->title }}</span> / <strong>Unit Outline</strong>
    </nav>

    <h3 class="section-title">Unit Outline</h3>

    @php $overallChapterWeight = 0; @endphp

    @if($module->items && $module->items->count() > 0)
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
                    @foreach($module->items as $item)
                        @php
                            $criteria = collect($item->grading_criteria ?? [])->values();
                            if ($criteria->isEmpty()) {
                                $criteria = collect([[ 'topic' => '-', 'marks' => '-', 'weight' => 0, ]]);
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
                                $topicKey = sha1($item->id . '|' . strtolower(trim($topic)) . '|' . $marks . '|' . $weight);
                                $checklistStatus = data_get($item->ai_options, 'checklist_status', []);
                                $manualStatus = is_array($checklistStatus) ? ($checklistStatus[$topicKey] ?? null) : null;

                                $assignments = collect();
                                $exams = collect();

                                if ($item->unit_id) {
                                    $assignments = \App\Models\Assignment::where('unit_id', $item->unit_id)
                                        ->with(['submissions'])
                                        ->get()
                                        ->filter(function ($assignment) use ($topic) {
                                            if (empty($assignment->covered_topics) || !is_array($assignment->covered_topics)) {
                                                return false;
                                            }

                                            $topicLower = strtolower(trim($topic));

                                            foreach ($assignment->covered_topics as $coveredTopic) {
                                                if (strtolower(trim($coveredTopic)) === $topicLower) {
                                                    return true;
                                                }
                                            }

                                            return false;
                                        });

                                    $exams = \App\Models\Exam::where('unit_id', $item->unit_id)
                                        ->with(['results'])
                                        ->get()
                                        ->filter(function ($exam) use ($topic) {
                                            if (empty($exam->covered_topics) || !is_array($exam->covered_topics)) {
                                                return false;
                                            }

                                            $topicLower = strtolower(trim($topic));

                                            foreach ($exam->covered_topics as $coveredTopic) {
                                                if (strtolower(trim($coveredTopic)) === $topicLower) {
                                                    return true;
                                                }
                                            }

                                            return false;
                                        });
                                }

                                $assignmentDone = $assignments->contains(function ($assignment) {
                                    return $assignment->submissions->isNotEmpty() && $assignment->submissions->every(fn ($submission) => $submission->status === 'graded');
                                });

                                $examDone = $exams->contains(fn ($exam) => $exam->results->isNotEmpty());
                                $autoDone = $assignmentDone || $examDone;

                                if ($manualStatus === 'done') {
                                    $isDone = true;
                                    $statusSource = 'Manual';
                                } elseif ($manualStatus === 'pending') {
                                    $isDone = false;
                                    $statusSource = 'Manual';
                                } else {
                                    $isDone = $autoDone;
                                    $statusSource = $autoDone ? 'Auto' : 'Auto';
                                }
                            @endphp
                            <tr>
                                @if($rowIndex === 0)
                                    <td class="outline-chapter-cell" rowspan="{{ $criteriaCount }}">
                                        <div class="outline-chapter-title">{{ $item->title }}</div>
                                        <div class="outline-row-actions">
                                            <a href="{{ route('teacher.courses.modules.items.show', [$course, $module, $item]) }}" class="btn-sm" style="background:#3b82f6;color:#fff;text-decoration:none;">View</a>
                                            <a href="{{ route('teacher.courses.modules.items.edit', [$course, $module, $item]) }}" class="btn-sm btn-edit">Edit</a>
                                            <form method="POST" action="{{ route('teacher.courses.modules.items.destroy', [$course, $module, $item]) }}" style="display:inline;" onsubmit="return confirm('Delete this unit outline?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-sm btn-delete">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                @endif

                                <td>{{ $topic }}</td>
                                <td style="text-align:right;">{{ $marks }}</td>
                                <td style="text-align:right;">{{ rtrim(rtrim(number_format($weight, 2, '.', ''), '0'), '.') }}%</td>
                                <td style="text-align:center;">
                                    <div style="display: flex; flex-direction: column; gap: 6px; align-items:center;">
                                        <span class="checklist-state {{ $isDone ? 'done' : 'pending' }}">
                                            {{ $isDone ? '✓ Done' : 'Not Done' }}
                                        </span>
                                        <span class="checklist-source">{{ $statusSource }}{{ $manualStatus ? ' override' : ' check' }}</span>

                                        @if($assignments->count() > 0 || $exams->count() > 0)
                                            <div style="font-size: 12px; margin-top: 4px;">
                                                @foreach($assignments as $assignment)
                                                    <span style="display: inline-block; background: #dbeafe; color: #1e40af; padding: 2px 6px; border-radius: 4px; margin-right: 4px; margin-bottom: 2px;">
                                                        📝 {{ Str::limit($assignment->title, 15) }}
                                                    </span>
                                                @endforeach
                                                @foreach($exams as $exam)
                                                    <span style="display: inline-block; background: #fed7aa; color: #92400e; padding: 2px 6px; border-radius: 4px; margin-right: 4px; margin-bottom: 2px;">
                                                        ✓ {{ Str::limit($exam->title, 15) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="checklist-actions">
                                            <form method="POST" action="{{ route('teacher.courses.modules.items.checklist', [$course, $module, $item]) }}">
                                                @csrf
                                                <input type="hidden" name="criteria_key" value="{{ $topicKey }}">
                                                <input type="hidden" name="status" value="done">
                                                <button type="submit" class="checklist-btn done">Mark Done</button>
                                            </form>
                                            <form method="POST" action="{{ route('teacher.courses.modules.items.checklist', [$course, $module, $item]) }}">
                                                @csrf
                                                <input type="hidden" name="criteria_key" value="{{ $topicKey }}">
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="checklist-btn pending">Mark Pending</button>
                                            </form>
                                            @if($manualStatus)
                                                <form method="POST" action="{{ route('teacher.courses.modules.items.checklist', [$course, $module, $item]) }}">
                                                    @csrf
                                                    <input type="hidden" name="criteria_key" value="{{ $topicKey }}">
                                                    <input type="hidden" name="status" value="auto">
                                                    <button type="submit" class="checklist-btn auto">Use Auto</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                @if($rowIndex === 0)
                                    <td style="text-align:center;" rowspan="{{ $criteriaCount }}">
                                        <div class="outline-total-value">{{ rtrim(rtrim(number_format($chapterWeight, 2, '.', ''), '0'), '.') }}%</div>
                                    </td>
                                    <td style="text-align:center;" rowspan="{{ $criteriaCount }}">
                                        @if($item->file_path)
                                            <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank">{{ $item->file_name ?: 'File upload' }}</a>
                                        @else
                                            <span style="color:#6b7280;">File upload</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endforeach

                    <tr style="background:#f9fafb; font-weight:700;">
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align:right;">{{ rtrim(rtrim(number_format($overallChapterWeight, 2, '.', ''), '0'), '.') }}%</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <div style="padding:20px;">No unit outlines have been added yet.</div>
    @endif

    <div style="margin-top:12px;">
        <a href="{{ route('teacher.courses.modules.items.create', [$course, $module]) }}" class="btn-add">+ Add Unit Outline</a>
    </div>
</div>

@endsection
