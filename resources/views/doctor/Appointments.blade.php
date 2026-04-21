<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Appointments</title>

<style>

/* 🔹 Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* 🔹 خلفية الصفحة */
body {
    background: #ffffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* 🔹 إطار الموبايل */
.phone {
    width: 390px;
    height: 844px;
    background: url('{{ asset('images/bg.png') }}') no-repeat center center/cover;
    border-radius: 22px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 12px 30px rgba(0,0,0,0.35);
}

/* 🔹 المحتوى */
.content {
    padding: 12px;
    height: 100%;
    overflow-y: auto;
}

/* 🔹 العنوان */
.title {
    text-align: center;
    font-size: 28px;
    font-weight: 800;
    color: #1d567e;
    margin-bottom: 10px;
}

/* 🔹 زر إضافة موعد */
.add-btn {
    display: block;
    width: 220px;
    margin: 0 auto 16px;
    background: #e28c3d;
    color: white;
    text-align: center;
    padding: 10px;
    border-radius: 20px;
    font-weight: bold;
}

/* appointment card */
.schedule-card {
    background: #efd7b8;
    border-radius: 24px;
    padding: 16px 12px;
    margin-bottom: 18px;
}

.appointment-box {
    background: #fffaf4;
    border-radius: 18px;
    padding: 14px 12px;
    display: grid;
    grid-template-columns: 48px 1fr;
    gap: 12px;
    align-items: start;
}

.times {
    color: #e28c3d;
    font-size: 14px;
    line-height: 2.1;
    padding-top: 14px;
    font-weight: 500;
    text-align: left;
    text-align: center;
}

.appointment-content {
    min-width: 0;
}

.appointment-header {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    color: #e28c3d;
    font-size: 14px;
    margin-bottom: 12px;
    padding-bottom: 6px;
    border-bottom: 2px dotted #e28c3d;
    font-weight: 500;
}

.appointment-main {
    background: #e9cfac;
    border-radius: 16px;
    padding: 12px 14px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
}

.appointment-info {
    flex: 1;
    min-width: 0;
}

.doctor-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 6px;
}

.doctor-name {
    color: #e28c3d;
    font-size: 20px;
    font-weight: 700;
    line-height: 1.2;
}

.appointment-sub {
    color: #2f2f2f;
    font-size: 15px;
    line-height: 1.5;
}

.appointment-time {
    color: #2f2f2f;
    font-size: 15px;
    line-height: 1.5;
    margin-top: 2px;
}

.doctor-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    margin-top: 2px;
}

.action-icon-btn,
.action-icon-link {
    width: 24px;
    height: 24px;
    border: none;
    background: transparent;
    color: #2f2f2f;
    font-size: 18px;
    line-height: 1;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    padding: 0;
}

.action-icon-link {
    font-size: 15px;
}

.action-icon-btn:hover,
.action-icon-link:hover {
    opacity: 0.7;
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
            background: #e28c3d;
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

        .header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 6px;
            margin-bottom: 18px;
        }

</style>
</head>

<body>

<div class="phone">

<div class="content">

    <div class="header">
        <div class="title">Appointments</div>
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
    </div>

    <a href="{{ route('doctor.add.appointment') }}" class="add-btn">Add Appointment</a>

    @forelse($appointments as $appointment)
    @php
        $appointmentDate = \Carbon\Carbon::parse($appointment->date);
        $isToday = $appointmentDate->isToday();

        $parentName = trim(
            ($appointment->parent->user->first_name ?? '') . ' ' . ($appointment->parent->user->last_name ?? '')
        );

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
                        <div class="doctor-row">
                            <div class="doctor-name">{{ $parentName ?: 'Parent not found' }}</div>
                        </div>

                        <div class="appointment-sub">
                            Child: {{ $appointment->child->name ?? 'N/A' }}
                        </div>

                        <div class="note">
                            {{ $appointment->note }}
                        </div>
                    </div>

                    <div class="doctor-actions">
                        <form action="{{ route('doctor.appointments.delete', $appointment->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-icon-btn">×</button>
                        </form>

                        <a href="{{ route('doctor.edit.appointment', $appointment->id) }}" class="action-icon-link">
                            ✎
                        </a>
                    </div>
                </div>
            </div>
    
        </div>
    </div>
@empty
    <div class="schedule-card">
        <div class="appointment-box">
            <div class="appointment-content">
                <div class="appointment-header">
                    <span>No upcoming appointments</span>
                </div>
            </div>
        </div>
    </div>
@endforelse

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


    <!--
    
    /* 🔹 كارد المواعيد */
.appointment-card {
    background: #efd7b8;
    border-radius: 20px;
    padding: 14px;
    margin-bottom: 18px;
}

/* 🔹 صف الأيام */
.days-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
}

/* 🔹 كل يوم */
.day {
    width: 42px;
    height: 56px;
    border-radius: 18px;
    background: #fff;
    text-align: center;
    font-size: 12px;
    padding-top: 6px;
}

/* 🔹 اليوم النشط */
.day.active {
    background: #e28c3d;
    color: white;
}

/* 🔹 صندوق الموعد */
.schedule-box {
    background: #fff;
    border-radius: 16px;
    padding: 12px;
}

/* 🔹 أوقات */
.times {
    font-size: 12px;
    color: #e28c3d;
    line-height: 2;
}

/* 🔹 تفاصيل الموعد */
.event {
    background: #efd7b8;
    border-radius: 14px;
    padding: 10px;
    margin-left: 40px;
}

/* 🔹 اسم */
.event-title {
    font-weight: bold;
    color: #e28c3d;
}

/* 🔹 وصف */
.event-sub {
    font-size: 13px;
}
    
    





-->


 <!--




    <div class="appointment-card">

        <div class="days-row">
            <div class="day">9<br>MON</div>
            <div class="day">10<br>TUE</div>
            <div class="day active">11<br>WED</div>
            <div class="day">12<br>THU</div>
            <div class="day">13<br>FRI</div>
            <div class="day">14<br>SAT</div>
        </div>

        <div class="schedule-box">
            <div class="times">
                9 AM<br>10 AM<br>11 AM<br>12 AM
            </div>

            <div class="event">
                <div class="event-title">Ali Salah</div>
                <div class="event-sub">Periodic review</div>
            </div>
        </div>

    </div>

    <div class="appointment-card">

        <div class="days-row">
            <div class="day">9<br>MON</div>
            <div class="day">10<br>TUE</div>
            <div class="day">11<br>WED</div>
            <div class="day">12<br>THU</div>
            <div class="day active">13<br>FRI</div>
            <div class="day">14<br>SAT</div>
        </div>

        <div class="schedule-box">
            <div class="times">
                1 PM<br>2 PM<br>3 PM<br>4 PM
            </div>

            <div class="event">
                <div class="event-title">Hifa Jaber</div>
                <div class="event-sub">Periodic review</div>
            </div>
        </div>

    </div>

</div>

   


-->