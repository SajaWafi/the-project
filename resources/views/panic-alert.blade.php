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

        .status-icon {
            display: flex;
            align-items: flex-end;
            gap: 2px;
            height: 14px;
        }

        .status-icon span {
            width: 3px;
            background: #111;
            border-radius: 2px;
        }

        .status-icon span:nth-child(1) { height: 5px; }
        .status-icon span:nth-child(2) { height: 7px; }
        .status-icon span:nth-child(3) { height: 10px; }
        .status-icon span:nth-child(4) { height: 13px; }

        .battery {
            width: 22px;
            height: 12px;
            border: 2px solid #111;
            border-radius: 4px;
            position: relative;
        }

        .battery::before {
            content: "";
            position: absolute;
            top: 2px;
            right: 2px;
            width: 12px;
            height: 6px;
            background: #111;
            border-radius: 2px;
        }

        .battery::after {
            content: "";
            position: absolute;
            right: -4px;
            top: 3px;
            width: 2px;
            height: 4px;
            background: #111;
            border-radius: 1px;
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

        .heart-overlay {
            position: absolute;
            inset: 0;
            background: rgba(130, 160, 210, 0.18);
            z-index: 20;
            display: none;
            align-items: flex-end;
            justify-content: center;
        }

        .heart-overlay.show {
            display: flex;
        }

        .heart-sheet {
            width: 100%;
            background: #f7f7f7;
            border-radius: 28px 28px 0 0;
            padding: 22px 20px 30px;
            box-shadow: 0 -8px 24px rgba(0, 0, 0, 0.08);
            transform: translateY(100%);
            transition: transform 0.25s ease;
        }

        .heart-overlay.show .heart-sheet {
            transform: translateY(0);
        }

        .heart-title {
            text-align: left;
            font-size: 18px;
            font-weight: 800;
            color: #111;
            margin-bottom: 8px;
        }

        .heart-text {
            font-size: 14px;
            color: #666;
            margin-bottom: 16px;
        }

        .heart-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .heart-option {
            width: 100%;
            height: 36px;
            border: none;
            border-radius: 14px;
            background: #cfeeee;
            color: #222;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.2s;
        }

        .heart-option.active {
            background: #bfeee4;
            color: #111;
            box-shadow: inset 0 0 0 2px rgba(47, 128, 237, 0.08);
        }

        .heart-option:active {
            transform: scale(0.98);
        }

        .heart-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .heart-action-btn {
            min-width: 96px;
            height: 36px;
            border-radius: 999px;
            border: none;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
        }

        .heart-action-btn:active {
            transform: scale(0.97);
        }

        .heart-cancel {
            background: #bcecdf;
            color: #2f80ed;
        }

        .heart-confirm {
            background: #2f80ed;
            color: #fff;
        }

        .motion-overlay {
            position: absolute;
            inset: 0;
            background: rgba(130, 160, 210, 0.18);
            z-index: 21;
            display: none;
            align-items: flex-end;
            justify-content: center;
        }

        .motion-overlay.show {
            display: flex;
        }

        .motion-sheet {
            width: 100%;
            background: #f7f7f7;
            border-radius: 28px 28px 0 0;
            padding: 22px 20px 30px;
            box-shadow: 0 -8px 24px rgba(0, 0, 0, 0.08);
            transform: translateY(100%);
            transition: transform 0.25s ease;
        }

        .motion-overlay.show .motion-sheet {
            transform: translateY(0);
        }

        .motion-title {
            text-align: left;
            font-size: 18px;
            font-weight: 800;
            color: #111;
            margin-bottom: 8px;
        }

        .motion-text {
            font-size: 14px;
            color: #666;
            line-height: 1.4;
            margin-bottom: 16px;
        }

        .motion-options {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 22px;
        }

        .motion-option {
            width: 100%;
            height: 36px;
            border: none;
            border-radius: 14px;
            background: #cfeeee;
            color: #111;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.2s;
        }

        .motion-option.active {
            background: #bfeee4;
            color: #111;
            box-shadow: inset 0 0 0 2px rgba(47, 128, 237, 0.08);
        }

        .motion-option:active {
            transform: scale(0.98);
        }

        .motion-actions {
            display: flex;
            justify-content: center;
            gap: 24px;
        }

        .motion-action-btn {
            min-width: 96px;
            height: 36px;
            border-radius: 999px;
            border: none;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
        }

        .motion-action-btn:active {
            transform: scale(0.97);
        }

        .motion-cancel {
            background: #bcecdf;
            color: #111;
        }

        .motion-confirm {
            background: #bcecdf;
            color: #111;
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

            <!-- Heart Rate -->
            <div class="card" onclick="openHeartModal()">
                <div class="left">
                    <div class="icon heart">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M20.8 5.6a5.5 5.5 0 0 0-7.8 0L12 6.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-6.6 1-1a5.5 5.5 0 0 0 0-7.8Z"
                                  stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <div>
                        <div class="title">Heart Rate Threshold</div>
                        <div class="sub">Alert if above 120 bpm</div>
                    </div>
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
            </div>

            <!-- Motion -->
            <div class="card" onclick="openMotionModal()">
                <div class="left">
                    <div class="icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <circle cx="13" cy="5" r="2" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M6 20l4-6 2 1 3 5"
                                  stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M10 8l3 2 3-1"
                                  stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </div>

                    <div class="title">Motion Sensitivity</div>
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
            </div>

            
               

        </div>

        <!-- Heart Rate Modal -->
        <div class="heart-overlay" id="heartOverlay" onclick="closeHeartModal(event)">
            <div class="heart-sheet">
                <div class="heart-title">Heart Rate Threshold</div>
                <div class="heart-text">Select BPM :</div>

                <div class="heart-options">
                    <button type="button" class="heart-option">100 bpm</button>
                    <button type="button" class="heart-option active">120 bpm</button>
                    <button type="button" class="heart-option">140 bpm</button>
                </div>

                <div class="heart-actions">
                    <button type="button" class="heart-action-btn heart-cancel" onclick="closeHeartModal()">Close</button>
                    <button type="button" class="heart-action-btn heart-confirm">Save</button>
                </div>
            </div>
        </div>

        <!-- Motion Modal -->
        <div class="motion-overlay" id="motionOverlay" onclick="closeMotionModal(event)">
            <div class="motion-sheet">
                <div class="motion-title">Motion Sensitivity</div>
                <div class="motion-text">
                    Choose how sensitive the bracelet is to movement.
                    Higher sensitivity detects smaller movements but may trigger more alerts.
                </div>

                <div class="motion-options">
                    <button type="button" class="motion-option">Low</button>
                    <button type="button" class="motion-option active">Medium</button>
                    <button type="button" class="motion-option">High</button>
                </div>

                <div class="motion-actions">
                    <button type="button" class="motion-action-btn motion-cancel" onclick="closeMotionModal()">Close</button>
                    <button type="button" class="motion-action-btn motion-confirm">Save</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function toggleSwitch(el) {
            el.classList.toggle('active');
        }

        const heartOverlay = document.getElementById('heartOverlay');

        function openHeartModal() {
            heartOverlay.classList.add('show');
        }

        function closeHeartModal(event) {
            if (event && event.target !== heartOverlay) return;
            heartOverlay.classList.remove('show');
        }

        document.querySelectorAll('.heart-option').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.heart-option').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });

        const motionOverlay = document.getElementById('motionOverlay');

        function openMotionModal() {
            motionOverlay.classList.add('show');
        }

        function closeMotionModal(event) {
            if (event && event.target !== motionOverlay) return;
            motionOverlay.classList.remove('show');
        }

        document.querySelectorAll('.motion-option').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.motion-option').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });
    </script>

</body>
</html>