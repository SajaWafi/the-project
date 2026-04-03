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
            max-width: 360px; /* هذا المهم */
            height: 100vh;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
            border-radius: 32px;
            background: #f8f8f8;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .mobile-screen::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('images/bg.png') }}');
            background-repeat: no-repeat;
            background-size: 150% 100%;
            background-position: right top;
            opacity: 0.95;
            z-index: 0;
        }

        .content {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 100%;
            padding: 28px 24px 26px;
            display: flex;
            flex-direction: column;
        }

        .top-logo {
            width: 100px;
            margin-bottom: 15px;
            align-self: flex-start;
        }

        .title {
            font-size: 30px;
            font-weight: 700;
            color: #424242;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 30px;
        }

        .label {
            display: block;
            font-size: 20px;
            color: #4b5563;
            margin-bottom: 22px;
        }

        .input {
            width: 100%;
            height: 45px;
            border: none;
            outline: none;
            border-radius: 14px;
            background: #cfeeee;
            padding: 0 16px;
            font-size: 16px;
            color: #374151;
        }

        .check-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 4px 0 22px;
            color: #6b7280;
            font-size: 16px;
        }

        .check-row input[type="checkbox"] {
            accent-color: #e3984a;
            width: 15px;
            height: 15px;
        }

        .btn-login {
             width: 100%;
            height: 60px;  
            border-radius: 16px;
            font-size: 18px;
            border: none;
            border-radius: 14px;
            background: #2f80ed;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 14px rgba(47, 128, 237, 0.25);
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
            height: 60px;
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
            margin-bottom: auto;
        }

        .google-icon {
            width: 22px;
            height: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 700;
        }

        .bottom-text {
            text-align: center;
            font-size: 16px;
            color: #6b7280;
            margin-top: 18px;
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

            .mobile-screen::before {
                background-size: 155% 100%;
                background-position: right top;
            }
        }
          
    .password-wrapper {
        position: relative;
        width: 100%;
    }

    .password-input {
        width: 100%;
        padding-right: 48px;
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 14px;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
    
    .mobile-screen {
            width: 100%;
            max-width: 360px; /* هذا المهم */
            max-height: 800px;
            height: 100vh;
            background: url('{{ asset('pics/bg.png') }}') no-repeat;
            background-position: left;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }
    </style>
</head>
<body>

    <div class="mobile-screen">
        <div class="content">

            <img src="{{ asset('images/logo.png') }}" alt="Top Logo" class="top-logo">

            <h1 class="title">Log in with Email</h1>

            <div class="form-group">
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

            <div class="bottom-text">
                Don’t have an account?
                <a href="{{ route('signup.step1') }}">Sign up</a>
            </div>

        </div>
    </div>
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeSlashIcon = document.getElementById('eyeSlashIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeSlashIcon.style.display = 'none';
            eyeIcon.style.display = 'block';
        } else {
            passwordInput.type = 'password';
            eyeSlashIcon.style.display = 'block';
            eyeIcon.style.display = 'none';
        }
    }
</script>
</body>
</html>