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
            font-family: Arial, sans-serif;
        }

        body {
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .phone {
            width: 390px;
            height: 844px;
            background: url('{{ asset('images/bg.png') }}') no-repeat center center/cover;
            border-radius: 22px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }

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

        .header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
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

        .add-btn {
            position: absolute;
            left: 4px;
            top: 0;
            font-size: 30px;
            line-height: 1;
            color: #3d78ff;
            text-decoration: none;
        }

        .profile-image-wrap {
            position: relative;
            width: fit-content;
            margin: 12px auto 24px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            background: #d9d9d9;
        }

        .doctor-name {
            text-align: center;
            font-size: 29px;
            font-weight: 700;
            color: #111;
            margin-bottom: 26px;
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
            border-radius: 16px;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.18);
            padding-left: 6px;
            padding-right: 6px;
        }

        .menu-item:active {
            transform: scale(0.985);
            background: rgba(255,255,255,0.28);
        }

        .menu-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .icon-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #c8ebe6;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
            flex-shrink: 0;
        }

        .menu-icon {
            width: 22px;
            height: 22px;
            color: #2f7cff;
        }

        .menu-text {
            font-size: 18px;
            font-weight: 500;
            color: #111;
        }

        .menu-arrow {
            font-size: 30px;
            line-height: 1;
            color: #ee943f;
            padding-right: 4px;
        }

        .logout-overlay {
            position: absolute;
            inset: 0;
            background: rgba(120,150,200,0.35);
            z-index: 20;
            opacity: 0;
            visibility: hidden;
            transition: 0.25s ease;
        }

        .logout-modal {
            position: absolute;
            left: 0;
            right: 0;
            bottom: -300px;
            background: #fff;
            border-top-left-radius: 28px;
            border-top-right-radius: 28px;
            padding: 26px 20px 34px;
            z-index: 25;
            text-align: center;
            transition: 0.3s ease;
        }

        .logout-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .logout-modal.show {
            bottom: 0;
        }

        .logout-modal-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .logout-modal-text {
            font-size: 16px;
            color: #666;
            margin-bottom: 18px;
        }

        .logout-modal-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            align-items: center;
        }

        .logout-modal-actions form {
            margin: 0;
        }

        .cancel-logout-btn,
        .confirm-logout-btn {
            min-width: 140px;
            height: 40px;
            border-radius: 20px;
            border: none;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .cancel-logout-btn {
            background: #c8ebe6;
            color: #2f80ed;
        }

        .confirm-logout-btn {
            background: #2f80ed;
            color: white;
        }
    </style>
</head>
<body>
    <div class="phone">

        <div class="logout-overlay" id="logoutOverlay" onclick="closeLogoutModal()"></div>

        <div class="logout-modal" id="logoutModal">
            <div class="logout-modal-title">Logout</div>
            <div class="logout-modal-text">
                Are you sure you want to logout?
            </div>

            <div class="logout-modal-actions">
                <button type="button" class="cancel-logout-btn" onclick="closeLogoutModal()">
                    Cancel
                </button>

                <form action="{{ route('doctor.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="confirm-logout-btn">
                        Yes, Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="content">

            <div class="header">
                <a href="{{ route('doctor.children.search') }}" class="add-btn">+</a>

                <div class="title">My Profile</div>

                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="logo">
                </div>
            </div>

            <div class="profile-image-wrap">
              <img 
            src="{{ !empty(auth()->user()->profile_image)
                ? asset('storage/' . auth()->user()->profile_image)
                : asset('images/default-user.png') }}"
            class="profile-image"
            alt="Profile"
        >
            </div>

            <div class="doctor-name">
                {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
            </div>

            <div class="menu-list">
                <a href="{{ route('doctor.privacy') }}" class="menu-item">
                    <div class="menu-left">
                        <div class="icon-circle">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none">
                                <rect x="6" y="10" width="12" height="10" rx="2" stroke="currentColor" stroke-width="2"/>
                                <path d="M8 10V7a4 4 0 1 1 8 0v3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="menu-text">Privacy Policy</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </a>

                <a href="{{ route('doctor.settings') }}" class="menu-item">
                    <div class="menu-left">
                        <div class="icon-circle">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="3.5" stroke="currentColor" stroke-width="2"/>
                                <path d="M19 12a7 7 0 0 0-.1-1l2-1.5-2-3.5-2.4 1a8 8 0 0 0-1.7-1l-.3-2.6h-4l-.3 2.6a8 8 0 0 0-1.7 1l-2.4-1-2 3.5 2 1.5a7 7 0 0 0 0 2l-2 1.5 2 3.5 2.4-1a8 8 0 0 0 1.7 1l.3 2.6h4l.3-2.6a8 8 0 0 0 1.7-1l2.4 1 2-3.5-2-1.5c.1-.3.1-.7.1-1Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="menu-text">Settings</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </a>

                <a href="javascript:void(0)" class="menu-item" onclick="openLogoutModal()">
                    <div class="menu-left">
                        <div class="icon-circle">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none">
                                <path d="M16 17l5-5-5-5" stroke="currentColor" stroke-width="2"/>
                                <path d="M21 12H9" stroke="currentColor" stroke-width="2"/>
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="menu-text">Logout</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </a>
            </div>

        </div>
    </div>

    <script>
        function openLogoutModal() {
            document.getElementById('logoutOverlay').classList.add('show');
            document.getElementById('logoutModal').classList.add('show');
        }

        function closeLogoutModal() {
            document.getElementById('logoutOverlay').classList.remove('show');
            document.getElementById('logoutModal').classList.remove('show');
        }
    </script>
</body>
</html>