@extends('signup.layout')

@section('content')
    <!-- الهيدر الاحترافي مع زر تغيير اللغة -->
    <div class="header" style="position: relative; display: flex; justify-content: center; align-items: center; height: 60px; padding: 0 15px;">
        
        <button class="back-btn" onclick="history.back()" type="button" aria-label="Back" style="position: absolute; left: 15px; background: transparent; border: none; cursor: pointer; padding: 0; color: #1f5b87; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" style="width: 24px; height: 24px;">
                <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <div class="title" style="margin: 0; font-size: 22px; font-weight: 800; color: #1f5b87;">{{ __('Create Account') }}</div>

     
    </div>

    <div class="subtitle">{{ __('Enter Your Details') }}</div>

    @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('step1.post') }}" method="POST" style="display:flex; flex-direction:column; height:100%;">
        @csrf

        <div class="field">
            <label>{{ __('First Name:') }}</label>
            <input class="input" type="text" name="first_name" value="{{ old('first_name', session('first_name')) }}">
        </div>

        <div class="field">
            <label>{{ __('Last Name:') }}</label>
            <input class="input" type="text" name="last_name" value="{{ old('last_name', session('last_name')) }}">
        </div>

        <div class="field">
            <label>{{ __('Phone Number:') }}</label>
            <input class="input" type="text" name="phone" value="{{ old('phone', session('phone')) }}">
        </div>

        <label>{{ __('Kinship:') }}</label>
        <div class="select-box">
            <select name="relation_to_child">
                <option value="">{{ __('Select') }}</option>
                <option value="Father" {{ old('relation_to_child') == 'Father' ? 'selected' : '' }}>{{ __('Father') }}</option>
                <option value="Mother" {{ old('relation_to_child') == 'Mother' ? 'selected' : '' }}>{{ __('Mother') }}</option>
                <option value="Relative" {{ old('relation_to_child') == 'Relative' ? 'selected' : '' }}>{{ __('Relative') }}</option>
                <option value="Other" {{ old('relation_to_child') == 'Other' ? 'selected' : '' }}>{{ __('Other') }}</option>
            </select>
        </div>

        <div class="terms">
            <input type="checkbox" name="agree" value="1" {{ old('agree') ? 'checked' : '' }}>
            <div>
                {{ __('I agree to the') }} <a href="{{ route('privacy.policy') }}">{{ __('Privacy Policy') }}</a>
            </div>
        </div>

        <div class="spacer"></div>
        <button class="btn" type="submit">{{ __('NEXT') }}</button>

        <div class="footer">
            {{ __('Already have an account?') }}
            <a href="{{ route('login.page') }}">{{ __('Log in') }}</a>
        </div>
    </form>
@endsection