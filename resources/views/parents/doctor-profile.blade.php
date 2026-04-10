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
    justify-content: center;
    align-items: center;
    font-size: 22px;
    font-weight: 800;
    color: #1f567f;
    margin-bottom: 20px;
    margin-top: 15px; /* 👈 هذا الجديد */
}


        .header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

       .back-btn {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 38px;
        height: 38px;
        border: none;
        background: transparent;
        padding: 0;
        cursor: pointer;
        color: #3d78ff;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 5;
    }

    .back-btn svg {
        width: 24px;
        height: 24px;
        display: block;
    }

        .title {
        font-size: 28px;
        font-weight: 800;
        color: #1d567e;
        text-align: center;
        }

       .header-logo {
            position: absolute;
            right: 10px;
            width: 50px;
            height:50px;
            object-fit: contain;
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
        .profile-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    z-index: 3;
}

.chat-btn,
.delete-icon-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 2px solid #3d78ff;
    color: #3d78ff;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 18px;
    background: #fff;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.delete-icon-btn {
    border-color: #ff6b6b;
    color: #ff4d4f;
}

.chat-btn:hover,
.delete-icon-btn:hover {
    transform: scale(1.05);
    transition: 0.2s ease;
}
.delete-modal-overlay {
    position: absolute;
    inset: 0;
    background: rgba(210, 216, 223, 0.82);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 100;
    padding: 22px;
}

.delete-modal-overlay.show {
    display: flex;
}

.delete-modal-box {
    width: 100%;
    max-width: 340px;
    background: #f7f7f7;
    border-radius: 34px;
    padding: 42px 26px 34px;
    text-align: center;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
}

.delete-modal-title {
    font-size: 28px;
    font-weight: 800;
    color: #000;
    margin-bottom: 22px;
}

.delete-modal-text {
    font-size: 22px;
    line-height: 1.45;
    color: #55657a;
    margin-bottom: 34px;
}

.delete-modal-actions {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.delete-modal-actions form {
    margin: 0;
}

.modal-cancel-btn,
.modal-delete-btn {
    min-width: 150px;
    height: 54px;
    border: none;
    border-radius: 30px;
    font-size: 20px;
    font-weight: 700;
    cursor: pointer;
    padding: 0 24px;
}

.modal-cancel-btn {
    background: #b8e6db;
    color: #2d63f6;
}

.modal-delete-btn {
    background: #3a82f6;
    color: #ffffff;
}
.info-grid {
    display: flex;
    gap: 12px;
}
.info-col {
    flex: 1;
    background: rgba(255, 255, 255, 0.6); /* أخف من الأبيض */
    border-radius: 14px;
    padding: 12px;
    text-align: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05); /* ظل خفيف جدًا */
    backdrop-filter: blur(4px); /* يعطي نعومة */
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
                    <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                    <div class="title">Doctors Profile</div>
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="header-logo">
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-actions">
                    <a href="{{ route('parents.chat', $doctor['id'] ?? 1) }}" class="chat-btn" title="Chat">
                        💬
                    </a>

                   <button type="button" class="delete-icon-btn" onclick="openDeleteModal()" title="Delete Doctor">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18"></path>
                        <path d="M8 6V4h8v2"></path>
                        <path d="M19 6l-1 14H6L5 6"></path>
                        <path d="M10 11v6"></path>
                        <path d="M14 11v6"></path>
                    </svg>
                </button>
                </div>

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
        <div class="delete-modal-overlay" id="deleteModal">
    <div class="delete-modal-box">
        <div class="delete-modal-title">Delete Doctor</div>
        <div class="delete-modal-text">
            Are you sure you want to delete this doctor?
        </div>

        <div class="delete-modal-actions">
            <button type="button" class="modal-cancel-btn" onclick="closeDeleteModal()">
                Cancel
            </button>

            <form action="{{ route('parents.doctors.delete', $doctor['id'] ?? 1) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-delete-btn">
                    Yes, Delete
                </button>
            </form>
        </div>
    </div>
</div>
    </div>
    <script>
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.add('show');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');
    }

    function confirmDeleteDoctor() {
        closeDeleteModal();
        alert('Doctor deleted successfully');
    }

    document.addEventListener('click', function (event) {
        const modal = document.getElementById('deleteModal');

        if (event.target === modal) {
            closeDeleteModal();
        }
    });
</script>
</body>
</html>