<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Registration</title>
    <style>
        :root { --bg: #f7f4f0; --card-bg: #ffffff; --muted: #6b7280; --accent: #111827; }
        * { box-sizing: border-box; }
        html,body { height: 100%; }
        body {
            margin: 0;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            min-height: 100vh;
            color: var(--accent);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            padding: 48px 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('/images/rim.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .card {
            width: 100%;
            max-width: 900px;
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(16,24,40,0.06);
            padding: 40px 48px;
        }

        .step { font-size: 12px; color: var(--muted); letter-spacing: 2px; margin-bottom: 18px; }
        .heading { font-size: 40px; line-height: 1.05; margin: 0 0 8px; font-weight: 700; }
        .heading em { font-style: italic; color: var(--accent); font-weight: 700; }
        .lead { color: var(--muted); margin-bottom: 22px; }

        .note { background: #fffaf0; border-left: 4px solid #f59e0b; padding: 14px 16px; border-radius: 6px; color: #92400e; margin-bottom: 22px; }

        form { display: grid; gap: 16px; }
        .field { display: flex; flex-direction: column; gap: 8px; }
        label { font-size: 13px; color: var(--muted); font-weight: 600; }
        input[type="text"], input[type="email"], input[type="password"] {
            padding: 12px 14px; border-radius: 6px; border: 1px solid #e6e6e6; font-size: 15px;
            background: #fff;
        }
        input:focus { outline: none; box-shadow: 0 0 0 4px rgba(99,102,241,0.06); border-color: #6b6ef6; }

        .assigned-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; margin: 6px 0 0 0; }
        .assigned-item { background: #fafafa; border: 1px solid #eee; padding: 10px 12px; border-radius: 6px; font-size: 13px; color: #374151; }
        .assigned-item b { display:block; font-weight:700; margin-bottom:6px; color:#111827 }

        .password-row { position: relative; }
        input[type="password"] { padding-right: 44px; }
        .toggle-eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #8d93a5;
            width: 18px;
            height: 18px;
            display: inline-grid;
            place-items: center;
        }
        .toggle-eye svg { width: 18px; height: 18px; }

        .btn-primary { margin-top: 12px; background: #0b0b0b; color: #fff; border: none; padding: 14px 18px; border-radius: 8px; font-weight:700; cursor:pointer; }

        .muted-small { font-size: 13px; color: var(--muted); margin-top: 10px; }

        @media (max-width: 720px) {
            .card { padding: 28px; }
            .heading { font-size: 28px; }
            .assigned-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="step">STEP 02 — PROFILE</div>
        <h1 class="heading">Complete your <em>registration</em>.</h1>
        <p class="lead">You've been invited as a <strong>{{ strtoupper($invitation->role) }}</strong>. A few details and you're in.</p>

        <div class="note">Please complete your profile to access the system.</div>

        @if($errors->any())
            <div style="background:#fff1f2;border:1px solid #fecaca;padding:12px;border-radius:6px;margin-bottom:12px;color:#991b1b;">
                <ul style="margin:0 0 0 18px;padding:0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.invitation.post', $invitation->token) }}">
            @csrf

            <div class="field">
                <label for="email">EMAIL</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="field">
                <label for="name">FULL NAME</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            @if($invitation->role === 'student')
                @if($invitation->course)
                    <div>
                        <div style="font-size:13px;color:#6b7280;margin-bottom:8px;font-weight:600;">Assigned</div>
                        <div class="assigned-grid">
                            <div class="assigned-item"><b>Category</b>{{ $invitation->course->category_name ?: 'Uncategorized' }}</div>
                            <div class="assigned-item"><b>Class</b>{{ $invitation->course->class_name ?: 'Unassigned' }}</div>
                            <div class="assigned-item"><b>Course</b>{{ $invitation->course->name }}</div>
                        </div>
                    </div>
                @endif

                <div class="field">
                    <label for="student_id">STUDENT ID</label>
                    <input type="text" id="student_id" name="student_id" value="{{ old('student_id') }}" required>
                </div>

                @if(!$invitation->course)
                    <div class="field">
                        <label for="class">CLASS</label>
                        <input type="text" id="class" name="class" value="{{ old('class') }}" placeholder="e.g., Class A" required>
                    </div>
                @endif
            @endif

            @if($invitation->role === 'teacher')
                <div class="field">
                    <label for="teacher_id">TEACHER ID</label>
                    <input type="text" id="teacher_id" name="teacher_id" value="{{ old('teacher_id') }}" required>
                </div>

                <div class="field">
                    <label for="subject">SUBJECT</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}" placeholder="e.g., Mathematics" required>
                </div>
            @endif

            <div class="field password-row">
                <label for="password">PASSWORD</label>
                <input type="password" id="password" name="password" required>
                <span class="toggle-eye" onclick="togglePassword('password')" role="button" aria-label="Toggle password visibility">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="#374151" stroke-width="1.25" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="3" stroke="#374151" stroke-width="1.25" fill="none"/>
                    </svg>
                </span>
            </div>

            <div class="field password-row">
                <label for="password_confirmation">CONFIRM PASSWORD</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                <span class="toggle-eye" onclick="togglePassword('password_confirmation')" role="button" aria-label="Toggle password visibility">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="#374151" stroke-width="1.25" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="3" stroke="#374151" stroke-width="1.25" fill="none"/>
                    </svg>
                </span>
            </div>

            <button type="submit" class="btn-primary">Complete registration</button>
            <div class="muted-small">By continuing you agree to the academic honor code.</div>
        </form>
    </div>

    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            if (!input) return;
            const svg = input.parentElement.querySelector('.toggle-eye svg');
            if (!svg) { input.type = input.type === 'password' ? 'text' : 'password'; return; }

            if (input.type === 'password') {
                input.type = 'text';
                svg.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="#374151" stroke-width="1.25" fill="none" stroke-linecap="round" stroke-linejoin="round"/> <circle cx="12" cy="12" r="3" stroke="#374151" stroke-width="1.25" fill="none"/> <line x1="1" y1="1" x2="23" y2="23" stroke="#374151" stroke-width="1.25" stroke-linecap="round"/>';
            } else {
                input.type = 'password';
                svg.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="#374151" stroke-width="1.25" fill="none" stroke-linecap="round" stroke-linejoin="round"/> <circle cx="12" cy="12" r="3" stroke="#374151" stroke-width="1.25" fill="none"/>';
            }
        }
    </script>
</body>
</html>
