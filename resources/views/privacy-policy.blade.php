<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>

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
            background-position: left bottom;
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

        .time {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }

        .top-right {
            display: flex;
            align-items: center;
            gap: 8px;
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

        .page-title {
            font-size: 22px;
            font-weight: 800;
            color: #1f5b87;
        }

        .app-logo {
            position: absolute;
            right: 0;
            width: 100px;
            height: 100px;
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
        }

        .policy-text {
            color: #222;
            font-size: 15px;
            line-height: 1.45;
        }

        .policy-text p {
            margin-bottom: 10px;
        }

        .policy-section {
            margin-bottom: 10px;
        }

        .policy-section h3 {
            font-size: 15px;
            font-weight: 800;
            color: #222;
            margin-bottom: 4px;
        }

        .policy-list {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .policy-list li {
            margin-bottom: 2px;
        }

        .policy-list li::before {
            content: "• ";
        }

        .agree-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 18px 0 34px;
            font-size: 15px;
            color: #222;
        }

        .agree-row input[type="checkbox"] {
            width: 14px;
            height: 14px;
            accent-color: #2f80ed;
            cursor: pointer;
        }

        .actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            margin-top: 6px;
        }

        .btn {
            min-width: 150px;
            height: 38px;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 700;
            transition: 0.2s;
        }

        .btn-primary {
            background: #2f80ed;
            color: #fff;
            box-shadow: 0 4px 10px rgba(47,128,237,0.22);
        }

        .btn-secondary {
            background: #f8f6f3;
            color: #6b6b6b;
            border: 2px solid #58d0bd;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
        }

        .btn:active {
            transform: scale(0.98);
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

                <div class="top-right">
                    
                 
                </div>
            </div>

            <div class="header">
                <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div class="page-title">Privacy Policy</div>

                <img src="{{ asset('images/logo.png') }}" alt="Taif" class="app-logo">
            </div>

            <div class="updated-badge">Last updated: March 2026</div>

            <div class="policy-text">
                <p>We care about your privacy and your child’s safety.</p>

                <div class="policy-section">
                    <h3>What Data We Collect</h3>
                    <ul class="policy-list">
                        <li>Heart rate data</li>
                        <li>Movement data</li>
                        <li>Location (GPS)</li>
                        <li>Altitude (floor detection)</li>
                        <li>Device information</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h3>How We Use Data</h3>
                    <ul class="policy-list">
                        <li>To monitor the child’s health</li>
                        <li>To send alerts during panic episodes</li>
                        <li>To track location for safety</li>
                    </ul>
                </div>

                <div class="policy-section">
                    <h3>Data Sharing</h3>
                    <p>We do not share your data with third parties except authorized doctors.</p>
                </div>

                <div class="policy-section">
                    <h3>Security</h3>
                    <p>Your data is securely stored and protected.</p>
                </div>
            </div>

            <label class="agree-row">
                <input type="checkbox">
                <span>I agree to the Privacy Policy</span>
            </label>

            <div class="actions">
                <button class="btn btn-primary">[ Accept & Continue ]</button>
                <button class="btn btn-secondary">[ Decline ]</button>
            </div>

        </div>
    </div>

</body>
</html>