@extends('signup.layout')

@section('content')
    <div class="header">
        <a href="{{ route('signup.step2') }}" class="back-link">‹</a>
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

    <form action="{{ route('signup.step3.post') }}" method="POST" style="display:flex; flex-direction:column; height:100%;">
        @csrf

        <div class="field">
            <label>Child’ Name:</label>
            <input class="input" type="text" name="child_name" value="{{ old('child_name', session('signup.child_name')) }}">
        </div>

        <div class="field">
            <label>Sex:</label>
            <select class="input" name="sex">
                <option value="" disabled {{ old('sex', session('signup.sex')) ? '' : 'selected' }}>Select option...</option>
                <option value="male" {{ old('sex', session('signup.sex')) == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('sex', session('signup.sex')) == 'female' ? 'selected' : '' }}>Female</option>
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