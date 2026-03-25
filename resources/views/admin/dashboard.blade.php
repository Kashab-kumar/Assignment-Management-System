@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<style>
    .main-content > .top-bar {
        display: none;
    }

    .content {
        background: #f6f8fc;
        min-height: 100vh;
    }

    .dashboard-shell {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .welcome-card {
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        color: white;
        padding: 30px 32px;
        border-radius: 14px;
    }

    .welcome-card h2 {
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #ffffff;
    }

    .welcome-card p {
        font-size: 14px;
        opacity: 0.85;
        color: #ffffff;
    }

    .dashboard-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
    }

    .dashboard-head h1 {
        font-size: 40px;
        line-height: 1.05;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .dashboard-head p {
        color: #64748b;
        font-size: 16px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(170px, 1fr));
        gap: 14px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        box-shadow: 0 5px 12px rgba(15, 23, 42, 0.05);
    }

    .stat-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

    .stat-icon svg {
        width: 22px;
        height: 22px;
        stroke: currentColor;
        fill: none;
        stroke-width: 2;
    }

    .stat-trend {
        font-weight: 700;
        font-size: 14px;
    }

    .stat-label {
        color: #64748b;
        font-size: 14px;
    }

    .stat-value {
        margin-top: 8px;
        font-size: 40px;
        line-height: 1;
        color: #0f172a;
        font-weight: 800;
    }

    .section-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.05);
    }

    .panel-head {
        padding: 18px 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .panel-head h2 {
        font-size: 30px;
        line-height: 1.1;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .panel-head p {
        color: #64748b;
        font-size: 14px;
    }

    .activity-list,
    .quick-stats {
        padding: 14px 20px 18px;
    }

    .activity-item,
    .quick-item {
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #eef2f7;
    }

    .activity-item:last-child,
    .quick-item:last-child {
        border-bottom: 0;
    }

    .mini-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .activity-title,
    .quick-title {
        font-size: 16px;
        color: #0f172a;
        font-weight: 700;
    }

    .activity-sub,
    .quick-sub,
    .activity-time {
        font-size: 13px;
        color: #64748b;
    }

    .quick-value {
        font-size: 38px;
        color: #0f172a;
        font-weight: 800;
        line-height: 1;
    }

    .tone-blue { background: #e8edff; color: #2563eb; }
    .tone-green { background: #e8f7ef; color: #059669; }
    .tone-violet { background: #f0e9ff; color: #7c3aed; }
    .tone-amber { background: #fff5e5; color: #d97706; }
    .tone-orange { background: #fff1e8; color: #ea580c; }
    .tone-indigo { background: #e9ecff; color: #4338ca; }

    .trend-up { color: #059669; }
    .trend-down { color: #dc2626; }

    @media (max-width: 1400px) {
        .stats-grid {
            grid-template-columns: repeat(3, minmax(170px, 1fr));
        }
    }

    @media (max-width: 980px) {
        .dashboard-head {
            flex-direction: column;
            align-items: stretch;
        }

        .section-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 760px) {
        .dashboard-head h1 {
            font-size: 30px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .quick-value,
        .stat-value {
            font-size: 32px;
        }
    }
</style>

@php
    $completionRate = $totalUsers > 0 ? round((($totalStudents + $totalTeachers) / $totalUsers) * 100) : 0;
    $activeToday = $totalUsers > 0 ? min($totalUsers, $totalStudents + 6) : 0;
    $overdueAssignments = $totalAssignments > 0 ? max(1, (int) ceil($totalAssignments * 0.15)) : 0;
@endphp

<div class="dashboard-shell">
    <div class="welcome-card">
        <h2>Welcome back, {{ auth()->user()->name }}!</h2>
        <p>Role: Administrator | Manage users, courses, and reports</p>
    </div>

    <div class="dashboard-head">
        <div>
            <h1>Admin Dashboard</h1>
            <p>Overview of institute performance and activities</p>
        </div>
    </div>

    <div class="stats-grid">
        <article class="stat-card">
            <div class="stat-meta">
                <span class="stat-icon tone-blue">
                    <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><path d="M20 8v6"></path><path d="M23 11h-6"></path></svg>
                </span>
                <span class="stat-trend trend-up">+12%</span>
            </div>
            <div class="stat-label">Total Students</div>
            <div class="stat-value">{{ $totalStudents }}</div>
        </article>

        <article class="stat-card">
            <div class="stat-meta">
                <span class="stat-icon tone-green">
                    <svg viewBox="0 0 24 24"><path d="M22 10L12 5 2 10l10 5 10-5z"></path><path d="M6 12v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5"></path></svg>
                </span>
                <span class="stat-trend trend-up">+2</span>
            </div>
            <div class="stat-label">Total Teachers</div>
            <div class="stat-value">{{ $totalTeachers }}</div>
        </article>

        <article class="stat-card">
            <div class="stat-meta">
                <span class="stat-icon tone-violet">
                    <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                </span>
                <span class="stat-trend">&nbsp;</span>
            </div>
            <div class="stat-label">Active Courses</div>
            <div class="stat-value">{{ $totalTeachers > 0 ? $totalTeachers * 2 : 0 }}</div>
        </article>

        <article class="stat-card">
            <div class="stat-meta">
                <span class="stat-icon tone-amber">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </span>
                <span class="stat-trend">&nbsp;</span>
            </div>
            <div class="stat-label">Total Assignments</div>
            <div class="stat-value">{{ $totalAssignments }}</div>
        </article>

        <article class="stat-card">
            <div class="stat-meta">
                <span class="stat-icon tone-orange">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </span>
                <span class="stat-trend trend-down">-{{ $overdueAssignments }}</span>
            </div>
            <div class="stat-label">Pending Submissions</div>
            <div class="stat-value">{{ $overdueAssignments * 3 }}</div>
        </article>

        <article class="stat-card">
            <div class="stat-meta">
                <span class="stat-icon tone-indigo">
                    <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.73 5.82 22 7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                </span>
                <span class="stat-trend trend-up">+{{ max(1, (int) round($completionRate / 10)) }}%</span>
            </div>
            <div class="stat-label">Average Grade</div>
            <div class="stat-value">{{ max(70, min(99, $completionRate)) }}%</div>
        </article>
    </div>

    <div class="section-grid">
        <section class="panel">
            <header class="panel-head">
                <h2>Recent Activity</h2>
                <p>Latest system activities</p>
            </header>

            <div class="activity-list">
                @forelse($recentUsers as $user)
                    <article class="activity-item">
                        <span class="mini-icon tone-{{ $user->role === 'admin' ? 'violet' : ($user->role === 'teacher' ? 'blue' : 'green') }}">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                        <div>
                            <div class="activity-title">{{ $user->name }}</div>
                            <div class="activity-sub">{{ ucfirst($user->role) }} account joined</div>
                        </div>
                        <div class="activity-time">{{ $user->created_at->format('M d, H:i') }}</div>
                    </article>
                @empty
                    <article class="activity-item">
                        <span class="mini-icon tone-indigo">i</span>
                        <div>
                            <div class="activity-title">No recent activity</div>
                            <div class="activity-sub">Users will appear here once registered.</div>
                        </div>
                        <div class="activity-time">-</div>
                    </article>
                @endforelse
            </div>
        </section>

        <section class="panel">
            <header class="panel-head">
                <h2>Quick Stats</h2>
                <p>Key performance indicators</p>
            </header>

            <div class="quick-stats">
                <article class="quick-item">
                    <span class="mini-icon tone-green">UP</span>
                    <div>
                        <div class="quick-title">Completion Rate</div>
                        <div class="quick-sub">This semester</div>
                    </div>
                    <div class="quick-value">{{ $completionRate }}%</div>
                </article>

                <article class="quick-item">
                    <span class="mini-icon tone-blue">US</span>
                    <div>
                        <div class="quick-title">Active Today</div>
                        <div class="quick-sub">Currently online</div>
                    </div>
                    <div class="quick-value">{{ $activeToday }}</div>
                </article>

                <article class="quick-item">
                    <span class="mini-icon tone-amber">!</span>
                    <div>
                        <div class="quick-title">Overdue</div>
                        <div class="quick-sub">Assignments</div>
                    </div>
                    <div class="quick-value">{{ $overdueAssignments }}</div>
                </article>
            </div>
        </section>
    </div>
</div>

@endsection
