<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Create Account' }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #edf1f4;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .phone {
            width: 100%;
            max-width: 360px; /* هذا المهم */
            max-height: 800px;
            height: 100vh;
            background: url('{{ asset('pics/bg.png') }}') no-repeat;
            background-position: left;
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
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center; /* يخلي العنوان بالنص */
            margin-top: 8px;
        }

        .back-link {
            position: absolute;
            left: 0;
            font-size: 28px;
            color: #3680e8;
            text-decoration: none;
        }
        .logo {
            position: absolute;
            right: 0;
        }

        .logo img {
            width: 100px;
            margin-bottom: 15px;
            align-self: flex-start;
        }

       .title {
            font-size: 26px;
            font-weight: 800;
            color: #1f567f;
            text-align: center;
            margin-top: 10px;
        }

        .subtitle {
            text-align: center;
            font-size: 28px;
            line-height: 1.25;
            color: #1f567f;
            margin-top: 44px;
            margin-bottom: 38px;
            font-weight: 500;
        }

        .subtitle.small {
            font-size: 26px;
            line-height: 1.35;
            margin-top: 36px;
            margin-bottom: 28px;
        }

        .subtitle span {
            color: #e9bf57;
        }

        .field {
            margin-bottom: 12px;
        }

        .field label,
        .dob-label {
            display: block;
            font-size: 20px;
            color: #555;
            margin-bottom: 8px;
        }

        .input, .select {
            width: 100%;
            height: 45px;
            border: none;
            outline: none;
            border-radius: 14px;
            background: linear-gradient(to right, #c8ece7, #d5f1ed);
            padding: 0 14px;
            font-size: 14px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .input {
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

        .spacer {
            flex: 1;
        }

        .btn {
            width: 100%;
            height: 60px;
            border: none;
            border-radius: 16px;
            background: #3680e8;
            color: white;
            font-size: 18px;
            font-weight: 800;
            cursor: pointer;
        }

        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 16px;
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

        .select-box {
            width: 100%;
            border: 1.5px solid #45ceb0;
            border-radius: 14px;
            overflow: hidden;
            background: rgba(205, 242, 235, 0.35);
        }

        .select-box select {
            width: 100%;
            height: 40px;
            border: none;
            outline: none;
            padding: 0 12px;
            font-size: 16px;
            background: linear-gradient(to right, #bfe7e0, #d5f1ed);
            color: #59615f;
        }

        .top-bg-1 {
            position: absolute;
            top: -80px;
            left: -80px;
            width: 220px;
            height: 220px;
            background: #dff0f7;
            transform: rotate(45deg);
            z-index: 0;
        }

        .top-bg-2 {
            position: absolute;
            top: -35px;
            right: 30px;
            width: 150px;
            height: 150px;
            background: rgba(212, 240, 242, 0.5);
            border-radius: 50%;
            z-index: 0;
        }

        .bottom-bg-1 {
            position: absolute;
            bottom: -70px;
            left: -60px;
            width: 160px;
            height: 160px;
            background: rgba(192, 232, 231, 0.65);
            border-radius: 50%;
            z-index: 0;
        }

        .bottom-bg-2 {
            position: absolute;
            bottom: -120px;
            left: 30px;
            width: 260px;
            height: 260px;
            background: rgba(217, 238, 246, 0.65);
            border-radius: 50%;
            z-index: 0;
        }

        .s2-top-bg-2 {
            position: absolute;
            top: 45px;
            right: -10px;
            width: 160px;
            height: 160px;
            background: rgba(205, 239, 238, 0.55);
            border-radius: 50%;
            z-index: 0;
        }

        .dot-blue {
            position: absolute;
            right: 28px;
            top: 405px;
            width: 12px;
            height: 12px;
            background: rgba(185, 221, 244, 0.7);
            border-radius: 50%;
            z-index: 0;
        }

        .dot-yellow {
            position: absolute;
            right: 48px;
            top: 300px;
            width: 44px;
            height: 44px;
            background: rgba(240, 228, 174, 0.35);
            border-radius: 50%;
            z-index: 0;
        }

        .shape-orange {
            position: absolute;
            right: 88px;
            bottom: 145px;
            font-size: 62px;
            color: rgba(240, 167, 77, 0.65);
            transform: rotate(35deg);
            z-index: 0;
            font-weight: bold;
        }

        .bottom-curve {
            position: absolute;
            right: -80px;
            bottom: -95px;
            width: 250px;
            height: 250px;
            background: rgba(244, 228, 193, 0.55);
            border-radius: 50%;
            z-index: 0;
        }

        .bottom-dot {
            position: absolute;
            left: 58px;
            bottom: 95px;
            width: 22px;
            height: 22px;
            background: rgba(237, 234, 214, 0.65);
            border-radius: 50%;
            z-index: 0;
        }

        .circle-right {
            position: absolute;
            top: 185px;
            right: 18px;
            width: 100px;
            height: 100px;
            background: rgba(195, 236, 238, 0.5);
            border-radius: 50%;
            z-index: 0;
        }

        .bottom-yellow {
            position: absolute;
            bottom: -90px;
            right: -60px;
            width: 190px;
            height: 190px;
            background: rgba(240, 227, 201, 0.45);
            border-radius: 50%;
            z-index: 0;
        }

        .bottom-green {
            position: absolute;
            bottom: -85px;
            right: -5px;
            width: 125px;
            height: 125px;
            background: rgba(205, 236, 221, 0.6);
            border-radius: 50%;
            z-index: 0;
        }

        .small-dot {
            position: absolute;
            right: 78px;
            bottom: 102px;
            width: 22px;
            height: 22px;
            background: rgba(238, 233, 214, 0.6);
            border-radius: 50%;
            z-index: 0;
        }
        .btn-next {
        width: 100%;
        height: 54px;
        border-radius: 18px;
        background: #2f80ed;
        color: #fff;
        font-size: 20px;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        justify-content: center;
        align-items: center;
        border: none;
        cursor: pointer;
    }
        .back-btn {
            position: absolute;
            left: 0;
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
        }
        </style>
</head>
<body>
    <div class="phone">
        @yield('background')

        <div class="status-bar">
            
        </div>

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