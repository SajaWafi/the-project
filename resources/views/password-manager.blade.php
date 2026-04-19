<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Manager</title>

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
            padding: 20px;
        }

        .mobile-screen {
            width: 390px;
            max-width: 100%;
            height: 844px;
            max-height: 95vh;
            position: relative;
            overflow-y: auto;
            overflow-x: hidden;
            border-radius: 30px;
            background: #f9f9f9;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.14);
            scrollbar-width: none;
        }

        .mobile-screen::-webkit-scrollbar {
            display: none;
        }

        .mobile-screen::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('images/bg.png') }}');
            background-repeat: no-repeat;
            background-size: 165% 100%;
            background-position: right bottom;
            opacity: 0.95;
            z-index: 0;
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
            min-height: 100%;
            padding: 16px 14px 34px;
            display: flex;
            flex-direction: column;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .top-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 44px;
            margin-bottom: 34px;
        }

        .back-btn {
            position: absolute;
            left: 0;
            border: none;
            background: transparent;
            cursor: pointer;
            color: #2f80ed;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-btn svg {
            width: 24px;
            height: 24px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 800;
            color: #1f5b87;
            white-space: nowrap;
        }

        .app-logo {
            position: absolute;
            right: 0;
            width: 100px;
            height: 100px;
            object-fit: contain;
        }

        .form-area {
            margin-top: 8px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 18px;
            font-weight: 700;
            color: #111;
            margin-bottom: 8px;
        }

        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .form-input {
            width: 100%;
            height: 40px;
            border: none;
            outline: none;
            border-radius: 14px;
            background: #cfeeee;
            padding: 0 46px 0 14px;
            font-size: 18px;
            color: #2f80ed;
            letter-spacing: 1px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            color: #2f2f2f;
        }

        .toggle-password svg {
            width: 20px;
            height: 20px;
        }

        .actions {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            padding-top: 40px;
        }

        .btn {
            width: 156px;
            height: 40px;
            border: none;
            border-radius: 999px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn:active {
            transform: scale(0.98);
        }

        .btn-primary {
            background: #2f80ed;
            color: #fff;
            box-shadow: 0 6px 16px rgba(47,128,237,0.22);
        }

        .btn-secondary {
            background: #bfeee4;
            color: #2f80ed;
            box-shadow: 0 6px 14px rgba(0,0,0,0.05);
        }

        .success-box,
        .error-box {
            padding: 10px 14px;
            border-radius: 12px;
            margin-bottom: 14px;
            font-size: 14px;
        }

        .success-box {
            background: #d4edda;
            color: #155724;
        }

        .error-box {
            background: #f8d7da;
            color: #721c24;
        }

        .error-box div + div {
            margin-top: 4px;
        }

        @media (max-width: 480px) {
            body {
                padding: 0;
                background: #fff;
            }

            .mobile-screen {
                width: 100%;
                max-width: 100%;
                height: 100vh;
                max-height: 100vh;
                border-radius: 0;
                box-shadow: none;
            }

            .content {
                padding: 14px 10px 28px;
            }
        }
    </style>
</head>
<body>

    <div class="mobile-screen">
        <div class="content">

            <div class="top-bar">
                <div class="top-right">
                    <div class="status-icon"></div>
                </div>
            </div>

            <div class="header">
                <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div class="page-title">Password Manager</div>

                <img src="{{ asset('images/logo.png') }}" alt="Taif" class="app-logo">
            </div>

            @if(session('success'))
                <div class="success-box">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="error-box">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('password.manager.update') }}" method="POST">
                @csrf

                <div class="form-area">
                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <div class="password-wrapper">
                            <input
                                type="password"
                                id="currentPassword"
                                name="current_password"
                                class="form-input"
                                value="{{ old('current_password') }}"
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword('currentPassword', 'eye1', 'eyeSlash1')">
                                <svg id="eyeSlash1" viewBox="0 0 24 24" fill="none">
                                    <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20C7 20 2.73 16.11 1 12c.92-2.17 2.36-4.02 4.18-5.39" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M9.9 4.24A10.89 10.89 0 0 1 12 4c5 0 9.27 3.89 11 8a11.83 11.83 0 0 1-1.67 2.68" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M14.12 14.12A3 3 0 1 1 9.88 9.88" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M1 1l22 22" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>

                                <svg id="eye1" viewBox="0 0 24 24" fill="none" style="display:none;">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.8"/>
                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <div class="password-wrapper">
                            <input
                                type="password"
                                id="newPassword"
                                name="new_password"
                                class="form-input"
                                value="{{ old('new_password') }}"
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword('newPassword', 'eye2', 'eyeSlash2')">
                                <svg id="eyeSlash2" viewBox="0 0 24 24" fill="none">
                                    <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20C7 20 2.73 16.11 1 12c.92-2.17 2.36-4.02 4.18-5.39" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M9.9 4.24A10.89 10.89 0 0 1 12 4c5 0 9.27 3.89 11 8a11.83 11.83 0 0 1-1.67 2.68" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M14.12 14.12A3 3 0 1 1 9.88 9.88" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M1 1l22 22" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>

                                <svg id="eye2" viewBox="0 0 24 24" fill="none" style="display:none;">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.8"/>
                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <div class="password-wrapper">
                            <input
                                type="password"
                                id="confirmPassword"
                                name="new_password_confirmation"
                                class="form-input"
                                value="{{ old('new_password_confirmation') }}"
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword', 'eye3', 'eyeSlash3')">
                                <svg id="eyeSlash3" viewBox="0 0 24 24" fill="none">
                                    <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20C7 20 2.73 16.11 1 12c.92-2.17 2.36-4.02 4.18-5.39" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M9.9 4.24A10.89 10.89 0 0 1 12 4c5 0 9.27 3.89 11 8a11.83 11.83 0 0 1-1.67 2.68" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M14.12 14.12A3 3 0 1 1 9.88 9.88" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M1 1l22 22" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>

                                <svg id="eye3" viewBox="0 0 24 24" fill="none" style="display:none;">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.8"/>
                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                    <button class="btn btn-secondary" onclick="history.back()" type="button">Cancel</button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function togglePassword(inputId, eyeId, eyeSlashId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            const eyeSlash = document.getElementById(eyeSlashId);

            if (input.type === 'password') {
                input.type = 'text';
                eye.style.display = 'block';
                eyeSlash.style.display = 'none';
            } else {
                input.type = 'password';
                eye.style.display = 'none';
                eyeSlash.style.display = 'block';
            }
        }
    </script>

</body>
</html>