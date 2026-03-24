<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Registration</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        input:disabled {
            background: #f3f4f6;
            cursor: not-allowed;
        }
        .password-wrapper {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            user-select: none;
            width: 20px;
            height: 20px;
        }
        .password-toggle svg {
            width: 100%;
            height: 100%;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #5568d3;
        }
        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        .info-box {
            background: #f0f7ff;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #667eea;
            margin-bottom: 20px;
            font-size: 13px;
            color: #555;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            background: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Complete Your Registration</h1>
        <p class="subtitle">You've been invited as a <span class="badge">{{ ucfirst($invitation->role) }}</span></p>

        <div class="info-box">
            ℹ️ Please complete your profile to access the system.
        </div>

        @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register.invitation.post', $invitation->token) }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            @if($invitation->role === 'student')
                @if($invitation->course)
                <div class="info-box">
                    <strong>Assigned Category:</strong> {{ $invitation->course->category_name ?: 'Uncategorized' }}<br>
                    <strong>Assigned Class:</strong> {{ $invitation->course->class_name ?: 'Unassigned' }}<br>
                    <strong>Assigned Course:</strong> {{ $invitation->course->name }}
                </div>
                @endif

            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input type="text" id="student_id" name="student_id" value="{{ old('student_id') }}" required>
            </div>

                @if(!$invitation->course)
            <div class="form-group">
                <label for="class">Class</label>
                <input type="text" id="class" name="class" value="{{ old('class') }}" placeholder="e.g., Class A" required>
            </div>
                @endif
            @endif

            @if($invitation->role === 'teacher')
            <div class="form-group">
                <label for="teacher_id">Teacher ID</label>
                <input type="text" id="teacher_id" name="teacher_id" value="{{ old('teacher_id') }}" required>
            </div>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" placeholder="e.g., Mathematics" required>
            </div>
            @endif

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required>
                    <span class="password-toggle" onclick="togglePassword('password')">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                    <span class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn">Complete Registration</button>
        </form>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggle = passwordInput.parentElement.querySelector('.password-toggle svg');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggle.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle><line x1="1" y1="1" x2="23" y2="23" stroke-width="2"></line>';
            } else {
                passwordInput.type = 'password';
                toggle.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            }
        }
    </script>
</body>
</html>
