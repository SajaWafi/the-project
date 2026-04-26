<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Doctors</title>

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

body {
    background: #ffffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.phone {
    width: 100%;
    max-width: 360px;
    max-height: 800px;
    background: url('{{ asset('pics/bg.png') }}') no-repeat;
    background-position: left;
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 12px 30px rgba(0,0,0,0.35);
}

.content {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.header {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px 16px 14px;
}

.back-btn {
    position: absolute;
    left: 12px;
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    color: #1d567e;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    text-decoration: none;
}

.back-btn svg {
    width: 22px;
    height: 22px;
    display: block;
}

.title {
    font-size: 28px;
    font-weight: 800;
    color: #1f567f;
}

.logo {
    position: absolute;
    right: 12px;
    width: 38px;
    height: 38px;
    object-fit: contain;
}

.notice-box {
    margin: 0 12px 10px;
    background: #fff4d9;
    border: 1px solid #f0cf7a;
    color: #8a5a00;
    border-radius: 14px;
    padding: 12px;
    font-size: 13px;
    line-height: 1.4;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

.notice-title {
    font-weight: 700;
    margin-bottom: 4px;
}

.list {
    padding: 10px;
    overflow-y: auto;
    height: 580px;
}

.card {
    background: #C3EDE5;
    border-radius: 20px;
    padding: 14px;
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}

.card img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
}

.card-info {
    margin-left: 12px;
    flex: 1;
}

.name {
    font-weight: 700;
    color: #1f567f;
}

.specialty {
    font-size: 14px;
    color: #333;
}

.actions {
    margin-top: 8px;
    display: flex;
    gap: 10px;
}

.btn-icon {
    width: 70px;
    height: 36px;
    border-radius: 25px;
    background: white;
    border: none;
    display: flex;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    color: #000000;
    font-size: 12px;
    cursor: pointer;
}

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

.nav-item.active {
    background: rgba(255,255,255,0.2);
    color: #ffffff;
    transform: translateY(-2px);
}
</style>
</head>

<body>

<div class="phone">

    <div class="content">
        <div class="header">
            <a href="{{ url()->previous() }}" class="back-btn" aria-label="Back">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M15 5L8 12L15 19"
                          stroke="currentColor"
                          stroke-width="2.2"
                          stroke-linecap="round"
                          stroke-linejoin="round"/>
                </svg>
            </a>

            <div class="title">Request</div>

            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
        </div>

        <div class="notice-box">
            <div class="notice-title">Appointment Updated</div>
            <div>The doctor has changed the appointment time. Please review the request and confirm your response.</div>
        </div>

        <div class="list">
            @forelse($requests as $request)
                <div class="card">
                    <img src="{{ $request->doctor->user->profile_image ? asset('storage/' . $request->doctor->user->profile_image) : asset('images/default-user.png') }}">

                    <div class="card-info">
                        <div class="name">
                            Dr. {{ $request->doctor->user->first_name ?? '' }} {{ $request->doctor->user->last_name ?? '' }}
                        </div>

                        <div class="specialty">Is sending request</div>

                        <div class="actions">
                            <form action="{{ route('parents.requests.accept', $request->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn-icon" type="submit">accept</button>
                            </form>

                            <form action="{{ route('parents.requests.reject', $request->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn-icon" type="submit">reject</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-info">
                        <div class="name">No requests</div>
                        <div class="specialty">There are no pending requests right now</div>
                    </div>
                </div>
            @endforelse   
        </div>

    </div>

</div>

</body>
</html>