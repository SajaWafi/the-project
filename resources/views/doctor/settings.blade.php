<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Doctor Settings') }}</title>
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
            background: #ffffffff;
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

        /* ===== Scrollable content ===== */
        .content {
            height: 100%;
            overflow-y: auto;
            padding: 10px 14px 24px;
            position: relative;
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
            margin-bottom: 18px;
            z-index: 2;
        }

        .back-btn {
            position: absolute;
            inset-inline-start: 0;
            top: 0;
            font-size: 30px;
            line-height: 1;
            color: #3d78ff;
            text-decoration: none;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: {{ app()->getLocale() == 'ar' ? 'scaleX(-1)' : 'none' }};
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
            inset-inline-end: 0;
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

        /* ===== Small category chip ===== */
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
            margin-top: 10px;
            margin-bottom: 10px;
            text-transform: capitalize;
        }

        /* ===== Menu list ===== */
        .menu-list {
            display: flex;
            flex-direction: column;
            gap: 2px;
            position: relative;
            z-index: 2;
        }

        /* ===== Each row button ===== */
        .menu-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #111;
            padding: 12px;
            padding-inline-start: 14px;
            padding-inline-end: 4px;
            border-radius: 14px;
            transition: all 0.15s ease;
            background: transparent;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.2);
        }

        .menu-item:active {
            transform: scale(0.985);
            background: rgba(255,255,255,0.32);
        }

        .menu-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .menu-icon {
            width: 22px;
            height: 22px;
            color: #2f80ed;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-icon svg {
            width: 20px;
            height: 20px;
        }

        .menu-text {
            font-size: 17px;
            font-weight: 500;
            color: #111;
        }

        .menu-arrow {
            color: #ee943f;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-inline-end: 4px;
        }

        .menu-arrow svg {
            width: 20px;
            height: 20px;
            transform: {{ app()->getLocale() == 'ar' ? 'scaleX(-1)' : 'none' }};
        }

        .delete-text { color: #ff3b3b; }
        .delete-icon { color: #ff3b3b; }

        /* ===== Overlays & Modals ===== */
        .modal-overlay {
            position: absolute;
            inset: 0;
            background: rgba(120, 150, 200, 0.35);
            z-index: 20;
            opacity: 0;
            visibility: hidden;
            transition: 0.25s ease;
        }

        .bottom-modal {
            position: absolute;
            left: 0;
            right: 0;
            bottom: -400px;
            background: #fff;
            border-top-left-radius: 28px;
            border-top-right-radius: 28px;
            padding: 26px 20px 34px;
            z-index: 25;
            text-align: center;
            transition: 0.3s ease;
            box-shadow: 0 -8px 20px rgba(0,0,0,0.12);
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .bottom-modal.show {
            bottom: 0;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            color: #111;
            margin-bottom: 10px;
        }

        .modal-text {
            font-size: 16px;
            color: #666;
            line-height: 1.45;
            margin-bottom: 18px;
        }

        /* 💡 تنسيق خيارات اللغة */
        .lang-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 24px;
            margin-top: 16px;
        }

        .lang-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            width: 100%;
            height: 54px;
            border-radius: 16px;
            background: #f4f7fb;
            color: #1d567e;
            font-size: 18px;
            font-weight: 700;
            text-decoration: none;
            transition: 0.2s;
            border: 2px solid transparent;
        }

        .lang-btn.active {
            background: #e6f0ff;
            border-color: #2f80ed;
            color: #2f80ed;
        }

        .lang-btn:active {
            transform: scale(0.98);
        }

        .check-icon {
            display: none;
            width: 20px;
            height: 20px;
            color: #2f80ed;
        }

        .lang-btn.active .check-icon {
            display: block;
        }

        /* ===== Buttons ===== */
        .modal-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            align-items: center;
        }

        .cancel-btn,
        .confirm-btn {
            min-width: 140px;
            height: 44px;
            border: none;
            border-radius: 22px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cancel-btn {
            background: #c8ebe6;
            color: #2f80ed;
            width: 100%;
        }

        .confirm-btn {
            background: #2f80ed;
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="phone">
        
        <div class="modal-overlay" id="modalOverlay" onclick="closeAllModals()"></div>

        <div class="bottom-modal" id="langModal">
            <div class="modal-title">{{ __('Select Language') }}</div>
            
            <div class="lang-options">
                <a href="{{ route('lang.switch', 'ar') }}" class="lang-btn {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                    <span>العربية</span>
                    <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </a>
                
                <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                    <span>English</span>
                    <svg class="check-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </a>
            </div>

            <button type="button" class="cancel-btn" onclick="closeAllModals()">{{ __('Cancel') }}</button>
        </div>

        <div class="bottom-modal" id="deleteModal">
            <div class="modal-title">{{ __('Delete Account') }}</div>
            <div class="modal-text">
                {{ __('Are you sure you want to delete your account?') }}
            </div>

            <div class="modal-actions">
                <button type="button" class="cancel-btn" style="width: auto;" onclick="closeAllModals()">{{ __('Cancel') }}</button>

                <form action="{{ route('doctor.delete.account') }}" method="POST" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="confirm-btn">{{ __('Yes, Delete') }}</button>
                </form>
            </div>
        </div>

        <div class="content">

            <div class="header">
                <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div class="title">{{ __('Settings') }}</div>

                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="logo" onerror="this.style.display='none'">
                </div>
            </div>

            <div class="section-chip">{{ __('Profile') }}</div>

            <div class="menu-list">
                
                <button type="button" class="menu-item" onclick="openLangModal()">
                    <div class="menu-left">
                        <div class="menu-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                            </svg>
                        </div>
                        <span class="menu-text">{{ __('Language') }}</span>
                    </div>
                    <span class="menu-arrow" style="font-size: 14px; font-weight: bold; gap: 4px;">
                        {{ app()->getLocale() == 'ar' ? 'العربية' : 'English' }}
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </button>

                <a href="{{ route('doctor.edit-profile') }}" class="menu-item">
                    <div class="menu-left">
                        <div class="menu-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <span class="menu-text">{{ __('Edit Profile') }}</span>
                    </div>
                    <span class="menu-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </a>

                <a href="{{ route('doctor.workplace.timing') }}" class="menu-item">
                    <div class="menu-left">
                        <div class="menu-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <span class="menu-text">{{ __('Workplace And Timing') }}</span>
                    </div>
                    <span class="menu-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </a>
            </div>

            <div class="section-chip">{{ __('Account') }}</div>

            <div class="menu-list">
                <a href="{{ route('doctor.password') }}" class="menu-item">
                    <div class="menu-left">
                        <div class="menu-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <span class="menu-text">{{ __('Change Password') }}</span>
                    </div>
                    <span class="menu-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </a>

                <button type="button" class="menu-item delete-trigger" onclick="openDeleteModal()">
                    <div class="menu-left">
                        <div class="menu-icon delete-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </div>
                        <div class="menu-text delete-text">{{ __('Delete Account') }}</div>
                    </div>
                    <span class="menu-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </button>
            </div>

            <div class="section-chip">{{ __('Notifications') }}</div>

            <div class="menu-list">
                <a href="{{ route('doctor.alert-sounds') }}" class="menu-item">
                    <div class="menu-left">
                        <div class="menu-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                        </div>
                        <span class="menu-text">{{ __('Alert and Vibration') }}</span>
                    </div>
                    <span class="menu-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </a>
            </div>

            <div class="section-chip">{{ __('Support') }}</div>

            <div class="menu-list">
                <a href="{{ url('/doctor/complaint') }}" class="menu-item">
                    <div class="menu-left">
                        <div class="menu-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                            </svg>
                        </div>
                        <span class="menu-text">{{ __('Submit Complaint') }}</span>
                    </div>
                    <span class="menu-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
                    </span>
                </a>
            </div>

        </div>
    </div>
</body>
<script>
    // 💡 دوال التحكم في النوافذ (Modals)
    function openLangModal() {
        document.getElementById('modalOverlay').classList.add('show');
        document.getElementById('langModal').classList.add('show');
    }

    function openDeleteModal() {
        document.getElementById('modalOverlay').classList.add('show');
        document.getElementById('deleteModal').classList.add('show');
    }

    function closeAllModals() {
        document.getElementById('modalOverlay').classList.remove('show');
        document.getElementById('langModal').classList.remove('show');
        document.getElementById('deleteModal').classList.remove('show');
    }
</script>
</html>