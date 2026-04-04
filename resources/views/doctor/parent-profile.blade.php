<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Profile</title>
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

        .star {
            position: absolute;
            left: -2px;
            top: 108px;
            color: #f3d467;
            font-size: 26px;
            z-index: 1;
        }

        .status-bar {
            height: 28px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 12px 0;
            font-size: 13px;
            font-weight: 700;
            color: #111;
            position: relative;
            z-index: 3;
        }

        .content {
            position: relative;
            z-index: 2;
            height: 100%;
            overflow-y: auto;
            padding: 8px 14px 30px;
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
            height: 42px;
            margin-bottom: 14px;
        }

        .back-btn {
            position: absolute;
            left: 6px;
            top: 2px;
            font-size: 30px;
            line-height: 1;
            color: #1fc9a8;
            text-decoration: none;
        }

        .logo {
            position: absolute;
            right: 0;
            top: 0;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            overflow: hidden;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-card {
            background: #b7e1d9;
            border-radius: 22px;
            padding: 16px 16px 18px;
            position: relative;
            margin-bottom: 20px;
        }

        .chat-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #fff;
            border: 1.8px solid #20d0b0;
            color: #20d0b0;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 17px;
        }

        .parent-image {
            width: 116px;
            height: 116px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 12px auto 18px;
            background: #d5d5d5;
        }

        .parent-name {
            text-align: center;
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .parent-subtitle {
            text-align: center;
            font-size: 18px;
            color: #111;
            margin-bottom: 12px;
        }

        .info-row {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .info-pill {
            min-width: 118px;
            background: #fff8f2;
            border-radius: 14px;
            padding: 4px 12px;
            text-align: center;
            font-size: 14px;
            color: #2bc7af;
            line-height: 1.2;
        }

        .pdf-btn {
            width: 180px;
            height: 42px;
            margin: 0 auto;
            background: #16d0b2;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: white;
            text-decoration: none;
            font-size: 22px;
            font-weight: 700;
        }

        .pdf-icon {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #fff;
            color: #f25c3b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .section-chip {
            display: inline-block;
            background: #44c9b2;
            color: #fff;
            border-radius: 14px;
            padding: 4px 14px;
            font-size: 14px;
            font-weight: 700;
            margin: 0 0 12px 4px;
        }

        .schedule-card {
            background: #b7e1d9;
            border-radius: 22px;
            padding: 16px 12px;
        }

        .appointment-box {
            background: #fffaf4;
            border-radius: 18px;
            padding: 12px 10px;
            display: grid;
            grid-template-columns: 44px 1fr;
            gap: 8px;
        }

        .times {
            color: #44c9b2;
            font-size: 12px;
            line-height: 2;
            padding-top: 10px;
        }

        .appointment-content {
            min-width: 0;
        }

        .appointment-header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            color: #44c9b2;
            font-size: 13px;
            margin-bottom: 10px;
            padding-bottom: 4px;
            border-bottom: 2px dotted #69d7c4;
        }

        .appointment-main {
            background: #b7e1d9;
            border-radius: 14px;
            padding: 10px 12px;
            margin-top: 12px;
            position: relative;
        }

        .doctor-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }

        .doctor-name {
            color: #30b9a6;
            font-size: 16px;
            font-weight: 700;
        }

        .doctor-actions {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #8b8b8b;
            font-size: 12px;
        }

        .mini-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #89b9af;
        }

        .appointment-sub {
            color: #2f2f2f;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="phone">
        <div class="content">
            <div class="header">
                <a href="{{ url()->previous() }}" class="back-btn">‹</a>

                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="logo">
                </div>
            </div>

            <div class="profile-card">
                <a href="#" class="chat-btn">💬</a>

                <img
                    class="parent-image"
                    src="{{ asset('images/' . ($parent['image'] ?? 'p1.png')) }}"
                    alt="Parent"
                >

                <div class="parent-name">{{ $parent['name'] ?? 'Ali Saloh' }}</div>
                <div class="parent-subtitle">{{ $parent['subtitle'] ?? "Ahmed Salah’s father" }}</div>

                <div class="info-row">
                    <div class="info-pill">{{ $parent['phone'] ?? '09X - XXXXXXX' }}</div>
                    <div class="info-pill">{{ $parent['autism_level'] ?? 'Autism Levels: Mild' }}</div>
                </div>

                <a href="{{ asset('files/sample.pdf') }}" class="pdf-btn" download>
                    <span class="pdf-icon">PDF</span>
                    <span>Download PDF</span>
                </a>
            </div>

            <div class="section-chip">Appointment</div>

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
                            <span>11 Wednesday - Today</span>
                        </div>

                        <div class="appointment-main">
                            <div class="doctor-row">
                                <div class="doctor-name">Dr. Olivia Turner</div>
                                <div class="doctor-actions">
                                    <a href="#" class="appointment-sub">×</a>
                                </div>
                            </div>

                            <div class="appointment-sub">Periodic review</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>