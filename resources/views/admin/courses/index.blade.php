@extends('layouts.admin')

@section('title', 'Courses')
@section('page-title', 'Courses Management')

@section('content')
<style>
    .courses-topbar {
        background: #0f172a;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }

    .courses-topbar h2 {
        color: #ffffff;
        margin: 0;
        font-size: 22px;
        font-weight: 700;
    }

    .courses-topbar p {
        color: #cbd5e1;
        margin: 2px 0 0;
        font-size: 12px;
    }

    .btn-add {
        background: #111827;
        color: #ffffff;
        text-decoration: none;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid rgba(255,255,255,0.25);
    }

    .courses-shell {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e4e7ef;
        padding: 16px;
    }

    .toolbar {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 220px;
    }

    .search-box svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        fill: #9ca3af;
    }

    .search-input {
        width: 100%;
        padding: 10px 12px 10px 38px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 14px;
        color: #111827;
        background: #ffffff;
    }

    .filter-toggle {
        border: 1px solid #d1d5db;
        background: #f3f4f6;
        color: #111827;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .filter-toggle svg { width: 14px; height: 14px; fill: currentColor; }

    .filters-panel {
        display: none;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px;
        margin-bottom: 14px;
        background: #fafbfe;
    }

    .filters-panel.show { display: block; }

    .filter-form {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-select {
        min-width: 190px;
        padding: 9px 10px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        background: #ffffff;
    }

    .btn-apply,
    .btn-reset {
        padding: 9px 12px;
        border-radius: 8px;
        font-size: 13px;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .btn-apply { background: #111827; color: #ffffff; }
    .btn-reset { background: #e5e7eb; color: #111827; }

    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 14px;
    }

    .course-card {
        border: 1px solid #dbe2ec;
        border-radius: 12px;
        background: #ffffff;
        padding: 14px;
    }

    .course-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .course-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4f74ff, #4f46e5);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 22px;
        text-transform: uppercase;
    }

    .chip {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 4px 9px;
        font-size: 12px;
        line-height: 1;
    }

    .chip-code { background: #e0ecff; color: #2459ff; }
    .chip-status-active { background: #dcfce7; color: #047857; }
    .chip-status-inactive { background: #fee2e2; color: #b91c1c; }

    .course-title {
        margin: 0 0 8px;
        font-size: 26px;
        line-height: 1;
        color: #0f172a;
        font-weight: 700;
    }

    .course-desc {
        margin: 0;
        color: #475569;
        font-size: 14px;
        min-height: 42px;
        line-height: 1.45;
    }

    .course-meta {
        margin-top: 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        color: #475569;
        font-size: 14px;
    }

    .course-footer {
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .course-tags {
        color: #64748b;
        font-size: 13px;
    }

    .course-link {
        color: #2459ff;
        font-weight: 700;
        text-decoration: none;
        font-size: 22px;
    }

    .alert-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 10px 12px;
        margin-bottom: 14px;
        font-size: 14px;
    }

    .empty-state {
        text-align: center;
        border: 1px dashed #d1d5db;
        border-radius: 12px;
        padding: 48px 20px;
        color: #6b7280;
    }

    .empty-state h3 { margin: 10px 0 4px; color: #111827; font-size: 20px; }
</style>

<div class="courses-topbar">
    <div>
        <h2>Courses</h2>
        <p>Manage all courses and enrollments</p>
    </div>
    <a href="{{ route('admin.courses.create') }}" class="btn-add">+ Add Course</a>
</div>

<div class="courses-shell">
    <div class="toolbar">
        <div class="search-box">
            <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 1 0-.71.71l.27.28v.79L20 20.49 21.49 19l-5.99-5zM10 15a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/></svg>
            <input id="courseSearch" type="text" class="search-input" placeholder="Search courses...">
        </div>
        <button id="filterToggle" type="button" class="filter-toggle" aria-expanded="false">
            <svg viewBox="0 0 24 24"><path d="M3 5h18v2l-7 7v5l-4 2v-7L3 7V5z"/></svg>
            Filter
        </button>
    </div>

    <div id="filtersPanel" class="filters-panel {{ $selectedCategory || $selectedClass ? 'show' : '' }}">
        <form method="GET" action="{{ route('admin.courses.index') }}" class="filter-form">
            <select name="category_name" class="filter-select">
                <option value="">All Categories</option>
                @foreach($categoryOptions as $categoryName)
                    <option value="{{ $categoryName }}" {{ $selectedCategory === $categoryName ? 'selected' : '' }}>{{ $categoryName }}</option>
                @endforeach
            </select>

            <select name="class_name" class="filter-select">
                <option value="">All Classes</option>
                @foreach($classOptions as $className)
                    <option value="{{ $className }}" {{ $selectedClass === $className ? 'selected' : '' }}>{{ $className }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn-apply">Apply</button>
            <a href="{{ route('admin.courses.index') }}" class="btn-reset">Reset</a>
        </form>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if($courses->count() > 0)
        <div class="courses-grid" id="coursesGrid">
            @foreach($courses as $course)
                @php
                    $teacherNames = $course->teachers->pluck('name')->filter()->values();
                    $avatarText = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $course->code ?? $course->name ?? 'CRS'), 0, 2));
                    $searchValue = strtolower(trim(($course->code ?? '') . ' ' . ($course->name ?? '') . ' ' . ($course->description ?? '') . ' ' . ($course->category_name ?? '') . ' ' . ($course->class_name ?? '') . ' ' . $teacherNames->join(' ')));
                @endphp
                <article class="course-card" data-search="{{ $searchValue }}">
                    <div class="course-head">
                        <div class="course-avatar">{{ $avatarText ?: 'CR' }}</div>
                        <div>
                            <span class="chip chip-code">{{ $course->code }}</span>
                            <span class="chip {{ $course->is_active ? 'chip-status-active' : 'chip-status-inactive' }}">{{ $course->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                    </div>

                    <h3 class="course-title">{{ $course->name }}</h3>
                    <p class="course-desc">{{ $course->description ? Str::limit($course->description, 90) : 'No description provided for this course.' }}</p>

                    <div class="course-meta">
                        <span>Teacher: {{ $teacherNames->isNotEmpty() ? $teacherNames->join(', ') : 'Not assigned' }}</span>
                        <span>{{ $course->students_count }} students</span>
                    </div>

                    <div class="course-footer">
                        <div class="course-tags">{{ $course->class_name ?: 'Unassigned class' }} | {{ $course->category_name ?: 'Uncategorized' }}</div>
                        <a href="{{ route('admin.courses.edit', $course) }}" class="course-link">View Details ›</a>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" style="width: 54px; height: 54px; fill: #cbd5e1;"><path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/></svg>
            <h3>No Courses Found</h3>
            <p>Create your first course to get started.</p>
            <a href="{{ route('admin.courses.create') }}" class="btn-add" style="display:inline-block; margin-top: 10px;">+ Create First Course</a>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterToggle = document.getElementById('filterToggle');
        const filtersPanel = document.getElementById('filtersPanel');
        const searchInput = document.getElementById('courseSearch');
        const cards = Array.from(document.querySelectorAll('#coursesGrid .course-card'));

        if (filterToggle && filtersPanel) {
            filterToggle.addEventListener('click', function () {
                const isOpen = filtersPanel.classList.toggle('show');
                filterToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        }

        if (searchInput && cards.length) {
            searchInput.addEventListener('input', function () {
                const value = searchInput.value.trim().toLowerCase();
                cards.forEach(function (card) {
                    const haystack = card.getAttribute('data-search') || '';
                    card.style.display = haystack.includes(value) ? '' : 'none';
                });
            });
        }
    });
</script>
@endsection
