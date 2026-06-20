@extends('signup.layout')

@section('content')
    <div class="header" style="position: relative; display: flex; justify-content: center; align-items: center; height: 60px; padding: 0 15px;">
        
        <button class="back-btn" onclick="history.back()" type="button" aria-label="Back" style="position: absolute; left: 15px; background: transparent; border: none; cursor: pointer; padding: 0; color: #1f5b87; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" style="width: 24px; height: 24px;">
                <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <div class="title" style="margin: 0; font-size: 19px; font-weight: 800; color: #1f5b87;">
            {{ __('Choose Sign Up') }}
        </div>

        <div class="lang-switch" style="position: absolute; right: 15px;">
            @if(app()->getLocale() == 'en')
                <a href="{{ route('lang.switch', 'ar') }}" style="display: flex; align-items: center; gap: 5px; text-decoration: none; font-size: 13px; font-weight: 800; color: #f6ad55; background: rgba(246, 173, 85, 0.15); padding: 5px 12px; border-radius: 20px; transition: 0.2s;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    عربي
                </a>
            @else
                <a href="{{ route('lang.switch', 'en') }}" style="display: flex; align-items: center; gap: 5px; text-decoration: none; font-size: 13px; font-weight: 800; color: #f6ad55; background: rgba(246, 173, 85, 0.15); padding: 5px 12px; border-radius: 20px; transition: 0.2s;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    EN
                </a>
            @endif
        </div>
    </div>

    <div class="subtitle">{{ __('Choose Sign Up As') }}</div>

    <a href="{{ route('signup.step1') }}" class="btn1">{{ __('Parent') }}</a>

    <a href="{{ route('doctor.step1') }}" class="btn1">{{ __('Doctor') }}</a>

    <div class="footer1">
        {{ __('Already have an account?') }}
        <a href="{{ route('login.page') }}">{{ __('Log in') }}</a>
    </div>

@endsection