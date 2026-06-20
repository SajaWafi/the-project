<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Verify Email') }}</title>
</head>
<body>

    <h2>{{ __('Verify Your Email') }}</h2>

    <p>{{ __('We sent a verification code to your email.') }}</p>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            <p style="color: red;">{{ $error }}</p>
        @endforeach
    @endif

    <form method="POST" action="{{ route('verify.email.submit') }}">
        @csrf

        <input
            type="text"
            name="code"
            placeholder="{{ __('Enter 6 digit code') }}"
            maxlength="6"
            required
        >

        <br><br>

        <button type="submit">{{ __('Verify') }}</button>
    </form>

    <br>

    <form method="POST" action="{{ route('verify.email.resend') }}">
        @csrf
        <button type="submit">{{ __('Resend Code') }}</button>
    </form>

</body>
</html>