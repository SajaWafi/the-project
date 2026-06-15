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

        .content::-webkit-scrollbar { width: 5px; }
        .content::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 10px; }

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

        .logo { position: absolute; right: 0; width: 100px; height: 100px; object-fit: contain; }
        .logo img { width: 100%; height: 100%; object-fit: cover; }

        /* 💡 تعديل الكرت الشخصي لتنظيف الزحمة */
        .profile-card {
            background: #bfc8f0;
            border-radius: 24px;
            padding: 18px 16px;
            position: relative;
            margin-bottom: 14px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .chat-btn, .settings-btn {
            position: absolute;
            width: 34px; height: 34px; border-radius: 50%;
            border: 2px solid #3d78ff; color: #3d78ff;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; background: #fff; right: 10px; transition: 0.2s;
        }
        .chat-btn { top: 10px; font-size: 17px; }
        .settings-btn { top: 50px; font-size: 18px; }
        .chat-btn:hover, .settings-btn:hover { background: #3d78ff; color: #fff; }
        .chat-svg { width: 20px; height: 20px; color: currentColor; }

        .top-profile {
            display: flex; gap: 16px; align-items: center; margin-bottom: 16px; padding-right: 40px;
        }

        .doctor-image {
            width: 90px; height: 90px; border-radius: 50%; object-fit: cover;
            border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); background: #cfd4d8; flex-shrink: 0;
        }

        .profile-info { flex: 1; }

        /* 💡 إزالة الصناديق البيضاء المزعجة وجعل النص أنظف */
        .welcome-text { color: #4f82ff; font-size: 14px; font-weight: 600; margin-bottom: 2px; }
        .patient-name { color: #1d567e; font-size: 20px; font-weight: 800; text-decoration: none; margin-bottom: 8px; display: block; }
        
        .specialize-label { color: #4f82ff; font-size: 13px; font-weight: 600; }
        .specialty-text { color: #333; font-size: 15px; font-weight: 700; }

        .workplace-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 12px; }
        .workplace-pill {
            flex: 1 1 48%; min-width: 0; background: rgba(255,255,255,0.7);
            border-radius: 12px; padding: 10px;
        }
        .workplace-name { color: #2d63f6; font-size: 14px; font-weight: 800; margin-bottom: 4px; }
        .workplace-meta { color: #555; font-size: 12px; line-height: 1.4; }

        /* 💡 تعديل لون الـ Bio باش يكون مريح للعين (أزرق فاتح) */
        .about-box {
            background: #e6ecfc;
            color: #1d567e;
            border-radius: 20px;
            padding: 16px 20px;
            font-size: 15px;
            line-height: 1.5;
            margin-bottom: 16px;
            font-weight: 500;
            border: 1px solid #d1dcf5;
        }

        .section-chip {
            display: inline-block; background: #2d63f6; color: #fff; border-radius: 14px;
            padding: 6px 14px; font-size: 15px; font-weight: 700; margin-bottom: 12px;
        }

        .schedule-card { background: #bfc8f0; border-radius: 24px; padding: 14px 12px; margin-bottom: 16px; }
        .appointment-box { background: #fff9f2; border-radius: 18px; padding: 12px 10px; display: grid; grid-template-columns: 42px 1fr; gap: 8px; }
        .times { color: #2d63f6; font-size: 12px; line-height: 2; padding-top: 10px; }
        .appointment-content { min-width: 0; }
        .appointment-header { display: flex; justify-content: space-between; align-items: center; color: #2d63f6; font-size: 13px; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 2px dotted #73a0ff; }
        .mmm { color: #2d63f6; font-size: 15px; font-weight: 700; text-align: center; display: block; padding: 10px; }
        .appointment-main { background: #bfc8f0; border-radius: 14px; padding: 10px 12px; margin-top: 14px; }
        .doctor-row { display: flex; justify-content: space-between; align-items: center; gap: 8px; margin-bottom: 3px; }
        .appointment-doctor-name { color: #2d63f6; font-size: 16px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .appointment-sub { color: #555; font-size: 14px; }
        .note { font-size: 13px; color: #444; margin-top: 6px; font-style: italic; }

        /* navbar */
        .bottom-nav { position: absolute; left: 0; right: 0; bottom: 0; height: 64px; background: #2f80ed; border-radius: 0 0 20px 20px; display: flex; justify-content: space-around; align-items: center; z-index: 1000; }
        .nav-item { width: 48px; height: 48px; border-radius: 14px; display: flex; justify-content: center; align-items: center; color: rgba(255,255,255,0.65); transition: 0.2s; text-decoration: none; }
        .nav-svg { width: 22px; height: 22px; }
        .nav-item.active { background: rgba(255,255,255,0.18); color: #ffffff; transform: translateY(-2px); }
        .leaflet-control-attribution { font-size: 10px; }
    </style>
</head>

@php
    $user = auth()->user();
    $doctorProfile = $user?->doctorProfile;
@endphp

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
                    <a href="{{ route('doctor.doctor-profile') }}">
                        <img class="doctor-image" src="{{ !empty($user->profile_image) ? asset('storage/' . $user->profile_image) : asset('images/default-user.png') }}" alt="Doctor">
                    </a>

                    <div class="profile-info">
                        <div class="welcome-text">Welcome,</div>
                        <a href="{{ route('doctor.doctor-profile') }}" class="patient-name">
                            Dr. {{ $user?->first_name }} {{ $user?->last_name }}
                        </a>
                        <div class="specialize-label">Specialize In:</div>
                        <div class="specialty-text">{{ $doctorProfile?->specialization ?? 'No specialization yet' }}</div>
                    </div>
                </div>

                <div class="workplace-row">
                    @forelse($workplaces as $workplace)
                        <div class="workplace-pill">
                            <div class="workplace-name">{{ $workplace->place_name }}</div>
                            <div class="workplace-meta">{{ is_array($workplace->days) ? implode(' - ', $workplace->days) : '' }}</div>
                            <div class="workplace-meta">
                                {{ str_pad($workplace->from_hour, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($workplace->from_minute, 2, '0', STR_PAD_LEFT) }} {{ $workplace->from_period }} - {{ str_pad($workplace->to_hour, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($workplace->to_minute, 2, '0', STR_PAD_LEFT) }} {{ $workplace->to_period }}
                            </div>
                        </div>
                    @empty
                        <div class="workplace-pill"><div class="workplace-name">No workplace</div></div>
                    @endforelse
                </div>
            </div>

            @if($doctorProfile?->bio)
                <div class="about-box">
                    "{{ $doctorProfile->bio }}"
                </div>
            @endif

            <div class="section-chip">Today's Appointments</div>

            @php
                $todayAppointments = $appointments->filter(function ($appointment) {
                    return \Carbon\Carbon::parse($appointment->date)->isToday();
                });
            @endphp

            @forelse($todayAppointments as $appointment)
                @php
                    $appointmentDate = \Carbon\Carbon::parse($appointment->date);
                    $isToday = $appointmentDate->isToday();
                    $parentName = trim(($appointment->parent->user->first_name ?? '') . ' ' . ($appointment->parent->user->last_name ?? ''));
                    $headerText = $appointmentDate->format('d l') . ($isToday ? ' - Today' : '');
                @endphp

                <div class="schedule-card">
                    <div class="appointment-box">
                        <div class="times">
                            <div>{{ str_pad($appointment->from_hour, 2, '0', STR_PAD_LEFT) }} {{ $appointment->from_period }}</div>
                            <div>|</div>
                            <div>|</div>
                            <div>{{ str_pad($appointment->to_hour, 2, '0', STR_PAD_LEFT) }} {{ $appointment->to_period }}</div>
                        </div>

                        <div class="appointment-content">
                            <div class="appointment-header">
                                <span>{{ $headerText }}</span>
                            </div>

                            <div class="appointment-main">
                                <div class="appointment-info">
                                    <div class="appointment-sub">Child: {{ $appointment->child->name ?? 'N/A' }}</div>
                                    @if($appointment->note)
                                        <div class="note">"{{ $appointment->note }}"</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="schedule-card">
                    <div class="appointment-box" style="display: block;">
                        <span class="mmm">No appointments for today</span>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="bottom-nav">
            <a href="{{ route('doctor.parents') }}" class="nav-item {{ request()->routeIs('doctor.parents') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none"><circle cx="10" cy="8" r="3.5" stroke="currentColor" stroke-width="1.8"/><path d="M4.5 18c1.2-2.8 3.3-4.2 5.5-4.2s4.3 1.4 5.5 4.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M18 9v6M15 12h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
            </a>
            <a href="{{ route('doctor.home') }}" class="nav-item {{ request()->routeIs('doctor.home') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none"><path d="M4 10.5 12 4l8 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M7 10v9h10v-9" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
            </a>
            <a href="{{ route('doctor.appointments') }}" class="nav-item {{ request()->routeIs('doctor.appointments') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none"><rect x="4" y="6" width="16" height="14" rx="3" stroke="currentColor" stroke-width="2"/><path d="M8 3v4M16 3v4M4 10h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </a>
        </div>
    </div>
</body>
</html>