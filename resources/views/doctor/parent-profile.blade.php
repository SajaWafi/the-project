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
         .title {
        font-size: 28px;
        font-weight: 800;
        color: #1d567e;
        text-align: center;
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

        /* stacked actions */
        .profile-actions-stack {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 48px;
            height: 48px;
            z-index: 5;
        }

        .stack-btn {
            position: absolute;
            top: 0;
            right: 0;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            cursor: pointer;
            padding: 0;
            transition: all 0.25s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.12);
        }

        .chat-btn {
            border: 2px solid #6f7d73;
            color: #6f7d73;
            font-size: 18px;
            z-index: 2;
            transform: translate(0, 0) scale(1);
        }

        .delete-btn {
            border: 2px solid #ef8a8a;
            color: #ef8a8a;
            z-index: 1;
            transform: translate(-8px, 8px) scale(0.92);
        }

        .profile-actions-stack:hover .chat-btn {
            z-index: 1;
            transform: translate(-8px, 8px) scale(0.92);
        }

        .profile-actions-stack:hover .delete-btn {
            z-index: 2;
            transform: translate(0, 0) scale(1);
        }

        .delete-svg {
            width: 18px;
            height: 18px;
            display: block;
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

        .appointment-sub {
            color: #2f2f2f;
            font-size: 14px;
            text-decoration: none;
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
    </style>
</head>
<body>
    <div class="phone">
        <div class="content">
            <div class="header">
                <a href="{{ url()->previous() }}" class="back-btn">‹</a>
                <div class="title">Parent Profile</div>
                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="logo">
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-actions-stack">
                    <button type="button" class="stack-btn delete-btn" onclick="openDeleteModal()" title="Delete Parent">
                        <svg class="delete-svg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M4 7H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M9 4H15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M18 7L17.2 18.2C17.13 19.18 16.31 19.94 15.33 19.94H8.67C7.69 19.94 6.87 19.18 6.8 18.2L6 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M10 10.5V16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M14 10.5V16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </button>

                    <a href="{{ route('doctor.chat', $doctor['id'] ?? 1) }}" class="stack-btn chat-btn" title="Chat">
                        💬
                    </a>
                </div>

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

        <div class="delete-modal-overlay" id="deleteModal">
            <div class="delete-modal-box">
                <div class="delete-modal-title">Delete Parent</div>
                <div class="delete-modal-text">
                    Are you sure you want to delete this parent?
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

        document.addEventListener('click', function (event) {
            const modal = document.getElementById('deleteModal');

            if (event.target === modal) {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>