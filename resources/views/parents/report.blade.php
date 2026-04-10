<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Health Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            font-family: Inter, Arial, sans-serif;
            background: #edf4fb;
            color: #1f2a37;
        }

        .mobile-shell {
            width: 390px;
            max-width: 100%;
            height: 844px;
            max-height: 95vh;
            margin: 0 auto;
            padding: 18px 14px 110px;
            position: relative;
            overflow-y: auto;
            overflow-x: hidden;
            border-radius: 30px;
            background: linear-gradient(180deg, #edf5ff 0%, #f7fbff 20%, #f3f6fb 100%);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.14);
            scrollbar-width: none;
        }

        .mobile-shell::-webkit-scrollbar {
            display: none;
        }

        .topbar {
             position: relative;
                display: flex;
                justify-content: center;
                align-items: center;
        }
        .back-link {
        position: absolute;
        left: 0;
        font-size: 28px;
        color: #2f80ed;
        text-decoration: none;
    }
        .topbar-title {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            color: #163b66;
        }

        .chip {
            background: #ffffff;
            color: #2563eb;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.10);
        }

        .card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 24px;
            box-shadow: 0 10px 26px rgba(31, 42, 55, 0.08);
            padding: 16px;
            margin-bottom: 14px;
            border: 1px solid rgba(219, 234, 254, 0.85);
        }

        .profile-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .profile-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

       .avatar {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        overflow: hidden; /* مهم */
         border: 2px solid #ffffff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .child-name {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            color: #163b66;
        }

        .muted {
            color: #6b7280;
            font-size: 12px;
            margin-top: 4px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e8fff3;
            color: #0f9f6e;
            padding: 9px 12px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            white-space: nowrap;
        }

        .filters {
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
        }

        .filters a {
            flex: 1;
            text-align: center;
            text-decoration: none;
            background: #eaf2ff;
            color: #2563eb;
            padding: 10px 12px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 700;
        }

        .filters a.active {
            background: linear-gradient(135deg, #1f75ff, #2d9cff);
            color: #ffffff;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.25);
        }

        .alert-card {
            background: linear-gradient(180deg, #fff4f4 0%, #ffffff 100%);
            border: 1px solid #ffd7d7;
        }

        .warning-card {
            background: linear-gradient(180deg, #fff9eb 0%, #ffffff 100%);
            border: 1px solid #ffe4a8;
        }

        .section-title {
            margin: 0 0 10px;
            font-size: 18px;
            font-weight: 800;
            color: #163b66;
        }

        .row-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }

        .badge-red,
        .badge-yellow,
        .badge-blue,
        .badge-soft {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
        }

        .badge-red {
            background: #ff5f57;
            color: #ffffff;
        }

        .badge-yellow {
            background: #fff1c7;
            color: #9a6700;
        }

        .badge-blue {
            background: #e8f1ff;
            color: #2563eb;
        }

        .badge-soft {
            background: #f3f4f6;
            color: #374151;
        }

        .summary-text {
            font-size: 17px;
            font-weight: 800;
            color: #991b1b;
            margin-bottom: 6px;
        }

        .small-note {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.6;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 12px;
        }

        .metric-box {
            background: #f8fbff;
            border-radius: 18px;
            padding: 12px;
            border: 1px solid #ebf2fb;
        }

        .metric-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .metric-value {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1;
        }

        .metric-sub {
            font-size: 12px;
            color: #2563eb;
            margin-top: 6px;
        }

        .list-clean {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .list-clean li {
            padding: 10px 0;
            border-bottom: 1px solid #edf2f7;
            font-size: 14px;
            line-height: 1.6;
        }

        .list-clean li:last-child {
            border-bottom: 0;
        }

        .episode-card {
            background: #f8fbff;
            border: 1px solid #ebf2fb;
            border-radius: 18px;
            padding: 12px;
            margin-bottom: 10px;
        }

        .episode-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
        }

        .episode-title {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
        }

        .episode-meta {
            font-size: 13px;
            color: #475569;
            margin-top: 5px;
        }

        .chart-card canvas {
            width: 100% !important;
            height: 220px !important;
        }

        .download-btn {
        width: 85%;
        display: block;
        text-align: center;
        text-decoration: none;
        background: linear-gradient(135deg, #1f75ff, #2d9cff);
        color: #ffffff;
        padding: 13px;
        border-radius: 16px;
        font-size: 15px;
        font-weight: 800;
        box-shadow: 0 10px 18px rgba(37, 99, 235, 0.22);
        margin: 20px auto 0;
    }
        @media (max-width: 480px) {
    body {
        padding: 0;
    }

    .mobile-shell {
        width: 100%;
        max-width: 100%;
        height: 100vh;
        max-height: 100vh;
        border-radius: 0;
        box-shadow: none;
    }
}
.topbar-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

.logo {
    width: 40px;
    height: auto;
}
    </style>
</head>
<body>
<div class="mobile-shell">

    <div class="topbar">
        <a href="{{ route('parents.home') }}" class="back-link">‹</a>
        <h1 class="topbar-title">Health Report</h1>
        <span class="chip">{{ $report['period_label'] }}</span>
        <img src="{{ asset('images/logo.png') }}" alt="logo" class="logo">
    </div>

    <div class="card profile-card">
        <div class="profile-left">
            <div class="avatar">
            <img src="{{ asset('images/child.png') }}" alt="child">
        </div>
            <div>
                <p class="child-name">{{ $report['child']['name'] }}</p>
                <div class="muted">Age: {{ $report['child']['age'] }} years</div>
                <div class="muted">Diagnosis: {{ $report['child']['diagnosis'] }}</div>
                <div class="muted">Live updated: {{ $report['live_updated_at'] }}</div>
            </div>
        </div>

        <div class="status-pill">● {{ $report['live_status'] }}</div>
    </div>

    <div class="filters">
        <a href="{{ route('parents.report', ['period' => 'week']) }}" class="{{ $period === 'week' ? 'active' : '' }}">
            Weekly
        </a>
        <a href="{{ route('parents.report', ['period' => 'month']) }}" class="{{ $period === 'month' ? 'active' : '' }}">
            Monthly
        </a>
    </div>

    <div class="card alert-card">
        <div class="row-between">
            <h2 class="section-title">Critical Summary</h2>
            <span class="badge-red">{{ $report['episodes_count'] }} Episodes</span>
        </div>

        <div class="summary-text">
            Current condition:
            {{ $report['live_status'] === 'Panic Episode' ? 'High Risk' : 'Needs Monitoring' }}
        </div>

        <div class="small-note">{{ $report['episode_trend_text'] }}</div>

        <div class="metrics-grid">
            <div class="metric-box">
                <div class="metric-label">Total Episodes</div>
                <div class="metric-value">{{ $report['episodes_count'] }}</div>
                <div class="metric-sub">Previous period: {{ $report['previous_episodes_count'] }}</div>
            </div>

            <div class="metric-box">
                <div class="metric-label">Avg Episode Duration</div>
                <div class="metric-value">{{ $report['avg_episode_duration'] }}m</div>
                <div class="metric-sub">Longest: {{ $report['longest_episode_duration'] }} min</div>
            </div>
        </div>
    </div>

    <div class="card warning-card">
        <div class="row-between">
            <h3 class="section-title">Medical Recommendation</h3>
            <span class="badge-yellow">Important</span>
        </div>

        <ul class="list-clean">
            @foreach($report['medical_recommendations'] as $recommendation)
                <li>{{ $recommendation }}</li>
            @endforeach
        </ul>
    </div>

    <div class="metrics-grid" style="margin-bottom: 14px;">
        <div class="card metric-box">
            <div class="metric-label">Average Heart Rate</div>
            <div class="metric-value">{{ $report['avg_heart_rate'] }}</div>
            <div class="metric-sub">
                Peak: {{ $report['peak_heart_rate'] }} | Min: {{ $report['min_heart_rate'] }}
            </div>
        </div>

        <div class="card metric-box">
            <div class="metric-label">Average Steps</div>
            <div class="metric-value">{{ $report['avg_steps'] }}</div>
            <div class="metric-sub">Safe-zone exits: {{ $report['safe_zone_breaches'] }}</div>
        </div>
    </div>

    <div class="card chart-card">
        <h3 class="section-title">Episode Trend</h3>
        <canvas id="episodesChart"></canvas>
    </div>

    <div class="card chart-card">
        <h3 class="section-title">Heart Rate Trend</h3>
        <canvas id="heartRateChart"></canvas>
    </div>

    <div class="card">
        <h3 class="section-title">Episode Details</h3>

        @foreach($report['episodes'] as $episode)
            <div class="episode-card">
                <div class="episode-head">
                    <div class="episode-title">{{ $episode['status'] }}</div>
                    <span class="badge-blue">{{ $episode['date'] }}</span>
                </div>

                <div class="episode-meta">Time: {{ $episode['time'] }}</div>
                <div class="episode-meta">Location: {{ $episode['location'] }}</div>
                <div class="episode-meta">Duration: {{ $episode['duration'] }}</div>
                <div class="episode-meta">Heart rate: {{ $episode['heart_rate'] }} BPM</div>
                <div class="episode-meta">Trigger: {{ $episode['trigger'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <h3 class="section-title">Alert History</h3>
        <ul class="list-clean">
            @foreach($report['alerts'] as $alert)
                <li>{{ $alert }}</li>
            @endforeach
        </ul>
    </div>

    <a class="download-btn" href="{{ route('parents.report.download-pdf', ['period' => $period]) }}">
        Download PDF Report
    </a>
</div>

<script>
    const labels = @json($report['chart_labels']);
    const episodesData = @json($report['chart_episodes']);
    const heartRateData = @json($report['chart_heart_rate']);

    const commonGridColor = 'rgba(148, 163, 184, 0.15)';
    const commonTickColor = '#64748b';

    new Chart(document.getElementById('episodesChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Episodes',
                data: episodesData,
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245, 158, 11, 0.14)',
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointHoverRadius: 5,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    padding: 10,
                    cornerRadius: 10
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: commonTickColor
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: commonGridColor
                    },
                    ticks: {
                        stepSize: 1,
                        color: commonTickColor
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('heartRateChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Heart Rate',
                data: heartRateData,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.12)',
                fill: true,
                tension: 0.35,
                pointRadius: 4,
                pointHoverRadius: 5,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    padding: 10,
                    cornerRadius: 10
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: commonTickColor
                    }
                },
                y: {
                    beginAtZero: false,
                    grid: {
                        color: commonGridColor
                    },
                    ticks: {
                        color: commonTickColor
                    }
                }
            }
        }
    });
</script>
</body>
</html>