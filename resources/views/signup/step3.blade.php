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

    <div class="subtitle small">Enter Your Child’s<br>Details</div>

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
            <label>Child’ Name:</label>
            <input class="input" type="text" name="child_name" value="{{ old('child_name', session('child_name')) }}">
        </div>

        <div class="field">
            <label>Sex:</label>
            <select class="input" name="gender">
                <option value="" disabled {{ old('gender', session('gender')) ? '' : 'selected' }}>Select option...</option>
                <option value="male" {{ old('gender', session('gender')) == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', session('gender')) == 'female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>

        <div class="field">
            <label>Date of Birth:</label>
            <input class="input" type="date" name="dob" value="{{ old('dob', session('signup.dob')) }}">
        </div>

        <div class="spacer"></div>

        <button class="btn" type="submit">NEXT</button>

         <div class="footer">
        Already have an account?
        <a href="{{ route('login.page') }}">Log in</a>
    </form>
@endsection