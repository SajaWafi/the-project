<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
    <style>
        /* ===== Reset ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* ===== Page background ===== */
        body {
            background: #111;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* ===== Phone frame ===== */
        .phone {
            width: 390px;
            height: 844px;
            background: url('{{ asset('images/bg.png') }}') no-repeat center center/cover;
            border-radius: 22px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }

        /* ===== Content ===== */
        .content {
            height: 100%;
            overflow-y: auto;
            padding: 10px 18px 24px;
        }

        .content::-webkit-scrollbar {
            width: 5px;
        }

        .content::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.12);
            border-radius: 10px;
        }

        /* ===== Status bar ===== */
        .status-bar {
            height: 28px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 12px 0;
            font-size: 13px;
            font-weight: 700;
            color: #111;
            margin-bottom: 6px;
        }

        /* ===== Header ===== */
        .header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
        }

        .back-btn {
            position: absolute;
            left: 4px;
            top: 0;
            font-size: 30px;
            line-height: 1;
            color: #3d78ff;
            text-decoration: none;
        }

        .title {
            font-size: 28px;
            font-weight: 800;
            color: #1d567e;
            text-align: center;
        }

        .logo {
            position: absolute;
            right: 0;
            top: -2px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            overflow: hidden;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ===== Policy container ===== */
        .policy-card {
            background: rgba(255,255,255,0.58);
            border-radius: 24px;
            padding: 18px 16px;
            backdrop-filter: blur(2px);
        }

        .policy-title {
            font-size: 18px;
            font-weight: 700;
            color: #1d567e;
            margin-bottom: 10px;
        }

        .policy-text {
            font-size: 15px;
            line-height: 1.75;
            color: #333;
            margin-bottom: 16px;
        }

        .policy-subtitle {
            font-size: 16px;
            font-weight: 700;
            color: #2f80ed;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="phone">
        <div class="content">

            <div class="header">
                <a href="{{ route('doctor.doctor-profile') }}" class="back-btn">‹</a>
                <div class="title">Privacy Policy</div>
                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="logo">
                </div>
            </div>

            <div class="policy-card">
                <div class="policy-title">Your Privacy Matters</div>
                <div class="policy-text">
                    We are committed to protecting your personal information and keeping your data secure.
                    This page explains how we collect, use, and protect your information while using the app.
                </div>

                <div class="policy-subtitle">Information Collection</div>
                <div class="policy-text">
                    We may collect profile details, appointment data, medical notes, and communication history
                    only for improving your experience and delivering app services.
                </div>

                <div class="policy-subtitle">How We Use Data</div>
                <div class="policy-text">
                    Your information is used to manage appointments, enable communication, and provide a
                    better healthcare experience between doctors and parents.
                </div>

                <div class="policy-subtitle">Security</div>
                <div class="policy-text">
                    We apply reasonable security practices to protect your stored data and prevent unauthorized access.
                </div>

                <div class="policy-subtitle">Contact</div>
                <div class="policy-text">
                    If you have any questions about privacy, please contact the support team through the application.
                </div>
            </div>
        </div>
    </div>
</body>
</html>