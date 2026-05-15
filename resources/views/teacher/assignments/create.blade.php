@extends('layouts.teacher')

@section('title', 'Create Assignment')
@section('page-title', 'Create New Assignment')

@section('content')
<style>
    .assignment-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }

    .assignment-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 32px;
        color: white;
        text-align: center;
    }

    .assignment-header h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .assignment-header p {
        font-size: 16px;
        opacity: 0.9;
    }

    .assignment-form {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #1f2937;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 16px;
        min-height: 120px;
        resize: vertical;
        transition: border-color 0.3s ease;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 16px;
        background: white;
        transition: border-color 0.3s ease;
    }

    .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .weightage-input {
        position: relative;
    }

    .weightage-input .form-input {
        padding-right: 40px;
    }

    .weightage-suffix {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-weight: 600;
    }

    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        margin-top: 32px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 16px;
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

    .help-text {
        font-size: 14px;
        color: #6b7280;
        margin-top: 4px;
    }

    .upload-box {
        display: block;
        width: 100%;
        box-sizing: border-box;
        border: 2px dashed #d1d5db;
        border-radius: 14px;
        background: linear-gradient(180deg, #fbfbff 0%, #ffffff 100%);
        padding: 28px 18px;
        text-align: center;
        transition: border-color 0.2s ease, background 0.2s ease, transform 0.2s ease;
        cursor: pointer;
    }

    .upload-box:hover {
        border-color: #7c3aed;
        background: #faf5ff;
        transform: translateY(-1px);
    }

    .upload-icon {
        width: 54px;
        height: 54px;
        margin: 0 auto 14px;
        border-radius: 16px;
        display: grid;
        place-items: center;
        background: rgba(124, 58, 237, 0.12);
        color: #7c3aed;
        font-size: 24px;
    }

    .upload-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .upload-subtitle {
        color: #6b7280;
        font-size: 14px;
    }

    .file-name {
        margin-top: 12px;
        font-size: 14px;
        color: #4b5563;
        font-weight: 600;
    }

    .error {
        color: #dc2626;
        font-size: 14px;
        margin-top: 4px;
    }

    .alert-danger {
        padding: 16px;
        background: #fee2e2;
        color: #dc2626;
        border-radius: 8px;
        margin-bottom: 24px;
        border: 1px solid #fecaca;
    }

    .module-info {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .module-info h4 {
        color: #0369a1;
        margin-bottom: 8px;
    }

    .module-info p {
        color: #0c4a6e;
        margin: 0;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>

<div class="assignment-container">
    <div class="assignment-header">
        <h1>Create Assignment</h1>
        <p>Create a new assignment for your module with weightage and instructions</p>
    </div>

    @if($errors->any())
        <div class="alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 10px 0 0 20px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(request('module_id'))
        @php
            $module = \App\Models\CourseModule::find(request('module_id'));
        @endphp
        @if($module)
            <div class="module-info">
                <h4>Creating assignment for: {{ $module->title }}</h4>
                <p>Course: {{ $module->course->name }} | Module Weightage: {{ $module->weightage }}%</p>
            </div>
        @endif
    @endif

    <form class="assignment-form" method="POST" action="{{ route('teacher.assignments.store') }}" enctype="multipart/form-data">
        @csrf

        @if(request('module_id'))
            <input type="hidden" name="module_id" value="{{ request('module_id') }}">
        @endif

        <div class="form-group">
            <label class="form-label" for="course_id">Course *</label>
            <select class="form-select" id="course_id" name="course_id" required>
                <option value="">Select a course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id', $selectedCourseId) == $course->id ? 'selected' : '' }}>
                        {{ $course->category_name ?: 'Uncategorized' }} / {{ $course->class_name ?: 'Unassigned' }} / {{ $course->name }}
                    </option>
                @endforeach
            </select>
            @error('course_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="module_id">Module *</label>
            <select class="form-select" id="module_id" name="module_id" required>
                <option value="">Select a module</option>
                @php
                    $teacher = auth()->user()->teacher;
                    $modules = \App\Models\CourseModule::where('teacher_id', $teacher->id)->with('units')->get();
                @endphp
                @foreach($modules as $module)
                    <option value="{{ $module->id }}" data-course-id="{{ $module->course_id }}" data-units='@json($module->units)' {{ old('module_id', $selectedModuleId) == $module->id ? 'selected' : '' }}>
                        {{ $module->title }} - {{ $module->course->name }}
                    </option>
                @endforeach
            </select>
            @error('module_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="unit_id">Chapter/Unit *</label>
            <select class="form-select" id="unit_id" name="unit_id" required>
                <option value="">Select a chapter/unit</option>
            </select>
            <div class="help-text">This assignment will appear under the selected chapter/unit in the student page.</div>
            @error('unit_id')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group" id="topics-group" style="display: none;">
            <label class="form-label">Topics Covered by This Assignment</label>
            <div id="topics-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 12px; padding: 12px; background: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb;">
                <p style="grid-column: 1/-1; color: #6b7280; font-size: 14px; margin: 0;">Select which topics from the chapter are covered by this assignment</p>
            </div>
            <div class="help-text">Topics will appear in the unit outline checklist, helping track assignment coverage</div>
            @error('covered_topics')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group" id="questionbank-group" style="display: none;">
            <label class="form-label">Pick Questions from Question Bank</label>
            <div id="questionbank-container" style="display: grid; grid-template-columns: 1fr; gap: 8px; padding: 8px; border-radius: 6px; border:1px solid #e5e7eb; background:#fff;">
                <p style="color:#6b7280; margin:0;">Select questions from the question bank for this assignment (optional)</p>
            </div>
            <div class="help-text">You can choose existing questions from the bank to include in this assignment.</div>
            @error('selected_questions')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group" id="questionbank-controls" style="display:none; margin-top:6px;">
            <input type="search" id="qb-search" placeholder="Search questions..." class="form-input" style="width:48%; display:inline-block;">
            <select id="qb-perpage" class="form-select" style="width:100px; display:inline-block; margin-left:8px;">
                <option value="5">5 / page</option>
                <option value="10" selected>10 / page</option>
                <option value="25">25 / page</option>
            </select>
            <button type="button" id="qb-checkall" class="btn btn-secondary" style="margin-left:12px;">Check all</button>
            <div id="qb-pagination" style="display:inline-block; margin-left:12px; color:#6b7280;"></div>
        </div>

        <div class="form-group">
            <label class="form-label" for="title">Assignment Title *</label>
            <input type="text" class="form-input" id="title" name="title" value="{{ old('title') }}" required>
            <div class="help-text">Enter a descriptive title for this assignment</div>
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="description">Description *</label>
            <textarea class="form-textarea" id="description" name="description" required>{{ old('description') }}</textarea>
            <div class="help-text">Provide a detailed description of the assignment requirements</div>
            @error('description')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="instructions">Instructions</label>
            <textarea class="form-textarea" id="instructions" name="instructions" rows="6">{{ old('instructions') }}</textarea>
            <div class="help-text">Detailed instructions for students on how to complete the assignment</div>
            @error('instructions')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-label">Instruction File</div>
            <label class="upload-box" for="instruction_file">
                <div class="upload-icon">☁</div>
                <div class="upload-title">Click to upload or drag and drop</div>
                <div class="upload-subtitle">PDF, Word, PowerPoint, Excel, or image files up to 10MB</div>
                <div id="instruction-file-name" class="file-name">No file selected</div>
            </label>
            <input type="file" id="instruction_file" name="instruction_file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png,.webp" style="display:none;">
            <div class="help-text">Upload a file containing the assignment brief, rubric, or supporting material.</div>
            @error('instruction_file')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="type">Assignment Type *</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="">Select type</option>
                    <option value="essay" {{ old('type') == 'essay' ? 'selected' : '' }}>Essay</option>
                    <option value="project" {{ old('type') == 'project' ? 'selected' : '' }}>Project</option>
                    <option value="quiz" {{ old('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                    <option value="presentation" {{ old('type') == 'presentation' ? 'selected' : '' }}>Presentation</option>
                    <option value="homework" {{ old('type') == 'homework' ? 'selected' : '' }}>Homework</option>
                    <option value="lab" {{ old('type') == 'lab' ? 'selected' : '' }}>Lab Work</option>
                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('type')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="max_score">Maximum Score *</label>
                <input type="number" class="form-input" id="max_score" name="max_score" value="{{ old('max_score', 100) }}" min="1" required>
                <div class="help-text">Maximum points students can earn</div>
                @error('max_score')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="weightage">Weightage (%) *</label>
                <div class="weightage-input">
                    <input type="number" class="form-input" id="weightage" name="weightage" value="{{ old('weightage') }}" min="0" max="100" step="0.1" required>
                    <span class="weightage-suffix">%</span>
                </div>
                <div class="help-text">Weightage of this assignment in the module grade</div>
                @error('weightage')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="due_date">Due Date *</label>
                <input type="datetime-local" class="form-input" id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                <div class="help-text">When students must submit their work</div>
                @error('due_date')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ request('module_id') ? route('teacher.modules.show', request('module_id')) : route('teacher.modules.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Assignment</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('.assignment-form');
    const weightageInput = document.getElementById('weightage');
    const maxScoreInput = document.getElementById('max_score');
    const dueDateInput = document.getElementById('due_date');
    const moduleSelect = document.getElementById('module_id');
    const courseSelect = document.getElementById('course_id');
    const unitSelect = document.getElementById('unit_id');
    const topicsGroup = document.getElementById('topics-group');
    const topicsContainer = document.getElementById('topics-container');
    const selectedUnitId = {{ $selectedUnitId ?? 'null' }};

    // Set minimum date to today
    const now = new Date();
    const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
    dueDateInput.min = localDateTime;

    weightageInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        if (value < 0) this.value = 0;
        if (value > 100) this.value = 100;
    });

    maxScoreInput.addEventListener('input', function() {
        const value = parseInt(this.value);
        if (value < 1) this.value = 1;
    });

    moduleSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        unitSelect.innerHTML = '<option value="">Select a chapter/unit</option>';
        topicsContainer.innerHTML = '<p style="grid-column: 1/-1; color: #6b7280; font-size: 14px; margin: 0;">Select which topics from the chapter are covered by this assignment</p>';
        topicsGroup.style.display = 'none';

        if (selectedOption.value) {
            courseSelect.value = selectedOption.dataset.courseId || courseSelect.value;

            try {
                const units = JSON.parse(selectedOption.dataset.units || '[]');
                units.forEach(unit => {
                    const option = document.createElement('option');
                    option.value = unit.id;
                    option.textContent = `Chapter/Unit ${unit.order ?? ''}: ${unit.title}`;
                    if (String(unit.id) === String(selectedUnitId)) {
                        option.selected = true;
                    }
                    unitSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error parsing units:', error);
            }
        }
    });

    // Handle unit selection to populate topics
    unitSelect.addEventListener('change', function() {
        if (!this.value) {
            topicsGroup.style.display = 'none';
            topicsContainer.innerHTML = '<p style="grid-column: 1/-1; color: #6b7280; font-size: 14px; margin: 0;">Select which topics from the chapter are covered by this assignment</p>';
            return;
        }

        // Fetch topics for this unit via AJAX (teacher routes are prefixed)
        fetch(`/teacher/api/units/${this.value}/topics`, { credentials: 'same-origin' })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                topicsContainer.innerHTML = '';

                if (!data.topics || data.topics.length === 0) {
                    topicsContainer.innerHTML = '<p style="grid-column: 1/-1; color: #6b7280; font-size: 14px; margin: 0;">No topics defined for this chapter yet</p>';
                    topicsGroup.style.display = 'block';
                    return;
                }

                data.topics.forEach((topic, index) => {
                    const checkbox = document.createElement('label');
                    checkbox.style.cssText = `
                        display: flex;
                        align-items: center;
                        padding: 8px 12px;
                        background: white;
                        border: 1px solid #e5e7eb;
                        border-radius: 6px;
                        cursor: pointer;
                        transition: all 0.2s ease;
                    `;

                    const input = document.createElement('input');
                    input.type = 'checkbox';
                    input.name = 'covered_topics[]';
                    input.value = topic.topic || topic;
                    input.style.marginRight = '8px';
                    input.style.cursor = 'pointer';

                    const topicText = topic.topic || topic;
                    const topicLabel = document.createTextNode(topicText);

                    checkbox.appendChild(input);
                    checkbox.appendChild(topicLabel);

                    checkbox.addEventListener('mouseenter', function() {
                        this.style.backgroundColor = '#f3f4f6';
                        this.style.borderColor = '#d1d5db';
                    });

                    checkbox.addEventListener('mouseleave', function() {
                        this.style.backgroundColor = 'white';
                        this.style.borderColor = '#e5e7eb';
                    });

                    topicsContainer.appendChild(checkbox);
                });

                topicsGroup.style.display = 'block';
            })
            .catch(error => {
                console.error('Error fetching topics:', error);
                topicsContainer.innerHTML = '<div style="grid-column: 1/-1; color: #dc2626; font-size: 14px; margin: 0; padding:10px; background:#fff1f2; border-radius:6px;">Error loading topics</div>';
                topicsGroup.style.display = 'block';
            });
    });

    if (moduleSelect.value) {
        moduleSelect.dispatchEvent(new Event('change'));
    }

    // Character counter for textareas
    const textareas = document.querySelectorAll('.form-textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            const charCount = this.value.length;
            const maxLength = 2000;
            if (charCount > maxLength) {
                this.value = this.value.substring(0, maxLength);
            }
        });
    });

    // Instruction file picker
    const instructionFileInput = document.getElementById('instruction_file');
    const instructionFileName = document.getElementById('instruction-file-name');

    instructionFileInput.addEventListener('change', function() {
        instructionFileName.textContent = this.files.length ? this.files[0].name : 'No file selected';
    });

    // Question bank loading
    const questionBankGroup = document.getElementById('questionbank-group');
    const questionBankContainer = document.getElementById('questionbank-container');

    function loadQuestionBankForUnit(unitId, page = 1) {
        if (!unitId) {
            questionBankGroup.style.display = 'none';
            questionBankContainer.innerHTML = '<p style="color:#6b7280; margin:0;">Select questions from the question bank for this assignment (optional)</p>';
            return;
        }

        const perPage = document.getElementById('qb-perpage') ? document.getElementById('qb-perpage').value : 10;
        const search = document.getElementById('qb-search') ? encodeURIComponent(document.getElementById('qb-search').value.trim()) : '';

        fetch(`/teacher/api/questions?unit_id=${unitId}&page=${page}&per_page=${perPage}&search=${search}`, { credentials: 'same-origin' })
            .then(r => { if (!r.ok) throw new Error('Network response was not ok'); return r.json(); })
            .then(data => {
                questionBankContainer.innerHTML = '';
                if (!data.questions || data.questions.length === 0) {
                    questionBankContainer.innerHTML = '<p style="color:#6b7280; margin:0;">No questions found for this chapter/unit</p>';
                    questionBankGroup.style.display = 'block';
                    document.getElementById('questionbank-controls').style.display = 'block';
                    return;
                }

                data.questions.forEach(q => {
                    const label = document.createElement('label');
                    label.style.cssText = 'display:flex; align-items:flex-start; gap:8px; padding:8px; border-radius:6px; border:1px solid #e5e7eb; background:#fff;';

                    const input = document.createElement('input');
                    input.type = 'checkbox';
                    input.dataset.qid = q.id;

                    // Hidden inputs for form submission as objects
                    const hiddenId = document.createElement('input');
                    hiddenId.type = 'hidden';
                    hiddenId.name = 'selected_questions[][id]';
                    hiddenId.value = q.id;

                    const hiddenMarks = document.createElement('input');
                    hiddenMarks.type = 'number';
                    hiddenMarks.name = 'selected_questions[][marks]';
                    hiddenMarks.value = q.marks ?? 0;
                    hiddenMarks.min = 0;
                    hiddenMarks.style.width = '80px';
                    hiddenMarks.style.marginLeft = '8px';
                    hiddenMarks.disabled = true;

                    const text = document.createElement('div');
                    text.innerHTML = `<strong style="display:block">${q.topic ? (q.topic + ' — ') : ''}${(q.question_text?.slice(0,200) ?? '').replace(/\n/g,' ')}...</strong><small style="color:#6b7280">Marks: ${q.marks ?? 0} | Type: ${q.question_type ?? ''}</small>`;

                    const previewBtn = document.createElement('button');
                    previewBtn.type = 'button';
                    previewBtn.className = 'btn btn-sm';
                    previewBtn.style.marginLeft = '8px';
                    previewBtn.textContent = 'Preview';
                    previewBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const modal = document.getElementById('qb-preview-modal');
                        modal.querySelector('.modal-body').textContent = q.question_text || '';
                        modal.querySelector('.modal-title').textContent = q.topic ? (q.topic + ' — Question') : 'Question Preview';
                        modal.style.display = 'block';
                    });

                    label.appendChild(input);
                    label.appendChild(hiddenId);
                    label.appendChild(hiddenMarks);
                    label.appendChild(text);
                    label.appendChild(previewBtn);
                    questionBankContainer.appendChild(label);

                    // Toggle marks input when selected
                    input.addEventListener('change', function() {
                        hiddenMarks.disabled = !this.checked;
                    });
                });

                questionBankGroup.style.display = 'block';
                document.getElementById('questionbank-controls').style.display = 'block';

                // Render pagination
                const meta = data.meta || {};
                const pagination = document.getElementById('qb-pagination');
                if (pagination) {
                    pagination.innerHTML = `Page ${meta.current_page || 1} / ${meta.last_page || 1}`;
                    // next/prev buttons
                    let controls = '';
                    if ((meta.current_page || 1) > 1) controls += `<button type="button" id="qb-prev" class="btn btn-secondary" style="margin-left:8px;">Prev</button>`;
                    if ((meta.current_page || 1) < (meta.last_page || 1)) controls += `<button type="button" id="qb-next" class="btn btn-secondary" style="margin-left:8px;">Next</button>`;
                    pagination.insertAdjacentHTML('beforeend', controls);

                    const prevBtn = document.getElementById('qb-prev');
                    const nextBtn = document.getElementById('qb-next');
                    if (prevBtn) prevBtn.addEventListener('click', () => loadQuestionBankForUnit(unitId, meta.current_page - 1));
                    if (nextBtn) nextBtn.addEventListener('click', () => loadQuestionBankForUnit(unitId, meta.current_page + 1));
                }
            })
            .catch(err => {
                questionBankContainer.innerHTML = '<div style="color:#dc2626; padding:8px; background:#fff1f2; border-radius:6px">Error loading question bank</div>';
                questionBankGroup.style.display = 'block';
                document.getElementById('questionbank-controls').style.display = 'block';
            });
    }

    unitSelect.addEventListener('change', function() {
        loadQuestionBankForUnit(this.value);
    });

    if (unitSelect.value) loadQuestionBankForUnit(unitSelect.value);
});
</script>
<!-- Preview modal -->
<div id="qb-preview-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); align-items:center; justify-content:center; z-index:9999;">
    <div style="background:white; max-width:800px; margin:40px auto; padding:20px; border-radius:8px; position:relative;">
        <h3 class="modal-title" style="margin:0 0 8px 0; font-size:18px; font-weight:700">Preview</h3>
        <div class="modal-body" style="max-height:60vh; overflow:auto; white-space:pre-wrap; color:#111"></div>
        <button type="button" id="qb-preview-close" class="btn btn-secondary" style="position:absolute; right:12px; top:12px;">Close</button>
    </div>
</div>

<script>
document.addEventListener('click', function(e){
    const modal = document.getElementById('qb-preview-modal');
    if (!modal) return;
    if (e.target && e.target.id === 'qb-preview-close') {
        modal.style.display = 'none';
    }
});

// Check all button
document.addEventListener('DOMContentLoaded', function(){
    const checkAll = document.getElementById('qb-checkall');
    if (checkAll) {
        checkAll.addEventListener('click', function(){
            const checks = document.querySelectorAll('#questionbank-container input[type="checkbox"]');
            const anyUnchecked = Array.from(checks).some(cb => !cb.checked);
            checks.forEach(cb => { cb.checked = anyUnchecked; const ev = new Event('change'); cb.dispatchEvent(ev); });
        });
    }
});
</script>
@endsection
