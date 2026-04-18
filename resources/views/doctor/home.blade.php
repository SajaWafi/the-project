<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors Profile</title>
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

        .star {
            position: absolute;
            left: -2px;
            top: 110px;
            color: #f3d467;
            font-size: 26px;
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 2;
            padding: 8px 12px 100px;
            height: 100%;
            overflow-y: auto;
        }

        .content::-webkit-scrollbar {
            width: 5px;
        }

        .content::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.15);
            border-radius: 10px;
        }

        .header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 6px;
            margin-bottom: 18px;
        }

        .back-btn {
            position: absolute;
            left: 10px;
            font-size: 30px;
            color: #3d78ff;
            text-decoration: none;
            line-height: 1;
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
            width: 100px;
            height:100px;
            object-fit: contain;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-card {
            background: #bfc8f0;
            border-radius: 24px;
            padding: 12px 12px 16px;
            position: relative;
            margin-bottom: 14px;
        }

        .chat-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 2px solid #3d78ff;
            color: #3d78ff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 17px;
            background: #fff;
        }

        .chat-svg {
            width: 20px;
            height: 20px;
            color: #3d78ff;
        }
        .chat-btn:hover {
        background: #3d78ff;
        color: #fff;
    }

        .top-profile {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 12px;
            padding-right: 40px;
        }

        .doctor-image {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            background: #cfd4d8;
        }

        .profile-info {
            flex: 1;
            padding-top: 4px;
        }

        .welcome-box,
        .specialty-box {
            background: #f6f4ef;
            border-radius: 14px;
            padding: 8px 12px;
        }

        .welcome-box {
            margin-bottom: 8px;
        }

        .welcome-text {
            color: #4f82ff;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .patient-name {
            color: #2b2b2b;
            font-size: 15px;
            font-weight: 400;
            text-decoration: none;
        }

        .specialize-label {
            color: #4f82ff;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .specialty-text {
            color: #4b4b4b;
            font-size: 15px;
        }

        .location-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 8px;
        }

        .location-pill {
            width: 100%;
            height: 22px;
            background: #2f64f3;
            border-radius: 12px;
            position: relative;
        }

        .location-pill::before {
            content: "◉";
            position: absolute;
            left: 9px;
            top: 50%;
            transform: translateY(-55%);
            color: #ffffff;
            font-size: 10px;
        }

        .schedule-mini-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .schedule-mini-col {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .mini-pill {
            background: #f6f4ef;
            border-radius: 14px;
            text-align: center;
            padding: 4px 8px;
            font-size: 13px;
            color: #4f82ff;
            line-height: 1.15;
        }

        .about-box {
            background: #2d63f6;
            color: #fff;
            border-radius: 20px;
            padding: 16px 20px;
            font-size: 18px;
            line-height: 1.45;
            margin-bottom: 12px;
        }

        .section-chip {
            display: inline-block;
            background: #2d63f6;
            color: #fff;
            border-radius: 14px;
            padding: 4px 12px;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .schedule-card {
            background: #bfc8f0;
            border-radius: 24px;
            padding: 14px 12px;
            margin-bottom: 16px;
        }

        .appointment-box {
            background: #fff9f2;
            border-radius: 18px;
            padding: 12px 10px 12px;
            display: grid;
            grid-template-columns: 42px 1fr;
            gap: 8px;
        }

        .times {
            color: #2d63f6;
            font-size: 12px;
            line-height: 2;
            padding-top: 10px;
        }

        .appointment-content {
            min-width: 0;
        }

        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #2d63f6;
            font-size: 13px;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 2px dotted #73a0ff;
        }

        .appointment-main {
            background: #bfc8f0;
            border-radius: 14px;
            padding: 10px 12px;
            margin-top: 14px;
        }

        .doctor-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 3px;
        }

        .appointment-doctor-name {
            color: #2d63f6;
            font-size: 16px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .doctor-actions {
            color: #2d63f6;
            font-size: 14px;
            flex-shrink: 0;
        }

        .appointment-sub {
            color: #555;
            font-size: 14px;
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

        .title {
            text-align: center;
            font-size: 28px;
            font-weight: 800;
            color: #1d567e;
            margin-bottom: 10px;
        }
        .settings-btn {
    position: absolute;
    top: 50px; /* تحت الجرس */
    right: 10px;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 2px solid #3d78ff;
    color: #3d78ff;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 18px;
    background: #fff;
}
.settings-btn:hover {
    background: #3d78ff;
    color: #fff;
}
</style>
</head>
<body>
    <div class="phone">

        <div class="content">
            <div class="header">
                <div class="title">Home</div>
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
            </div>

            <div class="profile-card">
                <a href="{{ route('doctor.request') }}" class="chat-btn">
                    <svg class="chat-svg" viewBox="0 0 24 24" fill="none">
                        <path d="M12 4a4 4 0 0 0-4 4v2.2c0 .7-.2 1.3-.6 1.8L6 14h12l-1.4-2c-.4-.5-.6-1.1-.6-1.8V8a4 4 0 0 0-4-4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10 17a2 2 0 0 0 4 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </a>
                <a href="{{ route('doctor.settings') }}" class="settings-btn">
                    <i class="ri-settings-3-line"></i>
                </a>
                <div class="top-profile">
                    <img
                        class="doctor-image"
                        src="{{ asset('images/doctor1.png') }}"
                        alt="Doctor"
                    >

                    <div class="profile-info">
                        <div class="welcome-box">
                            <div class="welcome-text">Hi, WelcomeBack</div>
                            <a href="{{ route('doctor.doctor-profile') }} " class="patient-name">
                                <div class="patient-name">John Doe</div>
                            </a>
                        </div>

                        <div class="specialty-box">
                            <div class="specialize-label">Specialize In</div>
                            <div class="specialty-text">Pediatric Neurologist</div>
                        </div>
                    </div>
                </div>

                <div class="location-row">
                    <div class="location-pill"></div>
                    <div class="location-pill"></div>
                </div>

                <div class="schedule-mini-row">
                    <div class="schedule-mini-col">
                        <div class="mini-pill">9:00AM - 5:00PM</div>
                        <div class="mini-pill">Sat - Mon - Wed</div>
                    </div>

                    <div class="schedule-mini-col">
                        <div class="mini-pill">9:00AM - 5:00PM</div>
                        <div class="mini-pill">Sun - Tue - Thu</div>
                    </div>
                </div>
            </div>

            <div class="about-box">
                The impact of hormonal imbalances on skin conditions,
                specializing in acne, hirsutism, and other skin disorders.
            </div>

            <div class="section-chip">Today Appointment</div>

            <div class="schedule-card">
                <div class="appointment-box">
                    <div class="times">
                        <div>9 AM</div>
                        <div>10 AM</div>
                        <div>11 AM</div>
                        <div>12 AM</div>
                    </div>

                    <div class="appointment-content">
                        <div class="appointment-header">
                            <span>11 Wednesday</span>
                            <span>Today</span>
                        </div>

                        <div class="appointment-main">
                            <div class="doctor-row">
                                <div class="appointment-doctor-name">Ali Salah</div>
                                <div class="doctor-actions"></div>
                            </div>

                            <div class="appointment-sub">Child Care Center</div>
                        </div>
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
