<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Error</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .error-container { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 500px; text-align: center; }
        .error-icon { width: 80px; height: 80px; margin: 0 auto 20px; fill: #e74c3c; }
        h1 { color: #e74c3c; margin-bottom: 15px; font-size: 24px; }
        p { color: #666; line-height: 1.6; margin-bottom: 20px; }
        .user-info { background: #f8f9fa; padding: 15px; border-radius: 4px; margin-bottom: 20px; text-align: left; }
        .user-info p { margin-bottom: 5px; }
        .btn { padding: 12px 24px; background: #e74c3c; color: white; text-decoration: none; border-radius: 4px; display: inline-block; margin: 5px; }
        .btn:hover { background: #c0392b; }
        .btn-secondary { background: #666; }
        .btn-secondary:hover { background: #555; }
    </style>
</head>
<body>
    <div class="error-container">
        <svg class="error-icon" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
        </svg>
        
        <h1>Profile Setup Required</h1>
        <p>{{ $message }}</p>
        
        <div class="user-info">
            <p><strong>Your Account:</strong></p>
            <p>Name: {{ $user->name }}</p>
            <p>Email: {{ $user->email }}</p>
            <p>Role: {{ ucfirst($user->role) }}</p>
        </div>
        
        <p style="font-size: 14px; color: #999;">
            This usually happens when your account was created manually. Please contact your administrator to complete your profile setup.
        </p>
        
        <form action="{{ route('logout', ['guard' => $user->role]) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn">Logout</button>
        </form>
    </div>
</body>
</html>
