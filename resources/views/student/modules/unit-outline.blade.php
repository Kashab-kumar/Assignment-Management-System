@extends('layouts.student')

@section('title', $module->title . ' - Unit Outline')
@section('page-title', 'Unit Outline Details')

@section('content')
<style>
    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 32px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
        color: #2459ff;
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px;
        padding: 40px;
        margin-bottom: 32px;
    }

    .header h1 {
        margin: 0 0 12px;
        font-size: 32px;
        font-weight: 700;
    }

    .header p {
        margin: 0;
        opacity: 0.95;
        font-size: 16px;
    }

    .outline-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .outline-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .outline-title {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 8px;
    }

    .outline-type {
        background: #dbeafe;
        color: #1e40af;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .outline-creator {
        color: #64748b;
        font-size: 14px;
        margin-top: 8px;
    }

    .btn-view {
        background: #3b82f6;
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        background: #2563eb;
        transform: translateY(-2px);
    }

    .description-box {
        background: #f9fafb;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        color: #374151;
        line-height: 1.7;
        font-size: 15px;
    }

    .file-section {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .file-card {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .file-icon {
        font-size: 40px;
    }

    .file-info {
        flex: 1;
    }

    .file-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 16px;
    }

    .file-meta {
        font-size: 13px;
        color: #6b7280;
        margin-top: 4px;
    }

    .file-actions {
        display: flex;
        gap: 8px;
    }

    .btn-sm {
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary-sm {
        background: #3b82f6;
        color: white;
    }

    .btn-primary-sm:hover {
        background: #2563eb;
    }

    .btn-secondary-sm {
        background: #10b981;
        color: white;
    }

    .btn-secondary-sm:hover {
        background: #059669;
    }

    .section-title {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0 0 16px;
    }

    .grade-scale {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .grade-item {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .grade-item:hover {
        border-color: #667eea;
        transform: translateY(-2px);
    }

    .grade-letter {
        font-size: 28px;
        font-weight: 800;
        color: #667eea;
    }

    .grade-range {
        font-size: 13px;
        color: #6b7280;
        margin-top: 4px;
    }

    .criteria-list {
        display: grid;
        gap: 12px;
    }

    .criteria-item {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .criteria-info h4 {
        margin: 0 0 4px;
        font-size: 15px;
        color: #0f172a;
    }

    .criteria-info p {
        margin: 0;
        font-size: 13px;
        color: #6b7280;
    }

    .criteria-weight {
        background: #dbeafe;
        color: #1e40af;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 700;
    }

    .assessment-summary {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .assessment-summary-title {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0 0 12px;
    }

    .assessment-summary-list {
        display: grid;
        gap: 10px;
    }

    .assessment-summary-item {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 14px 16px;
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: center;
    }

    .assessment-summary-name {
        font-weight: 600;
        color: #0f172a;
    }

    .chapter-section {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .chapter-section:first-of-type {
        margin-top: 0;
        padding-top: 0;
        border-top: 0;
    }

    .chapter-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0 0 12px;
    }

    .chapter-items-list {
        display: grid;
        gap: 10px;
    }

    .chapter-item {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 14px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .chapter-item-name {
        font-weight: 600;
        color: #0f172a;
    }

    .chapter-item-meta {
        font-size: 13px;
        color: #6b7280;
        margin-top: 4px;
    }

    .assessment-summary-desc {
        font-size: 13px;
        color: #6b7280;
        margin-top: 4px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .actions-bar {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        padding: 14px 28px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-outline {
        background: transparent;
        color: #6b7280;
        border: 2px solid #d1d5db;
    }

    .btn-outline:hover {
        background: #f3f4f6;
    }

    .btn-continue {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        margin-left: auto;
    }

    .btn-continue:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    /* Student table style similar to teacher view (question bank excluded) */
    .student-outline-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .student-outline-table th,
    .student-outline-table td {
        border: 1px solid #cbd5e1;
        padding: 10px 12px;
        text-align: left;
        vertical-align: middle;
    }
    .student-outline-table th {
        background: #f8fafc;
        font-weight: 700;
    }
    .student-outline-table .chapter-cell {
        background: #f8fafc;
        font-weight: 700;
    }
    .status-completed { color: #16a34a; font-weight: 700; }
    .status-not-done { color: #ef4444; font-weight: 700; }
</style>

<div class="container">
    <a href="{{ route('student.courses.show', $course->id) }}" class="back-link">
        ← Back to {{ $course->name }}
    </a>

    <div class="header">
        <h1>{{ $module->title }}</h1>
        <p>{{ $module->description ?: 'Unit Outline Details' }}</p>
    </div>

    @if($module->items && $module->items->count() > 0)
        <div class="outline-card">
            @php $overallChapterWeight = 0; @endphp
            <table class="student-outline-table">
                <thead>
                    <tr>
                        <th>Chapter/unit</th>
                        <th>Tasks</th>
                        <th>Marks</th>
                        <th>Weightage</th>
                        <th>Check list</th>
                        <th>Total Weightage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($module->items as $item)
                        @php
                            $unit = $item->unit;
                            $criteria = collect($item->grading_criteria ?? [])->values();

                            if ($criteria->isEmpty() && $unit && is_array($unit->grading_criteria)) {
                                $criteria = collect($unit->grading_criteria)->values();
                            }

                            if ($criteria->isEmpty()) {
                                $criteria = collect([[ 'topic' => '-', 'marks' => '-', 'weight' => 0 ]]);
                            }

                            $criteriaCount = max($criteria->count(), 1);
                            $criteriaWeightTotal = $criteria->sum(fn ($criterion) => (float) ($criterion['weight'] ?? 0));
                            $chapterWeight = (float) data_get($item->ai_options, 'chapter_total_weight', $criteriaWeightTotal);
                            $overallChapterWeight += $chapterWeight;
                        @endphp

                        @foreach($criteria as $rowIndex => $criterion)
                            @php
                                $topic = trim((string) ($criterion['topic'] ?? $criterion['name'] ?? $criterion['description'] ?? '-'));
                                $marks = $criterion['marks'] ?? '-';
                                $weight = (float) ($criterion['weight'] ?? 0);
                                $criteriaKey = sha1($item->id . '|' . strtolower(trim($topic)) . '|' . $marks . '|' . $weight);
                                $checklistStatus = data_get($item->ai_options, 'checklist_status', []);
                                $manualStatus = is_array($checklistStatus) ? ($checklistStatus[$criteriaKey] ?? null) : null;

                                $topicLower = strtolower(trim($topic));
                                $unitAssignments = $unit?->assignments ?? collect();
                                $unitExams = $unit?->exams ?? collect();

                                $matchingAssignments = $unitAssignments->filter(function ($assignment) use ($topicLower) {
                                    if (empty($assignment->covered_topics) || !is_array($assignment->covered_topics)) {
                                        return false;
                                    }

                                    foreach ($assignment->covered_topics as $coveredTopic) {
                                        if (strtolower(trim($coveredTopic)) === $topicLower) {
                                            return true;
                                        }
                                    }

                                    return false;
                                });

                                $matchingExams = $unitExams->filter(function ($exam) use ($topicLower) {
                                    if (empty($exam->covered_topics) || !is_array($exam->covered_topics)) {
                                        return false;
                                    }

                                    foreach ($exam->covered_topics as $coveredTopic) {
                                        if (strtolower(trim($coveredTopic)) === $topicLower) {
                                            return true;
                                        }
                                    }

                                    return false;
                                });

                                $matchingAssignments->loadMissing('submissions');
                                $matchingExams->loadMissing('results');

                                $assignmentDone = $matchingAssignments->contains(function ($assignment) {
                                    return $assignment->submissions->isNotEmpty() && $assignment->submissions->every(fn ($submission) => $submission->status === 'graded');
                                });

                                $examDone = $matchingExams->contains(fn ($exam) => $exam->results->isNotEmpty());
                                $autoDone = $assignmentDone || $examDone;

                                if ($manualStatus === 'done') {
                                    $isDone = true;
                                } elseif ($manualStatus === 'pending') {
                                    $isDone = false;
                                } else {
                                    $isDone = $autoDone;
                                }
                            @endphp

                            <tr>
                                @if($rowIndex === 0)
                                    <td class="chapter-cell" rowspan="{{ $criteriaCount }}">{{ $item->title }}</td>
                                @endif

                                <td>{{ $topic }}</td>
                                <td style="text-align:right;">{{ $marks }}</td>
                                <td style="text-align:right;">{{ rtrim(rtrim(number_format($weight, 2, '.', ''), '0'), '.') }}%</td>
                                <td class="{{ $isDone ? 'status-completed' : 'status-not-done' }}" style="text-align:center;">{{ $isDone ? '✓ Done' : 'Not Done' }}</td>

                                @if($rowIndex === 0)
                                    <td style="text-align:center; font-weight:700;" rowspan="{{ $criteriaCount }}">{{ rtrim(rtrim(number_format($chapterWeight, 2, '.', ''), '0'), '.') }}%</td>
                                @endif
                            </tr>
                        @endforeach
                    @endforeach

                    <tr style="background:#f8fafc; font-weight:700;">
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align:right;">{{ rtrim(rtrim(number_format($overallChapterWeight, 2, '.', ''), '0'), '.') }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="actions-bar">
            <a href="{{ route('student.courses.show', $course->id) }}" class="btn btn-outline">← Back to Course</a>
            <a href="{{ route('student.modules.show', $module->id) }}" class="btn btn-continue">Continue to Module Workspace →</a>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <h3>No Unit Outline Materials</h3>
            <p>No materials have been added to this module yet.</p>
        </div>
    @endif
</div>
@endsection
