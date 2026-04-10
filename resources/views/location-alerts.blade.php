<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Alerts</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #edf1f4;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .mobile-screen {
            width: 390px;
            height: 844px;
            max-width: 100%;
            max-height: 95vh;
            border-radius: 30px;
            overflow: hidden;
            position: relative;
            background: #f9f9f9;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.14);
        }

        .mobile-screen::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('images/bg.png') }}');
            background-size: 165% 100%;
            background-position: right bottom;
            opacity: 0.9;
            z-index: 0;
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
            padding: 16px 14px 24px;
            min-height: 100%;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin-bottom: 22px;
            min-height: 44px;
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

        .logo {
            position: absolute;
            right: 0;
            width: 100px;
            height: 100px;
            object-fit: contain;
        }

        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 14px 14px;
            margin-bottom: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.06);
            text-decoration: none;
        }

        .left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon svg {
            width: 20px;
            height: 20px;
            display: block;
        }

        .icon.red svg {
            color: #ff5a5a;
        }

        .icon.green svg {
            color: #1fc76a;
        }

        .icon.blue svg {
            color: #2f80ed;
        }

        .icon.teal svg {
            color: #27d0b9;
        }

        .title {
            font-size: 15px;
            font-weight: 600;
            color: #202020;
            white-space: nowrap;
        }

        .right-side {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .small-value {
            font-size: 12px;
            font-weight: 500;
            color: #4a4a4a;
        }

        .arrow {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .arrow svg {
            width: 18px;
            height: 18px;
        }

        .switch {
            width: 42px;
            height: 22px;
            background: #ddd;
            border-radius: 20px;
            position: relative;
            cursor: pointer;
            transition: 0.3s;
            flex-shrink: 0;
        }

        .switch.active {
            background: #4ecdc4;
        }

        .switch::after {
            content: "";
            width: 18px;
            height: 18px;
            background: #fff;
            border-radius: 50%;
            position: absolute;
            top: 2px;
            left: 2px;
            transition: 0.3s;
        }

        .switch.active::after {
            left: 22px;
        }

        @media (max-width: 480px) {
            body {
                padding: 0;
                background: #fff;
            }

            .mobile-screen {
                width: 100%;
                height: 100vh;
                max-height: 100vh;
                border-radius: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    <div class="mobile-screen">
        <div class="content">

            <div class="top-bar">

                <div class="top-right">
                    <div class="status-icon">
                        <span></span><span></span><span></span><span></span>
                    </div>
                    <div class="battery"></div>
                </div>
            </div>

            <div class="header">
                <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div class="page-title">Location Alerts</div>

                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Taif">
            </div>

            <div class="card">
                <div class="left">
                    <div class="icon red">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M12 3a5 5 0 0 0-5 5v3.2c0 .7-.2 1.3-.6 1.8L5 15h14l-1.4-2c-.4-.5-.6-1.1-.6-1.8V8a5 5 0 0 0-5-5Z"
                                  stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 18a2 2 0 0 0 4 0"
                                  stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <div class="title">Location Alerts</div>
                </div>

                <div class="switch active" onclick="toggleSwitch(this)"></div>
            </div>

        <a href="{{ route('safe.zone.settings') }}" class="card" style="text-decoration: none;">
                <div class="left">
                    <div class="icon green">
                        <svg viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="7" fill="currentColor"/>
                        </svg>
                    </div>

                    <div class="title">Safe Zone Settings</div>
                </div>

                <div class="arrow">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M9 5L16 12L9 19"
                            stroke="#e69a4b"
                            stroke-width="2.2"
                            stroke-linecap="round"
                            stroke-linejoin="round"/>
                    </svg>
                </div>
            </a>

            <div class="card">
                <div class="left">
                    <div class="icon red">
                        <svg viewBox="0 0 24 24" fill="none">
                            <rect x="5" y="5" width="14" height="14" rx="3" fill="currentColor"/>
                            <path d="M9 12h6" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
                            <path d="M12 9v6" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <div class="title">Leave Zone Alert</div>
                </div>

                <div class="switch active" onclick="toggleSwitch(this)"></div>
            </div>

            <div class="card">
                <div class="left">
                    <div class="icon blue">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M12 20s6-5 6-10a6 6 0 1 0-12 0c0 5 6 10 6 10Z"
                                  stroke="currentColor"
                                  stroke-width="1.8"
                                  stroke-linejoin="round"/>
                            <path d="M9.5 10.5l1.5 1.5 3.5-3.5"
                                  stroke="currentColor"
                                  stroke-width="1.8"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <div class="title">Enter Zone Alert</div>
                </div>

                <div class="switch active" onclick="toggleSwitch(this)"></div>
            </div>

            </div>
        </div>

    </div>

    <script>
        function toggleSwitch(el) {
            el.classList.toggle('active');
        }
    </script>

</body>
</html>