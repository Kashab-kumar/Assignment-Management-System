@extends($layout)

@section('title', ($roleLabel ?? 'Account') . ' Settings')
@section('page-title', ($roleLabel ?? 'Account') . ' Settings')

@section('content')
<style>
    .card { background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 24px; max-width: 760px; }
    .avatar-preview { width: 120px; height: 120px; border-radius: 50%; overflow: hidden; background: #f2f4f7; border: 3px solid #e1e6ef; display: flex; align-items: center; justify-content: center; }
    .avatar-preview img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .avatar-fallback { font-size: 42px; font-weight: 700; color: #5f6b7a; }
    .muted { color: #666; font-size: 13px; }
    .form-group { margin: 16px 0 20px; }
    .btn { padding: 10px 16px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; }
    .btn-primary { background: #2196F3; color: white; }
    .btn-danger { background: #e74c3c; color: white; }
    .btn-secondary { background: #666; color: white; }
    .btn-logout { background: #e74c3c; color: white; }
    .settings-divider { margin: 18px 0; border: 0; border-top: 1px solid #e7ebf0; }
    .alert-success { padding: 12px; border-radius: 4px; background: #d4edda; color: #155724; margin-bottom: 16px; }
    .alert-danger { padding: 12px; border-radius: 4px; background: #f8d7da; color: #721c24; margin-bottom: 16px; }
</style>

<div class="card">
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-danger">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2 style="margin-bottom: 10px;">{{ $roleLabel ?? 'Account' }} Settings</h2>
    <h3 style="margin-bottom: 10px; font-size: 18px;">Update Avatar</h3>
    <p class="muted" style="margin-bottom: 18px;">Supported formats: JPG, PNG, WEBP. Maximum size: 2 MB.</p>

    @php
        $avatarUrl = $user->avatar_path ? url('/storage/' . $user->avatar_path) : null;
        $avatarUpdateRouteName = $user->isAdmin()
            ? 'admin.settings.avatar.update'
            : ($user->isTeacher() ? 'teacher.settings.avatar.update' : 'student.settings.avatar.update');
        $avatarDestroyRouteName = $user->isAdmin()
            ? 'admin.settings.avatar.destroy'
            : ($user->isTeacher() ? 'teacher.settings.avatar.destroy' : 'student.settings.avatar.destroy');
        $avatarUpdateRoute = route($avatarUpdateRouteName, [], false);
        $avatarDestroyRoute = route($avatarDestroyRouteName, [], false);
    @endphp

    <div class="avatar-preview" style="margin-bottom: 12px;">
        @if($avatarUrl)
            <img src="{{ $avatarUrl }}" alt="{{ $user->name }} Avatar">
        @else
            <span class="avatar-fallback">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
        @endif
    </div>

    <form action="{{ $avatarUpdateRoute }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 12px;">
        @csrf
        <div class="form-group">
            <label for="avatar" style="display:block; margin-bottom:6px; font-weight:bold;">Choose New Avatar</label>
            <input type="file" id="avatar" name="avatar" accept="image/png,image/jpeg,image/webp" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload Avatar</button>
    </form>

    @if($user->avatar_path)
        <form action="{{ $avatarDestroyRoute }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Remove your avatar?')">Remove Avatar</button>
        </form>
    @endif

    <hr class="settings-divider">

    <h3 style="margin-bottom: 10px; font-size: 18px;">Account</h3>
    <p class="muted" style="margin-bottom: 12px;">Use logout from Settings.</p>

    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-logout">Logout</button>
    </form>

    <a href="{{ $backRoute }}" class="btn btn-secondary" style="margin-left: 8px;">Back</a>
</div>
@endsection
