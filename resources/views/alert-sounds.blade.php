<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Alert Sounds</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #edf1f4;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .mobile-screen {
            width: 390px;
            height: 844px;
            max-width: 100%;
            max-height: 95vh;
            position: relative;
            overflow-y: auto;
            overflow-x: hidden;
            border-radius: 30px;
            background: #f9f9f9;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.14);
        }

        .mobile-screen::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('images/bg.png') }}');
            background-size: 165% 100%;
            background-position: left bottom;
            opacity: 0.9;
            z-index: 0;
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
            padding: 16px 14px 24px;
            min-height: 100%;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .top-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin-bottom: 18px;
            min-height: 44px;
        }

        .back-btn {
            position: absolute;
            left: 0;
            border: none;
            background: transparent;
            cursor: pointer;
            color: #2f80ed;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-btn svg {
            width: 24px;
            height: 24px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 800;
            color: #1f5b87;
        }

        .logo {
            position: absolute;
            right: 0;
            width: 100px;
            height: 100px;
            object-fit: contain;
        }

        .section-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 30px;
            padding: 0 12px;
            background: #cfeeee;
            color: #111;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            margin: 10px 0 8px;
        }

        .sound-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 4px 10px 12px;
            color: #202020;
            font-size: 15px;
        }

        .switch {
            width: 42px;
            height: 22px;
            background: #d8e5e2;
            border-radius: 20px;
            position: relative;
            cursor: pointer;
            transition: 0.3s;
            flex-shrink: 0;
        }

        .switch.active {
            background: #4ecdc4;
        }

        .switch::after {
            content: "";
            width: 18px;
            height: 18px;
            background: #fff;
            border-radius: 50%;
            position: absolute;
            top: 2px;
            left: 2px;
            transition: 0.3s;
        }

        .switch.active::after {
            left: 22px;
        }

        @media (max-width: 480px) {
            body {
                padding: 0;
                background: #fff;
            }

            .mobile-screen {
                width: 100%;
                height: 100vh;
                max-height: 100vh;
                border-radius: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    @php
        // جلب الإعدادات لتحديد حالة الأزرار
        $userSettings = \App\Models\NotificationSetting::where('user_id', auth()->id())->get()->keyBy('notification_type');
    @endphp

    <div class="mobile-screen">
        <div class="content">

            <div class="top-bar">
                <div class="top-right">
                    <div class="status-icon"></div>
                </div>
            </div>

            <div class="header">
                <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div class="page-title">Alert Sounds</div>

                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Taif">
            </div>

            <div class="section-chip">Warning</div>

            <div class="sound-row">
                <span>Sound</span>
                <div class="switch {{ (optional($userSettings->get('warning'))->has_sound ?? true) ? 'active' : '' }}" 
                     onclick="toggleSetting(this)" data-type="warning" data-field="has_sound"></div>
            </div>

            <div class="sound-row">
                <span>Vibrate</span>
                <div class="switch {{ (optional($userSettings->get('warning'))->has_vibrate ?? true) ? 'active' : '' }}" 
                     onclick="toggleSetting(this)" data-type="warning" data-field="has_vibrate"></div>
            </div>

            <div class="section-chip">Alerts</div>

            <div class="sound-row">
                <span>Sound</span>
                <div class="switch {{ (optional($userSettings->get('alert'))->has_sound ?? true) ? 'active' : '' }}" 
                     onclick="toggleSetting(this)" data-type="alert" data-field="has_sound"></div>
            </div>

            <div class="sound-row">
                <span>Vibrate</span>
                <div class="switch {{ (optional($userSettings->get('alert'))->has_vibrate ?? true) ? 'active' : '' }}" 
                     onclick="toggleSetting(this)" data-type="alert" data-field="has_vibrate"></div>
            </div>

            <div class="section-chip">Appointment</div>

            <div class="sound-row">
                <span>Sound</span>
                <div class="switch {{ (optional($userSettings->get('appointment'))->has_sound ?? true) ? 'active' : '' }}" 
                     onclick="toggleSetting(this)" data-type="appointment" data-field="has_sound"></div>
            </div>

            <div class="sound-row">
                <span>Vibrate</span>
                <div class="switch {{ (optional($userSettings->get('appointment'))->has_vibrate ?? true) ? 'active' : '' }}" 
                     onclick="toggleSetting(this)" data-type="appointment" data-field="has_vibrate"></div>
            </div>

            <div class="section-chip">Doctors Messages</div>

            <div class="sound-row">
                <span>Sound</span>
                <div class="switch {{ (optional($userSettings->get('chat'))->has_sound ?? true) ? 'active' : '' }}" 
                     onclick="toggleSetting(this)" data-type="chat" data-field="has_sound"></div>
            </div>

            <div class="sound-row">
                <span>Vibrate</span>
                <div class="switch {{ (optional($userSettings->get('chat'))->has_vibrate ?? true) ? 'active' : '' }}" 
                     onclick="toggleSetting(this)" data-type="chat" data-field="has_vibrate"></div>
            </div>

        </div>
    </div>

    <script>
        function toggleSetting(el) {
            el.classList.toggle('active');
            
            let isActive = el.classList.contains('active') ? 1 : 0;
            let typeVal = el.getAttribute('data-type');
            let fieldVal = el.getAttribute('data-field');
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // لمسة UX إضافية: لو اختار اهتزاز (Vibrate)، التليفون يهتز كنوع من التأكيد
            if (isActive === 1 && fieldVal === 'has_vibrate') {
                if (navigator.vibrate) {
                    navigator.vibrate(150);
                }
            }

            fetch("{{ route('settings.toggle') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    type: typeVal,
                    field: fieldVal,
                    status: isActive
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    console.log('Saved:', typeVal, fieldVal, isActive);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                el.classList.toggle('active'); // إرجاع الزر لحالته لو صار خطأ في النت
            });
        }
    </script>

</body>
</html>