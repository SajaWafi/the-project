<!DOCTYPE html>
<html dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Taif</title>
    <style>
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #ffffff; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .mobile-screen { background-image: url('{{ asset("images/bg.png") }}'); background-size: cover; background-position: center bottom; background-repeat: no-repeat; width: 100%; max-width: 400px; height: 100vh; max-height: 800px; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.1); display: flex; flex-direction: column; }
        @media (min-width: 400px) { .mobile-screen { height: 90vh; border-radius: 20px; } }
        .content { padding: 30px 24px; display: flex; flex-direction: column; height: 100%; z-index: 2; }
        .header { display: flex; align-items: center; margin-bottom: 10px; position: relative; }
        .back-btn { background: none; border: none; color: #3b82f6; font-size: 20px; cursor: pointer; padding: 0; position: absolute; left: 0; }
        .logo-container { text-align: center; margin-bottom: 15px; margin-top: 20px; }
        .logo-container img { max-width: 110px; height: auto; }
        .page-title { color: #1e3a8a; font-weight: 800; font-size: 22px; text-align: center; width: 100%; margin: 0 0 5px 0; }
        .subtitle { color: #475569; font-size: 14px; text-align: center; margin-bottom: 30px; }
        .input-group { margin-bottom: 20px; }
        .input-label { display: block; color: #334155; font-size: 14px; margin-bottom: 8px; font-weight: 600; }
        .form-input { width: 100%; background-color: #e0f2fe; border: none; padding: 15px; border-radius: 12px; font-size: 15px; color: #1e293b; box-sizing: border-box; outline: none; }
        .primary-btn { width: 100%; background-color: #2563eb; color: white; border: none; padding: 15px; border-radius: 9999px; font-size: 16px; font-weight: 700; cursor: pointer; margin-top: 10px; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 8px; font-size: 13px; text-align: center; margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="mobile-screen" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="content">
        
        <div class="header">
            <button onclick="window.location.href='{{ route('login.page') }}'" class="back-btn" 
                    style="transform: {{ app()->getLocale() == 'ar' ? 'scaleX(-1)' : 'none' }};">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
        </div>

        <div class="logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="Taif Logo">
        </div>

        <h1 class="page-title">{{ __('Forgot Password') }}</h1>
        <p class="subtitle" style="text-align: center;">{{ __('Enter your registered email address to receive a password reset code.') }}</p>

        @if($errors->any()) 
            <div class="alert-error">{{ $errors->first() }}</div> 
        @endif

        <form method="POST" action="{{ route('forgot.password.post') }}" style="text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};">
            @csrf
            <div class="input-group">
                <label class="input-label">{{ __('Your email:') }}</label>
                <input type="email" name="email" class="form-input" placeholder="example@gmail.com" required>
            </div>
            <button type="submit" class="primary-btn">{{ __('SEND CODE') }}</button>
        </form>
    </div>
</div>
</body>
</html>