<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Assignment Management</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=DM+Sans:wght@400;500;700&display=swap');

        :root {
            --surface: #f2f3f7;
            --card: #ffffff;
            --ink: #161616;
            --muted: #7b8094;
            --line: #e5e7f0;
            --brand-a: #2a2de2;
            --brand-b: #4a38ff;
            --brand-c: #5f49ff;
            --brand-ink: #eef0ff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', 'Segoe UI', sans-serif;
            background: radial-gradient(circle at 10% 20%, #ffffff 0%, var(--surface) 60%);
            min-height: 100vh;
            color: var(--ink);
            padding: 0;
        }

        .shell {
            width: 100%;
            min-height: 100vh;
            margin: 0;
            border-radius: 0;
            overflow: hidden;
            background: var(--card);
            box-shadow: none;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
        }

        .panel-left {
            padding: 46px 58px 26px;
            display: grid;
            grid-template-rows: auto 1fr auto;
            gap: 24px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 28px;
            letter-spacing: 0.2px;
        }

        .brand-mark {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: linear-gradient(145deg, var(--brand-a), var(--brand-c));
            position: relative;
        }

        .brand-mark::after {
            content: '';
            position: absolute;
            inset: 7px;
            border: 2px solid rgba(255,255,255,0.9);
            border-radius: 5px;
        }

        .auth-wrap {
            align-self: center;
            max-width: 420px;
            width: 100%;
        }

        .auth-wrap h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(36px, 4vw, 52px);
            line-height: 1.04;
            margin-bottom: 10px;
            letter-spacing: -0.8px;
        }

        .subtitle {
            color: var(--muted);
            margin-bottom: 28px;
            font-size: 15px;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 9px;
            margin-bottom: 14px;
            font-size: 14px;
        }

        .alert-error {
            background: #ffe8ec;
            color: #8b1c33;
            border: 1px solid #ffc8d2;
        }

        .alert-success {
            background: #eaf9ee;
            color: #1f6a34;
            border: 1px solid #caedd4;
        }

        .form-group { margin-bottom: 16px; }

        label {
            display: block;
            margin-bottom: 6px;
            color: #3a3f55;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input {
            width: 100%;
            padding: 12px 44px 12px 14px;
            border: 1px solid var(--line);
            border-radius: 9px;
            font-size: 15px;
            transition: border-color .2s, box-shadow .2s;
            background: #fff;
        }

        .input:focus {
            outline: none;
            border-color: #7b80ff;
            box-shadow: 0 0 0 4px rgba(91, 97, 255, 0.12);
        }

        .password-wrapper { position: relative; }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #73799a;
            user-select: none;
            width: 20px;
            height: 20px;
        }

        .password-toggle svg { width: 100%; height: 100%; }

        .meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 4px 0 18px;
            font-size: 14px;
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #656b85;
            cursor: pointer;
        }

        .remember input { accent-color: #3a39ec; }

        .meta-row a,
        .register a {
            color: #3431e7;
            text-decoration: none;
            font-weight: 600;
        }

        .meta-row a:hover,
        .register a:hover { text-decoration: underline; }

        .btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand-b), var(--brand-c));
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 10px 18px rgba(70, 65, 241, 0.3);
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 20px rgba(70, 65, 241, 0.35);
        }

        .register {
            margin-top: 18px;
            text-align: center;
            color: #7a8095;
            font-size: 14px;
        }

        .panel-foot {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            color: #9ca1b3;
            font-size: 13px;
        }

        .panel-right {
            position: relative;
            overflow: hidden;
            background: linear-gradient(155deg, var(--brand-a) 0%, var(--brand-b) 45%, #3a30d8 100%);
            color: var(--brand-ink);
            padding: 62px 54px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .panel-right::before,
        .panel-right::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            pointer-events: none;
        }

        .panel-right::before {
            width: 420px;
            height: 420px;
            top: -110px;
            right: -160px;
        }

        .panel-right::after {
            width: 380px;
            height: 380px;
            bottom: -170px;
            left: -110px;
        }

        .hero-copy {
            position: relative;
            z-index: 1;
            max-width: 430px;
            margin-bottom: 28px;
        }

        .hero-copy h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(32px, 3.2vw, 44px);
            line-height: 1.07;
            margin-bottom: 12px;
            letter-spacing: -0.6px;
            color: #ffffff;
        }

        .hero-copy p {
            color: rgba(240, 242, 255, 0.86);
            font-size: 15px;
        }

        .dashboard-mock {
            position: relative;
            z-index: 1;
            width: min(470px, 100%);
            background: rgba(255, 255, 255, 0.95);
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 20px 35px rgba(8, 7, 54, 0.32);
        }

        .mock-header {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 12px;
        }

        .mini {
            background: #eef0ff;
            border-radius: 10px;
            height: 86px;
            position: relative;
            overflow: hidden;
        }

        .mini::after {
            content: '';
            position: absolute;
            inset: auto 10px 10px;
            height: 8px;
            border-radius: 99px;
            background: linear-gradient(90deg, #6762ff, #8d84ff);
            opacity: 0.55;
        }

        .mock-table {
            border: 1px solid #eceef6;
            border-radius: 10px;
            overflow: hidden;
        }

        .mock-row {
            height: 34px;
            border-bottom: 1px solid #eceef6;
            background: linear-gradient(90deg, #ffffff 0%, #f7f8fc 100%);
        }

        .mock-row:last-child { border-bottom: none; }

        @media (max-width: 1080px) {
            .panel-left { padding: 40px 34px 22px; }
            .panel-right { padding: 44px 32px; }
        }

        @media (max-width: 900px) {
            .shell {
                grid-template-columns: 1fr;
                min-height: auto;
            }
            .panel-right { display: none; }
            .panel-left { min-height: 100vh; }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="panel-left">
            <div class="brand">
                <span class="brand-mark"></span>
                <span>Assignora</span>
            </div>

            <div class="auth-wrap">
                <h1>Welcome Back</h1>
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
                        <label for="email">Email</label>
                        <input class="input" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input class="input" type="password" id="password" name="password" required>
                            <span class="password-toggle" onclick="togglePassword()">
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
                            <span>Remember Me</span>
                        </label>
                        <a href="{{ route('password.request') }}">Forgot Your Password?</a>
                    </div>

                    <button type="submit" class="btn">Log In</button>
                </form>

                <p class="register">
                    Need a first admin? <a href="{{ route('register.admin') }}">Create Admin Account</a>
                </p>
            </div>

            <div class="panel-foot">
                <span>Copyright {{ date('Y') }} Assignment Management System</span>
                <span>Privacy Policy</span>
            </div>
        </section>

        <section class="panel-right">
            <div class="hero-copy">
                <h2>Effortlessly manage your classes and operations.</h2>
                <p>Log in to access your dashboard, monitor submissions, and keep your academic workflow moving.</p>
            </div>

            <div class="dashboard-mock" aria-hidden="true">
                <div class="mock-header">
                    <div class="mini"></div>
                    <div class="mini"></div>
                    <div class="mini"></div>
                </div>

                <div class="mock-table">
                    <div class="mock-row"></div>
                    <div class="mock-row"></div>
                    <div class="mock-row"></div>
                    <div class="mock-row"></div>
                    <div class="mock-row"></div>
                </div>
            </div>
        </section>
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
