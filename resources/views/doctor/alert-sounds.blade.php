<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Alert Sounds') }}</title>

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
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
        }

        /* 💡 زر الرجوع مع دعم الاتجاهين */
        .back-btn {
            position: absolute;
            inset-inline-start: 0;
            background: transparent;
            border: none;
            cursor: pointer;
            color: #2f80ed;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: {{ app()->getLocale() == 'ar' ? 'scaleX(-1)' : 'none' }};
        }

        .back-btn svg {
            width: 26px;
            height: 26px;
        }

        .title {
            font-size: 26px;
            font-weight: 800;
            color: #1d567e;
        }

        /* 💡 دعم الشعار للاتجاهين */
        .logo {
            position: absolute;
            inset-inline-end: -10px;
            top: -10px;
            width: 100px;
            height: 100px;
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

        /* 💡 تصميم زر التبديل لدعم اللغتين */
        .slider:before {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            inset-inline-start: 2px;
            top: 2px;
            background: white;
            border-radius: 50%;
            transition: 0.3s;
        }

        input:checked + .slider {
            background: #3cc1a7;
        }

        input:checked + .slider:before {
            transform: translateX({{ app()->getLocale() == 'ar' ? '-22px' : '22px' }});
        }

        /* ===== Alerts ===== */
        .success-box {
            background: #e7fff0;
            color: #0a7c3a;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            display: none;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>

@php
    $userSettings = \App\Models\NotificationSetting::where('user_id', auth()->id())->get()->keyBy('notification_type');
    
    $notif = $userSettings['doctor_notification'] ?? null;
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
            <div class="title">{{ __('Alert Sounds') }}</div>
            <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo">
        </div>

        <div id="toast-message" class="success-box">{{ __('Saved Successfully!') }}</div>

        <div class="chip">{{ __('Notifications') }}</div>

        <div class="row">
            <span>{{ __('Sound') }}</span>
            <label class="switch">
                <input type="checkbox" class="smart-toggle" data-type="doctor_notification" data-field="has_sound" 
                       {{ ($notif->has_sound ?? true) ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <div class="row">
            <span>{{ __('Vibrate') }}</span>
            <label class="switch">
                <input type="checkbox" class="smart-toggle" data-type="doctor_notification" data-field="has_vibrate" 
                       {{ ($notif->has_vibrate ?? true) ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <div class="chip" style="margin-top:20px;">{{ __('Parents Messages') }}</div>

        <div class="row">
            <span>{{ __('Sound') }}</span>
            <label class="switch">
                <input type="checkbox" class="smart-toggle" data-type="chat" data-field="has_sound" 
                       {{ ($chat->has_sound ?? true) ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <div class="row">
            <span>{{ __('Vibrate') }}</span>
            <label class="switch">
                <input type="checkbox" class="smart-toggle" data-type="chat" data-field="has_vibrate" 
                       {{ ($chat->has_vibrate ?? true) ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

    </div>
</div>

<script>
    // 💡 نقل رسالة الخطأ لتكون قابلة للترجمة
    const serverErrorMsg = "{{ __('Connection error. Please try again later.') }}";

    document.querySelectorAll('.smart-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            let typeVal = this.getAttribute('data-type');
            let fieldVal = this.getAttribute('data-field');
            let isChecked = this.checked ? 1 : 0;
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

           fetch("{{ route('doctor.settings.toggle') }}",, {
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
                    let toast = document.getElementById('toast-message');
                    toast.style.display = 'block';
                    setTimeout(() => { toast.style.display = 'none'; }, 2000);

                    if(isChecked === 1 && fieldVal === 'has_vibrate') {
                        if (navigator.vibrate) {
                            navigator.vibrate(200);
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !this.checked; 
                alert(serverErrorMsg);
            });
        });
    });
</script>

</body>
</html>