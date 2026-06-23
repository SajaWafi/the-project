<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Privacy Policy') }} - Taif</title>

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
            /* 💡 خلفية تتكيف مع لغة النظام */
            background-position: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }} bottom;
            opacity: 0.92;
            z-index: 0;
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
            min-height: 100%;
            padding: 12px 16px 28px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .header {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 44px;
            margin-bottom: 12px;
        }

        .back-btn {
            position: absolute;
            inset-inline-start: 0; /* 💡 محاذاة منطقية تعكس المكان تلقائياً */
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
            /* 💡 عكس سهم الرجوع في حالة اللغة العربية */
            transform: scaleX({{ app()->getLocale() == 'ar' ? '-1' : '1' }});
        }

        .page-title {
            font-size: 22px;
            font-weight: 800;
            color: #1f5b87;
        }

        .app-logo {
            position: absolute;
            inset-inline-end: 0; /* 💡 محاذاة منطقية */
            width: 35px;
            height: 35px;
            object-fit: contain;
        }

        .updated-badge {
            width: fit-content;
            margin: 0 auto 18px;
            background: #ffffff;
            color: #8a8a8a;
            font-size: 12px;
            padding: 9px 14px;
            border-radius: 14px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            /* لضمان بقاء التواريخ بأرقام واضحة ومقروءة بشكل صحيح بغض النظر عن اللغة */
            direction: ltr; 
        }

        .policy-text {
            color: #222;
            font-size: 14px;
            line-height: 1.6;
            background: rgba(255, 255, 255, 0.7);
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            text-align: justify; /* يجعل النص متناسقاً على الأطراف */
        }

        .policy-text p {
            margin-bottom: 12px;
        }

        .policy-section {
            margin-bottom: 15px;
        }

        .policy-section h3 {
            font-size: 15px;
            font-weight: 800;
            color: #1f5b87;
            margin-bottom: 6px;
        }

        .policy-list {
            list-style: none;
            padding-inline-start: 0; /* منطقي */
            margin: 0;
        }

        .policy-list li {
            margin-bottom: 6px;
            padding-inline-start: 14px; /* منطقي */
            position: relative;
        }

        .policy-list li::before {
            content: "•";
            position: absolute;
            inset-inline-start: 0; /* منطقي لتظهر النقطة في الجهة الصحيحة */
            color: #2f80ed;
            font-weight: bold;
        }

        @media (max-width: 480px) {
            body {
                padding: 0;
                background: #fff;
            }

            .mobile-screen {
                width: 100%;
                max-width: 100%;
                height: 100vh;
                max-height: 100vh;
                border-radius: 0;
                box-shadow: none;
            }

            .content {
                padding: 12px 14px 24px;
            }
        }
    </style>
</head>
<body>

    <div class="mobile-screen">
        <div class="content">

            <div class="top-bar">
                <div class="top-right"></div>
            </div>

            <div class="header">
                <a href="{{ route('profile') ?? '#' }}" class="back-btn" aria-label="{{ __('Back') }}">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>

                <div class="page-title">{{ __('Privacy Policy') }}</div>

                <img src="{{ asset('images/logo.png') }}" alt="Taif Logo" class="app-logo">
            </div>

            <!-- يمكنك جعل الشهر يترجم من خلال Carbon أو استخدام الترجمة اليدوية -->
            <div class="updated-badge">{{ __('Last updated: May 2026') }}</div>

            <div class="policy-text">
                <p>{!! __('Welcome to Taif. We are deeply committed to protecting your child\'s privacy and ensuring the highest level of data security.') !!}</p>

                <div class="policy-section">
                    <h3>{{ __('1. What Data We Collect') }}</h3>
                    <ul class="policy-list">
                        <li>{{ __('Vital Signs: Heart rate and motion levels.') }}</li>
                        <li>{{ __('Location Data: GPS coordinates and altitude (floor detection).') }}</li>
                        <li>{{ __('Device Info: Bracelet ID and connection status.') }}</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h3>{{ __('2. How We Use Your Data') }}</h3>
                    <ul class="policy-list">
                        <li>{{ __('To monitor your child\'s well-being in real-time.') }}</li>
                        <li>{{ __('To detect panic attacks or meltdowns and trigger instant alerts.') }}</li>
                        <li>{{ __('To track safe zone boundaries and ensure physical safety.') }}</li>
                        <li>{{ __('To generate medical reports for better healthcare decisions.') }}</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h3>{{ __('3. Data Sharing & Privacy') }}</h3>
                    <p>{!! __('Your child\'s data is strictly confidential. It is only shared with the linked parent accounts and authorized healthcare professionals you explicitly approve. We never sell your data to third parties.') !!}</p>
                </div>

                <div class="policy-section">
                    <h3>{{ __('4. Data Security & Control') }}</h3>
                    <p>{{ __('We implement industry-standard encryption to protect your data. You have full control to connect or disconnect the bracelet via the app at any time.') }}</p>
                </div>
            </div>

        </div>
    </div>

</body>
</html>