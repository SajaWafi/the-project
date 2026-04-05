<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alert Sounds</title>

    <style>
        /* ===== Reset ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #ffffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .phone {
            width: 390px;
            height: 844px;
            background: url('{{ asset('images/bg.png') }}') no-repeat center center/cover;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }

        .content {
            padding: 14px 18px;
        }

        /* ===== Header ===== */
        .header {
            position: relative;
            text-align: center;
            margin-bottom: 25px;
        }

        .back-btn {
            position: absolute;
            left: 0;
            font-size: 28px;
            color: #2f66f3;
            text-decoration: none;
        }

        .title {
            font-size: 26px;
            font-weight: 800;
            color: #1d567e;
        }

        .logo {
            position: absolute;
            right: 0;
            top: -2px;
            width: 44px;
            height: 60;
            border-radius: 50%;
            overflow: hidden;
        }

        /* ===== Section chip ===== */
        .chip {
            display: inline-block;
            background: linear-gradient(to right, #bde6e1, #8fd3cc);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        /* ===== Row ===== */
        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            font-size: 18px;
        }

        /* ===== Toggle ===== */
        .switch {
            position: relative;
            width: 46px;
            height: 24px;
        }

        .switch input {
            display: none;
        }

        .slider {
            position: absolute;
            width: 100%;
            height: 100%;
            background: #cfd6fb;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .slider:before {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            left: 2px;
            top: 2px;
            background: white;
            border-radius: 50%;
            transition: 0.3s;
        }

        input:checked + .slider {
            background: #3cc1a7;
        }

        input:checked + .slider:before {
            transform: translateX(22px);
        }

        /* ===== Alerts ===== */
        .success-box {
            background: #e7fff0;
            color: #0a7c3a;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
<div class="phone">
    <div class="content">

        <div class="header">
            <a href="{{ route('doctor.settings') }}" class="back-btn">‹</a>
            <div class="title">Alert Sounds</div>
            <img src="{{ asset('images/logo.png') }}" class="logo">
        </div>

        @if(session('success'))
            <div class="success-box">{{ session('success') }}</div>
        @endif

        <form action="{{ route('doctor.alert.update') }}" method="POST">
            @csrf

            <!-- Notifications -->
            <div class="chip">Notifications</div>

            <div class="row">
                <span>Sound</span>
                <label class="switch">
                    <input type="checkbox" name="notif_sound" value="1" {{ old('notif_sound', $settings['notif_sound'] ?? true) ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            <div class="row">
                <span>Vibrate</span>
                <label class="switch">
                    <input type="checkbox" name="notif_vibrate" value="1" {{ old('notif_vibrate', $settings['notif_vibrate'] ?? false) ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            <!-- Parents Messages -->
            <div class="chip" style="margin-top:20px;">Parents Messages</div>

            <div class="row">
                <span>Sound</span>
                <label class="switch">
                    <input type="checkbox" name="msg_sound" value="1" {{ old('msg_sound', $settings['msg_sound'] ?? true) ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

            <div class="row">
                <span>Vibrate</span>
                <label class="switch">
                    <input type="checkbox" name="msg_vibrate" value="1" {{ old('msg_vibrate', $settings['msg_vibrate'] ?? false) ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>

        </form>

    </div>
</div>
</body>
</html>