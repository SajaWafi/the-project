<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #edf1f4;
            font-family: Arial, sans-serif;
        }

        .mobile-screen {
            width: 100%;
            max-width: 360px;
            height: 100vh;
            max-height: 800px;
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            background: #f8f8f8;
            box-shadow: 0 12px 30px rgba(0,0,0,0.20);
        }

        .mobile-screen::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('images/bg.png') }}');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            opacity: 0.96;
            z-index: 0;
        }

        .content {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 100%;
            padding: 24px 22px 24px;
            display: flex;
            flex-direction: column;
        }

        .header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            min-height: 40px;
        }

        .back-btn {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            color: #2f80ed;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-btn svg {
            width: 22px;
            height: 22px;
        }

        .title {
            font-size: 20px;
            font-weight: 800;
            color: #1d567e;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            font-size: 15px;
            color: #6b7280;
            margin-bottom: 24px;
        }

        .top-logo {
            width: 75px;
            margin: 0 auto 16px;
            display: block;
        }

        .error-box {
            background: #fde8e8;
            color: #c53030;
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-size: 14px;
            line-height: 1.5;
        }

        .success-box {
            background: #e6ffed;
            color: #1f7a39;
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 18px;
            font-size: 14px;
            line-height: 1.5;
        }

        .field {
            margin-bottom: 20px;
        }

        .label {
            display: block;
            font-size: 17px;
            color: #4b5563;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .input {
            width: 100%;
            height: 48px;
            border: none;
            outline: none;
            border-radius: 14px;
            background: #cfeeee;
            padding: 0 16px;
            font-size: 16px;
            color: #374151;
        }

        .input-wrap {
            position: relative;
            width: 100%;
        }

        .input-wrap .input {
            padding-right: 50px;
        }

        .eye-btn {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 18px;
            color: #555;
        }

        .check-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 2px 0 20px;
            color: #6b7280;
            font-size: 15px;
        }

        .check-row input[type="checkbox"] {
            accent-color: #e3984a;
            width: 15px;
            height: 15px;
        }

        .btn-login {
            width: 100%;
            height: 54px;
            border: none;
            border-radius: 16px;
            background: #2f80ed;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 14px rgba(47, 128, 237, 0.25);
            margin-top: 6px;
            margin-bottom: 18px;
        }

        .divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 16px;
            color: #555;
            font-size: 16px;
        }

        .divider::before,
        .divider::after {
            content: "";
            width: 80px;
            height: 1px;
            background: #666;
            opacity: 0.6;
        }

        .google-btn {
            width: 100%;
            height: 56px;
            border-radius: 16px;
            border: 1.5px solid #87d7cc;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            color: #35b8a6;
            font-size: 15px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .bottom-text {
            text-align: center;
            font-size: 15px;
            color: #6b7280;
            margin-top: auto;
            padding-top: 20px;
        }

        .bottom-text a {
            color: #e59749;
            font-weight: 700;
            text-decoration: none;
        }

        @media (max-width: 480px) {
            body {
                padding: 0;
                background: #fff;
            }

            .mobile-screen {
                width: 100%;
                height: 100vh;
                max-width: 100%;
                max-height: 100vh;
                border-radius: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
<div class="mobile-screen">
    <div class="content">


    <div class="header">
        <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <div class="title">Login with Email</div>
        
    </div>        

        <div class="subtitle">Login to continue</div>

        @if (session('success'))
            <div class="success-box">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" style="display:flex; flex-direction:column; flex:1;">
            @csrf

            <div class="field">
                <label class="label" for="email">Email:</label>
                <input
                    class="input"
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                >
            </div>

            <div class="field">
                <label class="label" for="password">Password:</label>
                <div class="input-wrap">
                    <input
                        class="input"
                        id="password"
                        type="password"
                        name="password"
                        required
                    >
                    <button type="button" class="eye-btn" onclick="togglePassword()">👁</button>
                </div>
            </div>

            <button type="submit" class="btn-login">Login</button>

            <div class="divider">or</div>

            <a href="#" class="google-btn">
                <svg width="20" height="20" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.69 1.22 9.18 3.6l6.85-6.85C35.91 2.32 30.36 0 24 0 14.82 0 6.73 5.2 2.69 12.81l7.98 6.2C12.3 13.6 17.69 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.5 24.5c0-1.63-.15-3.2-.43-4.71H24v9.02h12.7c-.55 2.96-2.22 5.47-4.74 7.15l7.29 5.67C43.98 37.5 46.5 31.47 46.5 24.5z"/>
                    <path fill="#FBBC05" d="M10.67 28.99A14.5 14.5 0 0 1 9.5 24c0-1.73.3-3.41.84-4.99l-7.98-6.2A23.93 23.93 0 0 0 0 24c0 3.87.93 7.52 2.59 10.81l8.08-5.82z"/>
                    <path fill="#34A853" d="M24 48c6.36 0 11.71-2.1 15.61-5.7l-7.29-5.67c-2.02 1.36-4.6 2.17-8.32 2.17-6.31 0-11.7-4.1-13.63-9.52l-8.08 5.82C6.73 42.8 14.82 48 24 48z"/>
                </svg>
                <span>Login with Google</span>
            </a>
        </form>

        <div class="bottom-text">
            Don’t have an account?
            <a href="{{ route('signup.choice') }}">Sign up</a>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
    }
</script>
</body>
</html>

<!--

    <div class="mobile-screen">
        <div class="content">
            <form action="{{ route('step1.post') }}" method="POST" style="display:flex; flex-direction:column; height:100%;">
                @csrf


            </form>
            <h1 class="title">Log in with Email</h1>

            <div class="form-group" >
                <label class="label" for="email">Your email:</label>
                <input type="email" id="email" class="input">
            </div>

          <div class="form-group">
    <label class="label" for="password">Password:</label>

    <div class="password-wrapper">
        <input type="password" id="password" class="input password-input">

        <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Toggle password visibility">
            <svg id="eyeSlashIcon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20C7 20 2.73 16.11 1 12c.92-2.17 2.36-4.02 4.18-5.39" />
                <path d="M9.9 4.24A10.89 10.89 0 0 1 12 4c5 0 9.27 3.89 11 8a11.83 11.83 0 0 1-1.67 2.68" />
                <path d="M14.12 14.12A3 3 0 1 1 9.88 9.88" />
                <path d="M1 1l22 22" />
            </svg>

            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
        </button>
    </div>
</div>
            <label class="check-row">
                <input type="checkbox" checked>
                <span>keep me logged in</span>
            </label>

            <button class="btn-login">Login</button>

            <div class="divider">or</div>

            <a href="#" class="google-btn">
            <svg width="20" height="20" viewBox="0 0 48 48">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.69 1.22 9.18 3.6l6.85-6.85C35.91 2.32 30.36 0 24 0 14.82 0 6.73 5.2 2.69 12.81l7.98 6.2C12.3 13.6 17.69 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.5 24.5c0-1.63-.15-3.2-.43-4.71H24v9.02h12.7c-.55 2.96-2.22 5.47-4.74 7.15l7.29 5.67C43.98 37.5 46.5 31.47 46.5 24.5z"/>
                <path fill="#FBBC05" d="M10.67 28.99A14.5 14.5 0 0 1 9.5 24c0-1.73.3-3.41.84-4.99l-7.98-6.2A23.93 23.93 0 0 0 0 24c0 3.87.93 7.52 2.59 10.81l8.08-5.82z"/>
                <path fill="#34A853" d="M24 48c6.36 0 11.71-2.1 15.61-5.7l-7.29-5.67c-2.02 1.36-4.6 2.17-8.32 2.17-6.31 0-11.7-4.1-13.63-9.52l-8.08 5.82C6.73 42.8 14.82 48 24 48z"/>
            </svg>

    <span>Login with Google</span>
</a>
-->