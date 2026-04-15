@extends('doctor-signup.layout')

@section('content')
    <div class="header">
        <a href="javascript:void(0)" class="back-link">‹</a>
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

    <form action="{{ route('doctor.step1.post') }}" method="POST" style="display:flex; flex-direction:column; height:100%;">
        @csrf

        <div class="field">
            <label>First Name:</label>
            <input class="input" type="text" name="first_name" value="{{ old('first_name', session('doctor_signup.first_name')) }}">
        </div>

        <div class="field">
            <label>last Name:</label>
            <input class="input" type="text" name="last_name" value="{{ old('last_name', session('doctor_signup.last_name')) }}">
        </div>

        <div class="field">
            <label>Your email:</label>
            <input class="input" type="email" name="email" value="{{ old('email', session('doctor_signup.email')) }}">
        </div>

        <div class="terms">
            <input type="checkbox" name="agree" value="1" {{ old('agree') ? 'checked' : '' }}>
            <div>
                I agree to the <a href="#">Terms & Conditions</a><br>
                and <a href="#">Privacy Policy</a>
            </div>
        </div>

        <button class="btn" type="submit" style="margin-top: 14px;">NEXT</button>

       
        <div class="spacer"></div>

        <div class="footer">
            Already have an account? <a href="{{ route('login.page') }}">Log in</a>
        </div>
    </form>
@endsection