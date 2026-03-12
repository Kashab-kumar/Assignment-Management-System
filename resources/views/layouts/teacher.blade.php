<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teacher Dashboard') - Assignment Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }

        .layout { display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: 260px; background: #34495e; color: white; position: fixed; height: 100vh; overflow-y: auto; }
        .sidebar-header { padding: 20px; background: #2c3e50; border-bottom: 1px solid #4a5f7f; }
        .sidebar-header h2 { font-size: 18px; margin-bottom: 5px; }
        .sidebar-header p { font-size: 12px; color: #95a5a6; }

        .sidebar-menu { padding: 20px 0; }
        .menu-item { display: flex; align-items: center; padding: 12px 20px; color: #ecf0f1; text-decoration: none; transition: all 0.3s; }
        .menu-item:hover { background: #4a5f7f; }
        .menu-item.active { background: #2196F3; border-left: 4px solid #fff; }
        .menu-item svg { width: 20px; height: 20px; margin-right: 12px; fill: currentColor; }

        .menu-section { padding: 10px 20px; font-size: 11px; color: #95a5a6; text-transform: uppercase; font-weight: bold; margin-top: 10px; }

        .account-section { position: absolute; bottom: 0; width: 100%; padding: 14px; border-top: 1px solid #4a5f7f; background: rgba(0,0,0,0.08); }
        .account-link { display: flex; align-items: center; gap: 10px; text-decoration: none; color: #ecf0f1; }
        .account-avatar { width: 42px; height: 42px; border-radius: 50%; background: #2196F3; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; overflow: hidden; border: 2px solid rgba(255,255,255,0.2); flex-shrink: 0; }
        .account-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .account-meta { min-width: 0; }
        .account-name { font-size: 13px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .account-settings { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #cfd9e3; margin-top: 2px; }
        .account-settings svg { width: 14px; height: 14px; fill: currentColor; }
        .account-link:hover .account-settings { color: #ffffff; }

        /* Main Content */
        .main-content { margin-left: 260px; flex: 1; }
        .top-bar { background: white; padding: 15px 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .top-bar h1 { font-size: 24px; color: #2c3e50; }
        .user-info { display: flex; align-items: center; gap: 10px; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: #2196F3; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; overflow: hidden; }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }

        .content { padding: 30px; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar-header h2, .sidebar-header p, .menu-item span, .menu-section { display: none; }
            .main-content { margin-left: 70px; }
            .account-section { padding: 10px; display: flex; justify-content: center; }
            .account-meta { display: none; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Teacher Panel</h2>
                <p>{{ auth()->user()->name }}</p>
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
                    <span>Calendar</span>
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
                        <div class="account-settings">
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
                    @php($avatarUrl = auth()->user()->avatar_path ? url('/storage/' . auth()->user()->avatar_path) : null)
                    <div class="user-avatar">
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ auth()->user()->name }} Avatar">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <div style="font-weight: bold; font-size: 14px;">{{ auth()->user()->name }}</div>
                        <div style="font-size: 12px; color: #7f8c8d;">Teacher</div>
                        <a href="{{ route('teacher.settings') }}" style="font-size: 12px; color: #2196F3; text-decoration: none;">Settings</a>
                    </div>
                </div>
            </div>

            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
