<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Health Report PDF') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.8;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
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
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
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
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
        }

        table.data th {
            background: #eff6ff;
            color: #1d4ed8;
        }

        ul {
            margin: 0;
            padding-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 18px;
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
        <div class="title">{{ __('TAIF - Health Report') }}</div>
        <div class="subtitle">
            {{ __('Child Name:') }} {{ $report['child']['name'] }} |
            {{ __('Age:') }} {{ $report['child']['age'] }} {{ __('years') }} |
            {{ __('Report Type:') }} {{ $period === 'week' ? __('Weekly') : __('Monthly') }} |
            {{ __('Issue Date:') }} {{ now()->format('Y-m-d') }}
        </div>
    </div>

    <div class="box danger">
        <div class="section-title">{{ __('General Summary') }}</div>
        <strong>{{ __('Current Status:') }}</strong> {{ __($report['live_status']) }}<br>
        <strong>{{ __('Total Episodes:') }}</strong> {{ $report['episodes_count'] }}<br>
        <strong>{{ __('Trend:') }}</strong> {{ __($report['episode_trend_text']) }}
    </div>

    <table class="grid">
        <tr>
            <td>
                <div class="metric">
                    <div class="metric-label">{{ __('Average Heart Rate') }}</div>
                    <div class="metric-value" dir="ltr" style="text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}">{{ $report['avg_heart_rate'] }} {{ __('BPM') }}</div>
                    <div>{{ __('Peak:') }} <span dir="ltr">{{ $report['peak_heart_rate'] }}</span></div>
                    <div>{{ __('Minimum:') }} <span dir="ltr">{{ $report['min_heart_rate'] }}</span></div>
                </div>
            </td>
            <td>
                <div class="metric">
                    <div class="metric-label">{{ __('Activity') }}</div>
                    <div class="metric-value">{{ $report['avg_steps'] }}</div>
                    <div>{{ __('Average steps') }}</div>
                    <div>{{ __('Safe-zone exits:') }} {{ $report['safe_zone_breaches'] }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="box warning">
        <div class="section-title">{{ __('Medical Recommendations') }}</div>
        <ul>
            @foreach($report['medical_recommendations'] as $item)
                <li>{{ __($item) }}</li>
            @endforeach
        </ul>
    </div>

    <div class="box">
        <div class="section-title">{{ __('Smart Insights') }}</div>
        <ul>
            @foreach($report['trigger_insights'] as $item)
                <li>{{ __($item) }}</li>
            @endforeach
        </ul>
    </div>

    <div class="box">
        <div class="section-title">{{ __('Episode Details') }}</div>
        <table class="data">
            <thead>
                <tr>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Time') }}</th>
                    <th>{{ __('Location') }}</th>
                    <th>{{ __('Duration') }}</th>
                    <th>{{ __('Heart Rate') }}</th>
                    <th>{{ __('Trigger') }}</th>
                    <th>{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report['episodes'] as $episode)
                    <tr>
                        <td dir="ltr" style="text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}">{{ $episode['date'] }}</td>
                        <td dir="ltr" style="text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}">{{ $episode['time'] }}</td>
                        <td>{{ __($episode['location']) }}</td>
                        <td>{{ __($episode['duration']) }}</td>
                        <td dir="ltr" style="text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}">{{ $episode['heart_rate'] }}</td>
                        <td>{{ __($episode['trigger']) }}</td>
                        <td>{{ __($episode['status']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="box">
        <div class="section-title">{{ __('Alert History') }}</div>
        <ul>
            @foreach($report['alerts'] as $alert)
                <li>{{ __($alert) }}</li>
            @endforeach
        </ul>
    </div>

    <div class="footer">
        {{ __('This report was generated by the TAIF monitoring system.') }}
    </div>
</body>
</html>