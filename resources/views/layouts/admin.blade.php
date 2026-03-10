<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Assignment Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        
        .layout { display: flex; min-height: 100vh; }
        
        /* Sidebar */
        .sidebar { width: 260px; background: #2c3e50; color: white; position: fixed; height: 100vh; overflow-y: auto; }
        .sidebar-header { padding: 20px; background: #1a252f; border-bottom: 1px solid #34495e; }
        .sidebar-header h2 { font-size: 18px; margin-bottom: 5px; }
        .sidebar-header p { font-size: 12px; color: #95a5a6; }
        
        .sidebar-menu { padding: 20px 0; }
        .menu-item { display: flex; align-items: center; padding: 12px 20px; color: #ecf0f1; text-decoration: none; transition: all 0.3s; }
        .menu-item:hover { background: #34495e; }
        .menu-item.active { background: #9C27B0; border-left: 4px solid #fff; }
        .menu-item svg { width: 20px; height: 20px; margin-right: 12px; fill: currentColor; }
        
        .menu-section { padding: 10px 20px; font-size: 11px; color: #95a5a6; text-transform: uppercase; font-weight: bold; margin-top: 10px; }
        
        .logout-section { position: absolute; bottom: 0; width: 100%; padding: 20px; border-top: 1px solid #34495e; }
        .logout-btn { width: 100%; padding: 10px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        .logout-btn:hover { background: #c0392b; }
        
        /* Main Content */
        .main-content { margin-left: 260px; flex: 1; }
        .top-bar { background: white; padding: 15px 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .top-bar h1 { font-size: 24px; color: #2c3e50; }
        .user-info { display: flex; align-items: center; gap: 10px; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: #9C27B0; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        
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
                        <div style="font-size: 12px; color: #7f8c8d;">Administrator</div>
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
