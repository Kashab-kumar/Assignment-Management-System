@extends('layouts.teacher')

@section('title', $module->title)
@section('page-title', 'Module Workspace')

@section('content')
<style>
/* Layout */
.wrap { display: grid; gap: 18px; }
.panel { background: #fff; border: 1px solid rgba(0,0,0,0.06); border-radius: 12px; padding: 16px; }

/* Top cards grid */
.cards-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 20px;
  margin-top: 16px;
  align-items: stretch;
}
@media (max-width: 1100px) { .cards-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
@media (max-width: 768px) { .cards-grid { grid-template-columns: 1fr; } }

.module-card {
  display: flex;
  flex-direction: column;
  background: #fff;
  border-radius: 12px;
  border: 1px solid #eceff3;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(16,24,40,0.04);
}
.module-card:hover { transform: translateY(-3px); box-shadow: 0 8px 18px rgba(16,24,40,0.06); }

.module-header {
  background: linear-gradient(180deg,#f6f8fa,#f0f3f6);
  padding: 12px 18px;
  border-bottom: 1px solid #eef2f6;
}
.module-title { font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.7px; }
.module-course { font-size: 12px; color: #9ca3af; }

.module-body { padding: 18px; min-height: 70px; }
.card-count { font-size: 42px; font-weight: 800; color: #111827; line-height: 1; margin-bottom: 8px; }
.module-description { font-size: 13px; color: #6b7280; }

.module-footer { padding: 12px 18px; border-top: 1px solid #f1f5f9; background: #fff; }
.module-actions { display:flex; gap:8px; }
.btn { padding: 8px 12px; border-radius: 8px; text-decoration:none; text-align:center; font-size:13px; font-weight:600; border:none; cursor:pointer; }
.btn-primary { background: linear-gradient(135deg,#3b82f6,#6d28d9); color:white; }

/* Grading cards */
.grading-grid { display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; margin-top: 18px; }
.card-grading {
  padding: 18px;
  border-radius: 12px;
  min-height: 110px;
  background: linear-gradient(180deg,#0d1724,#0b1220);
  color: #fff;
  display: block;
  border: 1px solid rgba(255,255,255,0.04);
}
.card-grading .grading-card-label { font-size: 13px; font-weight: 700; color: #cbd5e1; text-transform: uppercase; margin-bottom: 8px; }
.card-grading .grading-card-count { font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 6px; }
.card-grading .grading-card-desc { color: #cbd5e1; font-size:13px; margin-bottom: 12px; }
.card-grading .grading-card-button { display:inline-block; padding:8px 12px; border-radius:8px; background:#2563eb; color:#fff; text-decoration:none; }

/* Small helpers */
.chips { display:flex; gap:8px; margin-top:10px; }
.chip { background:#eef2ff; color:#3730a3; padding:4px 10px; border-radius:999px; font-size:12px; }

</style>

<div class="wrap">
  <div class="panel header">
    <nav class="breadcrumb">
      <a href="{{ route('teacher.courses.index') }}">Courses</a>
      <span>/</span>
      <a href="{{ route('teacher.courses.show', $course) }}">{{ $course->name }}</a>
      <span>/</span>
      <span>{{ $module->title }}</span>
    </nav>
    <h2>{{ $module->title }}</h2>
    <p>{{ $course->name }} ({{ $course->code }})</p>
    <p>{{ $module->description ?: 'No module description provided.' }}</p>
    <div class="chips">
      <span class="chip">{{ $assignments->count() }} assignments</span>
      <span class="chip">{{ $exams->count() }} exams</span>
      <span class="chip">Assigned Teacher: {{ $module->teacher?->name ?? 'Any assigned course teacher' }}</span>
    </div>
  </div>

  @php
    $unitOutlineItems = ($module->items ?? collect())->where('type','unit_outline')->values();
  @endphp

  <div class="cards-grid">
    <div class="card-wrapper">
      <a href="{{ route('teacher.assignments.index', ['course_id'=>$course->id,'module_id'=>$module->id]) }}" class="card-link">
        <div class="module-card">
          <div class="module-header">
            <div class="module-title">Assignments</div>
            <div class="module-course">{{ $assignments->count() }} items</div>
          </div>
          <div class="module-body">
            <div class="card-count">{{ $assignments->count() }}</div>
            <div class="module-description">Manage assignments</div>
          </div>
          <div class="module-footer">
            <div class="module-actions">
              <a class="btn btn-primary" href="{{ route('teacher.assignments.index', ['course_id'=>$course->id,'module_id'=>$module->id]) }}">Manage Assignments</a>
            </div>
          </div>
        </div>
      </a>
    </div>

    <div class="card-wrapper">
      <a href="{{ route('teacher.exams.index', ['course_id'=>$course->id,'module_id'=>$module->id]) }}" class="card-link">
        <div class="module-card">
          <div class="module-header">
            <div class="module-title">Exams</div>
            <div class="module-course">{{ $exams->count() }} items</div>
          </div>
          <div class="module-body">
            <div class="card-count">{{ $exams->count() }}</div>
            <div class="module-description">Manage exams</div>
          </div>
          <div class="module-footer">
            <div class="module-actions">
              <a class="btn btn-primary" href="{{ route('teacher.exams.index', ['course_id'=>$course->id,'module_id'=>$module->id]) }}">Manage Exams</a>
            </div>
          </div>
        </div>
      </a>
    </div>

    <div class="card-wrapper">
      <a href="{{ route('teacher.courses.modules.unit-outline', [$course,$module]) }}" class="card-link">
        <div class="module-card">
          <div class="module-header">
            <div class="module-title">Unit Outline</div>
            <div class="module-course">{{ $unitOutlineItems->count() }} chapters</div>
          </div>
          <div class="module-body">
            <div class="card-count">{{ $unitOutlineItems->count() }}</div>
            <div class="module-description">View chapter table</div>
          </div>
          <div class="module-footer">
            <div class="module-actions">
              <a class="btn btn-primary" href="{{ route('teacher.courses.modules.unit-outline', [$course,$module]) }}">View Unit Outline</a>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>

  <div class="grading-section">
    <div class="grading-grid">
      <div class="card-grading">
        <div class="grading-card-label">Assignment Grading</div>
        <div class="grading-card-count">{{ $assignmentPendingCount ?? 0 }} <span style="font-size:14px; font-weight:600; color:#bfdbfe; margin-left:8px;">Pending</span></div>
        <div class="grading-card-desc">Grade submitted assignments, leave feedback, and publish marks for students in this module.</div>
        <a class="grading-card-button" href="{{ route('teacher.courses.assignment-grading', $course) }}?module={{ $module->id }}">Manage Grading</a>
      </div>

      <div class="card-grading">
        <div class="grading-card-label">Exam Grading</div>
        <div class="grading-card-count">{{ $examPendingCount ?? 0 }} <span style="font-size:14px; font-weight:600; color:#bfdbfe; margin-left:8px;">Pending</span></div>
        <div class="grading-card-desc">Review exam answers and update student scores with comments for this module.</div>
        <a class="grading-card-button" href="{{ route('teacher.courses.exam-grading', $course) }}?module={{ $module->id }}">Manage Grading</a>
      </div>
    </div>
  </div>

</div>
@endsection
