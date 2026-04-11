<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Health Report PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.8;
        }

        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            color: #1d4ed8;
            margin-bottom: 4px;
        }

        .subtitle {
            color: #6b7280;
            font-size: 11px;
        }

        .box {
            border: 1px solid #dbe5f1;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 12px;
            background: #f8fbff;
        }

        .danger {
            background: #fff5f5;
            border-color: #fecaca;
        }

        .warning {
            background: #fff9eb;
            border-color: #fde68a;
        }

        .section-title {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #111827;
        }

        .grid {
            width: 100%;
            border-collapse: collapse;
        }

        .grid td {
            width: 50%;
            vertical-align: top;
            padding: 8px;
        }

        .metric {
            border: 1px solid #e5e7eb;
            background: #ffffff;
            border-radius: 8px;
            padding: 10px;
        }

        .metric-label {
            color: #6b7280;
            font-size: 11px;
        }

        .metric-value {
            font-size: 22px;
            font-weight: bold;
            color: #111827;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table.data th,
        table.data td {
            border: 1px solid #dbe5f1;
            padding: 8px;
            text-align: left;
        }

        table.data th {
            background: #eff6ff;
            color: #1d4ed8;
        }

        ul {
            margin: 0;
            padding-left: 18px;
        }

        .footer {
            margin-top: 20px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            color: #6b7280;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">TAIF - Health Report</div>
        <div class="subtitle">
            Child Name: {{ $report['child']['name'] }} |
            Age: {{ $report['child']['age'] }} years |
            Report Type: {{ $period === 'week' ? 'Weekly' : 'Monthly' }} |
            Issue Date: {{ now()->format('Y-m-d') }}
        </div>
    </div>

    <div class="box danger">
        <div class="section-title">General Summary</div>
        <strong>Current Status:</strong> {{ $report['live_status'] }}<br>
        <strong>Total Episodes:</strong> {{ $report['episodes_count'] }}<br>
        <strong>Trend:</strong> {{ $report['episode_trend_text'] }}
    </div>

    <table class="grid">
        <tr>
            <td>
                <div class="metric">
                    <div class="metric-label">Average Heart Rate</div>
                    <div class="metric-value">{{ $report['avg_heart_rate'] }} BPM</div>
                    <div>Peak: {{ $report['peak_heart_rate'] }}</div>
                    <div>Minimum: {{ $report['min_heart_rate'] }}</div>
                </div>
            </td>
            <td>
                <div class="metric">
                    <div class="metric-label">Activity</div>
                    <div class="metric-value">{{ $report['avg_steps'] }}</div>
                    <div>Average steps</div>
                    <div>Safe-zone exits: {{ $report['safe_zone_breaches'] }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="box warning">
        <div class="section-title">Medical Recommendations</div>
        <ul>
            @foreach($report['medical_recommendations'] as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>

    <div class="box">
        <div class="section-title">Smart Insights</div>
        <ul>
            @foreach($report['trigger_insights'] as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>

    <div class="box">
        <div class="section-title">Episode Details</div>
        <table class="data">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Duration</th>
                    <th>Heart Rate</th>
                    <th>Trigger</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report['episodes'] as $episode)
                    <tr>
                        <td>{{ $episode['date'] }}</td>
                        <td>{{ $episode['time'] }}</td>
                        <td>{{ $episode['location'] }}</td>
                        <td>{{ $episode['duration'] }}</td>
                        <td>{{ $episode['heart_rate'] }}</td>
                        <td>{{ $episode['trigger'] }}</td>
                        <td>{{ $episode['status'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="box">
        <div class="section-title">Alert History</div>
        <ul>
            @foreach($report['alerts'] as $alert)
                <li>{{ $alert }}</li>
            @endforeach
        </ul>
    </div>

    <div class="footer">
        This report was generated by the TAIF monitoring system.
    </div>
</body>
</html>