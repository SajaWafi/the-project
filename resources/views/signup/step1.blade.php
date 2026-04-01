@extends('signup.layout')

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

    <form action="{{ route('signup.step1.post') }}" method="POST" style="display:flex; flex-direction:column; height:100%;">
        @csrf

        <div class="field">
            <label>Your email:</label>
            <input class="input" type="email" name="email" value="{{ old('email', session('signup.email')) }}">
        </div>

        <div class="field">
            <label>Full Name:</label>
            <input class="input" type="text" name="full_name" value="{{ old('full_name', session('signup.full_name')) }}">
        </div>

        <div class="field">
            <label>Phone Number:</label>
            <input class="input" type="text" name="phone" value="{{ old('phone', session('signup.phone')) }}">
        </div>

        <div class="terms">
            <input type="checkbox" name="agree" value="1" {{ old('agree') ? 'checked' : '' }}>
            <div>
                I agree to the <a href="#">Terms & Conditions</a><br>
                and <a href="#">Privacy Policy</a>
            </div>
        </div>

        <div class="spacer"></div>

        <button class="btn" type="submit">NEXT</button>

        <div class="footer">
            Already have an account? <a href="#">Log in</a>
        </div>
    </form>
@endsection