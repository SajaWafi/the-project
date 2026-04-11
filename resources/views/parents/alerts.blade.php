<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts</title>

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
            padding: 12px 14px 100px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: none;
        }

        .content::-webkit-scrollbar {
            display: none;
        }

        .header {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
            min-height: 54px;
            margin-top: 6px;
        }

        .back-btn {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            border: none;
            background: transparent;
            padding: 0;
            cursor: pointer;
            color: #2f80ed;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
        }

        .back-btn svg {
            width: 24px;
            height: 24px;
            display: block;
        }

        .page-title {
            font-size: 30px;
            font-weight: 800;
            color: #1f5b87;
            text-align: center;
        }

        .logo {
            position: absolute;
            right: 0;
            width: 38px;
            height: 38px;
            object-fit: contain;
        }

        .section-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 26px;
            padding: 0 14px;
            border-radius: 999px;
            background: #4fcbb9;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            width: fit-content;
            margin: 4px 0 10px;
        }

        .alerts-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 8px;
        }

        .alert-card {
            background: #bed1f1;
            border-radius: 20px;
            padding: 16px 16px 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }

        .alert-title {
            font-size: 18px;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 12px;
            letter-spacing: 0.2px;
        }

        .alert-text {
            font-size: 14px;
            color: #5a6270;
            line-height: 1.25;
        }

        .alert-card.severe .alert-title {
            color: #ff3434;
        }

        .alert-card.moderate .alert-title,
        .alert-card.zone .alert-title {
            color: #f1912f;
        }

        .alert-card.health .alert-title {
            color: #1f5b87;
        }

        .alert-actions {
            margin-top: 12px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #5f6673;
        }

        .action-chip {
            min-width: 34px;
            height: 20px;
            padding: 0 10px;
            border: none;
            border-radius: 999px;
            background: #f4f1ea;
            color: #7b8088;
            font-size: 11px;
            cursor: pointer;
        }

        .bottom-nav {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background: #2f80ed;
            border-radius: 24px 24px 0 0;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 0 10px;
            z-index: 5;
        }

        .nav-item {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            transition: 0.2s;
        }

        .nav-item.active {
            background: rgba(255,255,255,0.25);
            color: #fff;
        }

        .nav-svg {
            width: 22px;
            height: 22px;
            display: block;
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
                padding: 12px 12px 90px;
            }
        }
    </style>
</head>
<body>

    <div class="mobile-screen">
        <div class="content">

            <div class="header">
                <a href="{{ route('parents.home') }}" class="back-btn" aria-label="Back to home">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>

                <div class="page-title">Alerts</div>

                <img src="{{ asset('images/logo.png') }}" alt="Taif" class="logo">
            </div>

            <div class="section-pill">Today</div>

            <div class="alerts-list">
                <div class="alert-card severe">
                    <div class="alert-title">WARNING: Severe<br>Panic Attack Detected</div>
                    <div class="alert-text">
                        Immediate intervention required. High-intensity
                        symptoms detected. Locate the child now.
                    </div>
                </div>

                <div class="alert-card moderate">
                    <div class="alert-title">Alert: Moderate Panic<br>Attack</div>
                    <div class="alert-text">
                        The child is showing signs of moderate anxiety.
                        Please check on them.
                    </div>
                </div>
            </div>

            <div class="section-pill">Yesterday</div>

            <div class="alerts-list">
                <div class="alert-card zone">
                    <div class="alert-title">Security Alert: Safe<br>Zone Breached</div>
                    <div class="alert-text">
                        The child has left the designated safe area. Check
                        the live location immediately.
                    </div>

                    <div class="alert-actions">
                        <span>Is the children with you?</span>
                        <button class="action-chip">yes</button>
                        <button class="action-chip">No</button>
                    </div>
                </div>

                <div class="alert-card health">
                    <div class="alert-title">Health Alert: High<br>Heart Rate</div>
                    <div class="alert-text">
                        Abnormal spike in heart rate detected. Please verify
                        the child's status.
                    </div>
                </div>
            </div>

        </div>

        <div class="bottom-nav">
            <a href="{{ route('parents.doctors') }}" class="nav-item {{ request()->routeIs('parents.doctors') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M6 4v5a6 6 0 0 0 12 0V4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 15v2a4 4 0 0 0 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="18" cy="19" r="2" fill="currentColor"/>
                </svg>
            </a>

            <a href="{{ route('parents.alerts') }}" class="nav-item {{ request()->routeIs('parents.alerts') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M12 4a4 4 0 0 0-4 4v2.2c0 .7-.2 1.3-.6 1.8L6 14h12l-1.4-2c-.4-.5-.6-1.1-.6-1.8V8a4 4 0 0 0-4-4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 17a2 2 0 0 0 4 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </a>

            <a href="{{ route('parents.home') }}" class="nav-item {{ request()->routeIs('parents.home') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M4 10.5 12 4l8 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M7 10v9h10v-9" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                </svg>
            </a>

            <a href="{{ route('parents.report') }}" class="nav-item {{ request()->routeIs('parents.report') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <rect x="6" y="4" width="12" height="16" rx="2" stroke="currentColor" stroke-width="2"/>
                    <path d="M9 8h6M9 12h6M9 16h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </a>

            <a href="{{ route('parents.location') }}" class="nav-item {{ request()->routeIs('parents.location') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M12 20s6-5 6-10a6 6 0 1 0-12 0c0 5 6 10 6 10Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    <circle cx="12" cy="10" r="2.5" fill="currentColor"/>
                </svg>
            </a>
        </div>
    </div>

</body>
</html>