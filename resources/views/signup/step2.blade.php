@extends('signup.layout')

@section('content')
    <div class="header">
        <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <div class="title">{{ __('Create Account') }}</div>
    </div>

    <div class="subtitle">{{ __('Enter Your Details') }}</div>

    @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('signup.step2.post') }}" method="POST" style="display:flex; flex-direction:column; height:100%;">
        @csrf

        <div class="field">
            <label>{{ __('Your email:') }}</label>
            <input class="input" type="email" name="email" value="{{ old('email') }}">
        </div>

        <div class="field">
            <label>{{ __('Password:') }}</label>
            <div class="input-wrap">
                <input class="input" id="password" type="password" name="password" value="{{ old('password') }}">
                <button type="button" class="eye-btn" onclick="togglePassword('password')">👁</button>
            </div>
        </div>

        <div class="field">
            <label>{{ __('Confirm password:') }}</label>
            <div class="input-wrap">
                <input class="input" id="password_confirmation" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}">
                <button type="button" class="eye-btn" onclick="togglePassword('password_confirmation')">👁</button>
            </div>
        </div>

        <div class="spacer"></div>

        <button class="btn" type="submit">{{ __('NEXT') }}</button>

        <div class="footer">
            {{ __('Already have an account?') }}
            <a href="{{ route('login.page') }}">{{ __('Log in') }}</a>
        </div>
    </form>
@endsection