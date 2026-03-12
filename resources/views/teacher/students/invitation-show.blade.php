@extends('layouts.teacher')

@section('title', 'Student Invitation')
@section('page-title', 'Student Invitation Link')

@section('content')
<style>
    .card { background: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 900px; }
    .info-box { background: #f0f7ff; padding: 15px; border-radius: 5px; border-left: 4px solid #2196F3; margin-bottom: 20px; }
    .link-box { background: #f8f9fa; padding: 15px; border-radius: 5px; border: 2px dashed #ddd; margin-bottom: 20px; }
    .link-box input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; font-family: monospace; }
    .btn { padding: 10px 16px; background: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
    .btn-secondary { background: #666; }
</style>

<div class="card">
    @if(session('success'))
        <div style="padding: 12px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 16px;">{{ session('success') }}</div>
    @endif

    <h2 style="margin-bottom: 12px;">Invitation Ready</h2>
    <div class="info-box">
        <p><strong>Role:</strong> Student</p>
        <p><strong>Category:</strong> {{ $invitation->course?->category_name ?: 'Uncategorized' }}</p>
        <p><strong>Class:</strong> {{ $invitation->course?->class_name ?: 'Unassigned' }}</p>
        <p><strong>Course:</strong> {{ $invitation->course?->name ?: '-' }}</p>
        <p><strong>Expires:</strong> {{ $invitation->expires_at->format('F d, Y') }}</p>
        <p><strong>Uses:</strong> {{ $invitation->uses_count }} / {{ $invitation->max_uses ?? '∞' }}</p>
        @if(!$invitation->isValid())
            <p style="color:#c0392b;"><strong>Status:</strong> Expired or max uses reached</p>
        @else
            <p style="color:#27ae60;"><strong>Status:</strong> Active</p>
        @endif
    </div>

    <div class="link-box">
        <input type="text" id="inviteLink" readonly value="{{ $inviteLink }}" onclick="this.select()">
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <button class="btn" type="button" onclick="copyToClipboard()">Copy Link</button>
        <a href="{{ route('teacher.students.index') }}" class="btn btn-secondary">Back to Students</a>
    </div>
</div>

<script>
function copyToClipboard() {
    const input = document.getElementById('inviteLink');
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(() => {
        alert('Invitation link copied to clipboard.');
    });
}
</script>
@endsection
