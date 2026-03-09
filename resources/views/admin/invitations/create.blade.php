@extends('layouts.admin')

@section('title', 'Create Invitation')
@section('page-title', 'Create Invitation Link')

@section('content')
<style>
    .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
    .form-group select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
    .btn { padding: 12px 24px; background: #9C27B0; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
    .btn:hover { background: #7B1FA2; }
    .btn-secondary { background: #666; margin-left: 10px; text-decoration: none; display: inline-block; }
    .btn-secondary:hover { background: #555; }
    .alert-error { padding: 12px; background: #fee; color: #c33; border: 1px solid #fcc; border-radius: 4px; margin-bottom: 20px; }
    .info-box { background: #f0f7ff; padding: 15px; border-radius: 5px; border-left: 4px solid #2196F3; margin-bottom: 20px; font-size: 14px; color: #555; }
    .role-option { padding: 20px; border: 2px solid #ddd; border-radius: 8px; margin-bottom: 15px; cursor: pointer; transition: all 0.3s; }
    .role-option:hover { border-color: #9C27B0; background: #f9f9f9; }
    .role-option input[type="radio"] { margin-right: 10px; }
    .role-option.selected { border-color: #9C27B0; background: #f3e5f5; }
</style>

<div class="card">
    <div class="info-box">
        ℹ️ Generate a registration link for teachers or students. They will fill in their details when they click the link.
    </div>

    @if($errors->any())
    <div class="alert-error">
        <ul style="margin-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.invitations.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label>Select Role</label>
            
            <label class="role-option" onclick="selectRole('teacher')">
                <input type="radio" name="role" value="teacher" {{ old('role') == 'teacher' ? 'checked' : '' }} required>
                <strong>Teacher</strong>
                <p style="margin: 5px 0 0 30px; color: #666; font-size: 13px;">
                    For instructors who will create assignments and grade students
                </p>
            </label>

            <label class="role-option" onclick="selectRole('student')">
                <input type="radio" name="role" value="student" {{ old('role') == 'student' ? 'checked' : '' }} required>
                <strong>Student</strong>
                <p style="margin: 5px 0 0 30px; color: #666; font-size: 13px;">
                    For learners who will submit assignments and view grades
                </p>
            </label>
        </div>

        <button type="submit" class="btn">Generate Invitation Link</button>
        <a href="{{ route('admin.invitations.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
    function selectRole(role) {
        document.querySelectorAll('.role-option').forEach(el => el.classList.remove('selected'));
        event.currentTarget.classList.add('selected');
        document.querySelector(`input[value="${role}"]`).checked = true;
    }
    
    // Highlight selected on load
    document.addEventListener('DOMContentLoaded', function() {
        const checked = document.querySelector('input[name="role"]:checked');
        if (checked) {
            checked.closest('.role-option').classList.add('selected');
        }
    });
</script>
@endsection
