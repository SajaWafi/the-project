<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taif Dashboard</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { min-height: 100vh; display: flex; justify-content: center; align-items: center; background: #edf1f4; font-family: Arial, sans-serif; padding: 20px; }
        .mobile-screen { width: 390px; max-width: 100%; height: 844px; max-height: 95vh; position: relative; overflow: hidden; border-radius: 30px; background: #f9f9f9; box-shadow: 0 18px 40px rgba(0, 0, 0, 0.14); scrollbar-width: none; }
        .mobile-screen::-webkit-scrollbar { display: none; }
        .mobile-screen::before { content: ""; position: absolute; inset: 0; background-image: url('{{ asset('images/bg.png') }}'); background-repeat: no-repeat; background-size: 165% 100%; background-position: left bottom; opacity: 0.92; z-index: 0; pointer-events: none; }
        .content { position: relative; z-index: 1; height: 100%; display: flex; flex-direction: column; padding: 16px 14px 90px; overflow-y: auto; overflow-x: hidden; scrollbar-width: none; }
        .content::-webkit-scrollbar { display: none; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; gap: 10px; }
        .user-box { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .user-box img { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 2px solid #fff; }
        .hello { font-size: 16px; font-weight: 700; color: #404040; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .header-icons { display: flex; align-items: center; gap: 1px; flex-shrink: 0; }
        .circle-icon { width: 34px; height: 34px; border-radius: 50%; background: #66d2be; display: flex; justify-content: center; align-items: center; position: relative; border: none; cursor: pointer; }
        .notif-dot { position: absolute; top: 4px; right: 6px; width: 7px; height: 7px; background: #ff5f5f; border-radius: 50%; }
        .app-logo-small { width: 88px; object-fit: contain; display: block; }
        .section-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .section-title { font-size: 15px; font-weight: 700; color: #262626; text-transform: lowercase; }
        .chart-card { margin-bottom: 14px; }
        .chart-divider { height: 1px; background: rgba(130, 130, 130, 0.35); margin: 0 6px 10px; }
        .chart-wrap { height: 255px; position: relative; background: rgba(255, 255, 255, 0.78); border-radius: 18px; padding: 8px 6px 0 0; isolation: isolate; }
        #activityChart { width: 100% !important; height: 255px !important; }
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 16px; }
        .stat-card { border-radius: 18px; padding: 14px 12px; min-height: 98px; display: flex; flex-direction: column; justify-content: flex-start; transition: background 0.3s ease; }
        .stat-card.red { background: #fff0f0; }
        .stat-card.blue { background: #edf2fb; }
        .stat-card.green { background: #d9f2ee; }
        .stat-icon { font-size: 24px; line-height: 1; margin-bottom: 8px; }
        .stat-value { font-size: 17px; font-weight: 700; margin-bottom: 4px; transition: color 0.3s ease; }
        .stat-card.red .stat-value { color: #ef4444; }
        .stat-title { font-size: 12px; color: #5b6472; line-height: 1.3; }
        .schedule-card { background: #f3e2d0; border-radius: 24px; padding: 10px 12px 14px; margin-bottom: 18px; }
        .appointment-box { background: #fff9f2; border-radius: 18px; padding: 10px 10px 12px; display: grid; grid-template-columns: 42px 1fr; gap: 8px; }
        .times { color: #e29244; font-size: 12px; line-height: 2; padding-top: 6px; }
        .appointment-content { min-width: 0; }
        .appointment-header { text-align: center; color: #e29244; font-size: 13px; margin-bottom: 8px; }
        .appointment-main { background: #fff2df; border-radius: 14px; border-top: 1px dashed #e6a86b; border-bottom: 1px dashed #e6a86b; padding: 10px 12px; }
        .doctor-name { color: #e29244; font-size: 16px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .appointment-sub { color: #555; font-size: 14px; }
        .bottom-nav { position: absolute; bottom: 0; left: 0; width: 100%; height: 70px; background: #2f80ed; border-radius: 24px 24px 0 0; display: flex; justify-content: space-around; align-items: center; padding: 0 10px; z-index: 5; }
        .nav-item { width: 48px; height: 48px; border-radius: 14px; display: flex; justify-content: center; align-items: center; color: rgba(255,255,255,0.65); text-decoration: none; transition: 0.2s; }
        .nav-item.active { background: rgba(255,255,255,0.25); color: #fff; }
        .nav-svg { width: 22px; height: 22px; color: #ffffff; display: inline-block; }
        .card-svg { width: 24px; height: 24px; display: block; }
        .heart-svg { color: #ef4444; }
        .activity-svg { color: #3b82f6; }
        .child-link { display: inline-flex; text-decoration: none; }
        .section-chip { width: 40%; display: inline-block; background: #e29244; color: #fff; border-radius: 14px; padding: 4px 12px; font-size: 16px; font-weight: 700; margin-bottom: 10px; text-align: center;}
    </style>
</head>
<body>
    <div class="mobile-screen">
        <div class="content">

            <div class="header">
                <div class="user-box">
                    <a href="{{ route('profile') }}" class="child-link">
                        <img 
                            src="{{ !empty(auth()->user()->profile_image) ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-user.png') }}"
                            alt="Profile"
                        >
                    </a>
                    <div class="hello">Hello, {{ auth()->user()->first_name ?? 'User' }}!</div>
                </div>

                <div class="header-icons">
                    <button class="circle-icon" type="button" onclick="window.location.href='{{ route('requests') }}'">
                        <svg class="icon-svg bell-icon" viewBox="0 0 24 24" fill="none">
                            <path d="M12 4a4 4 0 0 0-4 4v2.2c0 .7-.2 1.3-.6 1.8L6 14h12l-1.4-2c-.4-.5-.6-1.1-.6-1.8V8a4 4 0 0 0-4-4Z" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M10 17a2 2 0 0 0 4 0" stroke="currentColor" stroke-width="1.8"/>
                        </svg>
                        <span class="notif-dot"></span>
                    </button>
                    <img src="{{ asset('images/logo.png') }}" alt="Taif" class="app-logo-small">
                </div>
            </div> <div class="section-title-row">
                <div class="section-title">Daily Activities</div>
            </div>

            <div class="chart-card">
                <div class="chart-divider"></div>
                <div class="chart-wrap">
                    <canvas id="activityChart" height="255"></canvas>
                </div>
            </div>

            <div class="stats">
                <div class="stat-card red">
                    <div class="stat-icon">
                        <svg class="card-svg heart-svg" viewBox="0 0 24 24" fill="none">
                            <path d="M12 20s-6.5-4.2-8.5-8A5.2 5.2 0 0 1 12 5.7 5.2 5.2 0 0 1 20.5 12C18.5 15.8 12 20 12 20Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="stat-value" id="liveHeartRate">{{ $heartRate ?? 0 }}</div>
                    <div class="stat-title">Heart Rate<br>bpm</div>
                </div>

                <div class="stat-card blue">
                    <div class="stat-icon">
                        <svg class="card-svg activity-svg" viewBox="0 0 24 24" fill="none">
                            <path d="M3 13h4l2-6 4 12 2-6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="stat-title">
                        Today<br>Activity Level<br>
                        <strong id="liveActivityStatus" style="color: #3b82f6;">{{ $liveStatus ?? $activityStatus ?? 'Calm' }}</strong>
                    </div>
                </div>
                
                <div id="liveConnectionCard" class="stat-card {{ ($isConnected ?? false) ? 'green' : 'red' }}">
                    <div class="stat-icon">
                        <svg id="liveConnectionSvg" class="card-svg" style="color: {{ ($isConnected ?? false) ? '#14b8a6' : '#ef4444' }};" viewBox="0 0 24 24" fill="none">
                            @if($isConnected ?? false)
                                <path d="M5 12.55a11 11 0 0 1 14.08 0M1.42 9a16 16 0 0 1 21.16 0M8.53 16.11a6 6 0 0 1 6.95 0M12 20h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            @else
                                <path d="M1.42 9a16 16 0 0 1 21.16 0M5 12.55a11 11 0 0 1 14.08 0M8.53 16.11a6 6 0 0 1 6.95 0M12 20h.01M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            @endif
                        </svg>
                    </div>
                    <div class="stat-title">
                        Device Status<br>
                        <strong id="liveConnectionStatus" style="color: {{ ($isConnected ?? false) ? '#14b8a6' : '#ef4444' }};">
                            {{ ($isConnected ?? false) ? 'Connected' : 'Disconnected' }}
                        </strong>
                    </div>
                </div>
            </div> <div class="section-chip">Appointments</div>
            
            @forelse($appointments ?? [] as $appointment)
                @php
                    $appointmentDate = \Carbon\Carbon::parse($appointment->date);
                    $isToday = $appointmentDate->isToday();
                    $doctorName = trim(($appointment->doctor->user->first_name ?? '') . ' ' . ($appointment->doctor->user->last_name ?? ''));
                    $headerText = $appointmentDate->format('d l') . ($isToday ? ' - Today' : '');
                @endphp

                <div class="schedule-card">
                    <div class="appointment-box">
                        <div class="times">
                            <div>{{ str_pad($appointment->from_hour, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($appointment->from_minute, 2, '0', STR_PAD_LEFT) }} {{ $appointment->from_period }}</div>
                            <div>|</div>
                            <div>|</div>
                            <div>{{ str_pad($appointment->to_hour, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($appointment->to_minute, 2, '0', STR_PAD_LEFT) }} {{ $appointment->to_period }}</div>
                        </div>

                        <div class="appointment-content">
                            <div class="appointment-header">
                                <span>{{ $headerText }}</span>
                            </div>

                            <div class="appointment-main">
                                <div class="appointment-info">
                                    <div class="appointment-sub">Doctor: {{ $doctorName ?: 'N/A' }}</div>
                                    <div class="appointment-sub">Child: {{ $appointment->child->name ?? 'N/A' }}</div>
                                    <div class="appointment-sub">Place: {{ $appointment->workplace->place_name ?? 'N/A' }}</div>
                                    <div class="note">Note: {{ $appointment->note }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="schedule-card">
                    <div class="appointment-box">
                        <div class="appointment-content" style="text-align: center; width: 100%;">
                            <span class="mmm" style="display: block; padding-top: 10px;">No upcoming appointments</span>
                        </div>
                    </div>
                </div>
            @endforelse

        </div> <div class="bottom-nav">
            <a href="{{ route('doctors') }}" class="nav-item {{ request()->routeIs('doctors') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M6 4v5a6 6 0 0 0 12 0V4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 15v2a4 4 0 0 0 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="18" cy="19" r="2" fill="currentColor"/>
                </svg>
            </a>
            <a href="{{ route('alerts') }}" class="nav-item {{ request()->routeIs('alerts') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M12 4a4 4 0 0 0-4 4v2.2c0 .7-.2 1.3-.6 1.8L6 14h12l-1.4-2c-.4-.5-.6-1.1-.6-1.8V8a4 4 0 0 0-4-4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 17a2 2 0 0 0 4 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </a>
            <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('parents.home') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M4 10.5 12 4l8 6.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M7 10v9h10v-9" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                </svg>
            </a>
            <a href="{{ route('report') }}" class="nav-item {{ request()->routeIs('parents.report') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <rect x="6" y="4" width="12" height="16" rx="2" stroke="currentColor" stroke-width="2"/>
                    <path d="M9 8h6M9 12h6M9 16h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </a>
            <a href="{{ route('location') }}" class="nav-item {{ request()->routeIs('parents.location') ? 'active' : '' }}">
                <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                    <path d="M12 20s6-5 6-10a6 6 0 1 0-12 0c0 5 6 10 6 10Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    <circle cx="12" cy="10" r="2.5" fill="currentColor"/>
                </svg>
            </a>
        </div>
    </div> </body>

   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js"></script>

<script>
    // 1. نفس بيانات مشروعك في الفايربيس
    const firebaseConfig = {
        apiKey: "YOUR_API_KEY",
        projectId: "YOUR_PROJECT_ID",
        messagingSenderId: "YOUR_SENDER_ID",
        appId: "YOUR_APP_ID"
    };

    // 2. تشغيل الفايربيس
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    // 3. طلب الإذن من الأب لإرسال الإشعارات
    function requestNotificationPermission() {
        Notification.requestPermission().then((permission) => {
            if (permission === 'granted') {
                console.log('Notification permission granted.');
                
                // 4. جلب التوكن من الفايربيس
                messaging.getToken({ vapidKey: "YOUR_VAPID_KEY" }).then((currentToken) => {
                    if (currentToken) {
                        console.log('FCM Token:', currentToken);
                        sendTokenToServer(currentToken); // نبعثوه للارافل
                    } else {
                        console.log('No registration token available.');
                    }
                }).catch((err) => {
                    console.log('An error occurred while retrieving token. ', err);
                });
            } else {
                console.log('Unable to get permission to notify.');
            }
        });
    }

    // 5. دالة إرسال التوكن للكنترولر متاعك اللي درناه في الخطوة 1
    function sendTokenToServer(token) {
        fetch('{{ route("save.token") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ token: token })
        }).then(response => {
            console.log('Token saved in database successfully.');
        }).catch(error => {
            console.error('Error saving token:', error);
        });
    }

    // نشغلوا طلب الإذن أول ما الصفحة تفتح
    requestNotificationPermission();

    // 6. هذي تستقبل الإشعار لو كان الأب فاتح التطبيق ويتصفح فيه
    messaging.onMessage((payload) => {
        console.log('Message received. ', payload);
        alert(payload.notification.title + "\n" + payload.notification.body);
    });
</script>

<script>
    const ctx = document.getElementById('activityChart').getContext('2d');

    const labels = {!! json_encode($chartLabels ?? []) !!};
    const heartRates = {!! json_encode($heartRatesChart ?? []) !!};
    const motionLevels = {!! json_encode($motionLevelsChart ?? []) !!};

    const chartBgPlugin = {
        id: 'chartBgPlugin',
        beforeDraw(chart) {
            const { ctx, chartArea } = chart;
            if (!chartArea) return;
            ctx.save();
            ctx.fillStyle = 'rgba(255,255,255,0.72)';
            ctx.fillRect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
            ctx.restore();
        }
    };

  const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'bar',
                    data: motionLevels,
                    backgroundColor: '#7CC7BD',
                    borderRadius: 12,
                    categoryPercentage: 0.72,
                    barPercentage: 0.9,
                    yAxisID: 'yMotion' // ربطنا الأعمدة بمحور الحركة المخفي
                },
                {
                    type: 'line',
                    data: heartRates,
                    borderColor: '#E5A96D',
                    backgroundColor: '#E5A96D',
                    tension: 0.4,
                    borderWidth: 5,
                    yAxisID: 'y' // ربطنا الخط بمحور النبض الأساسي
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, border: { display: false } },
                
                // 1. المحور الأساسي للنبض (من 0 إلى 150)
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    min: 0,
                    max: 150, 
                    afterBuildTicks: (scale) => {
                        scale.ticks = [{ value: 0 }, { value: 50 }, { value: 100 }, { value: 150 }];
                    },
                    ticks: {
                        callback: function(value) {
                            if (value === 50) return 'Low';
                            if (value === 100) return 'Medium';
                            if (value === 150) return 'High';
                            return '';
                        }
                    }
                },

                // 2. المحور الجديد للحركة (من 0 إلى 100) ومخفي
                yMotion: {
                    type: 'linear',
                    display: false, // مخفي باش ما يخربش شكل الواجهة
                    min: 0,
                    max: 100 
                }
            }
        },
        plugins: [chartBgPlugin]
    });

    setInterval(function() {
        fetch('{{ route("home.live") }}')
            .then(response => response.json())
            .then(data => {
                // تحديث النبض
                document.getElementById('liveHeartRate').innerText = data.heartRate;
                
                // تحديث حالة النشاط وتغيير اللون حسب الخطورة
                const activityStatusEl = document.getElementById('liveActivityStatus');
                activityStatusEl.innerText = data.live_status;
                if (data.live_status === 'Panic Episode') {
                    activityStatusEl.style.color = '#ef4444'; // أحمر للخطورة
                } else if (data.live_status === 'Anxiety') {
                    activityStatusEl.style.color = '#e29244'; // برتقالي للقلق
                } else {
                    activityStatusEl.style.color = '#3b82f6'; // أزرق للطبيعي (Calm)
                }
                
                // تحديث أيقونة وخلفية مربع الاتصال ديناميكياً
                const connCard = document.getElementById('liveConnectionCard');
                const connElement = document.getElementById('liveConnectionStatus');
                const svgElement = document.getElementById('liveConnectionSvg');
                
                if (data.isConnected) {
                    connElement.innerText = 'Connected';
                    connElement.style.color = '#14b8a6'; // أخضر
                    svgElement.style.color = '#14b8a6'; // أخضر
                    
                    connCard.classList.remove('red');
                    connCard.classList.add('green');
                    
                    svgElement.innerHTML = '<path d="M5 12.55a11 11 0 0 1 14.08 0M1.42 9a16 16 0 0 1 21.16 0M8.53 16.11a6 6 0 0 1 6.95 0M12 20h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
                } else {
                    connElement.innerText = 'Disconnected';
                    connElement.style.color = '#ef4444'; // أحمر
                    svgElement.style.color = '#ef4444'; // أحمر
                    
                    connCard.classList.remove('green');
                    connCard.classList.add('red');
                    
                    svgElement.innerHTML = '<path d="M1.42 9a16 16 0 0 1 21.16 0M5 12.55a11 11 0 0 1 14.08 0M8.53 16.11a6 6 0 0 1 6.95 0M12 20h.01M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
                }

                // تحديث الرسم البياني
                myChart.data.labels = data.chartLabels;
                myChart.data.datasets[0].data = data.motionLevelsChart; 
                myChart.data.datasets[1].data = data.heartRatesChart; 
                myChart.update();
            })
            .catch(error => console.error('Error fetching live data:', error));
    }, 5000);
</script>
</body>
</html>