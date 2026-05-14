<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teacher Dashboard') - Assignment Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Dark Mode Support */
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --sidebar-bg: #ffffff;
            --sidebar-text: #1f2937;
        }

        [data-theme="dark"] {
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --sidebar-bg: #1f2937;
            --sidebar-text: #f3f4f6;
        }

        body {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        [data-theme="dark"] body {
            background-color: #111827;
        }

        [data-theme="dark"] .top-nav {
            background-color: var(--sidebar-bg) !important;
            border-color: #374151 !important;
        }

        [data-theme="dark"] .top-nav * {
            color: var(--sidebar-text);
        }

        .top-nav-scroll {
            overflow-x: auto;
            overflow-y: hidden;
        }

        .top-nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: max-content;
        }

        .top-nav-link {
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
            padding: 10px 20px;
            font-size: 15px !important;
            border-radius: 9999px;
            text-decoration: none;
        }

        .top-nav-link-active {
            background-color: #1f2937 !important;
            color: #ffffff !important;
        }

        .top-nav-link-inactive {
            color: #1f2937 !important;
            background-color: transparent !important;
        }

        .top-nav-link-inactive:hover {
            background-color: transparent !important;
            color: #111827 !important;
        }

        .top-nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .page-tab-header {
            background-color: #111827;
            color: #ffffff;
            border-radius: 14px;
            padding: 24px 28px;
            margin-bottom: 24px;
        }

        .page-tab-title {
            font-size: 32px;
            line-height: 1.2;
            font-weight: 700;
            margin: 0;
        }

        .page-tab-subtitle {
            margin-top: 8px;
            margin-bottom: 0;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.86);
        }

        [data-theme="dark"] main {
            background-color: #111827;
        }

        [data-theme="dark"] .card,
        [data-theme="dark"] .panel,
        [data-theme="dark"] .section,
        [data-theme="dark"] .container,
        [data-theme="dark"] .table-container,
        [data-theme="dark"] .grades-container,
        [data-theme="dark"] .reports-container,
        [data-theme="dark"] .course-container,
        [data-theme="dark"] .courses-container,
        [data-theme="dark"] .stat-card,
        [data-theme="dark"] .stats-card,
        [data-theme="dark"] .filters,
        [data-theme="dark"] .export-options,
        [data-theme="dark"] .assignment-card,
        [data-theme="dark"] .submission-card {
            background-color: #1f2937 !important;
            color: #f3f4f6 !important;
            border-color: #374151 !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.35) !important;
        }

        [data-theme="dark"] table {
            color: #e5e7eb !important;
        }

        [data-theme="dark"] thead {
            background-color: #111827 !important;
        }

        [data-theme="dark"] th {
            background-color: #111827 !important;
            color: #d1d5db !important;
            border-color: #374151 !important;
        }

        [data-theme="dark"] td {
            border-color: #374151 !important;
            color: #e5e7eb !important;
        }

        [data-theme="dark"] tbody tr:hover {
            background-color: #111827 !important;
        }

        [data-theme="dark"] select,
        [data-theme="dark"] input,
        [data-theme="dark"] textarea {
            background-color: #111827 !important;
            color: #f3f4f6 !important;
            border-color: #374151 !important;
        }

        [data-theme="dark"] .btn,
        [data-theme="dark"] .btn-secondary,
        [data-theme="dark"] .btn-primary,
        [data-theme="dark"] .btn-add,
        [data-theme="dark"] .btn-filter,
        [data-theme="dark"] .filter-btn {
            color: #ffffff !important;
        }


    </style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans" data-theme="light">
    <div class="min-h-screen flex flex-col">
        <header class="top-nav bg-white border-b border-gray-200 px-4 md:px-6 py-2 sticky top-0 z-20">
            <div class="flex items-center justify-between w-full">
                <nav class="top-nav-scroll flex-1 flex justify-center min-w-0">
                    <div class="top-nav-links">
                        <a href="{{ route('teacher.dashboard') }}" class="top-nav-link font-medium transition-colors {{ request()->routeIs('teacher.dashboard') ? 'top-nav-link-active' : 'top-nav-link-inactive' }}">Dashboard</a>
                        <a href="{{ route('teacher.courses.index') }}" class="top-nav-link font-medium transition-colors {{ request()->routeIs('teacher.courses.*') ? 'top-nav-link-active' : 'top-nav-link-inactive' }}">Courses</a>
                        <a href="{{ route('teacher.students.index') }}" class="top-nav-link font-medium transition-colors {{ request()->routeIs('teacher.students.*') ? 'top-nav-link-active' : 'top-nav-link-inactive' }}">My Students</a>
                        <a href="{{ route('teacher.grades.index') }}" class="top-nav-link font-medium transition-colors {{ request()->routeIs('teacher.grades.*') ? 'top-nav-link-active' : 'top-nav-link-inactive' }}">Grades</a>
                        <a href="{{ route('teacher.reports.index') }}" class="top-nav-link font-medium transition-colors {{ request()->routeIs('teacher.reports.*') ? 'top-nav-link-active' : 'top-nav-link-inactive' }}">Reports</a>
                    </div>
                </nav>

                <div class="top-nav-actions shrink-0 flex items-center gap-4">
                    <span class="text-sm text-gray-700 font-medium whitespace-nowrap">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout', ['guard' => auth()->user()->role]) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="px-6 py-2 rounded-full bg-black text-white hover:bg-gray-900 transition-colors text-sm font-medium border border-black whitespace-nowrap" style="min-width: 92px; background-color: #111827; color: #ffffff; border-color: #111827;">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1">
            <div class="p-4 md:p-8">
                <div class="page-tab-header">
                    <h1 class="page-tab-title">{{ request()->routeIs('teacher.courses.*') ? 'Courses' : (request()->routeIs('teacher.students.*') ? 'My Students' : (request()->routeIs('teacher.grades.*') ? 'Grades' : (request()->routeIs('teacher.reports.*') ? 'Reports' : 'Dashboard'))) }}</h1>
                    @if(request()->routeIs('teacher.dashboard'))
                        <p class="page-tab-subtitle">Teacher ID: {{ auth()->user()->teacher->teacher_id ?? 'N/A' }} | Subject: {{ auth()->user()->teacher->subject ?? 'Not set' }} | Courses: {{ auth()->user()->teacher ? auth()->user()->teacher->courses()->count() : 0 }}</p>
                    @else
                        <p class="page-tab-subtitle">You are viewing the current section.</p>
                    @endif
                </div>

                @if(session('role_notice'))
                    <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg text-amber-700 text-sm">
                        {{ session('role_notice') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Close notifications after 5 seconds
        setTimeout(() => {
            const notifications = document.querySelectorAll('.mb-4');
            notifications.forEach(notif => {
                notif.style.transition = 'opacity 0.5s';
                notif.style.opacity = '0';
                setTimeout(() => notif.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
