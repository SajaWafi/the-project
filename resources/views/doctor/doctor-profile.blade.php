<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
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

        /* ===== Content wrapper ===== */
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

        /* ===== Status bar ===== */
        .status-bar {
            height: 28px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 12px 0;
            font-size: 13px;
            font-weight: 700;
            color: #111;
            margin-bottom: 6px;
        }

        /* ===== Header ===== */
        .header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        /* ===== Back button ===== */
        .back-btn {
            position: absolute;
            left: 4px;
            top: 0;
            font-size: 30px;
            line-height: 1;
            color: #3d78ff;
            text-decoration: none;
        }

        /* ===== Page title ===== */
        .title {
            font-size: 28px;
            font-weight: 800;
            color: #1d567e;
            text-align: center;
        }

        /* ===== Logo ===== */
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

        /* ===== Profile image wrapper ===== */
        .profile-image-wrap {
            position: relative;
            width: fit-content;
            margin: 12px auto 24px;
        }

        /* ===== Doctor image ===== */
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            background: #d9d9d9;
        }

        /* ===== Star near image ===== */
        .star {
            position: absolute;
            left: -14px;
            top: 74px;
            color: #f0d46a;
            font-size: 34px;
            line-height: 1;
        }

        /* ===== Doctor name ===== */
        .doctor-name {
            text-align: center;
            font-size: 29px;
            font-weight: 700;
            color: #111;
            margin-bottom: 26px;
        }

        /* ===== Menu list ===== */
        .menu-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        /* ===== Each row is a full button/link ===== */
        .menu-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #111;
            padding: 6px 0;
            border-radius: 16px;
            transition: all 0.15s ease;
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

        /* ===== Left section of row ===== */
        .menu-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* ===== Rounded icon background ===== */
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

        /* ===== SVG icon style ===== */
        .menu-icon {
            width: 22px;
            height: 22px;
            color: #2f7cff;
        }

        /* ===== Text label ===== */
        .menu-text {
            font-size: 18px;
            font-weight: 500;
            color: #111;
        }

        /* ===== Right arrow ===== */
        .menu-arrow {
            font-size: 30px;
            line-height: 1;
            color: #ee943f;
            padding-right: 4px;
        }

        /* ===== Overlay ===== */
.logout-overlay {
    position: absolute;
    inset: 0;
    background: rgba(120,150,200,0.35);
    z-index: 20;
    opacity: 0;
    visibility: hidden;
    transition: 0.25s ease;
}

/* ===== Modal ===== */
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

/* ===== Active ===== */
.logout-overlay.show {
    opacity: 1;
    visibility: visible;
}

.logout-modal.show {
    bottom: 0;
}

/* ===== Title ===== */
.logout-modal-title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 10px;
}

/* ===== Text ===== */
.logout-modal-text {
    font-size: 16px;
    color: #666;
    margin-bottom: 18px;
}

/* ===== Buttons ===== */
.logout-modal-actions {
    display: flex;
    justify-content: center;
    gap: 12px;
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

.cancel-logout-btn1 {
    color: #ffffffff;
}

.confirm-logout-btn {
    background: #2f80ed;
    color: white;
}
    </style>
</head>
<body>
    <div class="phone">
        <!-- Overlay -->
        <div class="logout-overlay" id="logoutOverlay" onclick="closeLogoutModal()"></div>

        <!-- Logout Modal -->
        <div class="logout-modal" id="logoutModal">
            <div class="logout-modal-title">Logout</div>
            <div class="logout-modal-text">
                Are you sure you want to logout?
            </div>

            <div class="logout-modal-actions">
                <button type="button" class="cancel-logout-btn" onclick="closeLogoutModal()">Cancel</button>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="confirm-logout-btn">
                        <a href="{{ route('welcome.second') }}" class="cancel-logout-btn1">Yes,Logout</a>
                    </button>
                </form>
            </div>
        </div>

        <div class="content">

            <div class="header">
                <a href="{{ url()->previous() }}" class="back-btn">‹</a>

                <div class="title">My Profile</div>

                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="logo">
                </div>
            </div>

            <div class="profile-image-wrap">
                <img
                    src="{{ asset('images/doctor1.png') }}"
                    alt="Doctor"
                    class="profile-image"
                >
            </div>

            <div class="doctor-name">Alexander Bennett</div>

            <div class="menu-list">
                <!-- Profile 
                <a href="#" class="menu-item">
                    <div class="menu-left">
                        <div class="icon-circle">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"/>
                                <path d="M4 20c1.7-4 4.7-6 8-6s6.3 2 8 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="menu-text">Profile</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </a>
-->
                <!-- Privacy Policy -->
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

                <!-- Settings -->
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

                <!-- Logout -->
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
</body>
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
</html>