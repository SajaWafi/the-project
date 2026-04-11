<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panic Alert</title>

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
            padding: 16px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .top-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin-bottom: 20px;
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
            padding: 14px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
            cursor: pointer;
        }

        .left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon svg {
            width: 22px;
            height: 22px;
            color: #2f80ed;
            display: block;
        }

        .icon.heart svg {
            color: #ff5a5a;
        }

        .title {
            font-size: 15px;
            font-weight: 600;
            color: #202020;
        }

        .sub {
            font-size: 13px;
            color: #777;
            margin-top: 2px;
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
                border-radius: 0;
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
                
                    </div>
                  
                </div>
            </div>

            <div class="header">
                <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div class="page-title">Panic Alert</div>

                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Taif">
            </div>

            <!-- Panic Alerts Switch -->
            <div class="card" style="cursor: default;">
                <div class="left">
                    <div class="icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M12 3a5 5 0 0 0-5 5v3.2c0 .7-.2 1.3-.6 1.8L5 15h14l-1.4-2c-.4-.5-.6-1.1-.6-1.8V8a5 5 0 0 0-5-5Z"
                                  stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 18a2 2 0 0 0 4 0"
                                  stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <div class="title">Panic Alerts</div>
                </div>

                <div class="switch active" onclick="toggleSwitch(this)"></div>
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