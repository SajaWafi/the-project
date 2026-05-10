<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Taif</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #1d567e;
            --sidebar-width: 260px;
            --bg-light: #f4f7fa;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        body { display: flex; background: var(--bg-light); min-height: 100vh; direction: ltr; }

        /* Sidebar Style */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary-color);
            color: white;
            padding: 20px;
            position: fixed;
            height: 100%;
            left: 0;
        }

        .sidebar h2 { font-size: 24px; margin-bottom: 30px; text-align: center; border-bottom: 1px solid #ffffff33; padding-bottom: 10px; }

        .sidebar-menu { list-style: none; }

        .sidebar-menu li { margin-bottom: 15px; }

        .sidebar-menu a {
            color: #d1d5db;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: #ffffff22;
            color: white;
        }

        .sidebar-menu span { margin-left: 12px; }

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

<div class="sidebar">
    <h2>Taif Project</h2>
    <ul class="sidebar-menu">
        <li><a href="#" class="active">📊 <span>Dashboard</span></a></li>
        <li><a href="#">🧑‍⚕️ <span>Manage Doctors</span></a></li>
        <li><a href="#">👨‍👩‍👧 <span>Manage Parents</span></a></li>
        <li><a href="#">👶 <span>Manage Children</span></a></li>
        <li><a href="#">🔗 <span>Linking Requests</span></a></li>
        <li><a href="#">⚠️ <span>Complaints</span></a></li>
        <li><a href="#">⚙️ <span>Settings</span></a></li>
    </ul>
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
        <h3>Weekly New Registrations</h3>
        <canvas id="registrationChart"></canvas>
    </div>
    
    <div class="chart-box">
        <h3>User Distribution</h3>
        <canvas id="userDistChart"></canvas>
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
            backgroundColor: '#1d567e',
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
</script>

</body>
</html>