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

    <form action="{{ route('doctor.step3.post') }}" method="POST" style="display:flex; flex-direction:column; height:100%;">
        @csrf

        <div class="field">
            <label>Phone Number:</label>
            <input class="input" type="text" name="phone" value="{{ old('phone', session('doctor_signup.phone')) }}">
        </div>

        <div class="field">
            <label>Gender:</label>
            <select class="select-box" name="gender">
                <option value="">Select</option>
                <option value="Male" {{ old('gender', session('doctor_signup.gender')) == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', session('doctor_signup.gender')) == 'Female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>

        <div class="field">
            <label>Specialize in:</label>
            <input class="input" type="text" name="specialize" value="{{ old('specialize', session('doctor_signup.specialize')) }}">
        </div>

        <div class="field">
            <label>Date of Birth:</label>
            <input class="input" type="date" name="dob" value="{{ old('dob', session('doctor_signup.dob')) }}">
        </div>

        <div class="spacer"></div>

        <div class="btn">
            <button class="btn" type="submit">DONE</button>
        </div>

        <div class="footer">
            Already have an account? <a href="{{ route('login.page') }}">Log in</a>
        </div>
    </form>
@endsection