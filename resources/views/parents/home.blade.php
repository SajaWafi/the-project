<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taif Dashboard</title>

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
            overflow-y: auto;
            overflow-x: hidden;
            border-radius: 30px;
            background: #f9f9f9;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.14);
            scrollbar-width: none;
        }

        .mobile-screen::-webkit-scrollbar {
            display: none;
        }

        .mobile-screen::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('images/bg.png') }}');
            background-repeat: no-repeat;
            background-size: 165% 100%;
            background-position: left bottom;
            opacity: 0.92;
            z-index: 0;
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            padding: 16px 14px 90px;
        }

        .top-time {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
            gap: 10px;
        }

        .user-box {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .user-box img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            border: 2px solid #fff;
        }

        .hello {
            font-size: 16px;
            font-weight: 700;
            color: #404040;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .header-icons {
            display: flex;
            align-items: center;
            gap: 1px;
            flex-shrink: 0;
        }

        .circle-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #66d2be;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            border: none;
            cursor: pointer;
        }

        .notif-dot {
            position: absolute;
            top: 4px;
            right: 6px;
            width: 7px;
            height: 7px;
            background: #ff5f5f;
            border-radius: 50%;
        }

        .app-logo-small {
           width: 88px;
            object-fit: contain;
            display: block;
        }

        .section-title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #262626;
            text-transform: lowercase;
        }

        .small-round-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #e69a4b;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
        }

        .chart-card {
            margin-bottom: 14px;
        }

        .chart-divider {
            height: 1px;
            background: rgba(130, 130, 130, 0.35);
            margin: 0 6px 10px;
        }

        .chart-wrap {
            height: 255px;
            position: relative;
            background: rgba(255, 255, 255, 0.78);
            border-radius: 18px;
            padding: 8px 6px 0 0;
            isolation: isolate;
        }

        #activityChart {
            width: 100% !important;
            height: 255px !important;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }

        .stat-card {
            border-radius: 18px;
            padding: 14px 12px;
            min-height: 98px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .stat-card.red {
            background: #fff0f0;
        }

        .stat-card.blue {
            background: #edf2fb;
        }

        .stat-card.green {
            background: #d9f2ee;
        }

        .stat-icon {
            font-size: 24px;
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-card.red .stat-value {
            color: #ef4444;
        }

        .stat-title {
            font-size: 12px;
            color: #5b6472;
            line-height: 1.3;
        }

        .schedule-card {
            background: #f3e2d0;
            border-radius: 24px;
            padding: 14px 12px;
            margin-bottom: 18px;
        }

        .days-row {
            display: flex;
            justify-content: space-between;
            gap: 7px;
            margin-bottom: 12px;
        }

        .day-pill {
            width: 42px;
            height: 56px;
            border-radius: 18px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            line-height: 1.05;
            color: #454545;
            font-size: 10px;
            flex-shrink: 0;
        }

        .day-pill strong {
            font-size: 15px;
            margin-bottom: 2px;
        }

        .day-pill.active {
            background: #e7a04c;
            color: #fff;
        }

        .appointment-box {
            background: #fff9f2;
            border-radius: 18px;
            padding: 10px 10px 12px;
            display: grid;
            grid-template-columns: 42px 1fr;
            gap: 8px;
        }

        .times {
            color: #e29244;
            font-size: 12px;
            line-height: 2;
            padding-top: 6px;
        }

        .appointment-content {
            min-width: 0;
        }

        .appointment-header {
            text-align: center;
            color: #e29244;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .appointment-main {
            background: #fff2df;
            border-radius: 14px;
            border-top: 1px dashed #e6a86b;
            border-bottom: 1px dashed #e6a86b;
            padding: 10px 12px;
        }

        .doctor-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
        }

        .doctor-name {
            color: #e29244;
            font-size: 16px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .doctor-actions {
            color: #e29244;
            font-size: 13px;
            flex-shrink: 0;
        }

        .appointment-sub {
            color: #555;
            font-size: 14px;
        }

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

    .nav-svg {
        width: 22px;
        height: 22px;
    }

    /* 🔥 الحالة النشطة */
    .nav-item.active {
        background: rgba(255,255,255,0.25);
        border-radius: 14px;
        color: #fff;
    }
    .icon-svg {
        width: 18px;
        height: 18px;
        color: #ffffff;
    }

    .card-svg {
        width: 24px;
        height: 24px;
        display: block;
    }

    .heart-svg {
        color: #ef4444;
    }

    .activity-svg {
        color: #3b82f6;
    }

    .battery-svg {
        color: #14b8a6;
    }

    .nav-svg {
        width: 22px;
        height: 22px;
        color: #ffffff;
        display: inline-block;
    }
        .nav-item.active {
            transform: translateY(-1px);
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
                padding: 16px 12px 90px;
            }

            .chart-wrap {
                height: 245px;
            }

            #activityChart {
                height: 245px !important;
            }
        }
    </style>
</head>
<body>

    <div class="mobile-screen">
        <div class="content">

            <div class="top-time">6.3</div>

            <div class="header">
                <div class="user-box">
                    <img src="{{ asset('images/child.png') }}" alt="Child">
                    <div class="hello">Hello, Ahmed!</div>
                </div>

                <div class="header-icons">
    <button class="circle-icon" type="button">
        <svg class="icon-svg bell-icon" viewBox="0 0 24 24" fill="none">
            <path d="M12 4a4 4 0 0 0-4 4v2.2c0 .7-.2 1.3-.6 1.8L6 14h12l-1.4-2c-.4-.5-.6-1.1-.6-1.8V8a4 4 0 0 0-4-4Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M10 17a2 2 0 0 0 4 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        <span class="notif-dot"></span>
    </button>

    <img src="{{ asset('images/logo.png') }}" alt="Taif" class="app-logo-small">
</div>
            </div>

            <div class="section-title-row">
                <div class="section-title">daily activities</div>
                <div class="small-round-icon">〰</div>
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
        <div class="stat-value">72</div>
        <div class="stat-title">Heart Rate<br>bpm</div>
    </div>

    <div class="stat-card blue">
        <div class="stat-icon">
            <svg class="card-svg activity-svg" viewBox="0 0 24 24" fill="none">
                <path d="M3 13h4l2-6 4 12 2-6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="stat-title">
            Today<br>
            Activity Level<br>
            Calm
        </div>
    </div>

    <div class="stat-card green">
        <div class="stat-icon">
            <svg class="card-svg battery-svg" viewBox="0 0 24 24" fill="none">
                <rect x="3" y="7" width="16" height="10" rx="2" stroke="currentColor" stroke-width="1.8"/>
                <path d="M21 10v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <rect x="5.5" y="9.5" width="8" height="5" rx="1" fill="currentColor"/>
            </svg>
        </div>
        <div class="stat-title">
            Device Battery<br>
            53%
        </div>
    </div>
</div>

            <div class="schedule-card">
                <div class="days-row">
                    <div class="day-pill"><strong>9</strong><span>MON</span></div>
                    <div class="day-pill"><strong>10</strong><span>TUE</span></div>
                    <div class="day-pill active"><strong>11</strong><span>WED</span></div>
                    <div class="day-pill"><strong>12</strong><span>THU</span></div>
                    <div class="day-pill active"><strong>13</strong><span>FRI</span></div>
                    <div class="day-pill active"><strong>14</strong><span>SAT</span></div>
                </div>

                <div class="appointment-box">
                    <div class="times">
                        <div>9 AM</div>
                        <div>10 AM</div>
                        <div>11 AM</div>
                        <div>12 AM</div>
                    </div>

                    <div class="appointment-content">
                        <div class="appointment-header">11 Wednesday - Today</div>

                        <div class="appointment-main">
                            <div class="doctor-row">
                                <div class="doctor-name">Dr. Olivia Turner</div>
                                <div class="doctor-actions">♡ ×</div>
                            </div>

                            <div class="appointment-sub">Periodic review</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- navbar -->
          <div class="bottom-nav">
                <a href="{{ route('parents.doctors') }}" class="nav-item {{ request()->routeIs('parents.doctors') ? 'active' : '' }}">
                    <svg class="nav-svg" viewBox="0 0 24 24" fill="none">
                        <path d="M6 4v5a6 6 0 0 0 12 0V4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 15v2a4 4 0 0 0 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="18" cy="19" r="2" fill="currentColor"/>
                    </svg>
                </a>

                <a href="{{ route('parents.notifications') }}" class="nav-item {{ request()->routeIs('parents.notifications') ? 'active' : '' }}">
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

                <a href="{{ route('parents.reports') }}" class="nav-item {{ request()->routeIs('parents.reports') ? 'active' : '' }}">
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

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('activityChart').getContext('2d');

        const chartBgPlugin = {
            id: 'chartBgPlugin',
            beforeDraw(chart) {
                const { ctx, chartArea } = chart;
                if (!chartArea) return;

                ctx.save();
                ctx.fillStyle = 'rgba(255,255,255,0.72)';
                ctx.fillRect(
                    chartArea.left,
                    chartArea.top,
                    chartArea.right - chartArea.left,
                    chartArea.bottom - chartArea.top
                );
                ctx.restore();
            }
        };

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['12:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00'],
                datasets: [
                    {
                        type: 'bar',
                        data: [95, 85, 45, 80, 35, 25, 18],
                        backgroundColor: '#7CC7BD',
                        borderRadius: 12,
                        borderSkipped: false,
                        categoryPercentage: 0.72,
                        barPercentage: 0.9
                    },
                    {
                        type: 'line',
                        data: [10, 25, 60, 45, 70, 60, 85],
                        borderColor: '#E5A96D',
                        backgroundColor: '#E5A96D',
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        borderWidth: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                devicePixelRatio: 2,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                layout: {
                    padding: {
                        top: 8,
                        left: 8,
                        right: 8,
                        bottom: 0
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: '#777',
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    },
                    y: {
                        min: 0,
                        max: 100,
                        afterBuildTicks: (scale) => {
                            scale.ticks = [
                                { value: 0 },
                                { value: 25 },
                                { value: 50 },
                                { value: 75 },
                                { value: 100 }
                            ];
                        },
                        ticks: {
                            color: '#666',
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            callback: function(value) {
                                if (value === 25) return 'Low';
                                if (value === 50) return 'Medium';
                                if (value === 75) return 'High';
                                return '';
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.07)',
                            lineWidth: 1
                        },
                        border: {
                            display: false
                        }
                    }
                }
            },
            plugins: [chartBgPlugin]
        });
    </script>

</body>
</html>