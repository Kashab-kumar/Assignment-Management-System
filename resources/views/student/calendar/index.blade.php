@extends('layouts.student')

@section('title', 'Calendar')
@section('page-title', 'My Calendar')

@section('content')
<style>
    .section { background: white; border-radius: 8px; padding: 20px; margin-bottom: 18px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; margin-bottom: 16px; }
    .stat { border: 1px solid #eee; border-radius: 8px; padding: 14px; background: #fafafa; }
    .stat h4 { margin: 0 0 6px 0; color: #666; font-size: 12px; text-transform: uppercase; }
    .stat p { margin: 0; font-size: 24px; font-weight: bold; }
    .filters { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .filters input { padding: 8px 10px; border: 1px solid #ddd; border-radius: 4px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
    th { background: #f8f8f8; }
    .badge { padding: 4px 8px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .badge-assignment { background: #eaf2ff; color: #1e5fd6; }
    .badge-quiz { background: #fff3e0; color: #f57c00; }
    .badge-exam { background: #ede7f6; color: #5e35b1; }
    .status-done { color: #2e7d32; font-weight: 700; }
    .status-upcoming { color: #1565c0; font-weight: 700; }
    .status-overdue { color: #c62828; font-weight: 700; }

    @media (max-width: 900px) {
        .stats { grid-template-columns: 1fr; }
    }
</style>

<div class="section">
    <h2 style="margin-bottom: 12px;">{{ $monthStart->format('F Y') }} Overview</h2>
    <div class="stats">
        <div class="stat">
            <h4>Completed</h4>
            <p style="color:#2e7d32;">{{ $counts['done'] }}</p>
        </div>
        <div class="stat">
            <h4>Upcoming</h4>
            <p style="color:#1565c0;">{{ $counts['upcoming'] }}</p>
        </div>
        <div class="stat">
            <h4>Overdue</h4>
            <p style="color:#c62828;">{{ $counts['overdue'] }}</p>
        </div>
    </div>

    <form method="GET" class="filters">
        <label for="month" style="font-weight:bold;">Month:</label>
        <input type="month" id="month" name="month" value="{{ $selectedMonth }}">
        <button type="submit" style="padding:8px 12px; border:0; border-radius:4px; background:#27ae60; color:#fff; cursor:pointer;">Apply</button>
    </form>
</div>

<div class="section">
    <h2 style="margin-bottom: 12px;">Assignments & Quiz Timeline</h2>

    @if($events->isEmpty())
        <p style="color:#666;">No assignment or quiz events found for this month.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Item</th>
                    <th>Course</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr>
                        <td>{{ $event['date']->format('M d, Y') }}</td>
                        <td>{{ $event['title'] }}</td>
                        <td>{{ $event['course'] ?? '-' }}</td>
                        <td><span class="badge badge-{{ $event['type'] }}">{{ ucfirst($event['type']) }}</span></td>
                        <td class="status-{{ $event['status'] }}">{{ ucfirst($event['status']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
