<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Management - Taif Project</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        /* 🔹 Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #ffffffff; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        
        .phone { width: 390px; height: 844px; background: url('{{ asset('images/bg.png') }}') no-repeat center center/cover; border-radius: 22px; overflow: hidden; position: relative; box-shadow: 0 12px 30px rgba(0,0,0,0.35); }
        
        .content { padding: 0 12px 12px 12px; height: 100%; overflow-y: auto; padding-bottom: 90px; scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none; }
        .content::-webkit-scrollbar { display: none; }

        .header { position: sticky; top: 0; z-index: 100; display: flex; align-items: center; justify-content: center; padding: 15px 0; margin-bottom: 12px; background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); margin-left: -12px; margin-right: -12px; padding-left: 12px; padding-right: 12px; border-bottom-left-radius: 15px; border-bottom-right-radius: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02); }
        .title { text-align: center; font-size: 28px; font-weight: 800; color: #1d567e; }
        .logo { position: absolute; right: 10px; width: 80px; height: 80px; object-fit: contain; }
        .logo img { width: 100%; height: 100%; object-fit: cover; }
        .back-btn { position: absolute; left: 10px; background: transparent; border: none; cursor: pointer; color: #2f80ed; padding: 6px; }
        .back-btn svg { width: 26px; height: 26px; }

        /* 💡 تنسيق فورم البحث والفلترة بالتواريخ */
        .doctor-filter-form {
            display: flex; gap: 8px; margin-bottom: 16px; align-items: center;
        }
        .doctor-search-input {
            flex: 1; border: 1px solid #e28c3d; border-radius: 12px; padding: 8px 12px; font-size: 13px; outline: none; background: #fffaf4; color: #1d567e;
        }
        .doctor-search-input::placeholder { color: #d09c6f; }
        .doctor-status-select {
            width: 140px; /* 💡 عرض أكبر للكلمات */
            border: 1px solid #e28c3d; border-radius: 12px; padding: 8px; font-size: 13px; outline: none; background: #e28c3d; color: white; cursor: pointer; font-weight: 600;
        }

        .add-btn { display: block; width: 100%; margin: 0 auto 16px; background: #e28c3d; color: white; text-align: center; padding: 12px; border-radius: 15px; font-weight: bold; text-decoration: none; box-shadow: 0 4px 10px rgba(226, 140, 61, 0.3); }
        .add-btn:hover { color: white; opacity: 0.9; }

        /* appointment card */
        .schedule-card { background: #efd7b8; border-radius: 24px; padding: 16px 12px; margin-bottom: 18px; position: relative; }
        .appointment-box { background: #fffaf4; border-radius: 18px; padding: 14px 12px; display: grid; grid-template-columns: 48px 1fr; gap: 12px; align-items: start; }
        .times { color: #e28c3d; font-size: 14px; line-height: 2.1; padding-top: 14px; font-weight: 500; text-align: center; }
        .appointment-content { min-width: 0; }
        .appointment-header { display: flex; justify-content: flex-end; align-items: center; color: #e28c3d; font-size: 14px; margin-bottom: 12px; padding-bottom: 6px; border-bottom: 2px dotted #e28c3d; font-weight: 500; }
        .appointment-main { background: #e9cfac; border-radius: 16px; padding: 12px 14px; display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; position: relative; }
        .appointment-info { flex: 1; min-width: 0; }
        .doctor-row { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 6px; }
        .doctor-name { color: #e28c3d; font-size: 18px; font-weight: 700; line-height: 1.2; }
        .appointment-sub { color: #2f2f2f; font-size: 14px; line-height: 1.5; }

        .doctor-actions { display: flex; flex-direction: column; align-items: center; gap: 12px; flex-shrink: 0; margin-top: 2px; }
        .action-icon-btn, .action-icon-link { width: 28px; height: 28px; border-radius: 8px; border: none; background: white; color: #e28c3d; font-size: 13px; display: flex; align-items: center; justify-content: center; cursor: pointer; padding: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: 0.2s;}
        .action-icon-btn i { color: #ef4444; } 
        .action-icon-btn:hover, .action-icon-link:hover { transform: scale(1.05); }
        .mmm { color: #e28c3d; font-size: 14px; font-weight: 700; text-align: center; margin: 0 auto; }

        /* navbar & Modals */
        .bottom-nav { position: absolute; left: 0; right: 0; bottom: 0; height: 64px; background: #2f80ed; border-radius: 0 0 20px 20px; display: flex; justify-content: space-around; align-items: center; z-index: 1000; }
        .nav-item { width: 48px; height: 48px; border-radius: 14px; display: flex; justify-content: center; align-items: center; color: rgba(255,255,255,0.65); transition: 0.2s; text-decoration: none; }
        .nav-svg { width: 22px; height: 22px; }
        .nav-item.active { background: rgba(255,255,255,0.18); color: #ffffff; transform: translateY(-2px); }

        .doctor-modal-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2000; display: none; justify-content: center; align-items: flex-end; background-color: rgba(0, 0, 0, 0.4); padding-bottom: 20px; }
        .doctor-modal-box { background: #ffffff; width: 90%; border-radius: 20px; padding: 30px 20px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.2); animation: slideUp 0.3s ease-out; }
        @keyframes slideUp { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .doctor-modal-title { font-size: 20px; font-weight: 800; color: #000000; margin-bottom: 10px; }
        .doctor-modal-text { font-size: 14px; color: #6b7280; margin-bottom: 25px; }
        .doctor-modal-actions { display: flex; gap: 15px; justify-content: center; }
        .btn-modal-cancel { flex: 1; padding: 12px 0; border-radius: 25px; border: none; font-size: 15px; font-weight: 700; background: #cbf4f0; color: #1a73e8; cursor: pointer; transition: 0.2s; }
        .btn-modal-confirm { flex: 1; padding: 12px 0; border-radius: 25px; border: none; font-size: 15px; font-weight: 700; background: #2b70f1; color: white; cursor: pointer; transition: 0.2s; }
    </style>
</head>

<body>

<div class="phone">

    <div class="content">

        <div class="header">
            <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                <svg viewBox="0 0 24 24" fill="none"><path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="title">Appointments</div>
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
        </div>

        <!--add appointment button-->
        <a href="{{ route('doctor.add.appointment') }}" class="add-btn"><i class="fas fa-plus-circle me-1"></i> Add Appointment</a>

        <!-- filter and search form -->
        <form action="{{ route('doctor.appointments') }}" method="GET" id="doctorFilterForm" class="doctor-filter-form">
            <input 
                type="text" 
                name="search" 
                class="doctor-search-input" 
                placeholder="Search patient..." 
                value="{{ request('search') }}"
            >
            <select name="date_filter" class="doctor-status-select" onchange="document.getElementById('doctorFilterForm').submit();">
                <option value="all" {{ request('date_filter') == 'all' ? 'selected' : '' }}>All</option>
                <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today</option>
                <option value="tomorrow" {{ request('date_filter') == 'tomorrow' ? 'selected' : '' }}>Tomorrow</option>
                <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>This Week</option>
            </select>
        </form>

        <!--appointments list-->
        @forelse($appointments as $appointment)
            @php
                $appointmentDate = \Carbon\Carbon::parse($appointment->date);
                $isToday = $appointmentDate->isToday();
                $parentName = trim(($appointment->parent->user->first_name ?? '') . ' ' . ($appointment->parent->user->last_name ?? ''));
                $headerText = $appointmentDate->format('d l') . ($isToday ? ' - Today' : '');
            @endphp

            <!--appointment card-->
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
                                <div class="appointment-sub">Child: {{ $appointment->child->name ?? 'N/A' }}</div>
                                <div class="appointment-sub">Place: {{ $appointment->workplace->place_name }}</div>
                                @if($appointment->note)
                                    <div class="note" style="color: #2f2f2f; font-size: 13px; margin-top: 6px; font-style: italic;">"{{ Str::limit($appointment->note, 30) }}"</div>
                                @endif
                            </div>

                            <div class="doctor-actions">
                                <button type="button" class="action-icon-btn" onclick="openCustomModal('{{ route('doctor.appointments.destroy', $appointment->id) }}')" title="Cancel Appointment">
                                    <i class="fas fa-times"></i>
                                </button>
                                <a href="{{ route('doctor.edit.appointment', $appointment->id) }}" class="action-icon-link" title="Edit Appointment">
                                    <i class="fas fa-pen"></i>
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
                        <div class="appointment-content">
                            <span class="mmm">No upcoming appointments</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse

        </div> 

        <!-- navbar -->
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

        <!-- cancel appointment message -->
        <div id="customConfirmModal" class="doctor-modal-overlay">
            <div class="doctor-modal-box">
                <div class="doctor-modal-title">Cancel Appointment</div>
                <div class="doctor-modal-text">Are you sure you want to cancel this appointment?</div>
                <div class="doctor-modal-actions">
                    <button type="button" class="btn-modal-cancel" onclick="closeCustomModal()">Cancel</button>
                    <button type="button" class="btn-modal-confirm" onclick="submitCustomModal()">Yes, Cancel</button>
                </div>
            </div>
        </div>
    </div> 

    <!-- delete form -->
    <form id="masterDeleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // cancel appointment message
        let deleteUrl = '';
        function openCustomModal(url) { deleteUrl = url; document.getElementById('customConfirmModal').style.display = 'flex'; }
        function closeCustomModal() { document.getElementById('customConfirmModal').style.display = 'none'; deleteUrl = ''; }
        function submitCustomModal() { if(deleteUrl) { let form = document.getElementById('masterDeleteForm'); form.action = deleteUrl; form.submit(); } }

        // إرسال الفورم لما الدكتور يكتب في مربع البحث
        // search input
        let searchTimeout = null;
        document.querySelector('.doctor-search-input').addEventListener('keyup', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => { document.getElementById('doctorFilterForm').submit(); }, 500);
        });
    </script>

</body>
</html>

