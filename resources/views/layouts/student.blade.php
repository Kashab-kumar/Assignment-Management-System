<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Dashboard') - Assignment Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }

        .layout { display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: 260px; background: #27ae60; color: white; position: fixed; height: 100vh; overflow-y: auto; }
        .sidebar-header { padding: 20px; background: #229954; border-bottom: 1px solid #2ecc71; }
        .sidebar-header h2 { font-size: 18px; margin-bottom: 5px; }
        .sidebar-header p { font-size: 12px; color: #d5f4e6; }

        .sidebar-menu { padding: 20px 0; }
        .menu-item { display: flex; align-items: center; padding: 12px 20px; color: white; text-decoration: none; transition: all 0.3s; }
        .menu-item:hover { background: #229954; }
        .menu-item.active { background: #1e8449; border-left: 4px solid #fff; }
        .menu-item svg { width: 20px; height: 20px; margin-right: 12px; fill: currentColor; }

        .menu-section { padding: 10px 20px; font-size: 11px; color: #d5f4e6; text-transform: uppercase; font-weight: bold; margin-top: 10px; }

        .logout-section { position: absolute; bottom: 0; width: 100%; padding: 20px; border-top: 1px solid #2ecc71; }
        .logout-btn { width: 100%; padding: 10px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        .logout-btn:hover { background: #c0392b; }

        /* Main Content */
        .main-content { margin-left: 260px; flex: 1; }
        .top-bar { background: white; padding: 15px 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .top-bar h1 { font-size: 24px; color: #2c3e50; }
        .user-info { display: flex; align-items: center; gap: 10px; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: #27ae60; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; }

        .content { padding: 30px; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar-header h2, .sidebar-header p, .menu-item span, .menu-section { display: none; }
            .main-content { margin-left: 70px; }
            .logout-section { padding: 10px; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Student Portal</h2>
                <p>{{ auth()->user()->name }}</p>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-section">Main</div>
                <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                    <span>Dashboard</span>
                </a>

                <div class="menu-section">Academics</div>
                <a href="{{ route('assignments.index') }}" class="menu-item {{ request()->routeIs('assignments.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                    <span>Assignments</span>
                </a>

                <a href="{{ route('student.exams.index') }}" class="menu-item {{ request()->routeIs('student.exams.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg>
                    <span>Exams</span>
                </a>
                <a href="{{ route('student.grades.index') }}" class="menu-item {{ request()->routeIs('student.grades.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                    <span>My Grades</span>
                </a>

                <div class="menu-section">Performance</div>
                <a href="{{ route('student.rankings') }}" class="menu-item {{ request()->routeIs('student.rankings') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M16 11V3H8v6H2v12h20V11h-6zm-6-6h4v14h-4V5zm-6 6h4v8H4v-8zm16 8h-4v-6h4v6z"/></svg>
                    <span>Class Rankings</span>
                </a>

                <div class="menu-section">Account</div>
                <a href="{{ route('student.profile') }}" class="menu-item {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    <span>My Profile</span>
                </a>
            </nav>

            <div class="logout-section">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div>
                        <div style="font-weight: bold; font-size: 14px;">{{ auth()->user()->name }}</div>
                        <div style="font-size: 12px; color: #7f8c8d;">Student</div>
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
