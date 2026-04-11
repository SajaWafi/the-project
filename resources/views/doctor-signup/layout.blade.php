<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Doctor Signup' }}</title>
    <style>
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

        .phone {
            width: 360px;
            max-width: 360px; /* هذا المهم */
            height: 844px;
            background: url('{{ asset('pics/bg.png') }}') no-repeat center center;
            background-size: cover;
            background-position: center;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }

        .status-bar {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            padding: 10px 14px 0;
            font-size: 12px;
            font-weight: bold;
            color: #111;
        }

        .content {
            position: relative;
            z-index: 2;
            height: calc(100% - 24px);
            padding: 10px 14px 18px;
            display: flex;
            flex-direction: column;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
        }

        .back-link {
            text-decoration: none;
            font-size: 40px;
            color: #ea9747;
            line-height: 1;
            transform: translateY(-2px);
        }

        .title {
            font-size: 28px;
            font-weight: 800;
            color: #1f567f;
        }

        .subtitle {
            text-align: center;
            font-size: 28px;
            line-height: 1.25;
            color: #1f567f;
            margin-top: 44px;
            margin-bottom: 34px;
            font-weight: 500;
        }

        .subtitle span {
            color: #e9bf57;
        }

        .field {
            margin-bottom: 10px;
        }

        .field label {
            display: block;
            font-size: 17px;
            color: #666;
            margin-bottom: 6px;
        }

        .select-box {
            width: 100%;
            height: 40px;
            border-radius: 14px;
            overflow: hidden;
            background: linear-gradient(to right, #c8ece7, #d5f1ed);
        }

        .input,
        .select-input {
            width: 100%;
            height: 36px;
            border: none;
            outline: none;
            border-radius: 14px;
            background: linear-gradient(to right, #c8ece7, #d5f1ed);
            padding: 0 14px;
            font-size: 14px;
            color: #333;
        }

        .select-input {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg fill='%23333' height='20' viewBox='0 0 24 24' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 36px;
        }

        .input-wrap {
            position: relative;
        }

        .password-input {
            padding-right: 42px;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .eye-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 16px;
            color: #444;
        }

        .terms {
            display: flex;
            gap: 8px;
            align-items: flex-start;
            margin-top: 6px;
            font-size: 12px;
            color: #777;
            line-height: 1.4;
        }

        .terms input {
            accent-color: #ea9747;
            transform: scale(1.2);
            margin-top: 3px;
        }

        .terms a,
        .footer a {
            color: #ea9747;
            font-weight: 700;
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            color: #777;
            font-size: 18px;
            margin: 8px 0 10px;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: #cfcfcf;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .google-btn {
            width: 100%;
            height: 42px;
            border: 1px solid #5fd0c0;
            border-radius: 14px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #48b9a8;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .google-icon {
            font-size: 22px;
            color: #f4a33e;
            font-weight: bold;
        }

        .spacer {
            flex: 1;
        }

        .btn {
            width: 100%;
            height: 48px;
            border: none;
            border-radius: 16px;
            background: #3680e8;
            color: white;
            font-size: 18px;
            font-weight: 800;
            cursor: pointer;
        }

        .btn-text {
            color: white;
            text-decoration: none;
            justify-content: center;
            padding-right: 10px;
            padding-left: 10px;
            padding-top: 10px;
            padding-bottom: 10px;
            align-items: center;
            display: flex;
            justify-content: center;
            align-content: center;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 12px;
            color: #777;
        }

        .error-box {
            background: #ffe7e7;
            color: #b60000;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 14px;
            font-size: 13px;
        }

        input[type="date"].input {
            font-size: 16px;
            color: #444;
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