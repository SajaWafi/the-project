<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        /* ===== Reset ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* ===== Page background ===== */
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

        /* ===== Scrollable content ===== */
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
            left: -2px;
            top: 205px;
            color: #f0d46a;
            font-size: 28px;
            line-height: 1;
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
            margin-bottom: 18px;
            z-index: 2;
        }

        /* ===== Back button ===== */
        .back-btn {
            position: absolute;
            left: 0;
            top: 0;
            font-size: 30px;
            line-height: 1;
            color: #3d78ff;
            text-decoration: none;
        }

        /* ===== Page title ===== */
        .title {
            font-size: 28px;
            font-weight: 800;
            color: #1d567e;
            text-align: center;
        }

        /* ===== Logo ===== */
        .logo {
            position: absolute;
            right: 0;
            top: -2px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            overflow: hidden;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ===== Small category chip ===== */
        .section-chip {
            display: inline-block;
            background: #f7f4ef;
            color: #222;
            border-radius: 16px;
            padding: 6px 14px;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 14px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.06);
            position: relative;
            z-index: 2;
        }

        /* ===== Menu list ===== */
        .menu-list {
            display: flex;
            flex-direction: column;
            gap: 2px;
            position: relative;
            z-index: 2;
        }

        /* ===== Each row button ===== */
        .menu-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #111;
            padding: 12px 4px 12px 26px;
            border-radius: 14px;
            transition: all 0.15s ease;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.2);
        }

        .menu-item:active {
            transform: scale(0.985);
            background: rgba(255,255,255,0.32);
        }

        /* ===== Item text ===== */
        .menu-text {
            font-size: 18px;
            font-weight: 500;
            color: #111;
        }

        /* ===== Orange arrow ===== */
        .menu-arrow {
            font-size: 30px;
            line-height: 1;
            color: #ee943f;
            padding-right: 4px;
        }

/* ===== Delete text color ===== */
.delete-text {
    color: #ff3b3b;
}

/* ===== Overlay خلفية غامقة ===== */
.delete-overlay {
    position: absolute;
    inset: 0;
    background: rgba(120, 150, 200, 0.35);
    z-index: 20;
    opacity: 0;
    visibility: hidden;
    transition: 0.25s ease;
}

/* ===== صندوق الحذف ===== */
.delete-modal {
    position: absolute;
    left: 0;
    right: 0;
    bottom: -320px;
    background: #fff;
    border-top-left-radius: 28px;
    border-top-right-radius: 28px;
    padding: 26px 20px 34px;
    z-index: 25;
    text-align: center;
    transition: 0.3s ease;
    box-shadow: 0 -8px 20px rgba(0,0,0,0.12);
}

/* ===== لما يفتح ===== */
.delete-overlay.show {
    opacity: 1;
    visibility: visible;
}

.delete-modal.show {
    bottom: 0;
}

/* ===== عنوان الرسالة ===== */
.delete-modal-title {
    font-size: 24px;
    font-weight: 700;
    color: #111;
    margin-bottom: 10px;
}

/* ===== نص الرسالة ===== */
.delete-modal-text {
    font-size: 16px;
    color: #666;
    line-height: 1.45;
    margin-bottom: 18px;
}

/* ===== أزرار الرسالة ===== */
.delete-modal-actions {
    display: flex;
    justify-content: center;
    gap: 12px;
    align-items: center;
}

.cancel-delete-btn,
.confirm-delete-btn {
    min-width: 140px;
    height: 40px;
    border: none;
    border-radius: 20px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
}

.cancel-delete-btn {
    background: #c8ebe6;
    color: #2f80ed;
}

.confirm-delete-btn {
    background: #2f80ed;
    color: white;
}
    </style>
</head>

<body>
    <div class="phone">
    
    <!-- Overlay -->
    <div class="delete-overlay" id="deleteOverlay" onclick="closeDeleteModal()"></div>

    <!-- Delete Modal -->
    <div class="delete-modal" id="deleteModal">
        <div class="delete-modal-title">Delete Account</div>
        <div class="delete-modal-text">
            are you sure you want to delete account?
        </div>

        <div class="delete-modal-actions">
            <button type="button" class="cancel-delete-btn" onclick="closeDeleteModal()">Cancel</button>

            <form action="{{ route('doctor.delete.account') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="confirm-delete-btn">
                    <a href="{{ route('welcome.second') }}" class="confirm-delete-btn">Yes,Delete</a>
                </button>
            </form>
        </div>
    </div>
        <div class="content">

            <div class="header">
                <a href="{{ route('doctor.doctor-profile') }}" class="back-btn">‹</a>

                <div class="title">Settings</div>

                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="logo">
                </div>
            </div>

            <div class="section-chip">profile</div>

            <div class="menu-list">
                <a href="{{ route('doctor.edit-profile') }}" class="menu-item">
                    <span class="menu-text">Edit Profile</span>
                    <span class="menu-arrow">›</span>
                </a>

                <a href="{{ route('doctor.workplace.timing') }}" class="menu-item">
                    <span class="menu-text">Workplace And Timing</span>
                    <span class="menu-arrow">›</span>
                </a>
            </div>

            <div class="section-chip">Account</div>

            <div class="menu-list">
                <a href="{{ route('doctor.password') }}" class="menu-item">
                    <span class="menu-text">Change Password</span>
                    <span class="menu-arrow">›</span>
                </a>

                <a href="javascript:void(0)" class="menu-item delete-trigger" onclick="openDeleteModal()">
                    <div class="menu-left">
                        <div class="menu-text delete-text">Delete Account</div>
                    </div>
                    <div class="menu-arrow">›</div>
                </a>
            </div>

            <div class="section-chip">Notifications</div>

            <div class="menu-list">
                <a href="{{ route('doctor.alert') }}" class="menu-item">
                    <span class="menu-text">Alert and Vibration</span>
                    <span class="menu-arrow">›</span>
                </a>
            </div>

        </div>
    </div>
</body>
<script>
    function openDeleteModal() {
        document.getElementById('deleteOverlay').classList.add('show');
        document.getElementById('deleteModal').classList.add('show');
    }

    function closeDeleteModal() {
        document.getElementById('deleteOverlay').classList.remove('show');
        document.getElementById('deleteModal').classList.remove('show');
    }
</script>
</html>