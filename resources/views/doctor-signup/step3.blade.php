@extends('doctor-signup.layout')

@section('content')
    <div class="header">
        <a href="{{ route('doctor.signup.step2') }}" class="back-link">‹</a>
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

    <form action="{{ route('doctor.signup.step3.post') }}" method="POST" style="display:flex; flex-direction:column; height:100%;">
        @csrf

        <div class="field">
            <label>Phone Number:</label>
            <input class="input" type="text" name="phone" value="{{ old('phone', session('doctor_signup.phone')) }}">
        </div>

        <div class="field">
            <label>Sex:</label>
            <select class="select-box" name="sex">
                <option value="">Select</option>
                <option value="Male" {{ old('sex', session('doctor_signup.sex')) == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('sex', session('doctor_signup.sex')) == 'Female' ? 'selected' : '' }}>Female</option>
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
            <a href="{{ route('doctor.home') }}" class="btn-text" type="submit">DONE</a>
        </div>

        <div class="footer">
            Already have an account? <a href="#">Log in</a>
        </div>
    </form>
@endsection