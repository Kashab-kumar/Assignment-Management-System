@extends('layouts.teacher')

@section('title', 'Grades')
@section('page-title', 'Grades')

@section('content')
<style>
    /* Dark Mode Support */
    :root {
        --bg-primary: #ffffff;
        --bg-secondary: #f8f9fa;
        --text-primary: #1f2937;
        --text-secondary: #6b7280;
        --border-color: #e5e7eb;
        --shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    [data-theme="dark"] {
        --bg-primary: #1f2937;
        --bg-secondary: #111827;
        --text-primary: #f3f4f6;
        --text-secondary: #d1d5db;
        --border-color: #374151;
        --shadow-light: 0 1px 3px rgba(0, 0, 0, 0.3);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    body {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Header Section */
    .grades-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .grades-title-section h1 {
        font-size: 32px;
        font-weight: 700;
        margin: 0;
        color: var(--text-primary);
    }

    .grades-title-section p {
        font-size: 14px;
        color: var(--text-secondary);
        margin: 4px 0 0 0;
    }

    /* Theme Toggle */
    .theme-toggle {
        display: flex;
        gap: 8px;
        background: var(--bg-primary);
        padding: 4px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        cursor: pointer;
    }

    .theme-toggle button {
        background: transparent;
        border: none;
        padding: 8px 12px;
        cursor: pointer;
        color: var(--text-secondary);
        transition: all 0.3s ease;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 14px;
    }

    .theme-toggle button.active {
        background: var(--bg-secondary);
        color: var(--text-primary);
    }

    .theme-toggle svg {
        width: 18px;
        height: 18px;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: var(--bg-primary);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-light);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .stat-card-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .stat-card-label {
        font-size: 13px;
        color: var(--text-secondary);
        text-transform: capitalize;
    }

    /* Filter Buttons */
    .filter-buttons {
        display: flex;
        gap: 12px;
        margin-bottom: 32px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 8px 16px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        background: var(--bg-primary);
        color: var(--text-primary);
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .filter-btn.active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-color: transparent;
    }

    .filter-btn:hover:not(.active) {
        background: var(--bg-secondary);
    }

    /* Table Container */
    .table-container {
        background: var(--bg-primary);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        box-shadow: var(--shadow-light);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: var(--bg-secondary);
    }

    th {
        padding: 16px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border-color);
    }

    td {
        padding: 16px;
        border-bottom: 1px solid var(--border-color);
        font-size: 14px;
        color: var(--text-primary);
    }

    tbody tr {
        transition: background-color 0.2s ease;
    }

    tbody tr:hover {
        background-color: var(--bg-secondary);
    }

    tbody tr:last-child td {
        border-bottom: none;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }

    /* Grade Colors */
    .grade-excellent {
        color: #059669;
        font-weight: 600;
    }

    .grade-good {
        color: #0891b2;
        font-weight: 600;
    }

    .grade-average {
        color: #d97706;
        font-weight: 600;
    }

    .grade-poor {
        color: #dc2626;
        font-weight: 600;
    }

    .empty-state {
        padding: 48px 20px;
        text-align: center;
        color: var(--text-secondary);
    }

    .empty-state svg {
        width: 48px;
        height: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .grades-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        table {
            font-size: 12px;
        }

        th, td {
            padding: 12px 8px;
        }
    }
</style>

<div class="grades-header">
    <div class="grades-title-section">
        <h1>Grades</h1>
        <p>Student grade overview and performance tracking</p>
    </div>
    <div class="theme-toggle" id="themeToggle">
        <button class="light-btn active" title="Light Mode">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="5"></circle>
                <line x1="12" y1="1" x2="12" y2="3"></line>
                <line x1="12" y1="21" x2="12" y2="23"></line>
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                <line x1="1" y1="12" x2="3" y2="12"></line>
                <line x1="21" y1="12" x2="23" y2="12"></line>
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
            </svg>
        </button>
        <button class="dark-btn" title="Dark Mode">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
        </button>
    </div>
</div>

@php
    $totalStudents = $students->count();
    $activeStudents = $students->filter(fn($s) => $s->user?->status === 'active')->count();
    $suspendedStudents = $students->filter(fn($s) => $s->user?->status === 'suspended')->count();
    $avgGPA = $students->map(function($student) {
        $assignmentAvg = $student->submissions->where('status', 'graded')->avg('score');
        $examAvg = $student->examResults->avg('score');
        return collect([$assignmentAvg, $examAvg])->filter(fn($v) => $v !== null)->avg();
    })->filter(fn($v) => $v !== null)->avg();
@endphp

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-value">{{ $totalStudents }}</div>
        <div class="stat-card-label">Total Students</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-value" style="color: #10b981;">{{ $activeStudents }}</div>
        <div class="stat-card-label">Active Students</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-value" style="color: #ef4444;">{{ $suspendedStudents }}</div>
        <div class="stat-card-label">Suspended</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-value" style="color: #8b5cf6;">{{ number_format($avgGPA ?? 0, 2) }}</div>
        <div class="stat-card-label">Avg GPA</div>
    </div>
</div>

<!-- Filter Buttons -->
<div class="filter-buttons">
    <button class="filter-btn active" onclick="filterGrades('all')">All Status</button>
    <button class="filter-btn" onclick="filterGrades('active')">Active</button>
    <button class="filter-btn" onclick="filterGrades('suspended')">Suspended</button>
</div>

<!-- Table -->
<div class="table-container">
    @if($students->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Student ID</th>
                    <th>Course</th>
                    <th>Assignment Avg</th>
                    <th>Exam Avg</th>
                    <th>Overall Avg</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="gradesTableBody">
                @forelse($students as $student)
                    @php
                        $assignmentAvg = $student->submissions->where('status', 'graded')->avg('score');
                        $examAvg = $student->examResults->avg('score');
                        $overallAvg = collect([$assignmentAvg, $examAvg])->filter(fn($v) => $v !== null)->avg();
                        $status = $student->user?->status ?? 'unknown';

                        $gradeClass = 'grade-poor';
                        if ($overallAvg >= 80) $gradeClass = 'grade-excellent';
                        elseif ($overallAvg >= 70) $gradeClass = 'grade-good';
                        elseif ($overallAvg >= 60) $gradeClass = 'grade-average';
                    @endphp
                    <tr data-status="{{ $status }}">
                        <td>
                            <div style="font-weight: 600; color: var(--text-primary);">{{ $student->user?->name ?? $student->name }}</div>
                            <div style="font-size: 12px; color: var(--text-secondary);">{{ $student->user?->email ?? '-' }}</div>
                        </td>
                        <td>{{ $student->student_id ?? '-' }}</td>
                        <td>{{ $student->course?->name ?? '-' }}</td>
                        <td><span class="grade-average">{{ $assignmentAvg !== null ? number_format($assignmentAvg, 2) : '-' }}</span></td>
                        <td><span class="grade-average">{{ $examAvg !== null ? number_format($examAvg, 2) : '-' }}</span></td>
                        <td><span class="{{ $gradeClass }}">{{ $overallAvg !== null ? number_format($overallAvg, 2) : '-' }}</span></td>
                        <td>
                            <span class="status-badge" style="background: {{ $status === 'active' ? '#d1fae5' : '#fee2e2' }}; color: {{ $status === 'active' ? '#059669' : '#dc2626' }};">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
            </svg>
            <p style="margin: 0; font-size: 16px;">No grade data available</p>
        </div>
    @endif
</div>

<script>
    // Theme Toggle Functionality
    const themeToggle = document.getElementById('themeToggle');
    const lightBtn = themeToggle.querySelector('.light-btn');
    const darkBtn = themeToggle.querySelector('.dark-btn');
    const html = document.documentElement;

    // Load theme preference from localStorage
    const savedTheme = localStorage.getItem('theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    updateThemeButtons(savedTheme);

    lightBtn.addEventListener('click', () => {
        setTheme('light');
    });

    darkBtn.addEventListener('click', () => {
        setTheme('dark');
    });

    function setTheme(theme) {
        html.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        updateThemeButtons(theme);
    }

    function updateThemeButtons(theme) {
        if (theme === 'light') {
            lightBtn.classList.add('active');
            darkBtn.classList.remove('active');
        } else {
            darkBtn.classList.add('active');
            lightBtn.classList.remove('active');
        }
    }

    // Filter Grades
    function filterGrades(status) {
        const rows = document.querySelectorAll('#gradesTableBody tr');
        const buttons = document.querySelectorAll('.filter-btn');

        // Update active button
        buttons.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');

        // Filter rows
        rows.forEach(row => {
            if (status === 'all') {
                row.style.display = '';
            } else {
                row.style.display = row.getAttribute('data-status') === status ? '' : 'none';
            }
        });
    }
</script>
@endsection
