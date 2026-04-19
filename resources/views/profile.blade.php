<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>

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
            padding: 16px 18px 28px;
        }

        .header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            min-height: 42px;
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
            font-size: 28px;
            font-weight: 800;
            color: #1f5b87;
        }

        .app-logo {
            position: absolute;
            right: 0;
            width:100px;
            height: 100px;
            object-fit: contain;
        }

        .profile-top {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 6px;
            margin-bottom: 28px;
        }

        .avatar-wrap {
            position: relative;
            width: 126px;
            height: 126px;
            margin-bottom: 18px;
        }

        .avatar {
            width: 126px;
            height: 126px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            border: 4px solid rgba(255,255,255,0.95);
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }

        .avatar-star {
            position: absolute;
            left: -8px;
            top: 70px;
            font-size: 28px;
            color: #f1d46a;
            line-height: 1;
        }

        .edit-avatar-btn {
            position: absolute;
            right: 2px;
            bottom: 6px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: none;
            background: #1f5b87;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(31, 91, 135, 0.25);
        }

        .edit-avatar-btn svg {
            width: 16px;
            height: 16px;
        }

        #avatarInput {
            display: none;
        }

        .name-ar {
            font-size: 22px;
            font-weight: 200;
            color: #111;
            margin-bottom: 5px;
            text-align: center;
        }

        .name-en {
            font-size: 25px;
            font-weight: 500;
            color: #111;
            margin-bottom: 5px;
            text-align: center;
        }

        .profile-id {
            font-size: 16px;
            color: #2d2d2d;
            text-align: center;
        }

        .menu-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #111;
            padding: 6px 0;
        }

        .menu-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .menu-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #bdeee5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2f80ed;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            flex-shrink: 0;
        }

        .menu-icon svg {
            width: 20px;
            height: 20px;
        }

        .menu-text {
            font-size: 20px;
            font-weight: 500;
            color: #111;
        }

        .arrow-right {
            color: #f19a43;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .arrow-right svg {
            width: 20px;
            height: 20px;
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
                padding: 14px 16px 26px;
            }
        }
                .logout-btn-trigger {
            width: 100%;
            border: none;
            background: transparent;
            cursor: pointer;
            font-family: inherit;
        }

        .logout-overlay {
            position: absolute;
            inset: 0;
            background: rgba(130, 160, 210, 0.18);
            z-index: 20;
            display: none;
            align-items: flex-end;
            justify-content: center;
            padding: 0 0 0;
        }

        .logout-overlay.show {
            display: flex;
        }

        .logout-sheet {
            width: 100%;
            background: #f7f7f7;
            border-radius: 28px 28px 0 0;
            padding: 24px 20px 28px;
            box-shadow: 0 -8px 24px rgba(0,0,0,0.08);
            transform: translateY(100%);
            transition: transform 0.25s ease;
        }

        .logout-overlay.show .logout-sheet {
            transform: translateY(0);
        }

        .logout-title {
            text-align: center;
            font-size: 18px;
            font-weight: 800;
            color: #111;
            margin-bottom: 10px;
        }

        .logout-text {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-bottom: 18px;
        }

        .logout-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .logout-action-btn {
            min-width: 96px;
            height: 36px;
            border-radius: 999px;
            border: none;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
        }

        .logout-action-btn:active {
            transform: scale(0.97);
        }

        .logout-cancel {
            background: #bcecdf;
            color: #2f80ed;
        }

        .logout-confirm {
            background: #2f80ed;
            color: #fff;
        }
    </style>
</head>
<body>

    <div class="mobile-screen">
        <div class="content">


            <div class="header">
                <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div class="page-title">My Profile</div>

                <img src="{{ asset('images/logo.png') }}" alt="Taif" class="app-logo">
            </div>

           <div class="profile-top">
    <div class="avatar-wrap">
            <img 
            src="{{ !empty(auth()->user()->profile_image)
                ? asset('storage/' . auth()->user()->profile_image)
                : asset('images/default-user.png') }}"
            alt="Profile"
            class="avatar"
        >

        <div class="avatar-star">★</div>

        <button class="edit-avatar-btn" type="button" onclick="window.location='{{ route('parent.profile.edit') }}'">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M4 20h4l10-10-4-4L4 16v4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M12.5 5.5 16.5 9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </button>
    </div>

    <div class="name-en">
        {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
    </div>

    <div class="name-ar">
        {{ optional(optional(auth()->user()->parentProfile)->child)->name ?? 'No Child' }}
    </div>

    <div class="profile-id">
        ID: {{ auth()->user()->id }}
    </div>
</div>

            <a href="{{ route('parent.profile.edit') }}" class="menu-item">
                <div class="menu-left">
                    <div class="menu-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M5 20c0-3.2 3-5 7-5s7 1.8 7 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="menu-text">Profile</div>
                </div>

                <div class="arrow-right">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </a>

                <a href="#" class="menu-item">
                    <div class="menu-left">
                        <div class="menu-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <rect x="7" y="3" width="10" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M10 7h4M10 17h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="menu-text">Connect Bracelet</div>
                    </div>

                    <div class="arrow-right">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </a>

               <a href="{{ route('privacy.policy') }}" class="menu-item">
                    <div class="menu-left">
                        <div class="menu-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M7 11V8a5 5 0 0 1 10 0v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                <rect x="5" y="11" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.8"/>
                            </svg>
                        </div>
                        <div class="menu-text">Privacy Policy</div>
                    </div>

                    <div class="arrow-right">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('settings') }}" class="menu-item">
                    <div class="menu-left">
                        <div class="menu-icon">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M12 8.5a3.5 3.5 0 1 0 0 7a3.5 3.5 0 0 0 0-7Z" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M19 12a7 7 0 0 0-.1-1l2-1.5-2-3.5-2.4 1a7.4 7.4 0 0 0-1.7-1L14.5 3h-5l-.3 3a7.4 7.4 0 0 0-1.7 1l-2.4-1-2 3.5l2 1.5a7 7 0 0 0 0 2l-2 1.5l2 3.5l2.4-1a7.4 7.4 0 0 0 1.7 1l.3 3h5l.3-3a7.4 7.4 0 0 0 1.7-1l2.4 1l2-3.5-2-1.5c.1-.3.1-.7.1-1Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="menu-text">Settings</div>
                    </div>

                    <div class="arrow-right">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </a>

           <button type="button" class="menu-item logout-btn-trigger" onclick="openLogoutModal()">
    <div class="menu-left">
        <div class="menu-icon">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M10 7V5a2 2 0 0 1 2-2h5a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5a2 2 0 0 1-2-2v-2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M14 12H4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M7 9L4 12L7 15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="menu-text">Logout</div>
    </div>

    <div class="arrow-right">
        <svg viewBox="0 0 24 24" fill="none">
            <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
</button>

</div>

<div class="logout-overlay" id="logoutOverlay" onclick="closeLogoutModal(event)">
    <div class="logout-sheet" onclick="event.stopPropagation()">
        <div class="logout-title">Logout</div>
        <div class="logout-text">Are you sure you want to log out?</div>

        <div class="logout-actions">
            <button type="button" class="logout-action-btn logout-cancel" onclick="closeLogoutModal()">
                Cancel
            </button>

            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="logout-action-btn logout-confirm">
                    Yes, Logout
                </button>
            </form>
        </div>
    </div>
</div>
    </div>

    <script>
        const avatarInput = document.getElementById('avatarInput');
        const profileAvatar = document.getElementById('profileAvatar');

        avatarInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                profileAvatar.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
            const logoutOverlay = document.getElementById('logoutOverlay');

    function openLogoutModal() {
        document.getElementById('logoutOverlay').classList.add('show');
    }

    function closeLogoutModal(event) {
        if (!event || event.target.id === 'logoutOverlay') {
            document.getElementById('logoutOverlay').classList.remove('show');
        }
    }
    </script>

</body>
</html>