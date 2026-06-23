<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Parent Profile') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            inset-inline-start: 0;
            background: transparent;
            border: none;
            cursor: pointer;
            color: #2f80ed;
            padding: 6px;
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
            line-height: 42px;
        }

        .logo {
            position: absolute;
            inset-inline-end: 0;
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
            background: #a8d3cc;
            border-radius: 22px;
            padding: 16px 16px 18px;
            position: relative;
            margin-bottom: 20px;
        }

        .profile-actions-stack {
            position: absolute;
            top: 12px;
            inset-inline-end: 12px;
            width: 48px;
            height: 48px;
            z-index: 5;
        }

        .stack-btn {
            position: absolute;
            top: 0;
            inset-inline-end: 0;
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
            transform: translate({{ app()->getLocale() == 'ar' ? '8px' : '-8px' }}, 8px) scale(0.92);
        }

        .profile-actions-stack:hover .chat-btn {
            z-index: 1;
            transform: translate({{ app()->getLocale() == 'ar' ? '8px' : '-8px' }}, 8px) scale(0.92);
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
            font-size: 26px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .parent-subtitle {
            text-align: center;
            font-size: 16px;
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
            background: #ffffff;
            border-radius: 14px;
            padding: 6px 12px;
            text-align: center;
            font-size: 13px;
            color: #0f766e;
            font-weight: bold;
            line-height: 1.2;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        }

        .icon {
            width: 16px;
            height: 16px;
            fill: #14b8a6;
        }
      
        .section-chip {
            display: inline-block;
            background: #44c9b2;
            color: #fff;
            border-radius: 14px;
            padding: 4px 14px;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 12px;
            margin-inline-start: 4px;
        }

        .schedule-card {
            background: #a8d3cc;
            border-radius: 22px;
            padding: 16px 12px;
            margin-bottom: 14px;
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
            background: #a8d3cc;
            border-radius: 14px;
            padding: 10px 12px;
            margin-top: 12px;
            position: relative;
        }

        .appointment-sub {
            color: #2f2f2f;
            font-size: 14px;
            font-weight: bold;
        }

        /* Modal Styling */
        .custom-modal-overlay {
            position: absolute;
            inset: 0;
            background: rgba(210, 216, 223, 0.82);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 22px;
        }

        .custom-modal-overlay.show {
            display: flex;
        }

        .custom-modal-box {
            width: 100%;
            max-width: 340px;
            background: #f7f7f7;
            border-radius: 34px;
            padding: 30px 26px;
            text-align: center;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
        }

        .custom-modal-title {
            font-size: 22px;
            font-weight: 800;
            color: #000;
            margin-bottom: 22px;
        }

        .custom-modal-text {
            font-size: 16px;
            line-height: 1.45;
            color: #55657a;
            margin-bottom: 24px;
        }

        .custom-modal-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .custom-modal-actions form {
            margin: 0;
            width: 100%;
        }

        .modal-btn {
            flex: 1;
            height: 48px;
            border: none;
            border-radius: 24px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            padding: 0 15px;
        }

        .btn-cancel { background: #b8e6db; color: #2d63f6; }
        .btn-danger { background: #ef8a8a; color: #ffffff; }

        .mmm {
            color: #30b9a6;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            display: block;
        }
    </style>
</head>
<body>
    <div class="phone">
        <div class="content">
            
            <div class="header">
                <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="title">{{ __('Parent Profile') }}</div>
                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="logo">
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-actions-stack">
                    <button type="button" class="stack-btn delete-btn" onclick="openModal('deleteModal')" title="{{ __('Delete Parent') }}">
                        <svg class="delete-svg" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M4 7H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M9 4H15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M18 7L17.2 18.2C17.13 19.18 16.31 19.94 15.33 19.94H8.67C7.69 19.94 6.87 19.18 6.8 18.2L6 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M10 10.5V16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M14 10.5V16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </button>

                    <a href="{{ route('doctor.chat', $parent['user_id'] ?? $parent['id'] ?? 0) }}" class="stack-btn chat-btn" title="{{ __('Chat') }}">
                        💬
                    </a>
                </div>

                <img
                    class="parent-image"
                    src="{{ !empty($parent['image']) ? asset('storage/' . $parent['image']) : asset('images/default-user.png') }}"
                    alt="Parent"
                />

                <div class="parent-name">{{ $parent['name'] ?? __('Unknown') }}</div>
                <div class="parent-subtitle">{{ $parent['subtitle'] ?? __('Parent of child') }}</div>

                <div class="info-row">
                    <div class="info-pill">
                        <svg viewBox="0 0 24 24" class="icon">
                            <path d="M6.6 10.8a15 15 0 006.6 6.6l2.2-2.2a1 1 0 011-.24c1.1.36 2.3.56 3.6.56a1 1 0 011 1V21a1 1 0 01-1 1C10.3 22 2 13.7 2 3a1 1 0 011-1h3.5a1 1 0 011 1c0 1.3.2 2.5.56 3.6a1 1 0 01-.25 1L6.6 10.8z"/>
                        </svg>
                        <span dir="ltr">{{ $parent['phone'] ?? __('No Phone') }}</span>
                    </div>

                    <div class="info-pill">
                        <svg viewBox="0 0 24 24" class="icon">
                            <path d="M12 2C8 2 6 5 6 8c0 1.5.5 2.5 1.5 3.5C6.5 12.5 6 13.5 6 15c0 3 2 5 6 5s6-2 6-5c0-1.5-.5-2.5-1.5-3.5C17.5 10.5 18 9.5 18 8c0-3-2-6-6-6z"/>
                        </svg>
                        {{ __($parent['autism_level'] ?? 'N/A') }}
                    </div>

                    <div class="info-pill">
                        <svg viewBox="0 0 24 24" class="icon">
                            <path d="M12 12c2.7 0 5-2.3 5-5S14.7 2 12 2 7 4.3 7 7s2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v2h20v-2c0-3.3-6.7-5-10-5z"/>
                        </svg>
                        {{ $parent['age'] ?? __('N/A') }}
                    </div>
                </div>
            </div>

            <div class="section-chip" style="background: #3a82f6;">{{ __('Medical Record') }}</div>
            <div class="medical-notes-wrapper" style="margin-bottom: 20px;">
                <a href="{{ route('doctor.parent.notes', $parent['id'] ?? 0) }}" style="display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 16px; border-radius: 16px; text-decoration: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-inline-start: 4px solid #3a82f6;">
                    <div>
                        <div style="color: #1d567e; font-size: 16px; font-weight: 800; margin-bottom: 4px;">{{ __('Clinical Notes & Reports') }}</div>
                        <div style="color: #6b7280; font-size: 13px;">{{ __('Manage notes, view history, and export PDF') }}</div>
                    </div>
                    <div style="background: #e0f2fe; color: #3a82f6; width: 40px; height: 40px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 18px;">
                        <i class="fas fa-chevron-right" style="transform: {{ app()->getLocale() == 'ar' ? 'scaleX(-1)' : 'none' }};"></i>
                    </div>
                </a>
            </div>

            <div class="section-chip" style="background: #10b981;">{{ __('Home Treatment Plan') }}</div>
            <div class="medical-notes-wrapper" style="margin-bottom: 20px;">
                <a href="{{ route('doctor.parent.tasks', $parent['id'] ?? 0) }}" style="display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 16px; border-radius: 16px; text-decoration: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-inline-start: 4px solid #10b981;">
                    <div>
                        <div style="color: #065f46; font-size: 16px; font-weight: 800; margin-bottom: 4px;">{{ __('Assigned Tasks') }}</div>
                        <div style="color: #6b7280; font-size: 13px;">{{ __('Manage home plan & track progress') }}</div>
                    </div>
                    <div style="background: #d1fae5; color: #10b981; width: 40px; height: 40px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 18px;">
                        <i class="fas fa-chevron-right" style="transform: {{ app()->getLocale() == 'ar' ? 'scaleX(-1)' : 'none' }};"></i>
                    </div>
                </a>
            </div>

            <div class="section-chip">{{ __('Appointments') }}</div>

            @forelse($appointments ?? [] as $appointment)
                @php
                    $appointmentDate = \Carbon\Carbon::parse($appointment->date);
                    $isToday = $appointmentDate->isToday();
                    $headerText = $appointmentDate->format('d l') . ($isToday ? ' - ' . __('Today') : '');
                @endphp

                <div class="schedule-card">
                    <div class="appointment-box">
                        <div class="times">
                            <div>{{ str_pad($appointment->from_hour, 2, '0', STR_PAD_LEFT) }} {{ __($appointment->from_period) }}</div>
                            <div>|</div>
                            <div>|</div>
                            <div>{{ str_pad($appointment->to_hour, 2, '0', STR_PAD_LEFT) }} {{ __($appointment->to_period) }}</div>
                        </div>

                        <div class="appointment-content">
                            <div class="appointment-header">
                                <span>{{ $headerText }}</span>
                            </div>

                            <div class="appointment-main">
                                <div class="appointment-info">
                                    <div class="appointment-sub">
                                        {{ __('Child:') }} {{ $appointment->child->name ?? __('N/A') }}
                                    </div>

                                    <div class="note" style="font-size: 13px; color: #555; margin-top: 5px;">
                                        {{ $appointment->note }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="schedule-card">
                    <div class="appointment-box">
                        <div class="appointment-content" style="display: flex; align-items: center; justify-content: center;">
                            <span class="mmm">{{ __('No upcoming appointments') }}</span>
                        </div>
                    </div>
                </div>
            @endforelse

        </div> 
        
        <div class="custom-modal-overlay" id="deleteModal">
            <div class="custom-modal-box">
                <div class="custom-modal-title">{{ __('Delete Parent') }}</div>
                <div class="custom-modal-text">
                    {{ __('Are you sure you want to delete this parent?') }}
                </div>

                <div class="custom-modal-actions">
                    <button type="button" class="modal-btn btn-cancel" onclick="closeModal('deleteModal')">
                        {{ __('Cancel') }}
                    </button>

                    <form action="{{ route('doctor.parent.remove', $parent['id'] ?? 0) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="modal-btn btn-danger" onclick="closeModal('deleteModal')">
                            {{ __('Yes, Delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div> 

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('show');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('custom-modal-overlay')) {
                event.target.classList.remove('show');
            }
        });
    </script>
</body>
</html>