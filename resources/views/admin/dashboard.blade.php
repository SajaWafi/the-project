<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Taif</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        :root {
            --primary-color: #2c5282;
            --sidebar-width: 260px;
            --bg-light: #f4f7fa;

            --sidebar-bg: #2c5282;
            --sidebar-text: #e2e8f0;
            --sidebar-active-bg: rgba(255, 255, 255, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            background: var(--bg-light);
            min-height: 100vh;
            direction: ltr;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .admin-sidebar-header {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-sidebar-logo {
            max-width: 70px;
            margin-bottom: 10px;
        }

        .admin-sidebar-title {
            color: white;
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .admin-sidebar-menu {
            margin-top: 1rem;
            text-align: left;
        }

        .admin-sidebar-link {
            display: flex;
            align-items: center;
            margin: 0.2rem 0.8rem;
            padding: 0.8rem 1.5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        .admin-sidebar-link:hover,
        .admin-sidebar-link.active {
            background-color: var(--sidebar-active-bg);
            color: white;
        }

        .admin-sidebar-link i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 30px;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            color: var(--primary-color);
            font-size: 28px;
        }

        .header p {
            color: #666;
            margin-top: 5px;
        }

        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-left: 4px solid var(--primary-color);
        }

        .stat-number {
            font-size: 26px;
            font-weight: bold;
            color: var(--primary-color);
            margin-top: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #666;
            font-weight: 600;
        }

        /* Charts */
        .charts-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .chart-box {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            height: 400px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .chart-box h3 {
            font-size: 18px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .chart-container {
            flex-grow: 1;
            position: relative;
        }

        @media (max-width: 992px) {

            .admin-sidebar {
                width: 75px;
            }

            .admin-sidebar-title,
            .admin-sidebar-link span {
                display: none;
            }

            .main-content {
                margin-left: 75px;
                width: calc(100% - 75px);
            }

            .charts-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    @include('admin.partials.sidebar')

    <div class="main-content">

        <div class="header">
            <h1>Admin Overview</h1>
            <p>System performance and management portal for Taif project</p>
        </div>

        <div class="stats-grid">

            <div class="stat-card">
                <div class="stat-label">Total Doctors</div>
                <div class="stat-number">{{ $doctorsCount ?? 0 }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Total Parents</div>
                <div class="stat-number">{{ $parentsCount ?? 0 }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Total Children</div>
                <div class="stat-number">{{ $childrenCount ?? 0 }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Pending Requests</div>
                <div class="stat-number">{{ $pendingRequestsCount ?? 0 }}</div>
            </div>

            <div class="stat-card" style="border-left-color: #ef4444;">
                <div class="stat-label">New Complaints</div>
                <div class="stat-number">{{ array_sum($complaintsData ?? []) }}</div>
            </div>

        </div>

        <div class="charts-container">

            <div class="chart-box">
                <h3>Doctors Registrations</h3>
                <canvas id="registrationChart"></canvas>
            </div>

            <div class="chart-box">
                <h3>User Distribution</h3>
                <canvas id="userDistChart"></canvas>
            </div>

        </div>

        <div class="charts-container">

            <div class="chart-box">
                <h3>Weekly Complaints Overview</h3>

                <div class="chart-container">
                    <canvas id="complaintsChart"></canvas>
                </div>
            </div>

            <div class="chart-box">
                <h3>Children by Autism Level</h3>

                <div class="chart-container">
                    <canvas id="autismLevelChart"></canvas>
                </div>
            </div>

        </div>

    </div>

@php
    $complaintsChartData = $complaintsData ?? [0,0,0,0,0,0,0];
@endphp

<script>

    // Registration Chart
    const ctx1 = document.getElementById('registrationChart').getContext('2d');

    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
            datasets: [{
                label: 'New Users',
                data: [5, 10, 8, 15, 12, 20, 7],
                backgroundColor: '#2c5282',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // User Distribution
    const ctx2 = document.getElementById('userDistChart').getContext('2d');

    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Doctors', 'Parents'],
            datasets: [{
                data: [{{ $doctorsCount ?? 0 }}, {{ $parentsCount ?? 0 }}],
                backgroundColor: ['#35b8a6', '#eb9443']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Autism Level Chart
    const ctxAutism = document.getElementById('autismLevelChart').getContext('2d');

    new Chart(ctxAutism, {
        type: 'pie',
        data: {
            labels: ['Mild', 'Moderate', 'Severe'],
            datasets: [{
                data: [
                    {{ $mild ?? 0 }},
                    {{ $moderate ?? 0 }},
                    {{ $severe ?? 0 }}
                ],
                backgroundColor: [
                    '#35b8a6',
                    '#eb9443',
                    '#ef4444'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Complaints Chart
    const ctxComplaints = document.getElementById('complaintsChart').getContext('2d');

    const last7Days = [];

    for (let i = 6; i >= 0; i--) {

        const d = new Date();

        d.setDate(d.getDate() - i);

        last7Days.push(
            d.toLocaleDateString('en-US', {
                weekday: 'short'
            })
        );
    }

  const complaintsData = @json($complaintsChartData);

    new Chart(ctxComplaints, {
        type: 'line',
        data: {
            labels: last7Days,
            datasets: [{
                label: 'Complaints',
                data: complaintsData,
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#ef4444',
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

</script>

</body>
</html>