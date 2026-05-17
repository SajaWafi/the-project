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
    <style>
        :root {
            --primary-color: #2c5282; 
            --sidebar-width: 260px;
            --bg-light: #f4f7fa;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        body { display: flex; background: var(--bg-light); min-height: 100vh; direction: ltr; }

        /* Sidebar Style */
:root {
    --sidebar-bg: #2c5282;
    --sidebar-text: #e2e8f0;
    --sidebar-active-bg: rgba(255, 255, 255, 0.15);
}

/* Sidebar Structure */
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

/* ضبط المساحة للمحتوى الرئيسي */
.admin-main-content {
    width: calc(100% - 260px);
    margin-left: 260px;
}

@media (max-width: 992px) {
    .admin-sidebar { width: 75px; }
    .admin-sidebar-title, .admin-sidebar-link span { display: none; }
    .admin-main-content { width: calc(100% - 75px); margin-left: 75px; }
}

    /* Main Content Style */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 30px;
        }

        .header { margin-bottom: 30px; }
        .header h1 { color: var(--primary-color); font-size: 28px; }
        .header p { color: #666; margin-top: 5px; }

        /* Stats Grid */
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

        .stat-number { font-size: 26px; font-weight: bold; color: var(--primary-color); margin-top: 5px; }
        .stat-label { font-size: 14px; color: #666; font-weight: 600; }

        /* Charts Section */
        .charts-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .chart-box {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin: 10px;
            height: 350px;
            position: relative;

            height: 400px; 
    display: flex;
    flex-direction: column;
    justify-content:间-between;
    position: relative;
        }

        .chart-box h3 { font-size: 18px; color: var(--primary-color); margin-bottom: 15px; }

        @media (max-width: 900px) {
            .charts-container { grid-template-columns: 1fr; }
            .sidebar { width: 70px; padding: 10px; }
            .sidebar h2, .sidebar span { display: none; }
            .main-content { margin-left: 70px; width: calc(100% - 70px); }
        }

    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="admin-sidebar-header">
            <img
                src="{{ asset('images/logo.png') }}"
                class="admin-sidebar-logo"
                alt="Logo"
            >

            <div class="admin-sidebar-title">
                TAIF PROJECT
            </div>
        </div>

        <div class="admin-sidebar-menu">
            <a
                href="{{ route('admin.dashboard') }}"
                class="admin-sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            >
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>

            <a
                href="{{ route('admin.doctors.index') }}"
                class="admin-sidebar-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}"
            >
                <i class="fas fa-user-md"></i>
                <span>Manage Doctors</span>
            </a>

            <a href="#" class="admin-sidebar-link">
                <i class="fas fa-users"></i>
                <span>Manage Parents</span>
            </a>

            <a href="{{ route('admin.children.index') }}" class="admin-sidebar-link">
                <i class="fas fa-child"></i>
                <span>Manage Children</span>
            </a>

            <a href="#" class="admin-sidebar-link">
                <i class="fas fa-link"></i>
                <span>Linking Requests</span>
            </a>

            <a href="{{ route('admin.complaints.index') }}" class="admin-sidebar-link">
                <i class="fas fa-exclamation-circle"></i>
                <span>Complaints</span>
            </a>

            <a href="#" class="admin-sidebar-link">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </div>
    </div>

<div class="main-content">
    <div class="header">
        <h1>Admin Overview</h1>
        <p>System performance and management portal for Taif project</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Doctors</div>
            <div class="stat-number">{{ $doctorsCount ?? 12 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Parents</div>
            <div class="stat-number">{{ $parentsCount ?? 45 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Children</div>
            <div class="stat-number">{{ $childrenCount ?? 38 }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pending Requests</div>
            <div class="stat-number">{{ $pendingRequestsCount ?? 5 }}</div>
        </div>
        <div class="stat-card" style="border-left-color: #ef4444;">
            <div class="stat-label">New Complaints</div>
            <div class="stat-number">3</div>
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
            <div class="chart-container" style="flex-grow: 1; position: relative;">
                <canvas id="complaintsChart"></canvas>
            </div>
        </div>

        <div class="chart-box">
            <h3>Children by Autism Level</h3>
            <div class="chart-container" style="flex-grow: 1; position: relative;">
                <canvas id="autismLevelChart"></canvas>
            </div>
        </div>

    </div>
</div>

<script>
    // bar chart: registration
const ctx1 = document.getElementById('registrationChart').getContext('2d');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
        datasets: [{
            label: 'New Users',
            data: [5, 10, 8, 15, 12, 20, 7], // بيانات تجريبية
            backgroundColor: '#2c5282',
            borderRadius: 5
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        }
    }
});

    // Doughnut Chart: User Distribution
    const ctx2 = document.getElementById('userDistChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Doctors', 'Parents'],
            datasets: [{
                data: [12, 45],
                backgroundColor: ['#35b8a6', '#eb9443']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    //pie chart: autism level
    const ctxAutism = document.getElementById('autismLevelChart').getContext('2d');
    new Chart(ctxAutism, {
        type: 'pie',
        data: {
            labels: ['Mild', 'Moderate', 'Severe'] ,
            datasets: [{
                data: [{{ $mild ?? 0 }}, {{ $moderate ?? 0 }}, {{ $severe ?? 0 }}],
                backgroundColor: [
                    '#35b8a6',
                    '#eb9443',
                    '#ef4444'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    const ctxComplaints = document.getElementById('complaintsChart').getContext('2d');

    //get last 7 days complaints count
    const last7Days = [];
    for (let i = 6; i >= 0; i--) {
        const d = new Date();
        d.setDate(d.getDate() - i);
        last7Days.push(d.toLocaleDateString('en-US', { weekday: 'short' }));
    }

    const fakeComplaintsData = [2, 5, 3, 8, 4, 10, 6]; 

    new Chart(ctxComplaints, {
        type: 'line',
        data: {
            labels: last7Days,
            datasets: [{
                label: 'Complaints',
                data: fakeComplaintsData,
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
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1d567e',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: 12,
                    ticks: { 
                        stepSize: 2,
                        color: '#718096'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#718096'
                    }
                }
            }
        }
    });
</script>

</body>
</html>