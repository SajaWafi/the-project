<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-straight/css/uicons-solid-straight.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parents</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #ffffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
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
            padding: 12px;
            height: 100%;
            overflow-y: auto;
        }

        .status-bar {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 14px;
        }

        .title {
            font-size: 26px;
            font-weight: 800;
            color: #1d567e;
        }

        .logo {
            position: absolute;
            right: 0;
            width: 50px;
            height: 34px;
        }

        .top-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .add-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #22c1a6;
            color: white;
            font-size: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search {
    flex: 1;
    height: 36px;
    border-radius: 20px;
    background: rgba(34,193,166,0.25);
    display: flex;
    align-items: center;
    padding: 0 12px;
    gap: 6px;
}

.search input {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    font-size: 14px;
    color: #333;
}

.search input::placeholder {
    color: #777;
}

        .parent-card {
            background: #a8d3cc;
            border-radius: 20px;
            padding: 12px;
            display: flex;
            align-items: center;
            margin-bottom: 14px;
        }

        .parent-card img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
        }

        .info {
            margin-left: 12px;
            flex: 1;
        }

        .name {
            font-weight: bold;
            color: white;
            font-size: 16px;
        }

        .sub {
            font-size: 13px;
            color: #333;
            margin-top: 2px;
        }

        .actions {
            margin-top: 8px;
            display: flex;
            gap: 8px;
        }

        .icon-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 16px;
            color: #1f567f;
            text-decoration: none;
        }

        /* navbar */
.bottom-nav {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 64px;
            background: #2f80ed;
            border-radius: 0 0 20px 20px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            z-index: 1000;
        }

        .nav-item {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: rgba(255,255,255,0.65);
            transition: 0.2s;
            text-decoration: none;
        }

        .nav-svg {
            width: 22px;
            height: 22px;
        }

        .nav-item.active {
            background: rgba(255,255,255,0.18);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .leaflet-control-attribution {
            font-size: 10px;
        }

        .custom-marker {
            width: 18px;
            height: 18px;
            background: #18b7b0;
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: 0 0 0 3px rgba(24,183,176,0.25);
        }

        .btn-icon {
            width:36px;
            height:36px;
            border-radius:50%;
            background:white;
            display:flex;
            justify-content:center;
            align-items:center;
            text-decoration:none;
            color:#1f567f;
            font-size:18px;
        }

    </style>
</head>
<body>

<div class="phone">
    <div class="content">

        <div class="header">
            <div class="title">Parents</div>
            <img src="{{ asset('images/logo.png') }}" class="logo">
        </div>

        <div class="top-bar">
            <a href="#" class="add-btn">+</a>
            <div class="search">
                <span>🔍</span>
                <input type="text" placeholder="Search..." name="search">
            </div>
        </div>

        <!-- Parent Card -->
        <div class="parent-card">
            <img src="{{ asset('images/p1.png') }}">
            <div class="info">
                <div class="name">Ali Salah</div>
                <div class="sub">Ahmed Salah's father</div>

                <div class="actions">
                     <a href="{{ route('doctor.parent.profile', 1) }}" class="btn-icon">
                            <i class="fi fi-sr-user"></i>
                        </a>
                        <a href="{{ route('doctor.chat', 1) }}" class="btn-icon">
                            <i class="fi fi-ss-messages"></i>
                        </a>
                </div>
            </div>
        </div>

        <div class="parent-card">
            <img src="{{ asset('images/p2.png') }}">
            <div class="info">
                <div class="name">Hifa Jaber</div>
                <div class="sub">Wala Ali's mother</div>

                <div class="actions">
                    <a href="{{ route('doctor.parent.profile', 1) }}" class="btn-icon">
                        <i class="fi fi-sr-user"></i>
                    </a>
                    <a href="#" class="btn-icon">
                        <i class="fi fi-ss-messages"></i>
                    </a> 
                </div>
            </div>
        </div>

        <div class="parent-card">
            <img src="{{ asset('images/p3.png') }}">
            <div class="info">
                <div class="name">Marwan Hasan</div>
                <div class="sub">Maryam Hasan's father</div>

                <div class="actions">
                    <a href="{{ route('doctor.parent.profile', 1) }}" class="btn-icon">
                        <i class="fi fi-sr-user"></i>
                    </a>
                    <a href="#" class="btn-icon">
                        <i class="fi fi-ss-messages"></i>
                    </a> 
                </div>
            </div>
        </div>

    </div>

            <!-- navbar -->
        <div class="bottom-nav">

            <a href="{{ route('doctor.parents') }}" class="nav-item {{ request()->routeIs('doctor.parents') ? 'active' : '' }}">
                    <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                        <circle cx="10" cy="8" r="3.5" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M4.5 18c1.2-2.8 3.3-4.2 5.5-4.2s4.3 1.4 5.5 4.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M18 9v6M15 12h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
            </a>

            <a href="{{ route('doctor.home') }}" class="nav-item {{ request()->routeIs('doctor.home') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M4 10.5 12 4l8 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M7 10v9h10v-9" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                </svg>
            </a>

            <a href="{{ route('doctor.appointments') }}" class="nav-item {{ request()->routeIs('doctor.appointments') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <rect x="4" y="6" width="16" height="14" rx="3" stroke="currentColor" stroke-width="2"/>
                    <path d="M8 3v4M16 3v4M4 10h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </a>

    </div>

</div>

</body>
</html>