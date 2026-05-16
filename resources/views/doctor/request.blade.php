<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Request</title>

<style>

.header-left {
            display: flex;
            align-items: center;
            gap: 10px;
}

.header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
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
        
body {
    background:#111;
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
    text-align:center;
    font-size:28px;
    font-weight:800;
    color:#1f567f;
    padding:20px;
}
  .logo {
            position: absolute;
            right: 0;
            width: 50px;
            height: 34px;
        }
/* doctor cards */
.list {
    padding:10px;
    overflow-y:auto;
    height:580px;
}

.card {
    background:#C3EDE5;
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
    width:70px;
    height:36px;
    border-radius: 25px;
    background:white;
    display:flex;
    justify-content:center;
    align-items:center;
    text-decoration:none;
    color:#48C9B0;
    font-size:12px;
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

<body>

<div class="phone">
    <div class="content">
        <div class="header">
            <div class="header-left">
                <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>   
                <div class="title">Requests</div>
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo">
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="list">
            @forelse($requests as $request)
                <div class="card">
                    @php
                        // 1. نتأكدوا أولاً لو الأهل عندهم صورة مخزنة في قاعدة البيانات
                        $userPhoto = $request->parentProfile?->user?->profile_image;

                        // 2. لو الصورة موجودة، نطلعوا مسارها من الـ storage، ولو مش موجودة نستخدموا الصورة الافتراضية
                        // لاحظ تم تعديل الامتداد إلى .png بدل .php
                        $displayImage = $userPhoto 
                                        ? asset('storage/' . $userPhoto) 
                                        : asset('images/default-user.png'); 
                    @endphp

                    <img src="{{ $displayImage }}" alt="Profile Image" style="object-fit: cover;">

                    <div class="card-info">
                        <div class="name">
                            {{ $request->parentProfile?->user?->first_name ?? 'ولي أمر' }} {{ $request->parentProfile?->user?->last_name ?? '' }}
                        </div>
                        
                        @if($request->status == 'pending')
                            <div class="specialty" style="color: #030201;">Waiting for response ⏳</div>
                        @elseif($request->status == 'accepted')
                            <div class="specialty" style="color: #021008;">Request Accepted ✔️</div>
                        @elseif($request->status == 'rejected')
                            <div class="specialty" style="color: #070303;">Request Rejected ❌</div>
                        @endif

                        <div class="actions">
                            @if($request->status == 'pending')
                                <form action="{{ route('doctor.request.cancel', $request->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon" style="border: none; cursor: pointer; color:#e74c3c;">
                                        Cancel Request
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div> @empty
                <div class="card">
                    <div class="card-info">
                        <div class="name" style="text-align: center; width: 100%;">No requests sent yet</div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

</body>

</html>