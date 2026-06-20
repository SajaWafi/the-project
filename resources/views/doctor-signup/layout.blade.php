<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? __('Doctor Signup') }}</title>
    <style>
        /* ===== Reset ===== */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #ffffffff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* ===== Phone Container ===== */
        .phone {
            width: 390px;
            max-width: 100%;
            height: 844px;
            background: url('{{ asset('pics/bg.png') }}') no-repeat center center;
            background-size: cover;
            border-radius: 22px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
            display: flex;
            flex-direction: column;
        }

        .content {
            position: relative;
            z-index: 2;
            height: 100%;
            padding: 16px 20px 24px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        /* ===== Header & Back Button ===== */
        .header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 60px;
            margin-bottom: 20px;
        }

        .back-btn {
            position: absolute;
            inset-inline-start: 0;
            top: 50%;
            transform: translateY(-50%) {{ app()->getLocale() == 'ar' ? 'scaleX(-1)' : '' }};
            background: transparent;
            border: none;
            cursor: pointer;
            color: #ea9747;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-btn svg {
            width: 28px;
            height: 28px;
        }

        .title {
            font-size: 26px;
            font-weight: 800;
            color: #1f567f;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: #1f567f;
            margin-bottom: 30px;
        }

        /* ===== Form Fields ===== */
        .field {
            margin-bottom: 16px;
        }

        .field label {
            display: block;
            font-size: 15px;
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            text-align: start;
        }

        .input,
        .select-input {
            width: 100%;
            height: 46px;
            border: none;
            outline: none;
            border-radius: 14px;
            background: linear-gradient(to right, #c8ece7, #d5f1ed);
            padding: 0 16px;
            font-size: 15px;
            color: #333;
        }

        .select-input {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg fill='%23333' height='20' viewBox='0 0 24 24' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: {{ app()->getLocale() == 'ar' ? 'left 14px center' : 'right 14px center' }};
            padding-inline-end: 36px;
        }

        /* 💡 حل مشكلة حقل كلمة المرور والعين 💡 */
        .input-wrap {
            position: relative;
            width: 100%;
        }

        .password-input {
            /* نثبت الكتابة من اليسار لليمين والمسافة من اليمين عشان العين */
            text-align: left !important;
            padding-left: 16px !important;
            padding-right: 42px !important;
            letter-spacing: 2px;
        }

        .eye-btn {
            position: absolute;
            right: 12px !important; /* نثبت العين دائماً على اليمين */
            left: auto !important;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 18px;
            color: #444;
            z-index: 10;
        }

        /* ===== Terms & Checkbox ===== */
        .terms {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .terms input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            accent-color: #ea9747;
            cursor: pointer;
            flex-shrink: 0;
        }

        .terms-text {
            font-size: 13px;
            color: #666;
            line-height: 1.5;
            text-align: start;
        }

        .terms-text a {
            color: #ea9747;
            font-weight: 700;
            text-decoration: underline;
        }

        /* ===== Buttons & Footer ===== */
        .btn {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 16px;
            background: #2f80ed;
            color: white;
            font-size: 18px;
            font-weight: 800;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 6px 15px rgba(47, 128, 237, 0.25);
            transition: 0.2s ease;
        }

        .btn:active {
            transform: scale(0.97);
        }

        .spacer {
            flex-grow: 1;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            margin-top: 20px;
            font-weight: 500;
        }

        .footer a {
            color: #ea9747;
            font-weight: 700;
            text-decoration: underline;
        }

        /* ===== Error Box ===== */
        .error-box {
            background: #ffe7e7;
            color: #b60000;
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="phone">
        <div class="content">
            @yield('content')
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>