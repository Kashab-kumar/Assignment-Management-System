<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Assignment Management</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap');

        :root {
            --bg: #efeff2;
            --card: #ffffff;
            --line: #d5d7de;
            --text: #141518;
            --muted: #5b5f6c;
            --link: #3f44d8;
            --button-a: #3c44d8;
            --button-b: #5448ef;
            --soft-purple: #5a53e3;
            --soft-purple-bg: #ebe8ff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Manrope', 'Segoe UI', sans-serif;
            min-height: 100vh;
            background-image: url('/images/rim.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 34px 16px 20px;
        }
        
                    flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 34px 16px 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 470px;
            background-color: #ffffff;
            background: linear-gradient(to bottom, #ffffff, #f8f9fa);
            border: 3px solid black;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            padding: 28px 30px 24px;
            position: relative;
            z-index: 10;
        }

        .brand-wrap {
            text-align: center;
            margin-bottom: 18px;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            margin: 0 auto 10px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--soft-purple), #4b3cf0);
            display: grid;
            place-items: center;
        }

        .brand-icon svg {
            width: 20px;
            height: 20px;
            color: #ffffff;
        }

        .brand-name {
            font-size: 40px;
            line-height: 1;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .title {
            text-align: center;
            font-size: 54px;
            line-height: 1;
            letter-spacing: -0.03em;
            margin-bottom: 8px;
            font-weight: 800;
        }

        .subtitle {
            text-align: center;
            color: #3d4150;
            font-size: 22px;
            line-height: 1.25;
            margin-bottom: 22px;
        }

        .alert {
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 12px;
            font-size: 13px;
        }

        .alert-error {
            background: #ffe7eb;
            color: #8f1732;
            border: 1px solid #fecfd9;
        }

        .alert-success {
            background: #e8f8ec;
            color: #145f2e;
            border: 1px solid #ccead5;
        }

        .form-group { margin-bottom: 14px; }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #303646;
        }

        .input {
            width: 100%;
            border: 2px solid var(--line);
            border-radius: 10px;
            height: 46px;
            padding: 0 12px;
            font-size: 15px;
            color: #0f1322;
            background: white !important;
        }

        .input:focus {
            outline: none;
            border-color: #8087f3;
            box-shadow: 0 0 0 4px rgba(84, 72, 239, 0.12);
        }

        .password-wrapper { position: relative; }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: #8d93a5;
            cursor: pointer;
            display: grid;
            place-items: center;
        }

        .password-toggle svg { width: 18px; height: 18px; }

        .meta-row {
            margin-top: 2px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #2f3340;
        }

        .remember input { accent-color: #4e49e8; }

        a {
            color: var(--link);
            text-decoration: none;
            font-weight: 600;
        }

        a:hover { text-decoration: underline; }

        .btn {
            width: 100%;
            border: none;
            border-radius: 10px;
            height: 46px;
            background: linear-gradient(90deg, var(--button-a), var(--button-b));
            color: #fff;
            font-size: 24px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(70, 76, 216, 0.3);
        }

        .register {
            margin-top: 14px;
            text-align: center;
            font-size: 14px;
            color: #3f4453;
        }

        .footer {
            margin-top: 34px;
            text-align: center;
            color: #7b808d;
            font-size: 14px;
            line-height: 1.7;
        }

        .footer a {
            color: #7b808d;
            font-weight: 500;
        }

        @media (max-width: 700px) {
            .auth-card { padding: 20px 16px 18px; }
            .brand-name { font-size: 30px; }
            .title { font-size: 42px; }
            .subtitle { font-size: 17px; }
            .btn { font-size: 20px; }
        }
    </style>
</head>
<body>
    <main class="auth-card" style="border: 2px solid #d1d5db !important; background: white !important; border-radius: 20px !important; padding: 40px !important;">
        <div class="brand-wrap">
            <div class="brand-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 4.5C6 3.67 6.67 3 7.5 3H16.5C17.33 3 18 3.67 18 4.5V19.5L12 16L6 19.5V4.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        <h1 class="title">Welcome Back</h1>
        <p class="subtitle">Enter your email and password to access your account.</p>

        @if($errors->any())
        <div class="alert alert-error">
            {{ $errors->first() }}
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="form-group">
                <label for="email">EMAIL</label>
                <input class="input" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">PASSWORD</label>
                <div class="password-wrapper">
                    <input class="input" type="password" id="password" name="password" required>
                    <span class="password-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="meta-row">
                <label class="remember" for="remember">
                    <input type="checkbox" id="remember" name="remember">
                    <span>Remember me</span>
                </label>
                <a href="{{ route('password.request') }}">Forgot Your Password?</a>
            </div>

            <button type="submit" class="btn">Log In</button>
        </form>

        <p class="register">
            Need a first admin? <a href="{{ route('register.admin') }}">Create Admin Account</a>
        </p>
    </main>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle><line x1="1" y1="1" x2="23" y2="23"></line>';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            }
        }
    </script>
</body>
</html>
