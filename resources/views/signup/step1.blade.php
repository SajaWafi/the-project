@extends('doctor-signup.layout')

@section('content')
    
    <div class="header">
        <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        
        <div class="title">{{ __('Create Account') }}</div>
    </div>

    <div class="subtitle">{{ __('Enter Your Details') }}</div>

    @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('doctor.step1.post') }}" method="POST" style="display:flex; flex-direction:column; flex-grow: 1;">
        @csrf

        <div class="field">
            <label>{{ __('First Name:') }}</label>
            <input class="input" type="text" name="first_name" value="{{ old('first_name', session('doctor_signup.first_name')) }}" required>
        </div>

        <div class="field">
            <label>{{ __('Last Name:') }}</label>
            <input class="input" type="text" name="last_name" value="{{ old('last_name', session('doctor_signup.last_name')) }}" required>
        </div>

        <div class="field">
            <label>{{ __('Your email:') }}</label>
            <input class="input" type="email" name="email" value="{{ old('email', session('doctor_signup.email')) }}" required dir="ltr" style="text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}">
        </div>

        <div class="terms">
            <input type="checkbox" name="agree" value="1" id="agree-checkbox" {{ old('agree') ? 'checked' : '' }} required>
            <label class="terms-text" for="agree-checkbox">
                {{ __('I agree to the Terms & Conditions') }}<br>
                {{ __('and') }} <a href="{{ route('privacy') ?? '#' }}">{{ __('Privacy Policy') }}</a>
            </label>
        </div>

        <button class="btn" type="submit">{{ __('NEXT') }}</button>

        <div class="spacer"></div>

        <div class="footer">
            {{ __('Already have an account?') }} <a href="{{ route('login.page') ?? '#' }}">{{ __('Log in') }}</a>
        </div>
    </form>

@endsection