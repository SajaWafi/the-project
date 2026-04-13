@extends('signup.layout')

@section('content')
    <div class="header">
        <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

        <div class="title">Create Account</div>
    </div>

    <div class="subtitle">Enter Your Details</div>

    @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('parent.signup.store') }}" method="POST" style="display:flex; flex-direction:column; height:100%;">
        @csrf

        <div class="field">
            <label>Your email:</label>
            <input class="input" type="email" name="email" value="{{ old('email', session('email')) }}">
        </div>

        <div class="field">
            <label>First Name:</label>
            <input class="input" type="text" name="first_name" value="{{ old('first_name', session('first_name')) }}">
        </div>

            <div class="field">
            <label>Last Name:</label>
            <input class="input" type="text" name="last_name" value="{{ old('last_name', session('last_name')) }}">
        </div>

        <div class="field">
            <label>Phone Number:</label>
            <input class="input" type="text" name="phone" value="{{ old('phone', session('phone')) }}">
        </div>

        <div class="terms">
            <input type="checkbox" name="agree" value="1" {{ old('agree') ? 'checked' : '' }}>
            <div>
                I agree to the 
    
                <a href="{{ route('privacy.policy') }}">Privacy Policy</a>
            </div>
        </div>

        <div class="spacer"></div>

        <button class="btn" type="submit">NEXT</button>

        <div class="footer">
        Already have an account?
        <a href="{{ route('login.page') }}">Log in</a>
    </div>
    </form>
@endsection