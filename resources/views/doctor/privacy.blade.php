<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Doctor</title>
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

        /* ===== Header ===== */
        .header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
            margin-top: 20px;
        }

        .back-btn {
            position: absolute;
            left: 0;
            background: transparent;
            border: none;
            cursor: pointer;
            color: #2f80ed;
            padding: 6px;
        }

        .back-btn svg {
            width: 26px;
            height: 26px;
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
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            margin-bottom: 16px;
        }

        .policy-subtitle {
            font-size: 15px;
            font-weight: 700;
            color: #2f80ed;
            margin-bottom: 6px;
        }
    </style>
</head>
<body>
    <div class="phone">
        <div class="content">

            <div class="header">
                <a href="{{ route('doctor.doctor-profile') ?? '#' }}" class="back-btn" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <div class="title">Privacy Policy</div>
                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="logo">
                </div>
            </div>

            <div class="policy-card">
                <div class="policy-title">Professional Confidentiality</div>
                <div class="policy-text">
                    As a healthcare provider on the Taif platform, you play a vital role in guiding and consulting parents. This policy outlines how information is handled within your dashboard.
                </div>

                <div class="policy-subtitle">1. Data Access & Scope</div>
                <div class="policy-text">
                    You have access to appointment schedules, basic profile details, and chat histories with parents. To ensure the highest level of child privacy, live IoT sensor data (such as real-time heart rate or location) is strictly controlled by the parents and is not accessible through this dashboard.
                </div>

                <div class="policy-subtitle">2. Purpose of Information</div>
                <div class="policy-text">
                    The information provided to you is used solely to facilitate appointment bookings, manage your consultation schedule, and enable secure, direct communication with parents.
                </div>

                <div class="policy-subtitle">3. Secure Communications</div>
                <div class="policy-text">
                    All chat messages, medical notes, and consultation details shared via the Taif platform are encrypted and strictly confidential. Sharing any patient or parent information outside this platform is prohibited.
                </div>
            </div>
        </div>
    </div>
</body>
</html>