@extends('layouts.teacher')

@section('title', $exam->title)
@section('page-title', $exam->title)

@section('content')
<style>
    .card { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f8f8; }
</style>

@if(session('success'))
    <div style="padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 16px;">{{ session('success') }}</div>
@endif

<div class="card">
    <h2 style="margin-bottom: 8px;">Exam Details</h2>
    <p><strong>Date:</strong> {{ $exam->exam_date->format('F d, Y') }}</p>
    <p><strong>Max Score:</strong> {{ $exam->max_score }}</p>
    <p><strong>Description:</strong> {{ $exam->description ?: 'No description' }}</p>
</div>

<div class="card">
    <h2 style="margin-bottom: 12px;">Add / Update Student Score</h2>
    <form method="POST" action="{{ route('teacher.exams.results.upsert', $exam) }}" style="display: grid; grid-template-columns: 1fr 120px 1fr auto; gap: 10px; align-items: end;">
        @csrf
        <div>
            <label style="display:block; margin-bottom:4px;">Student</label>
            <select name="student_id" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                <option value="">Select student</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->student_id }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display:block; margin-bottom:4px;">Score</label>
            <input type="number" name="score" min="0" max="{{ $exam->max_score }}" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
        </div>
        <div>
            <label style="display:block; margin-bottom:4px;">Remarks</label>
            <input name="remarks" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;" placeholder="Optional remarks">
        </div>
        <button type="submit" style="padding:9px 14px; border:0; border-radius:4px; background:#2196F3; color:#fff; cursor:pointer;">Save</button>
    </form>
</div>

<div class="card">
    <h2 style="margin-bottom: 12px;">Exam Results</h2>
    <table>
        <thead>
            <tr>
                <th>Student</th>
                <th>Student ID</th>
                <th>Score</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($results as $result)
                <tr>
                    <td>{{ $result->student->name }}</td>
                    <td>{{ $result->student->student_id }}</td>
                    <td>{{ $result->score }}/{{ $exam->max_score }}</td>
                    <td>{{ $result->remarks ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No exam results yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
