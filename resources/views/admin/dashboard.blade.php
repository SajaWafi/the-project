<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Admin Dashboard - Taif') }}</title>

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
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            top: 0;
            inset-inline-start: 0; /* 💡 دعم الاتجاهين */
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
            text-align: start; /* 💡 دعم الاتجاهين */
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
            margin-inline-end: 12px; /* 💡 دعم الاتجاهين */
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-inline-start: var(--sidebar-width); /* 💡 دعم الاتجاهين */
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
            border-inline-start: 4px solid var(--primary-color); /* 💡 دعم الاتجاهين */
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

        .charts-container2 {
            display: grid;
            grid-template-columns: 1fr 2fr;
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
                margin-inline-start: 75px; /* 💡 دعم الاتجاهين */
                width: calc(100% - 75px);
            }

            .charts-container {
                grid-template-columns: 1fr;
            }
        }

        #autismLevelChart {
            height: 100vh;
            max-height: 350px; 
            margin: 0 auto;    
        }

        #userDistChart{
            height: 100vh;  
            max-height: 350px; 
            margin: 0 auto;           
        }
        #complaintsChart{
            height: 100vh;  
            max-height: 350px; 
            width: 100%;
            margin: 0 auto;           
        }

        #registrationChart{
            height: 100vh;  
            max-height: 350px;
            width: 100%;
            margin: 0 auto;           
        }

        #approvalChart {
            height: 100vh;
            max-height: 350px;
            width: 100%;
            margin: 0 auto;
        }

        #appointmentsChart {
            height: 100vh;
            max-height: 350px;
            width: 100%;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    @include('admin.partials.sidebar')

    <div class="main-content">

        <div class="header">
            <h1>{{ __('Admin Overview') }}</h1>
            <p>{{ __('System performance and management portal for Taif project') }}</p>
        </div>

        <div class="stats-grid">

            <div class="stat-card">
                <div class="stat-label">{{ __('Total Doctors') }}</div>
                <div class="stat-number">{{ $doctorsCount ?? 0 }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">{{ __('Total Parents') }}</div>
                <div class="stat-number">{{ $parentsCount ?? 0 }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">{{ __('Total Children') }}</div>
                <div class="stat-number">{{ $childrenCount ?? 0 }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">{{ __('Pending Requests') }}</div>
                <div class="stat-number">{{ $pendingRequestsCount ?? 0 }}</div>
            </div>

            <div class="stat-card" style="border-inline-start-color: #ef4444;">
                <div class="stat-label">{{ __('New Complaints') }}</div>
                <div class="stat-number">{{ array_sum($complaintsData ?? []) }}</div>
            </div>

        </div>

        <div class="charts-container">

            <div class="chart-box">
                <h3>{{ __('Doctors Registrations') }}</h3>
                <canvas id="registrationChart"></canvas>
            </div>

            <div class="chart-box">
                <h3>{{ __('User Distribution') }}</h3>
                <canvas id="userDistChart"></canvas>
            </div>

        </div>

        <div class="charts-container2">
            <div class="chart-box">
                <h3>{{ __('Children by Autism Level') }}</h3>

                <div class="chart-container">
                    <canvas id="autismLevelChart"></canvas>
                </div>
            </div>

            <div class="chart-box">
                <h3>{{ __('Weekly Complaints Overview') }}</h3>
                <div class="chart-container">
                    <canvas id="complaintsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="charts-container">

            <div class="chart-box" style="margin-bottom: 30px;">
                <h3>{{ __('Appointments Booked') }}</h3>
                <canvas id="appointmentsChart"></canvas>
            </div>

            <div class="chart-box">
                <h3>{{ __('Doctor Approvals') }}</h3>
                <canvas id="approvalChart"></canvas>
            </div>
            
        </div>

    </div>

@php
    $complaintsChartData = $complaintsData ?? [0,0,0,0,0,0,0];
@endphp

<script>
    // استقبال البيانات من الكنترولر
    const registrationData = @json($doctorRegistrationsData);
    const labelsData = @json($daysLabels);

    // doctor registration chart
    const ctx1 = document.getElementById('registrationChart').getContext('2d');

    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: labelsData, 
            datasets: [{
                label: '{{ __('New Registrations') }}',
                data: registrationData, 
                borderColor: '#2c5282',
                backgroundColor: 'rgba(44, 82, 130, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#2c5282',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // User Distribution
    const ctx2 = document.getElementById('userDistChart').getContext('2d');

    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['{{ __('Doctors') }}', '{{ __('Parents') }}'],
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
            labels: ['{{ __('Mild') }}', '{{ __('Moderate') }}', '{{ __('Severe') }}'],
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
    const localeString = '{{ app()->getLocale() == "ar" ? "ar-EG" : "en-US" }}'; // 💡 تحديد اللغة للأيام تلقائياً

    for (let i = 6; i >= 0; i--) {
        const d = new Date();
        d.setDate(d.getDate() - i);
        last7Days.push(
            d.toLocaleDateString(localeString, {
                weekday: 'short'
            })
        );
    }

    const complaintsData = @json($complaintsData ?? []);

    new Chart(ctxComplaints, {
        type: 'bar',
        data: {
            labels: last7Days,
            datasets: [{
                label: '{{ __('Complaints') }}',
                data: complaintsData,
                backgroundColor: '#ef4444',
                borderRadius: 5
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

    // Doctor Approvals Chart
    const approvalData = @json($approvalStats ?? []);

    const pendingCount = approvalData['pending'] || 0;
    const approvedCount = approvalData['approved'] || 0;
    const rejectedCount = approvalData['rejected'] || 0;

    const ctxApproval = document.getElementById('approvalChart').getContext('2d');

    new Chart(ctxApproval, {
        type: 'doughnut',
        data: {
            labels: ['{{ __('Pending') }}', '{{ __('Approved') }}', '{{ __('Rejected') }}'],
            datasets: [{
                label: '{{ __('Doctor Approvals') }}',
                data: [pendingCount, approvedCount, rejectedCount],
                backgroundColor: [
                    '#f6ad55', 
                    '#48bb78', 
                    '#f56565'  
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });

    // Appointments Chart
    const apptData = @json($appointmentsChartData);
    const apptLabels = @json($daysLabels);

    const ctxAppt = document.getElementById('appointmentsChart').getContext('2d');

    new Chart(ctxAppt, {
        type: 'line',
        data: {
            labels: apptLabels, 
            datasets: [{
                label: '{{ __('New Appointments') }}',
                data: apptData, 
                borderColor: '#38a169', 
                backgroundColor: 'rgba(56, 161, 105, 0.1)',
                fill: true,
                tension: 0.4, 
                borderWidth: 3,
                pointBackgroundColor: '#38a169',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false 
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1 
                    }
                }
            }
        }
    });
</script>

</body>
</html>