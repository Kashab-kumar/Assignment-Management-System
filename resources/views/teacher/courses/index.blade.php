@extends('layouts.teacher')

@section('title', 'Courses')
@section('page-title', 'Courses')

@section('content')
<style>
    .courses-container { background: #ffffff; border-radius: 12px; border: 1px solid rgba(0,0,0,0.06); padding: 20px; }
    .courses-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid rgba(0,0,0,0.08); gap: 10px; flex-wrap: wrap; }
    .filter-form { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .filter-select { min-width: 200px; padding: 8px 10px; border: 1px solid rgba(0,0,0,0.14); border-radius: 8px; font-size: 14px; background: rgba(0,0,0,0.02); color: #1f2937; }

    .tree { border: 1px solid rgba(0,0,0,0.1); border-radius: 12px; overflow: hidden; }
    .tree details { border-bottom: 1px solid rgba(0,0,0,0.08); background: rgba(0,0,0,0.02); }
    .tree details:last-child { border-bottom: none; }
    .tree summary { list-style: none; cursor: pointer; }
    .tree summary::-webkit-details-marker { display: none; }
    .category-summary { display: flex; align-items: center; gap: 10px; padding: 14px 18px; font-weight: 700; color: #1f2937; background: rgba(124,58,237,0.08); font-size: 20px; }
    .class-summary { display: flex; align-items: center; gap: 10px; padding: 12px 18px 12px 36px; font-weight: 700; color: #1f2937; font-size: 16px; background: rgba(124,58,237,0.03); }
    .tree-caret { width: 0; height: 0; border-left: 6px solid #94a3b8; border-top: 5px solid transparent; border-bottom: 5px solid transparent; transition: transform .2s ease; }
    details[open] > summary .tree-caret { transform: rotate(90deg); }
    .course-list { padding: 0 18px 12px 54px; }
    .course-row { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 14px; align-items: center; padding: 12px 0; border-top: 1px solid rgba(255,255,255,0.08); }
    .course-row:first-child { border-top: none; }
    .course-code { font-family: monospace; background: rgba(148,163,184,0.08); padding: 4px 8px; border-radius: 6px; font-size: 12px; color: #64748b; }
    .course-name { font-size: 16px; font-weight: 600; color: #000000; margin: 4px 0 6px; }
    .course-meta { display: flex; align-items: center; gap: 10px; }
    .students-count { background: rgba(59,130,246,0.18); border: 1px solid rgba(59,130,246,0.3); color: #60a5fa; padding: 4px 10px; border-radius: 999px; font-size: 12px; }
    .btn { padding: 6px 14px; border-radius: 8px; text-decoration: none; font-size: 13px; }
    .btn-view { background: #7c3aed; color: white; }
    .btn-view:hover { background: #6d28d9; }
    .btn-clear { background: rgba(0,0,0,0.05); color: #1f2937; padding: 8px 14px; font-size: 13px; border-radius: 8px; text-decoration: none; }
</style>

<div class="courses-container">
    <div class="courses-header">
        <h2 style="font-size:20px; color: #000000;">All Courses ({{ $courses->count() }})</h2>

        <form method="GET" class="filter-form">
            <select name="category_name" class="filter-select" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach($categoryOptions as $cat)
                    <option value="{{ $cat }}" {{ $selectedCategory === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <select name="class_name" class="filter-select" onchange="this.form.submit()">
                <option value="">All Classes</option>
                @foreach($classOptions as $cls)
                    <option value="{{ $cls }}" {{ $selectedClass === $cls ? 'selected' : '' }}>{{ $cls }}</option>
                @endforeach
            </select>
            @if($selectedCategory || $selectedClass)
                <a href="{{ route('teacher.courses.index') }}" class="btn-clear">Clear</a>
            @endif
        </form>
    </div>

    @if($courseTree->isEmpty())
        <div style="text-align:center; padding:40px; color: #000000;">No courses found.</div>
    @else
    <div class="tree">
        @foreach($courseTree as $category => $classes)
        <details open>
            <summary class="category-summary">
                <span class="tree-caret"></span>
                {{ $category }}
            </summary>
            @foreach($classes as $className => $classCourses)
            <details open>
                <summary class="class-summary">
                    <span class="tree-caret"></span>
                    {{ $className }}
                </summary>
                <div class="course-list">
                    @foreach($classCourses as $course)
                    <div class="course-row">
                        <div>
                            <span class="course-code">{{ $course->code }}</span>
                            <div class="course-name">{{ $course->name }}</div>
                            <div class="course-meta">
                                <span class="students-count">{{ $course->students_count }} student{{ $course->students_count !== 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('teacher.courses.show', $course) }}" class="btn btn-view">View</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </details>
            @endforeach
        </details>
        @endforeach
    </div>
    @endif
</div>
@endsection
