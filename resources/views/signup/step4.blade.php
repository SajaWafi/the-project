@extends('signup.layout')

@section('content')
    <div class="header">
        <a href="{{ route('signup.step3') }}" class="back-link">‹</a>
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

    <form action="{{ route('signup.step4.post') }}" method="POST" style="display:flex; flex-direction:column; height:100%;">
        @csrf

        <div class="field">
            <label>Autism Levels:</label>

            <div class="select-box">
                <select name="autism_level">
                    <option value="">Select level</option>
                    <option value="Mild" {{ old('autism_level', session('signup.autism_level')) == 'Mild' ? 'selected' : '' }}>Mild</option>
                    <option value="Moderate" {{ old('autism_level', session('signup.autism_level')) == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                    <option value="Severe" {{ old('autism_level', session('signup.autism_level')) == 'Severe' ? 'selected' : '' }}>Severe</option>
                </select>
            </div>

            <label>Kinship:</label>

            <div class="select-box">
                <select name="kinship">
                    <option value="">Select </option>
                    <option value="Father" {{ old('kinship', session('signup.kinship')) == 'Father' ? 'selected' : '' }}>Father</option>
                    <option value="Mother" {{ old('kinship', session('signup.kinship')) == 'Mother' ? 'selected' : '' }}>Mother</option>
                    <option value="Relative" {{ old('kinship', session('signup.kinship')) == 'Relative' ? 'selected' : '' }}>Relative</option>
                    <option value="Other" {{ old('kinship', session('signup.kinship')) == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

        </div>

        <div class="spacer"></div>

       <a href="{{ route('home') }}" class="btn-next">Done</a>

        <div class="footer">
        Already have an account?
        <a href="{{ route('login.page') }}">Log in</a>
    </form>
@endsection