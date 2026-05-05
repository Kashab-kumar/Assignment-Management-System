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
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">Description</label>
                    <textarea name="description" rows="3" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;" placeholder="Unit description and topics covered..."></textarea>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">Import Unit Outline File (PDF, DOC, DOCX, TXT)</label>
                    <input type="file" name="unit_file" accept=".pdf,.doc,.docx,.txt" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <small style="color: #6b7280;">Upload a file containing the unit outline. The content will be extracted and used for AI marking.</small>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">Order</label>
                    <input type="number" name="order" value="{{ $module->units->count() + 1 }}" style="width: 100px; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn btn-success">Save Unit</button>
                    <button type="button" class="btn btn-secondary" onclick="hideAddUnitForm()">Cancel</button>
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
                            </div>
                            <div class="activity-actions">
                                <button type="button" class="btn btn-secondary" onclick="editUnit({{ $unit->id }}, '{{ $unit->title }}', '{{ $unit->description ?? '' }}', {{ $unit->order }})">Edit</button>
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

        @if($activities->count() > 0)
            <div class="activities-grid">
                @foreach($activities as $activity)
                    <div class="activity-card">
                        <div class="activity-header">
                            <div class="activity-icon {{ $activity['type'] }}">
                                {{ $activity['type'] == 'assignment' ? 'A' : 'E' }}
                            </div>
                            <div>
                                <div class="activity-title">{{ $activity['title'] }}</div>
                                <div class="activity-type">{{ $activity['type'] }}</div>
                            </div>
                        </div>

                        <div class="activity-description">
                            {{ Str::limit($activity['description'], 150) }}
                        </div>

                        <div class="activity-meta">
                            <div class="activity-weightage">Weightage: {{ $activity['weightage'] }}%</div>
                            <div class="activity-date">
                                @if($activity['type'] == 'assignment')
                                    Due: {{ $activity['due_date'] ? \Carbon\Carbon::parse($activity['due_date'])->format('M d, Y') : 'No due date' }}
                                @else
                                    Date: {{ $activity['exam_date'] ? \Carbon\Carbon::parse($activity['exam_date'])->format('M d, Y') : 'No date set' }}
                                @endif
                            </div>
                        </div>

                        <div class="activity-status status-{{ $activity['status'] }}">
                            {{ ucfirst(str_replace('_', ' ', $activity['status'])) }}
                        </div>

                        <div class="activity-actions">
                            @if($activity['type'] == 'assignment')
                                <a href="{{ route('teacher.assignments.show', $activity['id']) }}" class="btn btn-primary">View & Grade</a>
                            @else
                                <a href="{{ route('teacher.exams.show', $activity['id']) }}" class="btn btn-primary">View</a>
                                <a href="{{ route('teacher.exams.edit', $activity['id']) }}" class="btn btn-secondary">Edit</a>
                                <a href="{{ route('teacher.exams.show', $activity['id']) }}" class="btn btn-success">Results</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-activities">
                <div class="empty-activities-icon">📚</div>
                <h3>No Activities Yet</h3>
                <p>Create assignments and exams for this module to get started</p>
                <div>
                    <button onclick="openAssignmentModal()" class="btn btn-primary">Create Assignment</button>
                    <a href="{{ route('teacher.exams.create', ['module_id' => $module->id]) }}" class="btn btn-success">Create Exam</a>
                </div>
            </div>
        @endif
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
}

function hideAddUnitForm() {
    document.getElementById('add-unit-form').style.display = 'none';
}

function editUnit(unitId, title, description, order) {
    const unitCard = document.getElementById('unit-' + unitId);
    const currentContent = unitCard.innerHTML;

    unitCard.innerHTML = `
        <form method="POST" action="{{ route('teacher.units.update', ':unitId') }}" style="width: 100%;">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">Unit Title *</label>
                <input type="text" name="title" value="${title}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">Description</label>
                <textarea name="description" rows="3" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">${description}</textarea>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1f2937;">Order</label>
                <input type="number" name="order" value="${order}" style="width: 100px; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-success">Update Unit</button>
                <button type="button" class="btn btn-secondary" onclick="cancelEditUnit(${unitId}, \`${currentContent.replace(/`/g, '\\`').replace(/\n/g, '')}\`)">Cancel</button>
            </div>
        </form>
    `;

    // Replace the unitId placeholder in the form action
    unitCard.querySelector('form').action = unitCard.querySelector('form').action.replace(':unitId', unitId);
}

function cancelEditUnit(unitId, originalContent) {
    document.getElementById('unit-' + unitId).innerHTML = originalContent;
}

function openAssignmentModal() {
    document.getElementById('assignmentModal').style.display = 'flex';
}

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

