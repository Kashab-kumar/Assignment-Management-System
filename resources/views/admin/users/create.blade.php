@extends('layouts.admin')

@section('title', 'Add New User')
@section('page-title', 'Add New User')

@section('content')
<style>
    .form-container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-weight: 600; color: #333; margin-bottom: 8px; }
    .form-group label .required { color: #f44336; }
    .form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
    .form-control:focus { outline: none; border-color: #9C27B0; }
    .btn { padding: 12px 24px; border-radius: 4px; text-decoration: none; font-size: 14px; border: none; cursor: pointer; }
    .btn-primary { background: #9C27B0; color: white; }
    .btn-primary:hover { background: #7B1FA2; }
    .btn-secondary { background: #6c757d; color: white; margin-left: 10px; }
    .error-message { color: #f44336; font-size: 13px; margin-top: 5px; }
    .alert { padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; }
    .alert-danger { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
    .help-text { font-size: 12px; color: #6b7280; margin-top: 5px; }
    .form-actions { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; }
</style>

<div class="form-container">
    <h2 style="margin-top: 0; margin-bottom: 25px; color: #333;">Add New User</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul style="margin: 10px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="form-group">
            <label for="name">Full Name <span class="required">*</span></label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Enter full name">
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email Address <span class="required">*</span></label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="Enter email address">
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="role">User Role <span class="required">*</span></label>
            <select id="role" name="role" class="form-control" required>
                <option value="">Select Role</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
            </select>
            <div class="help-text">Student/Teacher ID will be auto-generated</div>
            @error('role')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password <span class="required">*</span></label>
            <input type="password" id="password" name="password" class="form-control" required placeholder="Enter password (min 8 characters)">
            <div class="help-text">Minimum 8 characters</div>
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password <span class="required">*</span></label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="Confirm password">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
