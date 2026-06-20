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

    <form action="{{ route('doctor.step3.post') }}" method="POST" style="display:flex; flex-direction:column; flex-grow: 1;">
        @csrf

        <div class="field">
            <label>{{ __('Phone Number:') }}</label>
            <input class="input" type="tel" name="phone" value="{{ old('phone', session('doctor_signup.phone')) }}" required dir="ltr" style="text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};">
        </div>

        <div class="field">
            <label>{{ __('Gender:') }}</label>
            <select class="select-input" name="gender" required>
                <option value="" disabled {{ old('gender', session('doctor_signup.gender')) == '' ? 'selected' : '' }}>{{ __('Select') }}</option>
                <option value="Male" {{ old('gender', session('doctor_signup.gender')) == 'Male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                <option value="Female" {{ old('gender', session('doctor_signup.gender')) == 'Female' ? 'selected' : '' }}>{{ __('Female') }}</option>
            </select>
        </div>

        <div class="field">
            <label>{{ __('Specialize in:') }}</label>
            <input class="input" type="text" name="specialize" value="{{ old('specialize', session('doctor_signup.specialize')) }}" required>
        </div>

        <div class="field">
            <label>{{ __('Date of Birth:') }}</label>
            <input class="input" type="date" name="dob" value="{{ old('dob', session('doctor_signup.dob')) }}" required>
        </div>

        <div class="spacer"></div>

        <button class="btn" type="submit">{{ __('DONE') }}</button>

        <div class="footer">
            {{ __('Already have an account?') }} <a href="{{ route('login.page') ?? '#' }}">{{ __('Log in') }}</a>
        </div>
    </form>
    
@endsection