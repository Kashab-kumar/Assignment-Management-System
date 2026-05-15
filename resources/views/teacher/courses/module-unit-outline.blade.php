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
                                $isDone = $topic !== '' && $topic !== '-' && is_numeric($marks) && (float) $marks > 0 && $weight > 0;
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
                                    @php
                                        // Get assignments and exams for this unit that cover this topic (case-insensitive)
                                        $topicLower = strtolower(trim($topic));

                                        // Filter assignments for this topic
                                        $assignments = \App\Models\Assignment::where('unit_id', $item->unit_id)->get()
                                            ->filter(function($a) use ($topicLower) {
                                                if (empty($a->covered_topics) || !is_array($a->covered_topics)) return false;
                                                foreach ($a->covered_topics as $coveredTopic) {
                                                    if (strtolower(trim($coveredTopic)) === $topicLower) {
                                                        return true;
                                                    }
                                                }
                                                return false;
                                            });

                                        // Filter exams for this topic
                                        $exams = \App\Models\Exam::where('unit_id', $item->unit_id)->get()
                                            ->filter(function($e) use ($topicLower) {
                                                if (empty($e->covered_topics) || !is_array($e->covered_topics)) return false;
                                                foreach ($e->covered_topics as $coveredTopic) {
                                                    if (strtolower(trim($coveredTopic)) === $topicLower) {
                                                        return true;
                                                    }
                                                }
                                                return false;
                                            });

                                        // Recompute isDone: true if any linked assignment/exam covers it; otherwise fallback to criteria checks
                                        if ($assignments->count() > 0 || $exams->count() > 0) {
                                            $isDone = true;
                                        } else {
                                            $isDone = $topic !== '' && $topic !== '-' && is_numeric($marks) && (float) $marks > 0 && $weight > 0;
                                        }
                                    @endphp
                                    <div style="display: flex; flex-direction: column; gap: 6px;">
                                        @if($isDone)
                                            <span style="font-weight:700; color:#16a34a;">✓ Done</span>
                                        @else
                                            <span style="font-weight:700; color:#dc2626;">Not Done</span>
                                        @endif

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
                                        {{-- Quick links to open the first matched assignment or exam --}}
                                        @if($assignments->count() > 0 || $exams->count() > 0)
                                            <div style="margin-top:6px;">
                                                @if($assignments->count() > 0)
                                                    <a href="{{ route('teacher.assignments.show', $assignments->first()) }}" style="font-size:12px; color:#1e40af; text-decoration:underline; display:inline-block; margin-right:8px;">Open assignment</a>
                                                @endif
                                                @if($exams->count() > 0)
                                                    <a href="{{ route('teacher.exams.show', $exams->first()) }}" style="font-size:12px; color:#92400e; text-decoration:underline; display:inline-block;">Open exam</a>
                                                @endif
                                            </div>
                                        @endif
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
