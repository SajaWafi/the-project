<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect Bracelet - Taif</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { min-height: 100vh; display: flex; justify-content: center; align-items: center; background: #edf1f4; font-family: Arial, sans-serif; padding: 20px; }
        .mobile-screen { width: 390px; max-width: 100%; height: 844px; position: relative; overflow: hidden; border-radius: 30px; background: #fff; box-shadow: 0 18px 40px rgba(0,0,0,0.14); }
        .mobile-screen::before { content: ""; position: absolute; inset: 0; background-image: url('{{ asset('images/bg.png') }}'); background-repeat: no-repeat; background-size: cover; opacity: 0.8; z-index: 0; }
        .content { position: relative; z-index: 1; padding: 20px; display: flex; flex-direction: column; height: 100%; }
        .header-logo {
            position: absolute;
            right: 10px;
            width: 50px;
            height: 50px;
            object-fit: contain;
        }
        /* Header */
        .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 40px; margin-top: 10px; }
        .back-btn { background: none; border: none; font-size: 20px; color: #3b82f6; cursor: pointer; text-decoration: none; display: flex; align-items: center; }
        .title { font-size: 20px; font-weight: 700; color: #1e3a8a; }
        .invisible { width: 20px; } /* لوزن المنتصف */

        /* Card */
        .status-card { background: rgba(255, 255, 255, 0.9); border-radius: 20px; padding: 30px 20px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        
        .icon-circle { width: 80px; height: 80px; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 20px; }
        .icon-circle.disconnected { background: #fee2e2; color: #ef4444; }
        .icon-circle.connected { background: #dcfce3; color: #10b981; }
        .icon-circle svg { width: 40px; height: 40px; }

        .status-title { font-size: 22px; font-weight: bold; color: #333; margin-bottom: 10px; }
        .status-desc { font-size: 14px; color: #666; margin-bottom: 25px; line-height: 1.5; }
        .bracelet-id-badge { display: inline-block; background: #f3f4f6; padding: 8px 15px; border-radius: 10px; font-family: monospace; font-size: 16px; color: #374151; font-weight: bold; margin-bottom: 20px;}

        /* Form */
        .input-group { margin-bottom: 20px; text-align: left; }
        .input-label { display: block; font-size: 14px; color: #4b5563; margin-bottom: 8px; font-weight: bold; }
        .input-field { width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #d1d5db; font-size: 16px; outline: none; transition: border 0.3s; }
        .input-field:focus { border-color: #3b82f6; }

        .btn { width: 100%; padding: 16px; border-radius: 14px; font-size: 16px; font-weight: bold; color: #fff; border: none; cursor: pointer; transition: opacity 0.3s; }
        .btn:active { opacity: 0.8; }
        .btn-connect { background: #3b82f6; }
        .btn-disconnect { background: #ef4444; }

        .alert { padding: 12px; border-radius: 10px; font-size: 14px; margin-bottom: 15px; text-align: center; }
        .alert-success { background: #dcfce3; color: #10b981; }
        .alert-error { background: #fee2e2; color: #ef4444; }
    </style>
</head>
<body>

    <div class="mobile-screen">
        <div class="content">
            
            <div class="header">
                <a href="{{ route('profile') ?? '#' }}" class="back-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                </a>
                <div class="title">Smart Bracelet</div>
                <img src="{{ asset('images/logo.png') }}" alt="Taif Logo" class="header-logo">
                <div class="invisible"></div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <div class="status-card">
                @if($child && $child->is_bracelet_connected)
                    <div class="icon-circle connected">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0M1.42 9a16 16 0 0 1 21.16 0M8.53 16.11a6 6 0 0 1 6.95 0M12 20h.01"/></svg>
                    </div>
                    <div class="status-title">Device Connected</div>
                    <div class="status-desc">Your child's smart bracelet is actively tracking and sending data.</div>
                    
                    <div class="bracelet-id-badge">ID: {{ $child->bracelet_id }}</div>

                    <form action="{{ route('parents.bracelet.disconnect') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-disconnect">Disconnect Device</button>
                    </form>
                @else
                    <div class="icon-circle disconnected">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1.42 9a16 16 0 0 1 21.16 0M5 12.55a11 11 0 0 1 14.08 0M8.53 16.11a6 6 0 0 1 6.95 0M12 20h.01M3 3l18 18"/></svg>
                    </div>
                    <div class="status-title">No Device Found</div>
                    <div class="status-desc">Connect a smart bracelet to start monitoring your child's activities and vital signs.</div>

                    <form action="{{ route('parents.bracelet.connect') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <label class="input-label">Bracelet Serial Number (ID)</label>
                            <input type="text" name="bracelet_id" class="input-field" placeholder="e.g. TAIF-BR-001" required>
                        </div>
                        <button type="submit" class="btn btn-connect">Connect Device</button>
                    </form>
                @endif
            </div>

        </div>
    </div>

</body>
</html>