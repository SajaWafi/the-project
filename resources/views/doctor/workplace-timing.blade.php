<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workplace</title>
    <style>
        /* ===== Reset ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* ===== Body ===== */
        body {
            background: #ffffffff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* ===== Phone frame ===== */
        .phone {
            width: 390px;
            height: 844px;
            background: url('{{ asset('images/bg.png') }}') no-repeat center center/cover;
            border-radius: 22px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }

        /* ===== Scroll area ===== */
        .content {
            height: 100%;
            overflow-y: auto;
            padding: 10px 14px 24px;
            position: relative;
        }

        .content::-webkit-scrollbar {
            width: 5px;
        }

        .content::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.12);
            border-radius: 10px;
        }

        /* ===== Decorative star ===== */
        .star {
            position: absolute;
            left: 105px;
            top: 173px;
            color: #f0d46a;
            font-size: 34px;
            line-height: 1;
            z-index: 1;
        }

        /* ===== Status bar ===== */
        .status-bar {
            height: 28px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 12px 0;
            font-size: 13px;
            font-weight: 700;
            color: #111;
            margin-bottom: 6px;
            position: relative;
            z-index: 2;
        }

        /* ===== Header ===== */
        .header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
            z-index: 2;
        }

        .back-btn {
            position: absolute;
            left: 0;
            top: 0;
            font-size: 30px;
            line-height: 1;
            color: #3d78ff;
            text-decoration: none;
        }

        .title {
            font-size: 28px;
            font-weight: 800;
            color: #1d567e;
            text-align: center;
        }

        .logo {
           position: absolute;
    right: 10px;
    width: 50px;
    height: 50px;
    object-fit: contain;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ===== Add button ===== */
        .add-btn {
            position: absolute;
            right: 0;
            top: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #3d78ff;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            line-height: 1;
        }
        .add-btn {
    position: absolute;
    right: 0;
    top: 0;
    transform: translateY(40px); /* 🔥 ينزل لتحت */
}

        /* ===== Workplace list ===== */
        .work-list {
            position: relative;
            z-index: 2;
        }

        /* ===== Single workplace card ===== */
        .work-item {
            position: relative;
            margin-bottom: 30px;
            padding: 0 6px;
        }

        /* ===== White label at top ===== */
        .place-chip {
            display: inline-block;
            background: #f8f6f2;
            color: #111;
            border-radius: 16px;
            padding: 8px 16px;
            font-size: 16px;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            margin-bottom: 14px;
        }

        /* ===== Right action buttons ===== */
        .actions {
            position: absolute;
            right: 0;
            top: 2px;
            display: flex;
            gap: 10px;
        }

        .action-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #c8d2fb;
            color: #3d78ff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 18px;
            cursor: pointer;
        }

        .action-btn svg {
            width: 16px;
            height: 16px;
        }

        /* ===== Info rows ===== */
        .info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            color: #111;
            font-size: 16px;
        }

        .info-icon {
            width: 18px;
            height: 18px;
            color: #3d78ff;
            flex-shrink: 0;
        }

        .info-icon svg {
            width: 100%;
            height: 100%;
        }
         .back-btn {
            position: absolute;
            left: 0;
            background: transparent;
            border: none;
            cursor: pointer;
            color: #2f80ed;
            padding: 6px;
        }

        .back-btn svg {
            width: 26px;
            height: 26px;
        }
        /* 🔥 زر عائم تحت */
.fab-btn {
    position: absolute;
    right: 16px;
    bottom: 20px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #3d78ff;
    color: #fff;
    font-size: 30px;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 20px rgba(61, 120, 255, 0.4);
    z-index: 5;
    transition: 0.2s;
}

/* تأثير ضغط */
.fab-btn:active {
    transform: scale(0.95);
}
    </style>
</head>
<body>
    <div class="phone">
        <div class="content">

            <div class="header">
                         <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

                <div class="title">Workplace</div>

                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="logo">
                </div>

                
            </div>

<div class="work-list">
    @forelse($workplaces as $workplace)
        <div class="work-item">
            <div class="place-chip">{{ $workplace->place_name }}</div>

            <div class="actions">
                <form action="{{ route('doctor.workplace.delete', $workplace->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn">×</button>
                </form>

                <a href="{{ route('doctor.edit-workplace', $workplace->id) }}" class="action-btn">
                    ✎
                </a>
            </div>

            <div class="info-row">
                <div class="info-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M12 20s6-5 6-10a6 6 0 1 0-12 0c0 5 6 10 6 10Z" stroke="currentColor" stroke-width="2"/>
                        <circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
                <div>{{ $workplace->place_name }}</div>
            </div>

            <div class="info-row">
                <div class="info-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 7v5l3 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    {{ $workplace->from_hour }}:{{ str_pad($workplace->from_minute, 2, '0', STR_PAD_LEFT) }}{{ $workplace->from_period }}
                    -
                    {{ $workplace->to_hour }}:{{ str_pad($workplace->to_minute, 2, '0', STR_PAD_LEFT) }}{{ $workplace->to_period }}
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <rect x="4" y="6" width="16" height="14" rx="3" stroke="currentColor" stroke-width="2"/>
                        <path d="M8 3v4M16 3v4M4 10h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    {{ is_array($workplace->days) ? implode(' - ', $workplace->days) : '' }}
                </div>
            </div>
        </div>
    @empty
        <div class="work-item" style="text-align:center;">
            <div class="place-chip">No workplaces yet</div>
        </div>
    @endforelse
</div>
        </div>
        <a href="{{ route('doctor.add.workplace') }}" class="fab-btn">+</a>
    </div>
</body>
</html>