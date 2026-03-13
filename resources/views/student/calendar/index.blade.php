@extends('layouts.student')

@section('title', 'Calendar')
@section('page-title', 'My Calendar')

@section('content')
<style>
    .section {
        background: #1e2235;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 18px;
        border: 1px solid rgba(255,255,255,0.06);
    }
    .section h2 { color: #f1f5f9; }
    .stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; margin-bottom: 16px; }
    .stat { border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 14px; background: rgba(0,0,0,0.14); }
    .stat h4 { margin: 0 0 6px 0; color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 0.04em; }
    .stat p { margin: 0; font-size: 24px; font-weight: bold; }
    .filters { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .filters label { color: #94a3b8; }
    .filters input {
        padding: 8px 10px;
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 8px;
        background: rgba(0,0,0,0.2);
        color: #e2e8f0;
    }
    .filters button {
        padding: 8px 12px;
        border: 0;
        border-radius: 8px;
        background: #7c3aed;
        color: #fff;
        cursor: pointer;
        font-weight: 600;
    }
    .filters button:hover { background: #6d28d9; }

    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.06); }
    th {
        background: rgba(0,0,0,0.12);
        color: #94a3b8;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    td { color: #cbd5e1; }
    tr:last-child td { border-bottom: none; }

    .badge { padding: 4px 8px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .badge-assignment { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.25); }
    .badge-quiz { background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.25); }
    .badge-exam { background: rgba(124,58,237,0.18); color: #a78bfa; border: 1px solid rgba(124,58,237,0.32); }

    .status-done { color: #10b981; font-weight: 700; }
    .status-upcoming { color: #60a5fa; font-weight: 700; }
    .status-overdue { color: #ef4444; font-weight: 700; }

    .empty { color: #64748b; }

    @media (max-width: 900px) {
        .stats { grid-template-columns: 1fr; }
    }
</style>

<div class="section">
    <h2 style="margin-bottom: 12px;">{{ $monthStart->format('F Y') }} Overview</h2>
    <div class="stats">
        <div class="stat">
            <h4>Completed</h4>
            <p style="color:#10b981;">{{ $counts['done'] }}</p>
        </div>
        <div class="stat">
            <h4>Upcoming</h4>
            <p style="color:#60a5fa;">{{ $counts['upcoming'] }}</p>
        </div>
        <div class="stat">
            <h4>Overdue</h4>
            <p style="color:#ef4444;">{{ $counts['overdue'] }}</p>
        </div>
    </div>

    <form method="GET" class="filters">
        <label for="month" style="font-weight:bold;">Month:</label>
        <input type="month" id="month" name="month" value="{{ $selectedMonth }}">
        <button type="submit">Apply</button>
    </form>
</div>

<div class="section">
    <h2 style="margin-bottom: 12px;">Assignments & Quiz Timeline</h2>

    @if($events->isEmpty())
        <p class="empty">No assignment or quiz events found for this month.</p>
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
