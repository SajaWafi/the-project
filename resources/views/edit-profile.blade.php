<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Edit Profile') }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #edf1f4;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .mobile-screen {
            width: 390px;
            max-width: 100%;
            height: 844px;
            max-height: 95vh;
            position: relative;
            overflow-y: auto;
            overflow-x: hidden;
            border-radius: 30px;
            background: #f9f9f9;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.14);
            scrollbar-width: none;
        }

        .mobile-screen::-webkit-scrollbar {
            display: none;
        }

        .mobile-screen::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('images/bg.png') }}');
            background-repeat: no-repeat;
            background-size: 165% 100%;
            /* 💡 خلفية متكيفة مع اتجاه اللغة */
            background-position: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }} bottom;
            opacity: 0.92;
            z-index: 0;
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
            min-height: 100%;
            padding: 16px 18px 30px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .top-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            min-height: 42px;
        }

        .back-btn {
            position: absolute;
            inset-inline-start: 0; /* 💡 محاذاة منطقية لزر الرجوع */
            border: none;
            background: transparent;
            cursor: pointer;
            color: #2f80ed;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-btn svg {
            width: 24px;
            height: 24px;
            /* 💡 قلب سهم الرجوع إذا كانت الشاشة بالعربي */
            transform: scaleX({{ app()->getLocale() == 'ar' ? '-1' : '1' }});
        }

        .page-title {
            font-size: 22px;
            font-weight: 800;
            color: #1f5b87;
        }

        .app-logo {
            position: absolute;
            inset-inline-end: 0; /* 💡 محاذاة منطقية لشعار التطبيق */
            width: 100px;
            height: 100px;
            object-fit: contain;
        }

        .profile-top {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 8px;
            margin-bottom: 10px;
        }

        .avatar-wrap {
            position: relative;
            width: 116px;
            height: 116px;
            margin-bottom: 10px;
        }

        .avatar {
            width: 116px;
            height: 116px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            border: 4px solid rgba(255,255,255,0.95);
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }

        .avatar-star {
            position: absolute;
            inset-inline-start: -8px; /* 💡 محاذاة منطقية للنجمة */
            top: 68px;
            font-size: 26px;
            color: #f1d46a;
            line-height: 1;
        }

        .edit-avatar-btn {
            position: absolute;
            inset-inline-end: 0; /* 💡 محاذاة منطقية لزر التعديل */
            bottom: 6px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: #1f5b87;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(31, 91, 135, 0.25);
        }

        .edit-avatar-btn svg {
            width: 15px;
            height: 15px;
        }

        #avatarInput {
            display: none;
        }

        .profile-name {
            font-size: 18px;
            font-weight: 800;
            color: #111;
            margin-bottom: 8px;
            text-align: center;
        }

        .form-area {
            margin-top: 6px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-label {
            display: block;
            font-size: 18px;
            font-weight: 600;
            color: #111;
            margin-bottom: 6px;
            text-align: start; /* 💡 محاذاة النص المنطقية */
        }

        .form-input,
        .form-select {
            width: 100%;
            height: 40px;
            border: none;
            outline: none;
            border-radius: 14px;
            background: #cfeeee;
            padding: 0 14px;
            font-size: 15px;
            color: #1f2937;
            text-align: start; /* 💡 محاذاة الإدخال المنطقية */
        }

        .ltr-input {
            direction: ltr; /* لضمان صحة كتابة الإيميل والأرقام من اليسار لليمين */
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        .form-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: none;
            cursor: pointer;
            padding-inline-end: 34px; /* 💡 تفريغ مساحة لعلامة الصح المنطقية */
        }

        .select-wrap {
            position: relative;
        }

        .select-wrap::after {
            content: "✓";
            position: absolute;
            inset-inline-end: 14px; /* 💡 وضع علامة الصح في النهاية المنطقية */
            top: 50%;
            transform: translateY(-50%);
            color: #41c8b6;
            font-size: 22px;
            font-weight: 700;
            pointer-events: none;
        }

        .save-btn {
            width: 45%;
            height: 52px;
            background: #2f80ed;
            color: #fff;
            border: none;
            border-radius: 18px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 20px;
            box-shadow: 0 6px 18px rgba(47,128,237,0.3);
            transition: 0.2s;
        }

        .save-btn:active {
            transform: scale(0.97);
        }

        .alert-success,
        .alert-error,
        .alert-warning {
            padding: 10px 14px;
            border-radius: 12px;
            margin-bottom: 12px;
            font-size: 14px;
            text-align: start; /* 💡 محاذاة التنبيهات */
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
        }

        .alert-warning ul {
            padding-inline-start: 18px; /* 💡 هوامش القوائم المنطقية */
            margin: 0;
        }

        @media (max-width: 480px) {
            body { padding: 0; background: #fff; }
            .mobile-screen { width: 100%; max-width: 100%; height: 100vh; max-height: 100vh; border-radius: 0; box-shadow: none; }
            .content { padding: 14px 16px 24px; }
        }
    </style>
</head>
<body>

@php
    $user = auth()->user();
    $child = $user?->child;
@endphp

<div class="mobile-screen">
    <div class="content">

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-warning">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="top-bar">
            <div class="top-right">
                <div class="status-icon"></div>
            </div>
        </div>

        <div class="header">
            <button class="back-btn" onclick="history.back()" type="button" aria-label="{{ __('Back') }}">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="page-title">{{ __('Profile') }}</div>
            <img src="{{ asset('images/logo.png') }}" alt="Taif" class="app-logo">
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="profile-top">
                <div class="avatar-wrap">
                  <img 
                id="profilePreview"
                src="{{ !empty(auth()->user()->profile_image)
                    ? asset('storage/' . auth()->user()->profile_image)
                    : asset('images/default-user.png') }}"
                alt="{{ __('Profile') }}"
                class="avatar"
            >
                    <div class="avatar-star">★</div>

                    <button class="edit-avatar-btn" type="button" onclick="document.getElementById('avatarInput').click()">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M4 20h4l10-10-4-4L4 16v4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            <path d="M12.5 5.5 16.5 9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </button>

                    <input type="file" id="avatarInput" name="profile_image" accept="image/*">
                </div>

                <div class="profile-name">
                    {{ $user?->first_name ?? '' }} {{ $user?->last_name ?? '' }}
                </div>
            </div>

            <div class="form-area">
                <div class="form-group">
                    <label class="form-label">{{ __('First name') }}</label>
                    <input type="text" class="form-input" name="first_name" value="{{ old('first_name', $user?->first_name ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Last name') }}</label>
                    <input type="text" class="form-input" name="last_name" value="{{ old('last_name', $user?->last_name ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Phone Number') }}</label>
                    <input type="text" class="form-input ltr-input" name="phone" value="{{ old('phone', $user?->phone ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Email') }}</label>
                    <input type="email" class="form-input ltr-input" name="email" value="{{ old('email', $user?->email ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Child Name') }}</label>
                    <input type="text" class="form-input" name="child_name" value="{{ old('child_name', $child?->name ?? '') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Gender') }}</label>
                    <div class="select-wrap">
                        <select class="form-select" name="gender">
                            <option value="">{{ __('Select') }}</option>
                            <option value="male" {{ old('gender', $child?->gender ?? '') == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                            <option value="female" {{ old('gender', $child?->gender ?? '') == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Autism Levels') }}</label>
                    <div class="select-wrap">
                        <select class="form-select" name="autism_level">
                            <option value="">{{ __('Select level') }}</option>
                            <option value="Mild" {{ old('autism_level', $child?->autism_level ?? '') == 'Mild' ? 'selected' : '' }}>{{ __('Mild') }}</option>
                            <option value="Moderate" {{ old('autism_level', $child?->autism_level ?? '') == 'Moderate' ? 'selected' : '' }}>{{ __('Moderate') }}</option>
                            <option value="Severe" {{ old('autism_level', $child?->autism_level ?? '') == 'Severe' ? 'selected' : '' }}>{{ __('Severe') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('Date of Birth') }}</label>
                    <input
                        type="date"
                        class="form-input ltr-input"
                        name="birth_date"
                        value="{{ old('birth_date', $child?->birth_date ? \Carbon\Carbon::parse($child->birth_date)->format('Y-m-d') : '') }}"
                    >
                </div>

                <div style="text-align: center;">
                    <button type="submit" class="save-btn">{{ __('Save') }}</button>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    const avatarInput = document.getElementById('avatarInput');
    const profilePreview = document.getElementById('profilePreview');

    avatarInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            profilePreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>

</body>
</html>