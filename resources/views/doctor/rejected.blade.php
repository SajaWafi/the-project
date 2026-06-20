<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Account Status - Taif') }}</title>

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

        /* --- إطار الموبايل والخلفية --- */
        .mobile-screen {
            width: 390px;
            max-width: 100%;
            height: 844px;
            max-height: 95vh;
            position: relative;
            overflow: hidden;
            border-radius: 30px;
            background: #f9f9f9 url('{{ asset('images/bg.png') }}') no-repeat left bottom;
            background-size: 165% 100%;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.14);
        }

        .content {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 12px 18px;
        }

        /* --- الهيدر واللوقو --- */
        .header {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 54px;
            margin-bottom: 20px;
            margin-top: 6px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 800;
            color: #1f5b87;
            text-align: center;
        }

        /* 💡 دعم الاتجاهين لمكان الشعار */
        .logo {
            position: absolute;
            inset-inline-end: 0;
            width: 38px;
            height: 38px;
            object-fit: contain;
        }

        /* --- كرت الحالة (الرفض أو التعطيل) --- */
        .status-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .status-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            padding: 30px 20px;
            border-radius: 24px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            width: 100%;
        }

        .icon {
            font-size: 55px;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }

        h1 {
            color: #ff3434;
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        p {
            color: #5a6270;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        /* --- زر تسجيل الخروج --- */
        .logout-btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 48px;
            background: #2f80ed;
            color: #fff;
            text-decoration: none;
            border-radius: 999px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 4px 12px rgba(47, 128, 237, 0.3);
        }

        .logout-btn:active {
            transform: scale(0.96);
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
        }
    </style>
</head>
<body>

    <div class="mobile-screen">
        <div class="content">
            
            <div class="header">
                <div class="page-title">{{ __('Account Status') }}</div>
                <img src="{{ asset('images/logo.png') }}" alt="Taif Logo" class="logo">
            </div>

            <div class="status-container">
                <div class="status-card">
                    @if($status === 'rejected')
                        <div class="icon">❌</div>
                        <h1>{{ __('Application Rejected') }}</h1>
                        <p>{{ __('We are sorry, but your application to join Taif as a doctor has been rejected by the administration.') }}</p>
                    @elseif($status === 'suspended')
                        <div class="icon">🚫</div>
                        <h1>{{ __('Account Suspended') }}</h1>
                        <p>{{ __('Your doctor account has been temporarily suspended by the administration.') }}</p>
                    @else
                        <div class="icon">⚠️</div>
                        <h1>{{ __('Account Unavailable') }}</h1>
                        <p>{{ __('Your account is currently unavailable.') }}</p>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">{{ __('Log out') }}</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</body>
</html>