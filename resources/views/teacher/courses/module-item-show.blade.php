@extends('layouts.teacher')

@section('title', $item->title)
@section('page-title', 'Unit Outline Details')

@section('content')
<style>
    .container {
        max-width: 900px;
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
    }

    .header h1 {
        margin: 0 0 8px;
        font-size: 24px;
        font-weight: 700;
    }

    .header p {
        margin: 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .body {
        padding: 32px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
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

    .section {
        background: #f9fafb;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    .section-content {
        font-size: 16px;
        color: #1f2937;
        line-height: 1.6;
    }

    .file-card {
        display: flex;
        align-items: center;
        gap: 16px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
    }

    .file-icon {
        font-size: 40px;
    }

    .file-info {
        flex: 1;
    }

    .file-name {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .file-meta {
        font-size: 12px;
        color: #6b7280;
    }

    .file-actions {
        display: flex;
        gap: 8px;
    }

    .btn {
        padding: 10px 20px;
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
    }

    .btn-sm {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .actions-bar {
        display: flex;
        gap: 12px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
        margin-top: 32px;
    }

    .meta-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .meta-item {
        background: #ffffff;
        border-radius: 6px;
        padding: 12px 16px;
    }

    .meta-label {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .meta-value {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
    }
</style>

<div class="container">
    <div class="header">
        <h1>{{ $item->title }}</h1>
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
            <span>Unit Outline</span>
        </nav>

        @if(session('success'))
            <div style="background: #d1fae5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Meta Information -->
        <div class="section">
            <div class="section-title">Information</div>
            <div class="meta-info">
                <div class="meta-item">
                    <div class="meta-label">Type</div>
                    <div class="meta-value">{{ ucfirst($item->type) }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Created By</div>
                    <div class="meta-value">{{ $item->creator?->name ?? 'Unknown' }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Created On</div>
                    <div class="meta-value">{{ $item->created_at->format('d M Y, h:i A') }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Last Updated</div>
                    <div class="meta-value">{{ $item->updated_at->format('d M Y, h:i A') }}</div>
                </div>
                @if($item->unit)
                <div class="meta-item">
                    <div class="meta-label">Linked Unit</div>
                    <div class="meta-value">{{ $item->unit->title }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Description -->
        @if($item->content)
        <div class="section">
            <div class="section-title">Description</div>
            <div class="section-content" style="white-space: pre-wrap;">{{ $item->content }}</div>
        </div>
        @endif

        <!-- Attached File -->
        @if($item->file_path)
        <div class="section">
            <div class="section-title">Attached File</div>
            <div class="file-card">
                <div class="file-icon">
                    @if($item->file_type == 'pdf') 📕
                    @elseif(in_array($item->file_type, ['doc', 'docx'])) 📘
                    @elseif($item->file_type == 'txt') 📝
                    @else 📄
                    @endif
                </div>
                <div class="file-info">
                    <div class="file-name">{{ $item->file_name }}</div>
                    <div class="file-meta">{{ strtoupper($item->file_type) }} file uploaded on {{ $item->created_at->format('d M Y') }}</div>
                </div>
                <div class="file-actions">
                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="btn-sm" style="background: #3b82f6; color: white; text-decoration: none;">👁 View</a>
                    <a href="{{ asset('storage/' . $item->file_path) }}" download class="btn-sm" style="background: #10b981; color: white; text-decoration: none;">⬇ Download</a>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="actions-bar">
            <a href="{{ route('teacher.courses.modules.show', [$course, $module]) }}" class="btn btn-outline">← Back to Module</a>
            <a href="{{ route('teacher.courses.modules.items.edit', [$course, $module, $item]) }}" class="btn btn-secondary">Edit Unit Outline</a>
        </div>
    </div>
</div>
@endsection
