@extends('layouts.admin')

@section('title', 'Create Course')
@section('page-title', 'Create New Course')

@section('content')
<style>
    .form-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 30px;
        max-width: 600px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .form-group textarea {
        min-height: 100px;
        resize: vertical;
    }

    .modules-builder {
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 14px;
        background: #fafafa;
    }

    .module-row {
        border: 1px solid #e4e4e4;
        border-radius: 6px;
        background: #fff;
        padding: 12px;
        margin-bottom: 10px;
    }

    .module-row-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
    }

    .module-row h4 {
        margin: 0 0 10px;
        font-size: 14px;
        color: #333;
    }

    .module-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
    }

    .btn-small {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
    }

    .btn-add-module {
        background: #2196F3;
        color: #fff;
    }

    .btn-remove-module {
        background: #f44336;
        color: #fff;
    }

    @media (max-width: 900px) {
        .module-row-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group small {
        color: #666;
        font-size: 12px;
        display: block;
        margin-top: 5px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: #9C27B0;
        color: white;
    }

    .btn-secondary {
        background: #666;
        color: white;
        margin-left: 10px;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        padding: 12px;
        border-radius: 4px;
        margin-bottom: 20px;
        border: 1px solid #f5c6cb;
    }
</style>

<div class="form-container">
    <h2 style="margin-top: 0; color: #333;">Create New Course</h2>

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

    <form action="{{ route('admin.courses.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Course Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g., Introduction to Programming">
            <small>The full name of the course</small>
        </div>

        <div class="form-group">
            <label for="code">Course Code *</label>
            <input type="text" id="code" name="code" value="{{ old('code') }}" required placeholder="e.g., CS101">
            <small>Unique course code (e.g., CS101, MATH201)</small>
        </div>

        <div class="form-group">
            <label for="category_name">Course Category *</label>
            <input type="text" id="category_name" name="category_name" list="category-options" value="{{ old('category_name') }}" required placeholder="e.g., Master Courses / Diploma Courses / SAT">
            <datalist id="category-options">
                @foreach($categoryOptions as $categoryName)
                    <option value="{{ $categoryName }}"></option>
                @endforeach
            </datalist>
            <small>Top-level group shown in the course category tree</small>
        </div>

        <div class="form-group">
            <label for="class_name">Class *</label>
            <input type="text" id="class_name" name="class_name" list="class-options" value="{{ old('class_name') }}" required placeholder="e.g., MBA 2025-2026 / Diploma in Information Technology">
            <datalist id="class-options">
                @foreach($classOptions as $className)
                    <option value="{{ $className }}"></option>
                @endforeach
            </datalist>
            <small>Second-level item under the selected category</small>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" placeholder="Course description...">{{ old('description') }}</textarea>
            <small>Optional course description</small>
        </div>

        <div class="form-group">
            <label>Modules and Teacher Assignment (Optional)</label>
            <div class="modules-builder">
                <small style="margin-bottom: 12px;">Add modules now and assign one teacher per module. You can still add/edit modules later.</small>

                <div id="modulesContainer">
                    @foreach(old('modules', []) as $index => $module)
                        <div class="module-row" data-module-row>
                            <h4>Module <span data-module-index>{{ $index + 1 }}</span></h4>
                            <input type="text" name="modules[{{ $index }}][title]" value="{{ $module['title'] ?? '' }}" placeholder="Module title">
                            <textarea name="modules[{{ $index }}][description]" rows="2" placeholder="Module description (optional)">{{ $module['description'] ?? '' }}</textarea>
                            <div class="module-row-grid">
                                <input type="number" name="modules[{{ $index }}][lesson_count]" min="0" value="{{ $module['lesson_count'] ?? 0 }}" placeholder="Lessons">
                                <input type="number" name="modules[{{ $index }}][assignment_count]" min="0" value="{{ $module['assignment_count'] ?? 0 }}" placeholder="Assignments">
                                <input type="number" name="modules[{{ $index }}][quiz_count]" min="0" value="{{ $module['quiz_count'] ?? 0 }}" placeholder="Quizzes">
                                <select name="modules[{{ $index }}][teacher_id]">
                                    <option value="">No specific teacher</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ (string)($module['teacher_id'] ?? '') === (string)$teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}{{ $teacher->teacher_id ? ' (' . $teacher->teacher_id . ')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="module-actions">
                                <span></span>
                                <button type="button" class="btn-small btn-remove-module" data-remove-module>Remove</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn-small btn-add-module" id="addModuleBtn">+ Add Module</button>
            </div>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">Create Course</button>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<template id="moduleRowTemplate">
    <div class="module-row" data-module-row>
        <h4>Module <span data-module-index></span></h4>
        <input type="text" data-name="title" placeholder="Module title">
        <textarea data-name="description" rows="2" placeholder="Module description (optional)"></textarea>
        <div class="module-row-grid">
            <input type="number" data-name="lesson_count" min="0" value="0" placeholder="Lessons">
            <input type="number" data-name="assignment_count" min="0" value="0" placeholder="Assignments">
            <input type="number" data-name="quiz_count" min="0" value="0" placeholder="Quizzes">
            <select data-name="teacher_id">
                <option value="">No specific teacher</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}{{ $teacher->teacher_id ? ' (' . $teacher->teacher_id . ')' : '' }}</option>
                @endforeach
            </select>
        </div>
        <div class="module-actions">
            <span></span>
            <button type="button" class="btn-small btn-remove-module" data-remove-module>Remove</button>
        </div>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modulesContainer = document.getElementById('modulesContainer');
        const addModuleBtn = document.getElementById('addModuleBtn');
        const rowTemplate = document.getElementById('moduleRowTemplate');

        function syncRowIndexes() {
            const rows = Array.from(modulesContainer.querySelectorAll('[data-module-row]'));

            rows.forEach(function (row, index) {
                const title = row.querySelector('[data-module-index]');
                if (title) {
                    title.textContent = String(index + 1);
                }

                const mappedInputs = row.querySelectorAll('[data-name]');
                mappedInputs.forEach(function (input) {
                    const field = input.getAttribute('data-name');
                    input.name = 'modules[' + index + '][' + field + ']';
                });
            });
        }

        function wireRemoveButton(row) {
            const removeBtn = row.querySelector('[data-remove-module]');
            if (!removeBtn) {
                return;
            }

            removeBtn.addEventListener('click', function () {
                row.remove();
                syncRowIndexes();
            });
        }

        function addModuleRow() {
            const fragment = rowTemplate.content.cloneNode(true);
            const row = fragment.querySelector('[data-module-row]');
            modulesContainer.appendChild(fragment);
            wireRemoveButton(modulesContainer.lastElementChild);
            syncRowIndexes();
        }

        Array.from(modulesContainer.querySelectorAll('[data-module-row]')).forEach(function (row) {
            const fields = row.querySelectorAll('input[name], textarea[name], select[name]');
            fields.forEach(function (field) {
                const match = field.name.match(/^modules\[(\d+)\]\[(.+)\]$/);
                if (match) {
                    field.setAttribute('data-name', match[2]);
                }
            });
            wireRemoveButton(row);
        });

        addModuleBtn.addEventListener('click', addModuleRow);

        if (!modulesContainer.querySelector('[data-module-row]')) {
            addModuleRow();
        } else {
            syncRowIndexes();
        }
    });
</script>
@endsection
