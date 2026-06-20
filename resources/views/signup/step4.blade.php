@extends('signup.layout')

@section('content')
    <div class="header">
        <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <div class="title">{{ __('Create Account') }}</div>
    </div>

    <div class="subtitle small">{{ __('Enter Your Child’s') }}<br>{{ __('Details') }}</div>

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
            <label>{{ __('Autism Levels:') }}</label>
            <div class="select-box">
                <select name="autism_level">
                    <option value="">{{ __('Select level') }}</option>
                    <option value="Mild" {{ old('autism_level') == 'Mild' ? 'selected' : '' }}>{{ __('Mild') }}</option>
                    <option value="Moderate" {{ old('autism_level') == 'Moderate' ? 'selected' : '' }}>{{ __('Moderate') }}</option>
                    <option value="Severe" {{ old('autism_level') == 'Severe' ? 'selected' : '' }}>{{ __('Severe') }}</option>
                </select>
            </div>
        </div>

        <div class="spacer"></div>

        <button type="submit" class="btn-next">{{ __('Done') }}</button>

        <div class="footer">
            {{ __('Already have an account?') }}
            <a href="{{ route('login.page') }}">{{ __('Log in') }}</a>
        </div>
    </form>
@endsection