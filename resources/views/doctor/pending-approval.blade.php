<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Approval</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #eaf0f4;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .phone {
            width: 390px;
            height: 844px;
            background: url('{{ asset('images/bg.png') }}') no-repeat center center/cover;
            border-radius: 22px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
        }

        .content {
            height: 100%;
            padding: 70px 28px 34px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .logo {
            width: 145px;
            height: 145px;
            margin-top: 10px;
            margin-bottom: 26px;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .title {
            font-size: 26px;
            font-weight: 800;
            color: #1d567e;
            margin-bottom: 12px;
        }

        .message {
            color: #44566c;
            font-size: 15px;
            line-height: 1.7;
            max-width: 290px;
            margin-bottom: 22px;
        }

        .status-box {
            width: 100%;
            background: rgba(255, 255, 255, 0.75);
            border: 1.5px solid rgba(235, 148, 67, 0.35);
            border-radius: 18px;
            padding: 18px 16px;
            margin-top: 10px;
            margin-bottom: auto;
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.04);
        }

        .status-icon {
            width: 62px;
            height: 62px;
            margin: 0 auto 12px;
            border-radius: 50%;
            background: #f7e5cf;
            color: #eb9443;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
        }

        .status-title {
            color: #eb9443;
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .status-text {
            color: #566579;
            font-size: 14px;
            line-height: 1.6;
        }

        .actions {
            width: 100%;
            margin-top: 26px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .primary-btn,
        .secondary-btn {
            width: 100%;
            height: 52px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .primary-btn {
            border: none;
            background: #2f80ed;
            color: #ffffff;
        }

        .secondary-btn {
            border: 1.5px solid #53d5d2;
            background: #ffffff;
            color: #2f80ed;
        }

        .small-note {
            margin-top: 14px;
            color: #7a8796;
            font-size: 12px;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <div class="phone">
        <div class="content">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Taif Logo">
            </div>

            <h1 class="title">
                Account Pending
            </h1>

            <p class="message">
                Your doctor account has been created successfully.
                Please wait until the admin approves your account.
            </p>

            <div class="status-box">
                <div class="status-icon">
                    ⏳
                </div>

                <div class="status-title">
                    Waiting for Admin Approval
                </div>

                <div class="status-text">
                    You cannot access the doctor dashboard until your account is approved by the admin.
                </div>
            </div>

            <div class="actions">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="primary-btn">
                        Logout
                    </button>
                </form>

                <a href="{{ route('welcome') }}" class="secondary-btn">
                    Back to Welcome
                </a>
            </div>

            <div class="small-note">
                If your approval is taking too long, please contact the system administrator.
            </div>
        </div>
    </div>
</body>
</html>