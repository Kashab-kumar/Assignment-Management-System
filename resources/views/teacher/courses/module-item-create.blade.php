@extends('layouts.teacher')

@section('title', 'Add Content')
@section('page-title', 'Add Content to Unit')

@section('content')
<style>
    .container {
        max-width: 800px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .header {
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        color: white;
        padding: 32px;
        text-align: center;
    }

    .header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
    }

    .header p {
        margin: 8px 0 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .body {
        padding: 32px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-group label .required {
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
        background: #ffffff;
    }

    .form-control:focus {
        outline: none;
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .ai-section {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 2px solid #7c3aed;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
    }

    .ai-section h3 {
        margin: 0 0 16px;
        color: #5b21b6;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ai-section h3::before {
        content: "🤖";
    }

    .ai-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #7c3aed;
    }

    .checkbox-group label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
        font-size: 13px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
        margin-top: 32px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #7c3aed, #5b21b6);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
    }

    .btn-ai {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .btn-ai:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    .btn-outline {
        background: transparent;
        color: #6b7280;
        border: 1px solid #d1d5db;
    }

    .btn-outline:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    .error-message {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #6b7280;
    }

    .breadcrumb a {
        color: #7c3aed;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }
</style>

<div class="container">
    <div class="header">
        <h1>Add Content to Unit</h1>
        <p>{{ $module->title }} - {{ $course->name }}</p>
    </div>

    <div class="body">
        <nav class="breadcrumb">
            <a href="{{ route('teacher.courses.index') }}">Courses</a>
            <span>/</span>
            <a href="{{ route('teacher.courses.show', $course) }}">{{ $course->name }}</a>
            <span>/</span>
            <a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}">{{ $module->title }}</a>
            <span>/</span>
            <span>Add Content</span>
        </nav>

        @if($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                <strong style="color: #dc2626;">Please fix the following errors:</strong>
                <ul style="margin: 10px 0 0 20px; color: #dc2626;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('teacher.courses.modules.items.store', [$course, $module]) }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">Topic/Title <span class="required">*</span></label>
                <input type="text" id="title" name="title" class="form-control" 
                       value="{{ old('title') }}" 
                       placeholder="e.g., Introduction to Photosynthesis" required>
                @error('title')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="file">Upload File (PDF, DOC, DOCX, TXT)</label>
                <input type="file" id="file" name="file" class="form-control" 
                       accept=".pdf,.doc,.docx,.txt"
                       onchange="handleFileUpload(this)">
                <small style="color: #6b7280; font-size: 12px;">Upload a file and AI will analyze it to generate content</small>
                <div id="file-info" style="margin-top: 8px; color: #10b981; font-size: 13px;"></div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="type">Content Type <span class="required">*</span></label>
                    <select id="type" name="type" class="form-control" required>
                        <option value="">Select content type</option>
                        <option value="unit_outline" {{ old('type') == 'unit_outline' ? 'selected' : '' }}>Unit Outline</option>
                        <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video</option>
                        <option value="note" {{ old('type') == 'note' ? 'selected' : '' }}>Note</option>
                        <option value="quiz" {{ old('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="test" {{ old('type') == 'test' ? 'selected' : '' }}>Test</option>
                    </select>
                    @error('type')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="ai-section">
                <h3>Generate AI Content</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="ai_items_count">Number of items to generate</label>
                        <select id="ai_items_count" class="form-control">
                            <option value="1">1 item</option>
                            <option value="2">2 items</option>
                            <option value="3" selected>3 items</option>
                            <option value="4">4 items</option>
                            <option value="5">5 items</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ai_difficulty">Difficulty level</label>
                        <select id="ai_difficulty" class="form-control">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate" selected>Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>
                </div>
                <div class="ai-options">
                    <div class="checkbox-group">
                        <input type="checkbox" id="ai_examples" checked>
                        <label for="ai_examples">Include examples</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="ai_summary" checked>
                        <label for="ai_summary">Include summary</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="ai_key_points">
                        <label for="ai_key_points">Include key points</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="ai_practice">
                        <label for="ai_practice">Include practice questions</label>
                    </div>
                </div>
                <div style="margin-top: 16px;">
                    <button type="button" class="btn btn-ai" onclick="generateAIContent()">🤖 Generate with AI</button>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" 
                          placeholder="Enter the content description or let AI generate it...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Content</button>
            </div>
        </form>
    </div>
</div>

<script>
let uploadedFile = null;

function handleFileUpload(input) {
    const fileInfo = document.getElementById('file-info');
    if (input.files && input.files[0]) {
        uploadedFile = input.files[0];
        fileInfo.textContent = `✓ File selected: ${uploadedFile.name} (${(uploadedFile.size / 1024).toFixed(2)} KB)`;
    } else {
        uploadedFile = null;
        fileInfo.textContent = '';
    }
}

async function generateAIContent() {
    const title = document.getElementById('title').value;
    const type = document.getElementById('type').value;
    const itemsCount = document.getElementById('ai_items_count').value;
    const difficulty = document.getElementById('ai_difficulty').value;
    const includeExamples = document.getElementById('ai_examples').checked;
    const includeSummary = document.getElementById('ai_summary').checked;
    const includeKeyPoints = document.getElementById('ai_key_points').checked;
    const includePractice = document.getElementById('ai_practice').checked;

    if (!title && !uploadedFile) {
        alert('Please enter a topic/title or upload a file first');
        document.getElementById('title').focus();
        return;
    }

    if (!type) {
        alert('Please select a content type first');
        document.getElementById('type').focus();
        return;
    }

    const description = document.getElementById('description');
    description.value = 'Generating content with AI...';
    description.disabled = true;

    const formData = new FormData();
    formData.append('title', title);
    formData.append('type', type);
    formData.append('items_count', itemsCount);
    formData.append('difficulty', difficulty);
    formData.append('include_examples', includeExamples);
    formData.append('include_summary', includeSummary);
    formData.append('include_key_points', includeKeyPoints);
    formData.append('include_practice', includePractice);
    
    if (uploadedFile) {
        formData.append('file', uploadedFile);
    }

    try {
        const response = await fetch('/api/generate-content', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });

        const data = await response.json();
        
        if (data.success) {
            description.value = data.content;
            if (data.analyzed_content) {
                alert('✓ AI analyzed your file successfully!\n\n' + data.analyzed_content);
            }
        } else {
            description.value = '';
            alert('Error generating content: ' + (data.error || 'Unknown error'));
        }
    } catch (error) {
        description.value = '';
        alert('Error generating content: ' + error.message);
    } finally {
        description.disabled = false;
    }
}
</script>
@endsection
