@extends('layouts.teacher')

@section('title', 'Exams')
@section('page-title', 'Exams')

@section('content')
<style>
    .section { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .btn { padding: 8px 14px; background: #4CAF50; color: #fff; text-decoration: none; border-radius: 4px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f8f8; }
</style>

<div class="section">
    <div class="header">
        <h2>Exam List</h2>
        <a href="{{ route('teacher.exams.create') }}" class="btn">+ Create Exam</a>
    </div>

    @if(session('success'))
        <div style="padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 16px;">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Max Score</th>
                <th>Results</th>
                <th>Average</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($exams as $exam)
                <tr>
                    <td>{{ $exam->title }}</td>
                    <td>{{ $exam->exam_date->format('M d, Y') }}</td>
                    <td>{{ $exam->max_score }}</td>
                    <td>{{ $exam->results_count }}</td>
                    <td>{{ $exam->results_avg_score ? number_format($exam->results_avg_score, 2) : '-' }}</td>
                    <td><a href="{{ route('teacher.exams.show', $exam) }}" style="color: #2196F3; text-decoration: none;">View</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No exams created yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
