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

    <div class="subtitle">Enter Y<span>o</span>ur Details</div>

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
            <label>Password:</label>
            <div class="input-wrap">
                <input class="input" id="password" type="password" name="password" value="{{ old('password', session('signup.password')) }}">
                <button type="button" class="eye-btn" onclick="togglePassword('password')">👁</button>
            </div>
        </div>

        <div class="field">
            <label>Confirm password:</label>
            <div class="input-wrap">
                <input class="input" id="password_confirmation" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}">
                <button type="button" class="eye-btn" onclick="togglePassword('password_confirmation')">👁</button>
            </div>
        </div>

        <div class="spacer"></div>

        <button class="btn" type="submit">NEXT</button>

         <div class="footer">
        Already have an account?
        <a href="{{ route('login.page') }}">Log in</a>
    </form>
@endsection