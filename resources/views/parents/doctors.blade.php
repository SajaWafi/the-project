<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-straight/css/uicons-solid-straight.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Doctors</title>

<style>
body {
    background:#f9f9f9ff;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    font-family:Arial;
}

.phone {
    width: 100%;
    max-width: 360px; /* هذا المهم */
    max-height: 800px;
    background: url('{{ asset('pics/bg.png') }}') no-repeat;
    background-position: left;
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 12px 30px rgba(0,0,0,0.35);
}

/* header */
.header {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 22px;
    font-weight: 800;
    color: #1f567f;
    margin-top: 15px;
    margin-bottom: 20px;
}
.header-logo {
    position: absolute;
    right: 10px;
    width: 50px;
    height: 50px;
    object-fit: contain;
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

.title {
    text-align: center;
}

/* doctor cards */
.list {
    padding:10px;
    overflow-y:auto;
    height:580px;
}

.card {
    background:#a8d3cc;
    border-radius:20px;
    padding:14px;
    display:flex;
    align-items:center;
    margin-bottom:12px;
}

.card img {
    width:70px;
    height:70px;
    border-radius:50%;
    object-fit:cover;
}

.card-info {
    margin-left:12px;
    flex:1;
}

.name {
    font-weight:700;
    color:#1f567f;
}

.specialty {
    font-size:14px;
    color:#333;
}

.actions {
    margin-top:8px;
    display:flex;
    gap:10px;
}

.btn-icon {
    width:36px;
    height:36px;
    border-radius:50%;
    background:white;
    display:flex;
    justify-content:center;
    align-items:center;
    text-decoration:none;
    color:#1f567f;
    font-size:18px;
}

/* navbar */
.bottom-nav {
    position: sticky;
    bottom: 0;
    margin-top: auto;
    background: #2f80ed;
    border-radius: 24px 24px 0 0;
    height: 64px;
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
    color: rgba(255,255,255,0.6);
    transition: 0.2s;
}

.nav-svg {
    width: 22px;
    height: 22px;
}

/* 🔥 الحالة النشطة */
.nav-item.active {
    background: rgba(255,255,255,0.2);
    color: #ffffff;
    transform: translateY(-2px);
}

</style>
</head>

<body>

<div class="phone">

    <div class="header">
        <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <div class="title">Doctors</div>
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="header-logo">
    </div>

    <div class="list">
        @foreach($doctors as $doc)
        <div class="card">
            <img src="{{ asset('pics/bg.png') }}">

            <div class="card-info">
                <div class="name">{{ $doc['name'] }}</div>
                <div class="specialty">{{ $doc['specialty'] }}</div>

                <div class="actions">
                    <a href="{{ route('parents.doctor-profile', $doc['id']) }}" class="btn-icon">
                        <i class="fi fi-sr-user"></i>
                    </a>

                    <a href="{{ route('parents.chat', $doc['id']) }}" class="btn-icon">
                        <i class="fi fi-ss-messages"></i>
                    </a>           
                </div>
            </div>
        </div>
        @endforeach
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

                <a href="{{ route('parents.alerts') }}" class="nav-item {{ request()->routeIs('parents.alerts') ? 'active' : '' }}">
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

                <a href="{{ route('parents.report') }}" class="nav-item {{ request()->routeIs('parents.report') ? 'active' : '' }}">
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

</body>
</html>