<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports History</title>

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
            height: 844px;
            max-width: 100%;
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
            opacity: 0.9;
            z-index: 0;
            pointer-events: none;
        }

        .content {
            position: relative;
            z-index: 1;
            min-height: 100%;
            padding: 16px 14px 24px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .top-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }


        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin-bottom: 24px;
            min-height: 44px;
        }

        .back-btn {
            position: absolute;
            left: 0;
            border: none;
            background: transparent;
            cursor: pointer;
            color: #2f80ed;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-btn svg {
            width: 24px;
            height: 24px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 800;
            color: #1f5b87;
        }

        .logo {
            position: absolute;
            right: 0;
            width: 100px;
            height: 100px;
            object-fit: contain;
        }

        .reports-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .report-card {
            text-decoration: none;
            background: #fff;
            border-radius: 14px;
            padding: 14px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 12px rgba(0,0,0,0.06);
        }

        .report-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .report-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2f80ed;
        }

        .report-icon svg {
            width: 18px;
            height: 18px;
        }

        .report-title {
            font-size: 15px;
            font-weight: 500;
            color: #202020;
        }

        .report-arrow {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .report-arrow svg {
            width: 18px;
            height: 18px;
        }

        .empty-state {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-top: 40px;
        }

        @media (max-width: 480px) {
            body {
                padding: 0;
                background: #fff;
            }

            .mobile-screen {
                width: 100%;
                height: 100vh;
                max-height: 100vh;
                border-radius: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    <div class="mobile-screen">
        <div class="content">

            <div class="top-bar">


                <div class="top-right">
                    <div class="status-icon">

                    </div>

                </div>
            </div>

            <div class="header">
                <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <div class="page-title">Reports History</div>

                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Taif">
            </div>

            @if(count($reports))
                <div class="reports-list">
                    @foreach($reports as $report)
                        <a href="{{ route('reports.details', $report['id']) }}" class="report-card">
                            <div class="report-left">
                                <div class="report-icon">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <rect x="5" y="4" width="14" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/>
                                        <path d="M9 9v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                        <path d="M12 7v8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                        <path d="M15 11v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    </svg>
                                </div>

                                <div class="report-title">{{ $report['title'] }}</div>
                            </div>

                            <div class="report-arrow">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M9 5L16 12L9 19"
                                          stroke="#e69a4b"
                                          stroke-width="2.2"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="empty-state">No reports yet.</div>
            @endif

        </div>
    </div>

</body>
</html>