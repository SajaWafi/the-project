<!DOCTYPE html>
<html lang="en">
<head>
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
            background: #111;
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

        .bg-shape-top {
            position: absolute;
            top: -70px;
            left: -70px;
            width: 220px;
            height: 220px;
            background: rgba(201, 228, 245, 0.6);
            border-radius: 50%;
            z-index: 0;
        }

        .bg-shape-left {
            position: absolute;
            bottom: 80px;
            left: -70px;
            width: 160px;
            height: 220px;
            background: rgba(210, 240, 225, 0.45);
            border-radius: 50%;
            z-index: 0;
        }

        .bg-shape-bottom {
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 260px;
            height: 180px;
            background: rgba(230, 240, 235, 0.5);
            border-radius: 50%;
            z-index: 0;
        }

        .star {
            position: absolute;
            left: 0;
            top: 110px;
            color: #f3d467;
            font-size: 26px;
            z-index: 1;
        }

        .status-bar {
            height: 26px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px 0;
            font-size: 13px;
            font-weight: 700;
            color: #111;
            position: relative;
            z-index: 2;
        }

        .content {
            position: relative;
            z-index: 2;
            padding: 8px 12px 18px;
            height: calc(100% - 26px);
            overflow-y: auto;
        }

        .header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }


        .header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .back-btn {
            position: absolute;
            left: 10px;
            font-size: 28px;
            color: #3d78ff;
            text-decoration: none;
        }

        .title {
        font-size: 28px;
        font-weight: 800;
        color: #1d567e;
        text-align: center;
        }

        .logo {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2d79ff;
            font-size: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .profile-card {
            background: #bfc8f0;
            border-radius: 24px;
            padding: 14px 12px 18px;
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
            font-size: 18px;
            background: #fff;
        }

        .doctor-image {
            width: 128px;
            height: 128px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 0 auto 12px;
            background: #cfd4d8;
        }

        .name-box {
            background: #f6f4ef;
            border-radius: 14px;
            text-align: center;
            padding: 6px 10px;
            margin-bottom: 8px;
        }

        .doctor-name {
            color: #2d63f6;
            font-size: 15px;
            font-weight: 700;
        }

        .doctor-specialty {
            color: #333;
            font-size: 14px;
            margin-top: 2px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 12px;
            margin-top: 6px;
        }

        .info-col {
            text-align: center;
        }

        .time-text,
        .days-text {
            color: #3b6cff;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .location-pill {
            width: 100%;
            height: 20px;
            background: #2f64f3;
            border-radius: 12px;
            margin-top: 6px;
            position: relative;
        }

        .location-pill::before {
            content: "◉";
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-55%);
            color: #ffffff;
            font-size: 10px;
        }

        .phone-pill {
            width: 170px;
            margin: 10px auto 0;
            background: #f2efe9;
            border-radius: 14px;
            text-align: center;
            padding: 4px 10px;
            color: #4a78ff;
            font-size: 13px;
        }

        .about-box {
            background: #2d63f6;
            color: #fff;
            border-radius: 20px;
            padding: 16px 20px;
            font-size: 18px;
            line-height: 1.45;
            margin-bottom: 14px;
        }

        /* schedule card */
        .schedule-card {
            background: #bfc8f0;
            border-radius: 22px;
            padding: 16px 12px;
            margin-bottom: 14px;
        }

        .schedule-inner {
            background: #f6f4ef;
            border-radius: 18px;
            padding: 14px 12px;
            position: relative;
            min-height: 108px;
        }

        .schedule-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #3d78ff;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .schedule-lines {
            position: relative;
            padding-left: 42px;
            padding-right: 6px;
        }

        .line {
            border-top: 2px dotted #73a0ff;
            margin: 14px 0;
            position: relative;
        }

        .time-label {
            position: absolute;
            left: -42px;
            top: -10px;
            color: #3d78ff;
            font-size: 14px;
            width: 38px;
            text-align: right;
        }

        .event-box {
            position: absolute;
            left: 26px;
            top: 16px;
            right: 16px;
            background: #dde1ea;
            border-radius: 16px;
            padding: 10px 14px;
        }

        .event-title {
            color: #2d63f6;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 4px;
            text-align: center;
        }

        .event-desc {
            color: #555;
            font-size: 13px;
            line-height: 1.25;
        }

        .bottom-space {
            height: 20px;
        }

        .content::-webkit-scrollbar {
            width: 6px;
        }

        .content::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.15);
            border-radius: 10px;
        }

        .schedule-card {
            background: #bfc8f0;
            border-radius: 24px;
            padding: 14px 12px;
            margin-bottom: 18px;
        }

        .days-row {
            display: flex;
            justify-content: space-between;
            gap: 7px;
            margin-bottom: 12px;
        }

        .day-pill {
            width: 42px;
            height: 56px;
            border-radius: 18px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            line-height: 1.05;
            color: #454545;
            font-size: 10px;
            flex-shrink: 0;
        }

        .day-pill strong {
            font-size: 15px;
            margin-bottom: 2px;
        }

        .day-pill.active {
            background: #2d63f6;
            color: #fff;
        }

        .appointment-box {
            background: #fff9f2;
            border-radius: 18px;
            padding: 10px 10px 12px;
            display: grid;
            grid-template-columns: 42px 1fr;
            gap: 8px;
        }

        .times {
            color: #2d63f6;
            font-size: 12px;
            line-height: 2;
            padding-top: 6px;
        }

        .appointment-content {
            min-width: 0;
        }

        .appointment-header {
            text-align: center;
            color: #2d63f6;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .appointment-main {
            background: #bfc8f0;
            border-radius: 14px;
            border-top: 1px dashed #2d63f6;
            border-bottom: 1px dashed #2d63f6;
            padding: 10px 12px;
        }

        .doctor-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
        }

        .doctor-name {
            color: #2d63f6;
            font-size: 16px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .doctor-actions {
            color: #2d63f6;
            font-size: 13px;
            flex-shrink: 0;
        }

        .appointment-sub {
            color: #555;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="phone">
        <div class="bg-shape-top"></div>
        <div class="bg-shape-left"></div>
        <div class="bg-shape-bottom"></div>
        <div class="star">★</div> 

        <div class="content">
            <div class="header">
                <div class="header-left">
                    <a href="{{ url()->previous() }}" class="back-btn">‹</a>
                    <div class="title">Doctors Profile</div>
                </div>
            </div>

            <div class="profile-card">
                <a href="{{ route('parents.chat', $doctor['id'] ?? 1) }}" class="chat-btn">💬</a>

                <img
                    class="doctor-image"
                    src="{{ asset('images/bg.png') }}"
                    alt="Doctor"
                >

                <div class="name-box">
                    <div class="doctor-name">{{ $doctor['name'] ?? 'Dr. Alexander Bennett' }}</div>
                    <div class="doctor-specialty">{{ $doctor['specialty'] ?? 'Pediatric Neurologist' }}</div>
                </div>

                <div class="info-grid">
                    <div class="info-col">
                        <div class="time-text">9:00AM - 5:00PM</div>
                        <div class="days-text">Mon-Sat - sun</div>
                        <div class="location-pill"></div>
                    </div>

                    <div class="info-col">
                        <div class="time-text">9:00AM - 5:00PM</div>
                        <div class="days-text">Mon-Sat - sun</div>
                        <div class="location-pill"></div>
                    </div>
                </div>

                <div class="phone-pill">091-xxx xxx x</div>
            </div>

            <div class="about-box">
                The impact of hormonal imbalances on skin conditions,
                specializing in acne, hirsutism, and other skin disorders.
            </div>

            <div class="schedule-card">

                <div class="appointment-box">
                    <div class="times">
                        <div>9 AM</div>
                        <div>10 AM</div>
                        <div>11 AM</div>
                        <div>12 AM</div>
                    </div>

                    <div class="appointment-content">
                        <div class="appointment-header">11 Wednesday - Today</div>

                        <div class="appointment-main">
                            <div class="doctor-row">
                                <div class="doctor-name">Dr. Olivia Turner</div>
                                <div class="doctor-actions">×</div>
                            </div>

                            <div class="appointment-sub">Periodic review</div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="schedule-card">

                <div class="appointment-box">
                    <div class="times">
                        <div>9 AM</div>
                        <div>10 AM</div>
                        <div>11 AM</div>
                        <div>12 AM</div>
                    </div>

                    <div class="appointment-content">
                        <div class="appointment-header">11 Wednesday - Today</div>

                        <div class="appointment-main">
                            <div class="doctor-row">
                                <div class="doctor-name">Dr. Olivia Turner</div>
                                <div class="doctor-actions">×</div>
                            </div>

                            <div class="appointment-sub">Periodic review</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bottom-space"></div>
        </div>
    </div>
</body>
</html>