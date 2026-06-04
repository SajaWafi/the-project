<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Taif</title>
    <style>
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #ffffff; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .mobile-screen { background-image: url('{{ asset("images/bg.png") }}'); background-size: cover; background-position: center bottom; background-repeat: no-repeat; width: 100%; max-width: 400px; height: 100vh; max-height: 800px; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.1); display: flex; flex-direction: column; overflow-y: auto; }
        @media (min-width: 400px) { .mobile-screen { height: 90vh; border-radius: 20px; } }
        .content { padding: 30px 24px; display: flex; flex-direction: column; z-index: 2; }
        .logo-container { text-align: center; margin-bottom: 15px; }
        .logo-container img { max-width: 90px; height: auto; }
        .page-title { color: #1e3a8a; font-weight: 800; font-size: 22px; text-align: center; margin: 0 0 20px 0; }
        .input-group { margin-bottom: 15px; }
        .input-label { display: block; color: #334155; font-size: 14px; margin-bottom: 8px; font-weight: 600; }
        .form-input { width: 100%; background-color: #e0f2fe; border: none; padding: 15px; border-radius: 12px; font-size: 15px; color: #1e293b; box-sizing: border-box; outline: none; }
        .code-input { width: 100%; background-color: #e0f2fe; border: none; padding: 15px; border-radius: 12px; font-size: 20px; font-weight: bold; color: #1e293b; text-align: center; letter-spacing: 10px; box-sizing: border-box; outline: none; }
        .primary-btn { width: 100%; background-color: #2563eb; color: white; border: none; padding: 15px; border-radius: 9999px; font-size: 16px; font-weight: 700; cursor: pointer; margin-top: 10px; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; font-size: 13px; text-align: center; margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="mobile-screen">
        <div class="content">
            <div class="logo-container"><img src="{{ asset('images/logo.png') }}" alt="Taif Logo"></div>
            <h1 class="page-title">Create New Password</h1>

            @if($errors->any()) <div class="alert-error">{{ $errors->first() }}</div> @endif

            <form method="POST" action="{{ route('reset.password.post') }}">
                @csrf
                <div class="input-group">
                    <label class="input-label">6-Digit Code:</label>
                    <input type="text" name="code" class="code-input" placeholder="000000" maxlength="6" required autocomplete="off">
                </div>
                <div class="input-group">
                    <label class="input-label">New Password:</label>
                    <input type="password" name="password" class="form-input" placeholder="Enter new password" required>
                </div>
                <div class="input-group">
                    <label class="input-label">Confirm Password:</label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm new password" required>
                </div>
                <button type="submit" class="primary-btn">RESET PASSWORD</button>
            </form>
        </div>
    </div>
</body>
</html>