@extends('layouts.teacher')

@section('title', $module->title)
@section('page-title', $module->title)

@section('content')
<style>
    .module-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .module-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 32px;
        color: white;
    }

    .module-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .module-course {
        font-size: 18px;
        opacity: 0.9;
        margin-bottom: 16px;
    }

    .module-description {
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 24px;
    }

    .module-weightage {
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
    }

    .module-outline {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 32px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .outline-title {
        font-size: 20px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 16px;
    }

    .outline-content {
        color: #4b5563;
        line-height: 1.6;
        white-space: pre-wrap;
    }

    .activities-section {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 32px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 24px;
        font-weight: 600;
        color: #1f2937;
    }

    .activities-grid {
        display: grid;
        gap: 20px;
    }

    .activity-card {
        background: #f9fafb;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .activity-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .activity-header {
        display: flex;
        align-items: center;
        margin-bottom: 16px;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        font-size: 18px;
        color: white;
    }

    .activity-icon.assignment {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }

    .activity-icon.exam {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .activity-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .activity-type {
        font-size: 12px;
        color: #6b7280;
        text-transform: uppercase;
        font-weight: 600;
    }

    .activity-description {
        color: #4b5563;
        margin-bottom: 16px;
        line-height: 1.5;
    }

    .activity-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .activity-weightage {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .activity-date {
        font-size: 14px;
        color: #6b7280;
    }

    .activity-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-active {
        background: #dcfce7;
        color: #16a34a;
    }

    .status-due-soon {
        background: #fef3c7;
        color: #d97706;
    }

    .status-overdue {
        background: #fee2e2;
        color: #dc2626;
    }

    .status-upcoming {
        background: #dbeafe;
        color: #2563eb;
    }

    .status-completed {
        background: #e5e7eb;
        color: #6b7280;
    }

    .activity-actions {
        display: flex;
        gap: 12px;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #4b5563;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .empty-activities {
        text-align: center;
        padding: 40px;
        color: #6b7280;
    }

    .empty-activities-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .module-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 14px;
        color: #6b7280;
    }

    @media (max-width: 768px) {
        .module-stats {
            grid-template-columns: 1fr;
        }

        .activity-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
    }
</style>

<div class="module-container">
    <div class="module-header">
        <h1 class="module-title">{{ $module->title }}</h1>
        <div class="module-course">Course: {{ $module->course->name }}</div>
        <p class="module-description">{{ $module->description }}</p>
        <div class="module-weightage">Weightage: {{ $module->weightage ?? 0 }}%</div>
    </div>

    <div class="module-stats">
        <div class="stat-card">
            <div class="stat-value">{{ $module->assignments->count() }}</div>
            <div class="stat-label">Assignments</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $module->exams->count() }}</div>
            <div class="stat-label">Exams</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $module->course->students->count() }}</div>
            <div class="stat-label">Students</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $module->weightage ?? 0 }}%</div>
            <div class="stat-label">Module Weightage</div>
        </div>
    </div>

    @if($module->model_unit_outline)
        <div class="module-outline">
            <h3 class="outline-title">Model Unit Outline</h3>
            <div class="outline-content">{{ $module->model_unit_outline }}</div>
        </div>
    @endif

    <div class="activities-section" id="unit-outline">
        <div class="section-header">
            <h2 class="section-title">Unit Outline</h2>
            <button type="button" class="btn btn-primary" onclick="showAddUnitForm()">+ Add Unit</button>
        </div>

        <div id="add-unit-form" style="display: none; margin-bottom: 24px; padding: 20px; background: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb;">
            <form method="POST" action="{{ route('teacher.modules.units.store', $module) }}" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">Unit Title *</label>
                    <input type="text" name="title" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;" placeholder="e.g., Chapter 1: Introduction">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 420px; gap: 16px;">
                    <div>
                        <div style="margin-bottom: 12px;">
                            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #1f2937;">Max marks</label>
                            <input type="number" name="max_marks" value="100" min="0" style="width: 160px; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px;">
                        </div>



                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #1f2937;">Learning objectives & description</label>
                            <textarea name="description" rows="6" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px;" placeholder="Describe what students should achieve — concepts covered, expected outcomes, and any special instructions..."></textarea>
                        </div>
                    </div>

                    <div>
                        <div style="background:#fff; border:1px solid #e5e7eb; padding:12px; border-radius:8px; margin-bottom:12px;">
                            <div style="font-weight:700; margin-bottom:8px;">Grade Scale</div>
                            <div style="display:grid; grid-template-columns:1fr 80px 80px; gap:8px; align-items:center">
                                <div>A</div>
                                <input name="grade_scale[a_min]" type="number" value="80" class="form-input">
                                <input name="grade_scale[a_max]" type="number" value="100" class="form-input">
                                <div>B</div>
                                <input name="grade_scale[b_min]" type="number" value="65" class="form-input">
                                <input name="grade_scale[b_max]" type="number" value="79" class="form-input">
                                <div>C</div>
                                <input name="grade_scale[c_min]" type="number" value="50" class="form-input">
                                <input name="grade_scale[c_max]" type="number" value="64" class="form-input">
                                <div>D</div>
                                <input name="grade_scale[d_min]" type="number" value="40" class="form-input">
                                <input name="grade_scale[d_max]" type="number" value="49" class="form-input">
                                <div>E</div>
                                <input name="grade_scale[e_min]" type="number" value="30" class="form-input">
                                <input name="grade_scale[e_max]" type="number" value="39" class="form-input">
                                <div>F</div>
                                <input name="grade_scale[f_min]" type="number" value="0" class="form-input">
                                <input name="grade_scale[f_max]" type="number" value="29" class="form-input">
                            </div>
                        </div>

                        <div style="background:#fff; border:1px solid #e5e7eb; padding:12px; border-radius:8px;">
                            <div style="font-weight:700; margin-bottom:8px;">Grading Criteria</div>
                            <div id="criteria-list">
                                <div class="criterion-row" data-index="0" style="display:grid; grid-template-columns: 1fr 1fr 80px; gap:8px; margin-bottom:8px; align-items:center;">
                                    <input type="text" class="criterion-name" placeholder="Conceptual understanding" value="Conceptual understanding" style="padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                                    <input type="text" class="criterion-desc" placeholder="Short description" value="Shows understanding of core concepts" style="padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                                    <div style="display:flex; gap:8px; align-items:center;"><input type="number" class="criterion-weight" value="30" min="0" style="width:80px; padding:8px; border:1px solid #d1d5db; border-radius:6px;"><button type="button" class="btn btn-secondary remove-criterion">×</button></div>
                                </div>
                            </div>
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px; margin-top:8px; align-items:center;">
                                <input id="new-criterion-name" placeholder="Criterion name" style="padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                                <input id="new-criterion-desc" placeholder="Short description (optional)" style="padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                            </div>
                            <div style="display:flex; gap:8px; margin-top:8px; align-items:center;"><button type="button" id="add-criterion-btn" class="btn btn-primary">+ Add Criterion</button></div>
                            <div style="margin-top:8px;">Total weight: <span id="total-weight">30</span>%</div>
                        </div>
                    </div>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">Description</label>
                    <textarea name="description" rows="3" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;" placeholder="Unit description and topics covered..."></textarea>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">Import Unit Outline File</label>
                    <label class="upload-box" style="display:block; border:2px dashed #e5e7eb; border-radius:8px; padding:24px; text-align:center; cursor:pointer;">
                        <div style="font-size:22px; color:#6b7280;">📁</div>
                        <div style="margin-top:8px; font-weight:600;">Upload unit outline file</div>
                        <div style="font-size:12px; color:#9ca3af; margin-top:6px;">PDF, DOC, DOCX, TXT — AI will use this for auto-grading</div>
                        <input type="file" name="unit_file" accept=".pdf,.doc,.docx,.txt" style="display:none">
                    </label>
                </div>
                <input type="hidden" name="grading_criteria" id="grading_criteria_input">
                <input type="hidden" name="grade_scale" id="grade_scale_input">

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">Order</label>
                    <input type="number" name="order" value="{{ ($module->units->max('order') ?? 0) + 1 }}" style="width: 100px; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-success">Save Unit</button>
                    <button type="button" class="btn btn-secondary" onclick="hideAddUnitForm()">Cancel</button>
                </div>
            </form>
        </div>

        <div id="edit-unit-form" style="display: none; margin-bottom: 24px; padding: 20px; background: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb;">
            <form id="unit-edit-form" method="POST" action="{{ route('teacher.units.update', ['unit' => '__UNIT__']) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">Unit Title *</label>
                    <input type="text" id="edit-unit-title" name="title" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">Description</label>
                    <textarea id="edit-unit-description" name="description" rows="3" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;"></textarea>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">Replace Unit Outline File</label>
                    <label class="upload-box" style="display:block; border:2px dashed #e5e7eb; border-radius:8px; padding:24px; text-align:center; cursor:pointer;">
                        <div style="font-size:22px; color:#6b7280;">📁</div>
                        <div style="margin-top:8px; font-weight:600;">Upload unit outline file</div>
                        <div style="font-size:12px; color:#9ca3af; margin-top:6px;">PDF, DOC, DOCX, TXT — AI will use this for auto-grading</div>
                        <input type="file" name="unit_file" accept=".pdf,.doc,.docx,.txt" onchange="handleFileUpload(this)" style="display:none">
                    </label>
                    <div id="edit-file-info" style="margin-top: 8px; color: #10b981; font-size: 13px;"></div>
                    <small style="color: #6b7280; display: block; margin-top: 6px;">Leave empty to keep the current file.</small>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">Order</label>
                    <input type="number" id="edit-unit-order" name="order" min="0" style="width: 100px; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-success">Update Unit</button>
                    <button type="button" class="btn btn-secondary" onclick="cancelEditUnit()">Cancel</button>
                </div>
            </form>
        </div>

        @if($module->units->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach($module->units as $unit)
                    <div class="activity-card" id="unit-{{ $unit->id }}">
                        <div class="activity-header">
                            <div class="activity-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                                {{ $unit->order }}
                            </div>
                            <div style="flex: 1;">
                                <div class="activity-title">{{ $unit->title }}</div>
                                @if($unit->description)
                                    <div class="activity-type">{{ Str::limit($unit->description, 100) }}</div>
                                @endif
                                @if($unit->file_path)
                                    <div class="activity-type" style="margin-top: 6px; color: #2563eb;">
                                        File: {{ basename($unit->file_path) }}
                                    </div>
                                @endif
                                @if($unit->extracted_content)
                                    <div class="activity-type" style="margin-top: 6px; color: #6b7280;">
                                        Outline: {{ Str::limit($unit->extracted_content, 120) }}
                                    </div>
                                @endif
                            </div>
                            <div class="activity-actions">
                                <button type="button" class="btn btn-secondary" onclick="editUnit({{ $unit->id }}, @json($unit->title), @json($unit->description ?? ''), {{ $unit->order }})">Edit</button>
                                <form method="POST" action="{{ route('teacher.units.destroy', $unit) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary" style="background: #fee2e2; color: #dc2626;" onclick="return confirm('Are you sure you want to delete this unit?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-activities">
                <div class="empty-activities-icon">📚</div>
                <p>No units added yet. Click "+ Add Unit" to create your unit outline.</p>
            </div>
        @endif
    </div>

    <div class="activities-section">
        <div class="section-header">
            <h2 class="section-title">Module Activities</h2>
            <div>
                <button onclick="openAssignmentModal()" class="btn btn-primary">+ Assignment</button>
                <a href="{{ route('teacher.exams.create', ['module_id' => $module->id]) }}" class="btn btn-success">+ Exam</a>
            </div>
        </div>

        <!-- Tabs Container -->
        <div style="margin-top: 24px;">
            <div style="display: flex; gap: 8px; border-bottom: 2px solid #e5e7eb; background: #f9fafb; padding: 0;">
                <button class="activity-tab-btn active" onclick="switchActivityTab(event, 'assignments')" style="flex: 1; padding: 16px; text-align: center; border: none; background: none; cursor: pointer; font-size: 15px; font-weight: 600; color: #64748b; border-bottom: 4px solid transparent; margin-bottom: -2px;">
                    📝 Assignments ({{ $activities->where('type', 'assignment')->count() }})
                </button>
                <button class="activity-tab-btn" onclick="switchActivityTab(event, 'exams')" style="flex: 1; padding: 16px; text-align: center; border: none; background: none; cursor: pointer; font-size: 15px; font-weight: 600; color: #64748b; border-bottom: 4px solid transparent; margin-bottom: -2px;">
                    🧪 Exams ({{ $activities->where('type', 'exam')->count() }})
                </button>
                <button class="activity-tab-btn" onclick="switchActivityTab(event, 'recent')" style="flex: 1; padding: 16px; text-align: center; border: none; background: none; cursor: pointer; font-size: 15px; font-weight: 600; color: #64748b; border-bottom: 4px solid transparent; margin-bottom: -2px;">
                    📅 Recent Activity
                </button>
            </div>

            <style>
                .activity-tab-btn.active {
                    color: #667eea !important;
                    border-bottom-color: #667eea !important;
                    background: white !important;
                }
                .activity-tab-btn:hover {
                    color: #667eea;
                    background: #f0f4ff;
                }
                .activity-tab-pane {
                    display: none;
                    padding: 24px;
                }
                .activity-tab-pane.active {
                    display: block;
                }
            </style>

            <!-- Assignments Tab -->
            <div id="assignments" class="activity-tab-pane active">
                @php
                    $assignments = $activities->where('type', 'assignment');
                @endphp
                @if($assignments->count() > 0)
                    <div class="activities-grid">
                        @foreach($assignments as $activity)
                            <div class="activity-card">
                                <div class="activity-header">
                                    <div class="activity-icon assignment">A</div>
                                    <div>
                                        <div class="activity-title">{{ $activity['title'] }}</div>
                                        <div class="activity-type">{{ ucfirst($activity['type']) }}</div>
                                    </div>
                                </div>

                                <div class="activity-description">
                                    {{ Str::limit($activity['description'], 150) }}
                                </div>

                                <div class="activity-meta">
                                    <div class="activity-weightage">Weightage: {{ $activity['weightage'] }}%</div>
                                    <div class="activity-date">
                                        Due: {{ $activity['due_date'] ? \Carbon\Carbon::parse($activity['due_date'])->format('d/m/Y') : 'No due date' }}
                                    </div>
                                </div>

                                <div class="activity-status status-{{ $activity['status'] }}">
                                    {{ ucfirst(str_replace('_', ' ', $activity['status'])) }}
                                </div>

                                <div class="activity-actions">
                                    <a href="{{ route('teacher.assignments.show', $activity['id']) }}" class="btn btn-primary">View & Grade</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-activities">
                        <div class="empty-activities-icon">📝</div>
                        <p>No assignments yet. Click "+ Assignment" to create one.</p>
                    </div>
                @endif
            </div>

            <!-- Exams Tab -->
            <div id="exams" class="activity-tab-pane">
                @php
                    $exams = $activities->where('type', 'exam');
                @endphp
                @if($exams->count() > 0)
                    <div class="activities-grid">
                        @foreach($exams as $activity)
                            <div class="activity-card">
                                <div class="activity-header">
                                    <div class="activity-icon exam">E</div>
                                    <div>
                                        <div class="activity-title">{{ $activity['title'] }}</div>
                                        <div class="activity-type">{{ ucfirst($activity['type']) }}</div>
                                    </div>
                                </div>

                                <div class="activity-description">
                                    {{ Str::limit($activity['description'], 150) }}
                                </div>

                                <div class="activity-meta">
                                    <div class="activity-weightage">Weightage: {{ $activity['weightage'] }}%</div>
                                    <div class="activity-date">
                                        Date: {{ $activity['exam_date'] ? \Carbon\Carbon::parse($activity['exam_date'])->format('d/m/Y') : 'No date set' }}
                                    </div>
                                </div>

                                <div class="activity-status status-{{ $activity['status'] }}">
                                    {{ ucfirst(str_replace('_', ' ', $activity['status'])) }}
                                </div>

                                <div class="activity-actions">
                                    <a href="{{ route('teacher.exams.show', $activity['id']) }}" class="btn btn-primary">View</a>
                                    <a href="{{ route('teacher.exams.edit', $activity['id']) }}" class="btn btn-secondary">Edit</a>
                                    <a href="{{ route('teacher.exams.show', $activity['id']) }}" class="btn btn-success">Results</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-activities">
                        <div class="empty-activities-icon">🧪</div>
                        <p>No exams yet. Click "+ Exam" to create one.</p>
                    </div>
                @endif
            </div>

            <!-- Recent Activity Tab -->
            <div id="recent" class="activity-tab-pane">
                @if($activities->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @foreach($activities->sortByDesc('created_at')->take(10) as $activity)
                            <div class="activity-card" style="padding: 16px; margin-bottom: 0;">
                                <div class="activity-header">
                                    <div class="activity-icon {{ $activity['type'] }}" style="width: 36px; height: 36px; font-size: 16px;">
                                        {{ $activity['type'] == 'assignment' ? '📝' : '🧪' }}
                                    </div>
                                    <div style="flex: 1;">
                                        <div class="activity-title" style="margin-bottom: 2px;">{{ $activity['title'] }}</div>
                                        <div class="activity-type">{{ ucfirst($activity['type']) }} created {{ $activity['created_at']->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-activities">
                        <div class="empty-activities-icon">📅</div>
                        <p>No activity yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Assignment Modal -->
    <style>
        .assignment-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .assignment-modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 32px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6b7280;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            color: #1f2937;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #6b7280;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>

    <div id="assignmentModal" class="assignment-modal">
        <div class="modal-content">
            <div class="modal-header">
                <span>Create New Assignment</span>
                <button class="modal-close" onclick="closeAssignmentModal()">✕</button>
            </div>

            <form id="assignmentForm" onsubmit="submitAssignmentForm(event)">
                @csrf
                <input type="hidden" name="module_id" value="{{ $module->id }}">

                <div class="form-group">
                    <label for="course_id">Course *</label>
                    <select id="course_id" name="course_id" required>
                        <option value="{{ $module->course_id }}">{{ $module->course->name }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" placeholder="Assignment title" required>
                </div>

                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" placeholder="Describe the assignment" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label for="instructions">Instructions</label>
                    <textarea id="instructions" name="instructions" placeholder="Special instructions for students" rows="2"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="type">Type *</label>
                        <select id="type" name="type" required>
                            <option value="">Select type</option>
                            <option value="essay">Essay</option>
                            <option value="project">Project</option>
                            <option value="quiz">Quiz</option>
                            <option value="presentation">Presentation</option>
                            <option value="homework">Homework</option>
                            <option value="lab">Lab</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="due_date">Due Date *</label>
                        <input type="datetime-local" id="due_date" name="due_date" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="max_score">Max Score *</label>
                        <input type="number" id="max_score" name="max_score" value="100" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="weightage">Weightage (%)</label>
                        <input type="number" id="weightage" name="weightage" value="0" min="0" max="100" step="0.01">
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeAssignmentModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Assignment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activity card interactions
        const activityCards = document.querySelectorAll('.activity-card');

        activityCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>

@push('scripts')
<script>
function showAddUnitForm() {
    document.getElementById('add-unit-form').style.display = 'block';
    document.getElementById('edit-unit-form').style.display = 'none';
}

function hideAddUnitForm() {
    document.getElementById('add-unit-form').style.display = 'none';
}

function handleFileUpload(input) {
    const fileInfo = document.getElementById('file-info');
    const editFileInfo = document.getElementById('edit-file-info');
    if (input.files && input.files[0]) {
        const fileSizeKB = (input.files[0].size / 1024).toFixed(2);
        const displayText = `✓ File selected: ${input.files[0].name} (${fileSizeKB} KB)`;
        if (fileInfo) fileInfo.textContent = displayText;
        if (editFileInfo) editFileInfo.textContent = displayText;
    } else {
        if (fileInfo) fileInfo.textContent = '';
        if (editFileInfo) editFileInfo.textContent = '';
    }
}

function editUnit(unitId, title, description, order) {
    document.getElementById('add-unit-form').style.display = 'none';
    document.getElementById('edit-unit-form').style.display = 'block';
    document.getElementById('edit-unit-title').value = title;
    document.getElementById('edit-unit-description').value = description || '';
    document.getElementById('edit-unit-order').value = order;

    const editForm = document.getElementById('unit-edit-form');
    editForm.dataset.baseAction = editForm.dataset.baseAction || editForm.action;
    editForm.action = editForm.dataset.baseAction.replace('__UNIT__', unitId);
    document.getElementById('edit-unit-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function cancelEditUnit() {
    document.getElementById('edit-unit-form').style.display = 'none';
}

function openAssignmentModal() {
    document.getElementById('assignmentModal').style.display = 'flex';
}

// --- Unit outline helpers ---
(function() {
    function updateTotal() {
        const weights = Array.from(document.querySelectorAll('.criterion-weight')).map(i => parseFloat(i.value) || 0);
        const total = weights.reduce((s, v) => s + v, 0);
        document.getElementById('total-weight').textContent = total;
    }

    function serializeCriteria() {
        const rows = Array.from(document.querySelectorAll('#criteria-list .criterion-row'));
        const items = rows.map(r => ({
            name: r.querySelector('.criterion-name').value || '',
            description: (r.querySelector('.criterion-desc') ? r.querySelector('.criterion-desc').value : ''),
            weight: parseFloat(r.querySelector('.criterion-weight').value) || 0
        }));
        document.getElementById('grading_criteria_input').value = JSON.stringify(items);
    }

    function serializeGradeScale() {
        const fields = ['a','b','c','d','e','f'];
        const scale = {};
        fields.forEach(f => {
            scale[f+'_min'] = document.querySelector(`[name="grade_scale[${f}_min]"]`).value || '';
            scale[f+'_max'] = document.querySelector(`[name="grade_scale[${f}_max]"]`).value || '';
        });
        document.getElementById('grade_scale_input').value = JSON.stringify(scale);
    }

    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'add-criterion-btn') {
            const name = document.getElementById('new-criterion-name').value.trim();
            if (!name) return;
            const desc = document.getElementById('new-criterion-desc') ? document.getElementById('new-criterion-desc').value.trim() : '';
            const weightInput = document.getElementById('new-criterion-weight');
            const weight = weightInput ? (parseFloat(weightInput.value) || 0) : 0;
            const list = document.getElementById('criteria-list');
            const idx = list.children.length;
            const row = document.createElement('div');
            row.className = 'criterion-row';
            row.dataset.index = idx;
            row.style.display = 'grid'; row.style.gridTemplateColumns = '1fr 1fr 80px'; row.style.gap = '8px'; row.style.marginBottom = '8px'; row.style.alignItems = 'center';
            row.innerHTML = `<input type="text" class="criterion-name" placeholder="${name}" value="${name}" style="padding:8px; border:1px solid #d1d5db; border-radius:6px;"><input type="text" class="criterion-desc" placeholder="Short description" value="${desc}" style="padding:8px; border:1px solid #d1d5db; border-radius:6px;"><div style=\"display:flex; gap:8px; align-items:center;\"><input type=\"number\" class=\"criterion-weight\" value=\"10\" min=\"0\" style=\"width:80px; padding:8px; border:1px solid #d1d5db; border-radius:6px;\"><button type=\"button\" class=\"btn btn-secondary remove-criterion\">×</button></div>`;
            list.appendChild(row);
            const addedWeightInput = row.querySelector('.criterion-weight');
            if (addedWeightInput) addedWeightInput.value = weight;
            document.getElementById('new-criterion-name').value = '';
            if(document.getElementById('new-criterion-desc')) document.getElementById('new-criterion-desc').value = '';
            if (weightInput) weightInput.value = '10';
            updateTotal(); serializeCriteria();
        }

        if (e.target && e.target.classList && e.target.classList.contains('remove-criterion')) {
            const row = e.target.closest('.criterion-row');
            if (row) { row.remove(); updateTotal(); serializeCriteria(); }
        }

        // content type selection removed; page is for unit outlines only
    });

    document.addEventListener('input', function(e) {
        if (e.target && e.target.classList && e.target.classList.contains('criterion-weight')) {
            updateTotal(); serializeCriteria();
        }

        if (e.target && e.target.name && e.target.name.startsWith('grade_scale')) {
            serializeGradeScale();
        }
    });

    // initial serialize
    document.addEventListener('DOMContentLoaded', function() {
        updateTotal(); serializeCriteria(); serializeGradeScale();
    });
})();

function closeAssignmentModal() {
    document.getElementById('assignmentModal').style.display = 'none';
    document.getElementById('assignmentForm').reset();
}

function submitAssignmentForm(e) {
    e.preventDefault();

    const form = document.getElementById('assignmentForm');
    const formData = new FormData(form);

    fetch('{{ route("teacher.assignments.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            closeAssignmentModal();
            showSuccessMessage('Assignment created successfully!');
            setTimeout(() => window.location.href = '{{ route("teacher.assignments.index") }}', 1500);
        } else {
            return response.json().then(data => {
                showErrorMessage(data.message || 'Failed to create assignment');
            });
        }
    })
    .catch(error => {
        showErrorMessage('Error creating assignment: ' + error.message);
    });
}

function showSuccessMessage(message) {
    const notification = document.createElement('div');
    notification.className = 'success-notification';
    notification.textContent = '✓ ' + message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #10b981;
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

function showErrorMessage(message) {
    const notification = document.createElement('div');
    notification.className = 'error-notification';
    notification.textContent = '✗ ' + message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #ef4444;
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('assignmentModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeAssignmentModal();
            }
        });
    }
});

function openAssignmentModal() {
    document.getElementById('assignmentModal').style.display = 'flex';
}

function switchActivityTab(event, tabName) {
    event.preventDefault();

    // Hide all tab panes
    const panes = document.querySelectorAll('.activity-tab-pane');
    panes.forEach(pane => pane.classList.remove('active'));

    // Remove active class from all buttons
    const buttons = document.querySelectorAll('.activity-tab-btn');
    buttons.forEach(btn => btn.classList.remove('active'));

    // Show the selected tab pane
    const selectedPane = document.getElementById(tabName);
    if (selectedPane) {
        selectedPane.classList.add('active');
    }

    // Add active class to the clicked button
    event.target.classList.add('active');
}

