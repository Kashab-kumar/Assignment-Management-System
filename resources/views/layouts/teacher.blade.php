<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Teacher Dashboard') - Assignment Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 fixed h-full flex flex-col overflow-y-auto z-10">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 3 1 9l11 6 9-4.91V17h2V9L12 3zm-7 9.18V17l7 4 7-4v-4.82L12 16l-7-3.82z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-gray-900">Institute</div>
                        <div class="text-xs text-gray-500">LMS Platform</div>
                    </div>
                </div>
                <div class="w-full text-center bg-gradient-to-r from-primary-500 to-primary-700 text-white rounded-full py-1.5 px-3 text-xs font-bold">
                    Teacher Portal
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-3 space-y-1">
                <a href="{{ route('teacher.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('teacher.dashboard') ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <div class="pt-4 pb-2 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Teaching</div>

                <a href="{{ route('teacher.courses.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('teacher.courses.*') ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 12.27L4.77 12 12 8.73 19.23 12 12 15.27zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                    </svg>
                    <span>Courses</span>
                </a>

                <a href="{{ route('teacher.calendar') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('teacher.calendar*') ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5C3.89 4 3 4.9 3 6v14c0 1.1.89 2 2 2h14a2 2 0 0 0 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10z"/>
                    </svg>
                    <span>Recent</span>
                </a>

                <a href="{{ route('teacher.assignments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('teacher.assignments.*') ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                    </svg>
                    <span>Assignments</span>
                </a>

                <a href="{{ route('teacher.submissions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('teacher.submissions.*') ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                    </svg>
                    <span>Submissions</span>
                </a>

                <!-- Tests & Exams moved to module pages. Access exams from a specific course module. -->

                <div class="pt-4 pb-2 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Students</div>

                <a href="{{ route('teacher.students.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('teacher.students.*') ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/>
                    </svg>
                    <span>My Students</span>
                </a>

                <a href="{{ route('teacher.grades.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('teacher.grades.*') ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <span>Grades</span>
                </a>

                <a href="{{ route('teacher.reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('teacher.reports.*') ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                    </svg>
                    <span>Reports</span>
                </a>
            </nav>

            <!-- Account Section -->
            <div class="p-4 border-t border-gray-200">
                @php($sidebarAvatarUrl = auth()->user()->avatar_path ? url('/storage/' . auth()->user()->avatar_path) : null)
                <a href="{{ route('teacher.settings') }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 transition-colors text-gray-900">
                    <div class="w-10 h-10 rounded-full bg-primary-600 text-white flex items-center justify-center font-bold flex-shrink-0 border-2 border-primary-300">
                        @if($sidebarAvatarUrl)
                            <img src="{{ $sidebarAvatarUrl }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-semibold truncate text-gray-900">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500">Teacher</div>
                    </div>
                    <svg class="w-4 h-4 text-primary-600 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19.14 12.94c.04-.31.06-.63.06-.94s-.02-.63-.06-.94l2.03-1.58a.5.5 0 0 0 .12-.64l-1.92-3.32a.5.5 0 0 0-.6-.22l-2.39.96a7.03 7.03 0 0 0-1.63-.94l-.36-2.54a.5.5 0 0 0-.5-.42h-3.84a.5.5 0 0 0-.5.42l-.36 2.54c-.58.23-1.12.54-1.63.94l-2.39-.96a.5.5 0 0 0-.6.22L2.71 8.84a.5.5 0 0 0 .12.64l2.03 1.58c-.04.31-.06.63-.06.94s.02.63.06.94l-2.03 1.58a.5.5 0 0 0-.12.64l1.92 3.32c.13.22.39.31.6.22l2.39-.96c.5.4 1.05.72 1.63.94l.36 2.54c.04.24.25.42.5.42h3.84c.25 0 .46-.18.5-.42l.36-2.54c.58-.23 1.12-.54 1.63-.94l2.39.96c.22.09.47 0 .6-.22l1.92-3.32a.5.5 0 0 0-.12-.64l-2.03-1.58zM12 15.5A3.5 3.5 0 1 1 12 8a3.5 3.5 0 0 1 0 7.5z"/>
                    </svg>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64">
            <div class="p-8">
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
