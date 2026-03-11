@extends('layouts.student')

@section('title', 'Exams')
@section('page-title', 'Exams')

@section('content')
<style>
    .section { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #f8f8f8; }
    .badge-pass { background: #4caf50; color: #fff; padding: 4px 8px; border-radius: 4px; }
    .badge-fail { background: #e74c3c; color: #fff; padding: 4px 8px; border-radius: 4px; }
</style>

<div class="section">
    <h2 style="margin-bottom: 16px;">Upcoming And Past Exams</h2>
    <table>
        <thead>
            <tr>
                <th>Exam</th>
                <th>Date</th>
                <th>Max Score</th>
                <th>Your Score</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($exams as $exam)
                @php($result = $exam->results->first())
                <tr>
                    <td>{{ $exam->title }}</td>
                    <td>{{ $exam->exam_date->format('M d, Y') }}</td>
                    <td>{{ $exam->max_score }}</td>
                    <td>{{ $result ? $result->score : '-' }}</td>
                    <td>
                        @if($result)
                            @if($result->score >= ($exam->max_score * 0.4))
                                <span class="badge-pass">Pass</span>
                            @else
                                <span class="badge-fail">Needs Improvement</span>
                            @endif
                        @else
                            <span style="color:#666;">Pending Result</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No exams found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
