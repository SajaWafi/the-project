<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>

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
            background-position: left top;
            opacity: 0.55;
            z-index: 0;
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
            min-height: 100%;
            padding: 16px 16px 26px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
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

        .header {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 46px;
            margin-bottom: 18px;
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
            font-size: 24px;
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

        .settings-section {
            margin-bottom: 18px;
        }

        .section-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
            padding: 0 16px;
            background: #ffffff;
            color: #222;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 10px;
        }

        .settings-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .settings-item {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #111;
            background: transparent;
            border: none;
            padding: 0;
            margin: 0;
            text-align: left;
            font: inherit;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
        }

        .settings-left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .settings-icon {
            width: 22px;
            height: 22px;
            color: #2f80ed;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .settings-icon svg {
            width: 18px;
            height: 18px;
            display: block;
        }

        .settings-text {
            font-size: 15px;
            font-weight: 500;
            color: #111;
            line-height: 1.2;
        }

        .settings-text.danger {
            color: #ff3b3b;
        }

        .item-arrow {
            color: #f19a43;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-left: 12px;
        }

        .item-arrow svg {
            width: 18px;
            height: 18px;
        }

        .delete-btn-trigger {
            width: 100%;
        }

        .delete-overlay {
            position: absolute;
            inset: 0;
            background: rgba(130, 160, 210, 0.18);
            z-index: 20;
            display: none;
            align-items: flex-end;
            justify-content: center;
        }

        .delete-overlay.show {
            display: flex;
        }

        .delete-sheet {
            width: 100%;
            background: #f7f7f7;
            border-radius: 28px 28px 0 0;
            padding: 22px 20px 30px;
            box-shadow: 0 -8px 24px rgba(0,0,0,0.08);
            transform: translateY(100%);
            transition: transform 0.25s ease;
        }

        .delete-overlay.show .delete-sheet {
            transform: translateY(0);
        }

        .delete-title {
            text-align: center;
            font-size: 18px;
            font-weight: 800;
            color: #111;
            margin-bottom: 10px;
        }

        .delete-text {
            text-align: center;
            font-size: 14px;
            color: #666;
            line-height: 1.35;
            margin-bottom: 18px;
        }

        .delete-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .delete-action-btn {
            min-width: 110px;
            height: 32px;
            border-radius: 999px;
            border: none;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
        }

        .delete-action-btn:active {
            transform: scale(0.97);
        }

        .delete-cancel {
            background: #bcecdf;
            color: #2f80ed;
        }

        .delete-confirm {
            background: #2f80ed;
            color: #fff;
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
                padding: 14px 14px 22px;
            }
        }
    </style>
</head>
<body>
    <div class="mobile-screen">
        <div class="content">

            <div class="top-bar">
                <div class="top-right">
                    <div class="status-icon"></div>
                </div>
            </div>

            <div class="header">
                <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div class="page-title">Settings</div>

                <img src="{{ asset('images/logo.png') }}" alt="Taif" class="app-logo">
            </div>

            <div class="settings-section">
                <div class="section-chip">Account</div>

                <div class="settings-list">
                    <a href="{{ route('password.manager') }}" class="settings-item">
                        <div class="settings-left">
                            <div class="settings-icon">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <circle cx="10" cy="10" r="4" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M14.5 14.5L20 20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M8.5 10h3M10 8.5v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="settings-text">Password Manager</div>
                        </div>

                        <div class="item-arrow">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </a>

                    <button type="button" class="settings-item delete-btn-trigger" onclick="openDeleteModal()">
                        <div class="settings-left">
                            <div class="settings-icon">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M5 20c0-3.2 3-5 7-5s7 1.8 7 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="settings-text danger">Delete Account</div>
                        </div>

                        <div class="item-arrow">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </button>
                </div>
            </div>

            <div class="settings-section">
                <div class="section-chip">Notifications</div>

                <div class="settings-list">
                    <a href="{{ route('panic.alert') }}" class="settings-item">
                        <div class="settings-left">
                            <div class="settings-icon">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M12 3a5 5 0 0 0-5 5v3.2c0 .7-.2 1.3-.6 1.8L5 15h14l-1.4-2c-.4-.5-.6-1.1-.6-1.8V8a5 5 0 0 0-5-5Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10 18a2 2 0 0 0 4 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="settings-text">Panic Alert</div>
                        </div>

                        <div class="item-arrow">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </a>

                    <a href="{{ route('location.alerts') }}" class="settings-item">
                        <div class="settings-left">
                            <div class="settings-icon">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M12 20s6-5 6-10a6 6 0 1 0-12 0c0 5 6 10 6 10Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                    <path d="M9.5 10.5l1.5 1.5 3.5-3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="settings-text">Location Alerts</div>
                        </div>

                        <div class="item-arrow">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </a>

                    <a href="{{ route('alert.sounds') }}" class="settings-item">
                        <div class="settings-left">
                            <div class="settings-icon">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M5 9v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M9 7v10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M13 10l4-3v10l-4-3h-2V10h2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="settings-text">Alert Sound</div>
                        </div>

                        <div class="item-arrow">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>

            <div class="settings-section">
                <div class="section-chip">Reports</div>

                <div class="settings-list">
                    <a href="{{ route('reports.history') }}" class="settings-item">
                        <div class="settings-left">
                            <div class="settings-icon">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M5 19V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M10 19V11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M15 19V5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M20 19V9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="settings-text">View Reports History</div>
                        </div>

                        <div class="item-arrow">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </a>

                    <a href="{{ route('reports.settings') }}" class="settings-item">
                        <div class="settings-left">
                            <div class="settings-icon">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M4 19h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M8 15V9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M12 15V5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M16 15v-3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="settings-text">Reports Settings</div>
                        </div>

                        <div class="item-arrow">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>

        </div>

        <div class="delete-overlay" id="deleteOverlay" onclick="closeDeleteModal(event)">
            <div class="delete-sheet">
                <div class="delete-title">Delete Account</div>
                <div class="delete-text">
                    Are you sure you want to delete<br>
                    account?
                </div>

                <div class="delete-actions">
            <button type="button" class="delete-action-btn delete-cancel" onclick="closeDeleteModal()">
                Cancel
            </button>

            <form action="{{ route('delete.account') }}" method="POST" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-action-btn delete-confirm">
                    Yes, Delete
                </button>
            </form>
        </div>
            </div>
        </div>
    </div>

    <script>
        const deleteOverlay = document.getElementById('deleteOverlay');

        function openDeleteModal() {
            deleteOverlay.classList.add('show');
        }

        function closeDeleteModal(event) {
            if (event && event.target !== deleteOverlay) return;
            deleteOverlay.classList.remove('show');
        }
    </script>
</body>
</html>