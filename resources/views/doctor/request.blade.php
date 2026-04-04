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
            left: 10px;
            font-size: 28px;
            color: #1d567e;
            text-decoration: none;
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

<div class="phone">

    <div class="content">
            <div class="header">
                <div class="header-left">
                    <a href="{{ url()->previous() }}" class="back-btn">‹</a>
                    <div class="title">Request</div>
                </div>
            </div>

    <div class="list">
        
        <div class="card">
            <img src="{{ asset('pics/bg.png') }}">

            <div class="card-info">
                <div class="name">Nawar Omar</div>
                <div class="specialty">waiting for accept the request</div>

                <div class="actions">
                    <!-- cancel -->
                    <button class="btn-icon">
                        <i class="fi fi-sr-user">cancel request</i>
                    </button>         
                </div>
            </div>
        </div>
      

    
</div>

</body>
</html>