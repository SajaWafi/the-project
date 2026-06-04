<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts</title>

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
            max-width: 100%;
            height: 844px;
            max-height: 95vh;
            position: relative;
            overflow: hidden;
            border-radius: 30px;
            background: #f9f9f9 url('{{ asset('images/bg.png') }}') no-repeat left bottom;
            background-size: 165% 100%;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.14);
        }

        .content {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 12px 14px 100px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: none;
        }

        .content::-webkit-scrollbar {
            display: none;
        }

        .header {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
            min-height: 54px;
            margin-top: 6px;
        }

        .back-btn {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            border: none;
            background: transparent;
            padding: 0;
            cursor: pointer;
            color: #2f80ed;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
        }

        .back-btn svg {
            width: 24px;
            height: 24px;
            display: block;
        }

        .page-title {
            font-size: 30px;
            font-weight: 800;
            color: #1f5b87;
            text-align: center;
        }

        .logo {
            position: absolute;
            right: 0;
            width: 38px;
            height: 38px;
            object-fit: contain;
        }

        .section-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 26px;
            padding: 0 14px;
            border-radius: 999px;
            background: #4fcbb9;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            width: fit-content;
            margin: 4px 0 10px;
        }

        .alerts-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 8px;
        }

        .alert-card {
            background: #bed1f1;
            border-radius: 20px;
            padding: 16px 16px 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            margin-bottom: 12px; 
        }

        .alert-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .alert-title {
            font-size: 18px;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 12px;
            letter-spacing: 0.2px;
        }

        .alert-time {
            font-size: 12px;
            color: #1f5b87;
            font-weight: bold;
        }

        .alert-message {
            font-size: 14px;
            color: #5a6270;
            line-height: 1.35;
        }

        /* --- الألوان الجديدة التي تمت إضافتها --- */
        .title-red { color: #ff3434 !important; }
        .title-yellow { color: #d68100 !important; } 
        .title-blue { color: #1f5b87 !important; }

        .heart-warning {
            margin-top: 8px;
            font-size: 13px;
            font-weight: bold;
            color: #ff3434;
        }

        /* --- تصميم قسم سؤال المنطقة الآمنة وأزرارها --- */
        .safe-question {
            margin-top: 15px;
            padding-top: 12px;
            border-top: 1px dashed #a3b9d6;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .safe-question span {
            font-size: 14px;
            font-weight: bold;
            color: #1f5b87;
            flex-grow: 1;
        }

        .safe-btn {
            padding: 6px 16px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
            font-size: 13px;
        }

        .safe-btn.btn-yes {
            background: #4fcbb9;
            color: white;
        }

        .safe-btn.btn-no {
            background: #ff3434;
            color: white;
        }

        .safe-btn:active {
            transform: scale(0.95);
        }

        .safe-answer-yes { color: #158a77 !important; }
        .safe-answer-no { color: #ff3434 !important; }

        .bottom-nav {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background: #2f80ed;
            border-radius: 24px 24px 0 0;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 0 10px;
            z-index: 5;
        }

        .nav-item {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            transition: 0.2s;
        }

        .nav-item.active {
            background: rgba(255,255,255,0.25);
            color: #fff;
        }

        .nav-svg {
            width: 22px;
            height: 22px;
            display: block;
        }

        @media (max-width: 480px) {
            body {
                padding: 0;
                background: #fff;
            }
            .mobile-screen {
                width: 100%;
                max-width: 100%;
                height: 100vh;
                max-height: 100vh;
                border-radius: 0;
                box-shadow: none;
            }
            .content {
                padding: 12px 12px 90px;
            }
        }
    </style>
</head>
<body>

    <div class="mobile-screen">
        <div class="content">

            <div class="header">
                <a href="{{ route('parents.home') }}" class="back-btn" aria-label="Back to home">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <div class="page-title">Alerts</div>
                <img src="{{ asset('images/logo.png') }}" alt="Taif" class="logo">
            </div>

            @php
                $todayAlerts = $alerts->filter(fn($alert) =>
                    \Carbon\Carbon::parse($alert->sent_at ?? $alert->created_at)->isToday()
                );

                $yesterdayAlerts = $alerts->filter(fn($alert) =>
                    \Carbon\Carbon::parse($alert->sent_at ?? $alert->created_at)->isYesterday()
                );
            @endphp

            {{-- Today Alerts --}}
            @if($todayAlerts->count())
                <div class="section-pill">Today</div>

                @foreach($todayAlerts as $alert)
                    @php
                        $type = $alert->alert_type;

                        // 💡 التعديل هنا: مطابقة الأسماء القادمة من SensorController
                        $titleClass = match($type) {
                            'Danger' => 'title-red',
                            'Warning' => 'title-yellow',
                            'safe_zone' => 'title-red',
                            'Motion Alert' => 'title-yellow',
                            default => 'title-blue',
                        };

                        $icon = match($type) {
                            'Danger' => '🚨',
                            'Warning' => '💓',
                            'safe_zone' => '📍',
                            'Motion Alert' => '🏃',
                            default => '🔔',
                        };
                    @endphp

                    <div class="alert-card {{ strtolower(str_replace(' ', '_', $type)) }}">
                        <div class="alert-top">
                            <div class="alert-title {{ $titleClass }}">
                                {{ $icon }} {{ $alert->title }}
                            </div>
                            <div class="alert-time">
                                {{ \Carbon\Carbon::parse($alert->sent_at ?? $alert->created_at)->format('h:i A') }}
                            </div>
                        </div>

                        <div class="alert-message">
                            {{ $alert->message }}
                        </div>

                        @if($type === 'Warning')
                            <div class="heart-warning">
                                ⚠️ High heart rate detected
                            </div>
                        @endif

                        @if($type === 'safe_zone')
                            <div class="safe-question" id="safe-container-{{ $alert->id }}">
                                @if(is_null($alert->parent_response))
                                    <span id="safe-text-{{ $alert->id }}">Is the child with you?</span>
                                    
                                    <form action="{{ route('parents.alerts.response', $alert->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="parent_response" value="yes">
                                        <button type="submit" class="safe-btn btn-yes">Yes</button>
                                    </form>

                                    <form action="{{ route('parents.alerts.response', $alert->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="parent_response" value="no">
                                        <button type="submit" class="safe-btn btn-no">No</button>
                                    </form>
                                @elseif($alert->parent_response === 'yes')
                                    <span class="safe-answer-yes">✅ Child is safe with parent</span>
                                @elseif($alert->parent_response === 'no')
                                    <span class="safe-answer-no">🚨 Parent confirmed child is missing!</span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif

            {{-- Yesterday Alerts --}}
            @if($yesterdayAlerts->count())
                <div class="section-pill">Yesterday</div>

                @foreach($yesterdayAlerts as $alert)
                    @php
                        $type = $alert->alert_type;

                        // 💡 نفس التعديل في الأمس
                        $titleClass = match($type) {
                            'Danger' => 'title-red',
                            'Warning' => 'title-yellow',
                            'safe_zone' => 'title-red',
                            'Motion Alert' => 'title-yellow',
                            default => 'title-blue',
                        };

                        $icon = match($type) {
                            'Danger' => '🚨',
                            'Warning' => '💓',
                            'safe_zone' => '📍',
                            'Motion Alert' => '🏃',
                            default => '🔔',
                        };
                    @endphp

                    <div class="alert-card {{ strtolower(str_replace(' ', '_', $type)) }}">
                        <div class="alert-top">
                            <div class="alert-title {{ $titleClass }}">
                                {{ $icon }} {{ $alert->title }}
                            </div>
                            <div class="alert-time">
                                {{ \Carbon\Carbon::parse($alert->sent_at ?? $alert->created_at)->format('h:i A') }}
                            </div>
                        </div>
                        <div class="alert-message">
                            {{ $alert->message }}
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- Empty State --}}
            @if($alerts->isEmpty())
                <div class="alert-card">
                    <div class="alert-title title-blue">
                        No alerts
                    </div>
                    <div class="alert-message">
                        No alerts for today or yesterday
                    </div>
                </div>
            @endif

        </div>

        {{-- Bottom Navigation --}}
        <div class="bottom-nav">
            <a href="{{ route('parents.doctors') }}" class="nav-item {{ request()->routeIs('parents.doctors') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M6 4v5a6 6 0 0 0 12 0V4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 15v2a4 4 0 0 0 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="18" cy="19" r="2" fill="currentColor"/>
                </svg>
            </a>
            <a href="{{ route('parents.alerts') }}" class="nav-item {{ request()->routeIs('parents.alerts') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M12 4a4 4 0 0 0-4 4v2.2c0 .7-.2 1.3-.6 1.8L6 14h12l-1.4-2c-.4-.5-.6-1.1-.6-1.8V8a4 4 0 0 0-4-4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 17a2 2 0 0 0 4 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </a>
            <a href="{{ route('parents.home') }}" class="nav-item {{ request()->routeIs('parents.home') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M4 10.5 12 4l8 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M7 10v9h10v-9" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                </svg>
            </a>
            <a href="{{ route('parents.report') }}" class="nav-item {{ request()->routeIs('parents.report') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <rect x="6" y="4" width="12" height="16" rx="2" stroke="currentColor" stroke-width="2"/>
                    <path d="M9 8h6M9 12h6M9 16h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </a>
            <a href="{{ route('parents.location') }}" class="nav-item {{ request()->routeIs('parents.location') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M12 20s6-5 6-10a6 6 0 1 0-12 0c0 5 6 10 6 10Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    <circle cx="12" cy="10" r="2.5" fill="currentColor"/>
                </svg>
            </a>
        </div>
    </div>

     <script>
 /*
        function answerSafeZone(alertId, answer) {
            const container = document.getElementById('safe-container-' + alertId);
            const text = document.getElementById('safe-text-' + alertId);
            
            // 1. إخفاء الأزرار فوراً وتغيير النص (لإعطاء استجابة سريعة للمستخدم)
            const buttons = container.querySelectorAll('.safe-btn');
            buttons.forEach(btn => btn.style.display = 'none');

            text.classList.remove('safe-answer-yes', 'safe-answer-no');

            if (answer === 'yes') {
                text.innerHTML = "✅ Child is safe with parent";
                text.classList.add('safe-answer-yes');
            } else {
                text.innerHTML = "🚨 Parent confirmed child is missing!";
                text.classList.add('safe-answer-no');
            }

            // 2. إرسال الإجابة لقاعدة البيانات في الخلفية (بدون تحديث الصفحة)
            fetch(`/alerts/${alertId}/response`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // حماية لارافل
                },
                body: JSON.stringify({ answer: answer })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response saved to database successfully');
            })
            .catch(error => {
                console.error('Error saving response:', error);
            });
        }
            */
</script>

</body>
</html>
