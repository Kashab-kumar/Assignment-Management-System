@extends('layouts.teacher')

@section('title', 'Calendar')
@section('page-title', 'Teaching Calendar')

@section('content')
<style>
    .section { background: white; border-radius: 8px; padding: 20px; margin-bottom: 18px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .filters { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; margin-bottom: 14px; }
    .filters input, .filters select { padding: 8px 10px; border: 1px solid #ddd; border-radius: 4px; }
    .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 10px; }
    .form-grid .full { grid-column: 1 / -1; }
    .form-grid input, .form-grid select, .form-grid textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
    .form-grid textarea { min-height: 90px; resize: vertical; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
    th { background: #f8f8f8; }
    .badge { padding: 4px 8px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .badge-assignment { background: #eaf2ff; color: #1e5fd6; }
    .badge-quiz { background: #fff3e0; color: #f57c00; }
    .badge-exam { background: #ede7f6; color: #5e35b1; }
    .badge-test { background: #fee2e2; color: #b91c1c; }
    .badge-other { background: #eceff1; color: #455a64; }
    .source-custom { color: #2e7d32; font-weight: 700; }
    .source-assignment, .source-exam { color: #546e7a; }

    @media (max-width: 900px) {
        .form-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="section">
    <h2 style="margin-bottom: 14px;">{{ $monthStart->format('F Y') }} Teaching Plan</h2>

    @if(session('success'))
        <div style="padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 12px;">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div style="padding: 12px; background: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 12px;">{{ $errors->first() }}</div>
    @endif

    <form method="GET" class="filters">
        <label for="month" style="font-weight:bold;">Month:</label>
        <input type="month" id="month" name="month" value="{{ $selectedMonth }}">

        <label for="course_id" style="font-weight:bold;">Course:</label>
        <select id="course_id" name="course_id">
            <option value="">All Courses</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" {{ (string) $selectedCourseId === (string) $course->id ? 'selected' : '' }}>
                    {{ $course->category_name ?: 'Uncategorized' }} / {{ $course->class_name ?: 'Unassigned' }} / {{ $course->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" style="padding:8px 12px; border:0; border-radius:4px; background:#2196F3; color:#fff; cursor:pointer;">Apply</button>
    </form>
</div>

<div class="section">
    <h2 style="margin-bottom: 14px;">Add Course Event</h2>
    <form method="POST" action="{{ route('teacher.calendar.events.store') }}" class="form-grid">
        @csrf

        <div>
            <label for="course_id" style="font-weight:bold; display:block; margin-bottom:6px;">Course *</label>
            <select id="course_id" name="course_id" required>
                <option value="">Choose course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ (string) old('course_id', $selectedCourseId) === (string) $course->id ? 'selected' : '' }}>
                        {{ $course->category_name ?: 'Uncategorized' }} / {{ $course->class_name ?: 'Unassigned' }} / {{ $course->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="event_type" style="font-weight:bold; display:block; margin-bottom:6px;">Type *</label>
            <select id="event_type" name="event_type" required>
                <option value="assignment" {{ old('event_type') === 'assignment' ? 'selected' : '' }}>Assignment</option>
                <option value="quiz" {{ old('event_type') === 'quiz' ? 'selected' : '' }}>Quiz</option>
                <option value="exam" {{ old('event_type') === 'exam' ? 'selected' : '' }}>Exam</option>
                <option value="other" {{ old('event_type') === 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>

        <div>
            <label for="title" style="font-weight:bold; display:block; margin-bottom:6px;">Title *</label>
            <input id="title" name="title" value="{{ old('title') }}" required>
        </div>

        <div>
            <label for="event_date" style="font-weight:bold; display:block; margin-bottom:6px;">Date *</label>
            <input type="date" id="event_date" name="event_date" value="{{ old('event_date', $monthStart->toDateString()) }}" required>
        </div>

        <div class="full">
            <label for="description" style="font-weight:bold; display:block; margin-bottom:6px;">Description</label>
            <textarea id="description" name="description">{{ old('description') }}</textarea>
        </div>

        <div class="full">
            <button type="submit" style="padding:10px 14px; border:0; border-radius:4px; background:#4CAF50; color:#fff; cursor:pointer;">Add Event</button>
        </div>
    </form>
</div>

<div class="section">
    <h2 style="margin-bottom: 12px;">Calendar Timeline</h2>

    @if($events->isEmpty())
        <p style="color:#666;">No assignment/quiz/exam events found for this month.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Title</th>
                    <th>Course</th>
                    <th>Type</th>
                    <th>Source</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr>
                        <td>{{ $event['date']->format('d/m/Y') }}</td>
                        <td>{{ $event['title'] }}</td>
                        <td>{{ $event['course'] ?? '-' }}</td>
                        <td><span class="badge badge-{{ $event['type'] }}">{{ ucfirst($event['type']) }}</span></td>
                        <td class="source-{{ $event['source'] }}">{{ ucfirst($event['source']) }}</td>
                        <td>
                            @if($event['source'] === 'custom' && $event['id'])
                                <form method="POST" action="{{ route('teacher.calendar.events.destroy', $event['id']) }}" onsubmit="return confirm('Delete this custom event?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding:6px 10px; border:0; border-radius:4px; background:#e74c3c; color:#fff; cursor:pointer;">Delete</button>
                                </form>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
