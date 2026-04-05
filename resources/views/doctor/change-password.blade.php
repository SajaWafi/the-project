<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Manager</title>

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
            position: relative;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }

        .content {
            padding: 10px 18px;
        }

        /* ===== Header ===== */
        .header {
            position: relative;
            text-align: center;
            margin-bottom: 20px;
        }

        .back-btn {
            position: absolute;
            left: 0;
            top: 0;
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
            top: 0;
            width: 50px;
            height: 34px;
        }

        /* ===== Fields ===== */
        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            margin-bottom: 6px;
            font-size: 17px;
            font-weight: 600;
            color: #111;
        }

        .input-wrapper {
            position: relative;
        }

        .input {
            width: 100%;
            height: 42px;
            border: none;
            border-radius: 18px;
            padding: 0 14px;
            background: linear-gradient(to right, #bde6e1, #8fd3cc);
            font-size: 16px;
            outline: none;
        }

        /* ===== Eye icon ===== */
        .eye {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 16px;
        }

        /* ===== Buttons ===== */
        .btn {
            width: 200px;
            height: 42px;
            border: none;
            border-radius: 20px;
            background: #2f66f3;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            display: block;
            margin: 30px auto 10px;
            cursor: pointer;
        }

        .cancel {
            display: block;
            text-align: center;
            color: #2f66f3;
            background: #cfe4d8;
            width: 140px;
            margin: auto;
            padding: 8px;
            border-radius: 14px;
            text-decoration: none;
        }

        /* ===== Alerts ===== */
        .error-box {
            background: #ffe7e7;
            color: #b60000;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .success-box {
            background: #e7fff0;
            color: #0a7c3a;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
<div class="phone">
    <div class="content">

        <div class="header">
            <a href="{{ route('doctor.settings') }}" class="back-btn">‹</a>
            <div class="title">Password Manager</div>
            <img src="{{ asset('images/logo.png') }}" class="logo">
        </div>

        @if(session('success'))
            <div class="success-box">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="error-box">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('doctor.password.update') }}" method="POST">
            @csrf

            <div class="field">
                <label>Current Password</label>
                <div class="input-wrapper">
                    <input type="password" name="current_password" class="input" id="current">
                    <span class="eye" onclick="toggle('current')">👁</span>
                </div>
            </div>

            <div class="field">
                <label>New Password</label>
                <div class="input-wrapper">
                    <input type="password" name="new_password" class="input" id="new">
                    <span class="eye" onclick="toggle('new')">👁</span>
                </div>
            </div>

            <div class="field">
                <label>Confirm New Password</label>
                <div class="input-wrapper">
                    <input type="password" name="new_password_confirmation" class="input" id="confirm">
                    <span class="eye" onclick="toggle('confirm')">👁</span>
                </div>
            </div>

            <button type="submit" class="btn">Change Password</button>
        </form>

        <a href="{{ route('doctor.settings') }}" class="cancel">Cancel</a>

    </div>
</div>

<script>
    function toggle(id) {
        let input = document.getElementById(id);
        input.type = input.type === "password" ? "text" : "password";
    }
</script>

</body>
</html>