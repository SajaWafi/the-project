@extends('signup.layout')

@section('content')
    <div class="header">
        <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

        <div class="title">Choose Sign Up</div>
    </div>

    <div class="subtitle">Choose Sign Up As</div>

        <a href="{{ route('signup.step1') }}" class="btn1">Parent</a>

        <a href="{{ route('doctor.step1') }}" class="btn1">Doctor</a>

        <div class="footer1">
        Already have an account?
        <a href="{{ route('login.page') }}">Log in</a>
    </div>

@endsection