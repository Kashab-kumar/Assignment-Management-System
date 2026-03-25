@extends('layouts.student')

@section('title', 'Modules')
@section('page-title', 'My Modules')

@section('content')
<style>
    .modules-topbar {
        background: #0f172a;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 14px;
    }

    .modules-topbar h2 {
        color: #ffffff;
        margin: 0;
        font-size: 22px;
        font-weight: 700;
    }

    .modules-topbar p {
        color: #cbd5e1;
        margin: 2px 0 0;
        font-size: 12px;
    }

    .modules-shell {
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

    .active-only {
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

    .modules-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 14px;
    }

    .module-card {
        border: 1px solid #dbe2ec;
        border-radius: 12px;
        background: #ffffff;
        padding: 14px;
    }

    .module-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .module-avatar {
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

    .chip-module { background: #e0ecff; color: #2459ff; }
    .chip-status-active { background: #dcfce7; color: #047857; }
    .chip-status-inactive { background: #fee2e2; color: #b91c1c; }

    .module-title {
        margin: 0 0 8px;
        font-size: 26px;
        line-height: 1;
        color: #0f172a;
        font-weight: 700;
    }

    .module-desc {
        margin: 0;
        color: #475569;
        font-size: 14px;
        min-height: 42px;
        line-height: 1.45;
    }

    .module-meta {
        margin-top: 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        color: #475569;
        font-size: 14px;
    }

    .module-footer {
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .module-tags {
        color: #64748b;
        font-size: 13px;
    }

    .module-link {
        color: #2459ff;
        font-weight: 700;
        text-decoration: none;
        font-size: 22px;
    }

    .empty {
        background: #ffffff;
        border: 1px dashed #d1d5db;
        border-radius: 12px;
        padding: 26px;
        text-align: center;
        color: #6b7280;
    }
</style>

<div class="modules-topbar">
    <div>
        <h2>Modules</h2>
        @if($course)
            <p>{{ $course->name }} ({{ $course->code }})</p>
        @else
            <p>No course assigned yet</p>
        @endif
    </div>
</div>

<div class="modules-shell">
    @if($modulesEnabled && $course && $moduleCards->isNotEmpty())
        <div class="toolbar">
            <div class="search-box">
                <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 1 0-.71.71l.27.28v.79L20 20.49 21.49 19l-5.99-5zM10 15a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/></svg>
                <input id="moduleSearch" type="text" class="search-input" placeholder="Search modules...">
            </div>
            <label class="active-only">
                <input id="activeOnly" type="checkbox">
                Active only
            </label>
        </div>
    @endif

@if(!$modulesEnabled)
    <div class="empty">Modules feature is not available yet. Please run the latest migrations.</div>
@elseif(!$course)
    <div class="empty">You are not assigned to a course yet. Contact your administrator.</div>
@elseif($moduleCards->isEmpty())
    <div class="empty">No modules are available for your course yet.</div>
@else
    <div class="modules-grid" id="modulesGrid">
        @foreach($moduleCards as $module)
            @php
                $avatarText = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $module['title'] ?? 'MDL'), 0, 2));
                $searchValue = strtolower(trim(($module['title'] ?? '') . ' ' . ($module['description'] ?? '') . ' ' . $module['teachers']->join(' ')));
            @endphp
            <article class="module-card" data-search="{{ $searchValue }}" data-active="{{ $module['is_active'] ? '1' : '0' }}">
                <div class="module-head">
                    <div class="module-avatar">{{ $avatarText ?: 'MD' }}</div>
                    <div>
                        <span class="chip chip-module">Module {{ $loop->iteration }}</span>
                        <span class="chip {{ $module['is_active'] ? 'chip-status-active' : 'chip-status-inactive' }}">{{ $module['is_active'] ? 'Active' : 'Inactive' }}</span>
                    </div>
                </div>

                <h3 class="module-title">{{ $module['title'] }}</h3>
                <p class="module-desc">{{ $module['description'] ?: 'No description provided for this module.' }}</p>

                <div class="module-meta">
                    <span>Teacher: {{ $module['teachers']->isNotEmpty() ? $module['teachers']->join(', ') : 'Not assigned' }}</span>
                    <span>{{ $moduleItemsEnabled ? $module['item_count'] : 0 }} items</span>
                </div>

                <div class="module-footer">
                    <div class="module-tags">{{ $module['lesson_count'] }} lessons | {{ $module['assignment_count'] }} assignments | {{ $module['quiz_count'] }} quizzes</div>
                    <a href="{{ route('student.modules.index') }}" class="module-link">View Details ›</a>
                </div>
            </article>
        @endforeach
    </div>
@endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('moduleSearch');
        const activeOnly = document.getElementById('activeOnly');
        const cards = Array.from(document.querySelectorAll('#modulesGrid .module-card'));

        function filterCards() {
            const value = (searchInput?.value || '').trim().toLowerCase();
            const onlyActive = !!activeOnly?.checked;

            cards.forEach(function (card) {
                const haystack = card.getAttribute('data-search') || '';
                const isActive = card.getAttribute('data-active') === '1';
                const matchesText = haystack.includes(value);
                const matchesActive = !onlyActive || isActive;
                card.style.display = (matchesText && matchesActive) ? '' : 'none';
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterCards);
        }

        if (activeOnly) {
            activeOnly.addEventListener('change', filterCards);
        }
    });
</script>
@endsection
