<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Assignment Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #ffffff; color: #1f2937; }

        .layout { display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: 260px; background: #ffffff; color: #1f2937; position: fixed; height: 100vh; overflow-y: auto; border-right: 1px solid rgba(0,0,0,0.06); display: flex; flex-direction: column; }
        .sidebar-header { padding: 22px 20px 18px; border-bottom: 1px solid rgba(255,255,255,0.07); }
        .sidebar-header h2 { font-size: 17px; font-weight: 700; color: #ffffff; margin-bottom: 4px; }
        .sidebar-header p { font-size: 12px; color: #000000; }

        .sidebar-menu { padding: 12px 0; flex: 1; }
        .menu-item { display: flex; align-items: center; padding: 11px 20px; color: #64748b; text-decoration: none; transition: all 0.2s; border-radius: 6px; margin: 2px 10px; font-size: 14px; }
        .menu-item:hover { background: rgba(124,58,237,0.12); color: #1f2937; }
        .menu-item.active { background: #0f172a; color: #ffffff; border-left: none; padding-left: 20px; }
        .menu-item svg { width: 18px; height: 18px; margin-right: 12px; fill: currentColor; flex-shrink: 0; }

        .menu-section { padding: 14px 20px 6px; font-size: 10px; color: #475569; text-transform: uppercase; font-weight: 700; letter-spacing: 0.08em; }

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
        .top-bar { background: #161b2e; padding: 14px 30px; border-bottom: 1px solid rgba(255,255,255,0.06); display: flex; justify-content: space-between; align-items: center; }
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

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { width: 68px; }
            .sidebar-header h2, .sidebar-header p, .menu-item span, .menu-section, .account-meta { display: none; }
            .menu-item { margin: 2px 6px; padding: 11px; justify-content: center; }
            .menu-item svg { margin-right: 0; }
            .menu-item.active { padding-left: 11px; border-left-width: 0; }
            .main-content { margin-left: 68px; }
            .account-link { justify-content: center; padding: 8px 4px; }
        }

        /* Global Admin Content Theme Overrides */
        .content h2,
        .content h3,
        .content h4,
        .content strong,
        .content label {
            color: #1f2937 !important;
        }

        .content p,
        .content span,
        .content li,
        .content td,
        .content summary,
        .content details,
        .content small {
            color: #475569;
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
        }

        .content [class$='-header'],
        .content .section-header,
        .content .users-header,
        .content .teachers-header,
        .content .students-header,
        .content .courses-header {
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

        .content .category-summary,
        .content .class-summary {
            background: rgba(124,58,237,0.1) !important;
            color: #1f2937 !important;
        }

        .content .course-code,
        .content .student-id,
        .content .teacher-id {
            background: rgba(148,163,184,0.16) !important;
            color: #000000 !important;
        }

        .content .role-badge,
        .content .badge,
        .content .status-badge,
        .content .course-badge,
        .content .subject-badge,
        .content .students-count {
            border-radius: 999px !important;
        }

        .content table,
        .content .users-table,
        .content .teachers-table,
        .content .students-table,
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
            color: #000000 !important;
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
        .content .btn-delete { background: #ef4444 !important; color: #ffffff !important; }

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
    </style>
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Admin Panel</h2>
                <p>{{ auth()->user()->name }}</p>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-section">Main</div>
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                    <span>Dashboard</span>
                </a>

                <div class="menu-section">User Management</div>
                <a href="{{ route('admin.invitations.index') }}" class="menu-item {{ request()->routeIs('admin.invitations.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    <span>Invitations</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    <span>All Users</span>
                </a>
                <a href="{{ route('admin.teachers.index') }}" class="menu-item {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    <span>Teachers</span>
                </a>
                <a href="{{ route('admin.students.index') }}" class="menu-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                    <span>Students</span>
                </a>

                <div class="menu-section">Academic</div>
                <a href="{{ route('admin.courses.index') }}" class="menu-item {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/></svg>
                    <span>Courses</span>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="menu-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
                    <span>Reports</span>
                </a>
            </nav>

            <div class="account-section">
                @php($sidebarAvatarUrl = auth()->user()->avatar_path ? url('/storage/' . auth()->user()->avatar_path) : null)
                <a href="{{ route('admin.settings') }}" class="account-link" title="Settings">
                    <div class="account-avatar">
                        @if($sidebarAvatarUrl)
                            <img src="{{ $sidebarAvatarUrl }}" alt="{{ auth()->user()->name }} Avatar">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="account-meta">
                        <div class="account-name">{{ auth()->user()->name }}</div>
                        <div class="account-role">Administrator</div>
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
                        <div class="user-role">Administrator</div>
                        <a href="{{ route('admin.settings') }}">Settings</a>
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
