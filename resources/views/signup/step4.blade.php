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

    <form action="{{ route('signup.step4.post') }}" method="POST" style="display:flex; flex-direction:column; height:100%;">
        @csrf

        <div class="field">
            <label>Autism Levels:</label>

            <div class="select-box">
                <select name="autism_level">
                    <option value="">Select level</option>
                    <option value="Mild" {{ old('autism_level') == 'Mild' ? 'selected' : '' }}>Mild</option>
                    <option value="Moderate" {{ old('autism_level') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                    <option value="Severe" {{ old('autism_level') == 'Severe' ? 'selected' : '' }}>Severe</option>
                </select>
            </div>

        </div>

        <div class="spacer"></div>

       <button type="submit" class="btn-next">Done</button>

        <div class="footer">
        Already have an account?
        <a href="{{ route('login.page') }}">Log in</a>
    </form>
@endsection