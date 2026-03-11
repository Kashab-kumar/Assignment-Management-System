@extends('layouts.student')

@section('title', 'Class Rankings')
@section('page-title', 'Class Rankings')

@section('content')
<style>
    .card { background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1); margin-bottom:16px; }
    table { width:100%; border-collapse: collapse; }
    th, td { padding: 10px; border-bottom:1px solid #ddd; text-align:left; }
    th { background:#f8f8f8; }
</style>

<div class="card">
    <h2 style="margin-bottom:8px;">Your Position</h2>
    <p><strong>{{ $groupLabel }}:</strong> {{ $groupValue }}</p>
    <p><strong>Your Rank:</strong> {{ $myRank ? '#' . $myRank : 'Not available' }}</p>
</div>

<div class="card">
    <h2 style="margin-bottom:12px;">Ranking Table</h2>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Student</th>
                <th>Student ID</th>
                <th>Average Score</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rankings as $index => $row)
                <tr style="{{ $row['student']->id === $student->id ? 'background:#e8f5e9;' : '' }}">
                    <td>#{{ $index + 1 }}</td>
                    <td>{{ $row['student']->name }}</td>
                    <td>{{ $row['student']->student_id }}</td>
                    <td>{{ number_format($row['average'], 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Ranking data is not available yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
