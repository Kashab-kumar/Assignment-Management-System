<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teacher Dashboard') - Assignment Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #ffffff; color: #1f2937; }

        .layout { display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: 260px; background: #ffffff; color: #1f2937; position: fixed; height: 100vh; overflow-y: auto; border-right: 1px solid rgba(0,0,0,0.06); display: flex; flex-direction: column; }
        .sidebar-header { padding: 18px 14px 14px; border-bottom: 1px solid rgba(0,0,0,0.07); }
        .sidebar-brand-row { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
        .sidebar-brand-icon { width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, #5b4ce6, #8a2be2); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .sidebar-brand-icon svg { width: 20px; height: 20px; fill: #ffffff; }
        .sidebar-brand-name { font-size: 31px; font-weight: 700; color: #111827; line-height: 1; }
        .sidebar-brand-subtitle { font-size: 12px; color: #64748b; margin-top: 3px; }
        .sidebar-portal-badge { display: block; width: 100%; text-align: center; background: linear-gradient(90deg, #5b4ce6, #8a2be2); color: #ffffff; border-radius: 999px; padding: 7px 10px; font-size: 12px; font-weight: 700; }

        .sidebar-menu { padding: 12px 0; flex: 1; }
        .menu-item { display: flex; align-items: center; padding: 11px 20px; color: #64748b; text-decoration: none; transition: all 0.2s; border-radius: 6px; margin: 2px 10px; font-size: 14px; }
        .menu-item:hover { background: rgba(124,58,237,0.12); color: #1f2937; }
        .menu-item.active { background: #0f172a; color: #ffffff; border-left: none; padding-left: 20px; }
        .menu-item svg { width: 18px; height: 18px; margin-right: 12px; fill: currentColor; flex-shrink: 0; }

        .menu-section { display: none; }

        .account-section { padding: 14px; border-top: 1px solid rgba(255,255,255,0.07); }
        .account-link { display: flex; align-items: center; gap: 10px; text-decoration: none; color: #1f2937; padding: 8px; border-radius: 8px; transition: background 0.2s; }
        .account-link:hover { background: rgba(255,255,255,0.06); }
        .account-avatar { width: 40px; height: 40px; border-radius: 50%; background: #7c3aed; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; overflow: hidden; border: 2px solid rgba(124,58,237,0.5); flex-shrink: 0; }
        .account-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .account-meta { min-width: 0; }
        .account-name { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #1f2937; }
        .account-role { font-size: 11px; color: #64748b; margin-top: 1px; }
        .account-settings-link { display: flex; align-items: center; gap: 4px; font-size: 11px; color: #7c3aed; margin-top: 1px; }
        .account-settings-link svg { width: 12px; height: 12px; fill: currentColor; }

        /* Main Content */
        .main-content { margin-left: 260px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }
        .top-bar { display: none; }
        .top-bar h1 { font-size: 22px; font-weight: 700; color: #1f2937; }
        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: #7c3aed; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; overflow: hidden; border: 2px solid rgba(124,58,237,0.5); }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .user-text { text-align: right; }
        .user-text .user-name { font-size: 13px; font-weight: 600; color: #1f2937; }
        .user-text .user-role { font-size: 11px; color: #64748b; }
        .user-text a { font-size: 11px; color: #7c3aed; text-decoration: none; }
        .user-text a:hover { text-decoration: underline; }

        .content { padding: 28px 30px; flex: 1; }

        /* Global Teacher Content Theme Overrides */
        .content h2,
        .content h3,
        .content h4,
        .content strong,
        .content label {
            color: #000000 !important;
        }

        .content p,
        .content span,
        .content li,
        .content td,
        .content summary,
        .content details,
        .content small {
            color: #000000;
        }

        .content [class$='-container'],
        .content .section,
        .content .card,
        .content [class$='-card'],
        .content .stat-card,
        .content .report-card,
        .content .overview-card,
        .content .filter-card,
        .content .form-card,
        .content .table-card {
            background: #ffffff !important;
            border: 1px solid rgba(0,0,0,0.06) !important;
            border-radius: 12px !important;
            box-shadow: none !important;
        }}

        .content [class$='-header'],
        .content .section-header {
            border-bottom: 1px solid rgba(255,255,255,0.08) !important;
        }

        .content .filters,
        .content .filter-form,
        .content .filter-group,
        .content .export-options,
        .content .info-box,
        .content .link-box,
        .content .role-option,
        .content .tree,
        .content .tree details,
        .content .course-list,
        .content .info-card {
            background: rgba(0,0,0,0.14) !important;
            border-color: rgba(255,255,255,0.1) !important;
            color: #000000 !important;
        }

        .content table,
        .content .report-table,
        .content .reports-table {
            background: transparent !important;
            color: #000000 !important;
        }

        .content th {
            background: rgba(0,0,0,0.12) !important;
            color: #000000 !important;
            border-bottom: 1px solid rgba(255,255,255,0.08) !important;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .content td {
            border-bottom: 1px solid rgba(255,255,255,0.06) !important;
            color: #000000 !important;
        }

        .content tr:hover {
            background: rgba(124,58,237,0.08) !important;
        }

        .content input,
        .content select,
        .content textarea {
            background: rgba(0,0,0,0.2) !important;
            border: 1px solid rgba(255,255,255,0.12) !important;
            color: #1f2937 !important;
            border-radius: 8px !important;
        }

        .content input::placeholder,
        .content textarea::placeholder {
            color: #64748b !important;
        }

        .content input:focus,
        .content select:focus,
        .content textarea:focus {
            outline: none;
            border-color: #7c3aed !important;
            box-shadow: 0 0 0 2px rgba(124,58,237,0.2) !important;
        }

        .content .btn,
        .content button {
            border-radius: 8px !important;
            border: none;
        }

        .content .btn-add,
        .content .btn-primary,
        .content .btn-create,
        .content .btn-submit,
        .content button[type='submit'] {
            background: #7c3aed !important;
            color: #ffffff !important;
        }

        .content .btn-add:hover,
        .content .btn-primary:hover,
        .content .btn-create:hover,
        .content .btn-submit:hover,
        .content button[type='submit']:hover {
            background: #6d28d9 !important;
        }

        .content .btn-view { background: #10b981 !important; color: #ffffff !important; }
        .content .btn-edit { background: #3b82f6 !important; color: #ffffff !important; }
        .content .btn-delete,
        .content .btn-danger { background: #ef4444 !important; color: #ffffff !important; }

        .content .status-active { color: #10b981 !important; }
        .content .status-inactive { color: #ef4444 !important; }

        .content [style*='background: white'],
        .content [style*='background:#fff'],
        .content [style*='background: #fff'],
        .content [style*='background: #f8'],
        .content [style*='background:#f8'],
        .content [style*='background: #f0'],
        .content [style*='background:#f0'] {
            background: #ffffff !important;
        }

        .content [style*='color: #333'],
        .content [style*='color:#333'] {
            color: #1f2937 !important;
        }

        .content [style*='color: #666'],
        .content [style*='color:#666'],
        .content [style*='color: #555'],
        .content [style*='color:#555'],
        .content [style*='color: #999'],
        .content [style*='color:#999'] {
            color: #64748b !important;
        }

        .content [style*='fill: #ddd'],
        .content [style*='fill:#ddd'] {
            fill: #334155 !important;
        }

        .content [style*='border: 1px solid #ddd'],
        .content [style*='border:1px solid #ddd'],
        .content [style*='border: 2px solid #ddd'],
        .content [style*='border:1px solid #eee'],
        .content [style*='border: 1px solid #eee'] {
            border-color: rgba(255,255,255,0.14) !important;
        }

        .content .pagination,
        .content nav[role='navigation'] {
            color: #000000;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { width: 68px; }
            .sidebar-brand-copy, .sidebar-portal-badge, .menu-item span, .menu-section, .account-meta { display: none; }
            .sidebar-header { padding: 12px 10px; }
            .sidebar-brand-row { justify-content: center; margin-bottom: 0; }
            .menu-item { margin: 2px 6px; padding: 11px; justify-content: center; }
            .menu-item svg { margin-right: 0; }
            .menu-item.active { padding-left: 11px; border-left-width: 0; }
            .main-content { margin-left: 68px; }
            .account-link { justify-content: center; padding: 8px 4px; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand-row">
                    <div class="sidebar-brand-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path d="M12 3 1 9l11 6 9-4.91V17h2V9L12 3zm-7 9.18V17l7 4 7-4v-4.82L12 16l-7-3.82z"/></svg>
                    </div>
                    <div class="sidebar-brand-copy">
                        <div class="sidebar-brand-name">Institute</div>
                        <div class="sidebar-brand-subtitle">LMS Platform</div>
                    </div>
                </div>
                <div class="sidebar-portal-badge">Teacher Portal</div>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-section">Main</div>
                <a href="{{ route('teacher.dashboard') }}" class="menu-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                    <span>Dashboard</span>
                </a>

                <div class="menu-section">Teaching</div>
                <a href="{{ route('teacher.courses.index') }}" class="menu-item {{ request()->routeIs('teacher.courses.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 12.27L4.77 12 12 8.73 19.23 12 12 15.27zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
                    <span>Courses</span>
                </a>
                <a href="{{ route('teacher.calendar') }}" class="menu-item {{ request()->routeIs('teacher.calendar*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5C3.89 4 3 4.9 3 6v14c0 1.1.89 2 2 2h14a2 2 0 0 0 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z"/></svg>
                        <span>Recent</span>
                </a>
                <a href="{{ route('teacher.assignments.index') }}" class="menu-item {{ request()->routeIs('teacher.assignments.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                    <span>Assignments</span>
                </a>

                <a href="{{ route('teacher.submissions.index') }}" class="menu-item {{ request()->routeIs('teacher.submissions.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                    <span>Submissions</span>
                </a>
                <a href="{{ route('teacher.exams.index') }}" class="menu-item {{ request()->routeIs('teacher.exams.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg>
                    <span>Exams</span>
                </a>

                <div class="menu-section">Students</div>
                <a href="{{ route('teacher.students.index') }}" class="menu-item {{ request()->routeIs('teacher.students.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                    <span>My Students</span>
                </a>
                <a href="{{ route('teacher.grades.index') }}" class="menu-item {{ request()->routeIs('teacher.grades.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                    <span>Grades</span>
                </a>
                <a href="{{ route('teacher.reports.index') }}" class="menu-item {{ request()->routeIs('teacher.reports.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
                    <span>Reports</span>
                </a>
            </nav>

            <div class="account-section">
                @php($sidebarAvatarUrl = auth()->user()->avatar_path ? url('/storage/' . auth()->user()->avatar_path) : null)
                <a href="{{ route('teacher.settings') }}" class="account-link" title="Settings">
                    <div class="account-avatar">
                        @if($sidebarAvatarUrl)
                            <img src="{{ $sidebarAvatarUrl }}" alt="{{ auth()->user()->name }} Avatar">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="account-meta">
                        <div class="account-name">{{ auth()->user()->name }}</div>
                        <div class="account-role">Teacher</div>
                        <div class="account-settings-link">
                            <svg viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.31.06-.63.06-.94s-.02-.63-.06-.94l2.03-1.58a.5.5 0 0 0 .12-.64l-1.92-3.32a.5.5 0 0 0-.6-.22l-2.39.96a7.03 7.03 0 0 0-1.63-.94l-.36-2.54a.5.5 0 0 0-.5-.42h-3.84a.5.5 0 0 0-.5.42l-.36 2.54c-.58.23-1.12.54-1.63.94l-2.39-.96a.5.5 0 0 0-.6.22L2.71 8.84a.5.5 0 0 0 .12.64l2.03 1.58c-.04.31-.06.63-.06.94s.02.63.06.94l-2.03 1.58a.5.5 0 0 0-.12.64l1.92 3.32c.13.22.39.31.6.22l2.39-.96c.5.4 1.05.72 1.63.94l.36 2.54c.04.24.25.42.5.42h3.84c.25 0 .46-.18.5-.42l.36-2.54c.58-.23 1.12-.54 1.63-.94l2.39.96c.22.09.47 0 .6-.22l1.92-3.32a.5.5 0 0 0-.12-.64l-2.03-1.58zM12 15.5A3.5 3.5 0 1 1 12 8a3.5 3.5 0 0 1 0 7.5z"/></svg>
                            <span>Settings</span>
                        </div>
                    </div>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <div class="user-info">
                    <div class="user-text">
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">Teacher</div>
                        <a href="{{ route('teacher.settings') }}">Settings</a>
                    </div>
                    @php($avatarUrl = auth()->user()->avatar_path ? url('/storage/' . auth()->user()->avatar_path) : null)
                    <div class="user-avatar">
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ auth()->user()->name }} Avatar">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="content">
                @if(session('role_notice'))
                    <div style="margin-bottom: 14px; padding: 10px 12px; border: 1px solid rgba(245,158,11,0.35); background: rgba(245,158,11,0.12); color: #fbbf24; border-radius: 8px; font-size: 13px;">
                        {{ session('role_notice') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
