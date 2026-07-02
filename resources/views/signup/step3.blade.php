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

    <div class="subtitle small">{{ __('Enter Your Child’s') }}<br>{{ __('Details') }}</div>

    @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('signup.step3.post') }}" method="POST" style="display:flex; flex-direction:column; height:100%;">
        @csrf

        <div class="field">
            <label>{{ __('Child’s Name:') }}</label>
            <input class="input" type="text" name="child_name" value="{{ old('child_name', session('signup.child_name')) }}">
        </div>

        <div class="field">
            <label>{{ __('Gender:') }}</label>
            <select class="input" name="gender">
                <option value="" disabled {{ old('gender', session('signup.gender')) ? '' : 'selected' }}>{{ __('Select option...') }}</option>
                <option value="Male" {{ old('gender', session('signup.gender')) == 'Male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                <option value="Female" {{ old('gender', session('signup.gender')) == 'Female' ? 'selected' : '' }}>{{ __('Female') }}</option>
            </select>
        </div>

        <div class="field">
            <label>{{ __('Date of Birth:') }}</label>
            <input class="input" type="date" name="dob" value="{{ old('dob', session('signup.child_birth_date')) }}">
        </div>

        <button class="btn" type="submit">{{ __('NEXT') }}</button>
    </form>

    <div class="footer">
        {{ __('Already have an account?') }}
        <a href="{{ route('login.page') }}">{{ __('Log in') }}</a>
    </div>
@endsection