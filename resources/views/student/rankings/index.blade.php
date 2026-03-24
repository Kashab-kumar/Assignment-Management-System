@extends('layouts.student')

@section('title', 'Class Rankings')
@section('page-title', 'Class Rankings')

@section('content')
<style>
    .card {
        background: #ffffff;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid rgba(0,0,0,0.06);
        margin-bottom: 16px;
    }
    .card h2 { color: #1f2937; }
    .card p { color: #475569; margin-bottom: 6px; }
    .card p strong { color: #64748b; }

    table { width: 100%; border-collapse: collapse; }
    th, td {
        padding: 11px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
        text-align: left;
    }
    th {
        background: rgba(0,0,0,0.12);
        color: #000000;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    td { color: #000000; }
    tr:last-child td { border-bottom: none; }
    .highlight-row { background: rgba(124,58,237,0.14); }
    .empty { color: #64748b; text-align: center; }
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
                <tr class="{{ $row['student']->id === $student->id ? 'highlight-row' : '' }}">
                    <td>#{{ $index + 1 }}</td>
                    <td>{{ $row['student']->name }}</td>
                    <td>{{ $row['student']->student_id }}</td>
                    <td>{{ number_format($row['average'], 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="empty">Ranking data is not available yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
