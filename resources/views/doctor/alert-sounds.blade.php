<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Alert Sounds</title>

    <style>
        /* ===== Reset ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }

        .content {
            padding: 14px 18px;
        }

        /* ===== Header ===== */
        .header {
            position: relative;
            text-align: center;
            margin-bottom: 25px;
        }

        .title {
            font-size: 26px;
            font-weight: 800;
            color: #1d567e;
            padding-top: 5px;
        }

        .logo {
            position: absolute;
            right: 0;
            top: -10px;
            width: 100px;
            height:100px;
            object-fit: contain;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ===== Section chip ===== */
        .chip {
            display: inline-block;
            background: linear-gradient(to right, #bde6e1, #8fd3cc);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        /* ===== Row ===== */
        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            font-size: 18px;
        }

        /* ===== Toggle ===== */
        .switch {
            position: relative;
            width: 46px;
            height: 24px;
        }

        .switch input {
            display: none;
        }

        .slider {
            position: absolute;
            width: 100%;
            height: 100%;
            background: #cfd6fb;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .slider:before {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            left: 2px;
            top: 2px;
            background: white;
            border-radius: 50%;
            transition: 0.3s;
        }

        input:checked + .slider {
            background: #3cc1a7;
        }

        input:checked + .slider:before {
            transform: translateX(22px);
        }

        /* ===== Alerts ===== */
        .success-box {
            background: #e7fff0;
            color: #0a7c3a;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            display: none; /* مخفي افتراضياً، يظهر بالجافاسكربت */
            text-align: center;
            font-weight: bold;
        }

        .back-btn {
            position: absolute;
            left: 0;
            top: 5px;
            background: transparent;
            border: none;
            cursor: pointer;
            color: #2f80ed;
            padding: 6px;
        }

        .back-btn svg {
            width: 26px;
            height: 26px;
        }
    </style>
</head>

<body>

@php
    // جلب إعدادات الدكتور الحالية من الداتابيز باش نعرضوها
    $userSettings = \App\Models\NotificationSetting::where('user_id', auth()->id())->get()->keyBy('notification_type');
    
    // إعدادات الإشعارات العامة
    $notif = $userSettings['doctor_notification'] ?? null;
    
    // إعدادات رسائل الأهل
    $chat = $userSettings['chat'] ?? null;
@endphp

<div class="phone">
    <div class="content">

        <div class="header">
            <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div class="title">Alert Sounds</div>
            <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo">
        </div>

        <div id="toast-message" class="success-box">Saved Successfully!</div>

        <div class="chip">Notifications</div>

        <div class="row">
            <span>Sound</span>
            <label class="switch">
                <input type="checkbox" class="smart-toggle" data-type="doctor_notification" data-field="has_sound" 
                       {{ ($notif->has_sound ?? true) ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <div class="row">
            <span>Vibrate</span>
            <label class="switch">
                <input type="checkbox" class="smart-toggle" data-type="doctor_notification" data-field="has_vibrate" 
                       {{ ($notif->has_vibrate ?? true) ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <div class="chip" style="margin-top:20px;">Parents Messages</div>

        <div class="row">
            <span>Sound</span>
            <label class="switch">
                <input type="checkbox" class="smart-toggle" data-type="chat" data-field="has_sound" 
                       {{ ($chat->has_sound ?? true) ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <div class="row">
            <span>Vibrate</span>
            <label class="switch">
                <input type="checkbox" class="smart-toggle" data-type="chat" data-field="has_vibrate" 
                       {{ ($chat->has_vibrate ?? true) ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

    </div>
</div>

<script>
    document.querySelectorAll('.smart-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            let typeVal = this.getAttribute('data-type');
            let fieldVal = this.getAttribute('data-field');
            let isChecked = this.checked ? 1 : 0;
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // إرسال البيانات للكنترولر
            fetch("{{ route('settings.toggle') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    type: typeVal,
                    field: fieldVal,
                    status: isChecked
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // إظهار رسالة النجاح لفترة قصيرة
                    let toast = document.getElementById('toast-message');
                    toast.style.display = 'block';
                    setTimeout(() => { toast.style.display = 'none'; }, 2000);

                    // ميزة إضافية: لو فعل الاهتزاز، خلي المتصفح يهتز كنوع من التأكيد
                    if(isChecked === 1 && fieldVal === 'has_vibrate') {
                        if (navigator.vibrate) {
                            navigator.vibrate(200);
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // لو صار خطأ في النت، رجع الزر لحالته السابقة
                this.checked = !this.checked; 
                alert('حدث خطأ في الاتصال بالسيرفر. يرجى المحاولة لاحقاً.');
            });
        });
    });
</script>

</body>
</html>