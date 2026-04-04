<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #111;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .phone {
            width: 390px;
            height: 844px;
            background: url('{{ asset('pics/bg.png') }}') no-repeat;
            border-radius: 22px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
        }

        .bg-top {
            position: absolute;
            top: -40px;
            left: -40px;
            width: 180px;
            height: 140px;
            background: rgba(180, 212, 250, 0.35);
            border-radius: 50%;
            z-index: 0;
        }

        .bg-bottom-left {
            position: absolute;
            bottom: 80px;
            left: -60px;
            width: 220px;
            height: 180px;
            background: rgba(188, 233, 223, 0.35);
            border-radius: 50%;
            z-index: 0;
        }

        .bg-bottom-right {
            position: absolute;
            bottom: 130px;
            right: -20px;
            width: 80px;
            height: 80px;
            background: rgba(240, 229, 197, 0.35);
            border-radius: 50%;
            z-index: 0;
        }

        .status-bar {
            height: 28px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 10px 12px 0;
            font-size: 13px;
            font-weight: 700;
            color: #111;
            position: relative;
            z-index: 2;
        }

        .header {
            height: 62px;
            background: #c9defa;
            display: flex;
            align-items: center;
            padding: 0 12px;
            gap: 10px;
            position: relative;
            z-index: 2;
        }

        .back-btn {
            text-decoration: none;
            color: #4b79ff;
            font-size: 28px;
            line-height: 1;
        }

        .doctor-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            object-fit: cover;
        }

        .header-info {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .doctor-name {
            font-size: 17px;
            font-weight: 700;
            color: #3d4d63;
        }

        .online-status {
            font-size: 13px;
            color: #34c759;
        }

        .chat-area {
            position: relative;
            z-index: 1;
            height: calc(100% - 28px - 62px - 84px);
            overflow-y: auto;
            padding: 18px 14px 10px;
        }

        .message-row {
            margin-bottom: 18px;
            display: flex;
            flex-direction: column;
        }

        .message-row.me {
            align-items: flex-end;
        }

        .message-row.other {
            align-items: flex-start;
        }

        .bubble {
            max-width: 68%;
            padding: 14px 16px;
            border-radius: 22px;
            font-size: 14px;
            line-height: 1.35;
            color: #6a7482;
            position: relative;
            word-wrap: break-word;
        }

        .bubble.me {
            background: #cfe0ff;
            border-bottom-right-radius: 8px;
        }

        .bubble.other {
            background: #c9efe9;
            border-bottom-left-radius: 8px;
        }

        .time {
            font-size: 12px;
            color: #7f7f7f;
            margin-top: 6px;
        }

        .voice-row {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #c9efe9;
            padding: 8px 12px;
            border-radius: 18px;
            width: 215px;
        }

        .small-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
        }

        .play-btn {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid #6f6f6f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
            color: #6f6f6f;
            flex-shrink: 0;
        }

        .audio-bar {
            flex: 1;
            height: 3px;
            background: #111;
            border-radius: 10px;
            position: relative;
        }

        .audio-bar::after {
            content: "";
            width: 8px;
            height: 8px;
            background: #111;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 68%;
            transform: translate(-50%, -50%);
        }

        .audio-time {
            font-size: 11px;
            color: #8c8c8c;
        }

        .typing {
            font-size: 15px;
            color: #666;
            padding: 0 18px 8px;
            position: relative;
            z-index: 2;
        }

        .input-bar-wrap {
            height: 84px;
            background: #c9defa;
            display: flex;
            align-items: center;
            padding: 0 12px;
            gap: 10px;
            position: relative;
            z-index: 2;
        }

        .icon-btn {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 2px solid #4d4d4d;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            background: #f7f7f7;
            font-size: 18px;
            flex-shrink: 0;
            cursor: pointer;
        }

        .message-input {
            flex: 1;
            height: 38px;
            border: none;
            outline: none;
            border-radius: 22px;
            background: #f8f4ef;
            padding: 0 18px;
            font-size: 14px;
            color: #666;
        }

        .send-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid #4d4d4d;
            background: #f7f7f7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
        }

        .chat-area::-webkit-scrollbar {
            width: 6px;
        }

        .chat-area::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.14);
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="phone">
        <div class="bg-top"></div>
        <div class="bg-bottom-left"></div>
        <div class="bg-bottom-right"></div>

        <div class="header">
            <a href="{{ url()->previous() }}" class="back-btn">‹</a>

            <img
                src="{{ asset('pics/' . ($doctor['image'] ?? 'doctor3.png')) }}"
                alt="Doctor"
                class="doctor-avatar"
            >

            <div class="header-info">
                <div class="doctor-name">{{ $doctor['name'] ?? 'Dr. Olivia Turner' }}</div>
                <div class="online-status">• Online</div>
            </div>
        </div>

        <div class="chat-area" id="chatArea">
            <div class="message-row me">
                <div class="bubble me">
                    lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore at dolore magna aliquot.
                </div>
                <div class="time">09:00</div>
            </div>

            <div class="message-row other">
                <div class="bubble other">
                    lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore at dolore magna aliqua
                </div>
                <div class="time">09:30</div>
            </div>

            <div class="message-row me">
                <div class="bubble me">
                    lorem ipsum dolor sit amet, consectetur adipisicing elit.
                </div>
                <div class="time">09:43</div>
            </div>

            <div class="message-row other">
                <div class="voice-row">
                    <img
                        src="{{ asset('pics/' . ($doctor['image'] ?? 'doctor3.png')) }}"
                        alt="Doctor"
                        class="small-avatar"
                    >
                    <div class="play-btn">▶</div>
                    <div class="audio-bar"></div>
                    <div class="audio-time">02:50</div>
                </div>
                <div class="time">09:50</div>
            </div>

            <div class="message-row me">
                <div class="bubble me">
                    lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </div>
                <div class="time">09:55</div>
            </div>
        </div>

        <div class="typing">Dr. Olivia is typing...</div>

        <form class="input-bar-wrap" onsubmit="sendMessage(event)">
            <button type="button" class="icon-btn">📎</button>

            <input
                type="text"
                class="message-input"
                id="messageInput"
                placeholder="Write Here..."
            >

            <button type="button" class="icon-btn">🎤</button>
            <button type="submit" class="send-btn">➤</button>
        </form>
    </div>

    <script>
        function sendMessage(event) {
            event.preventDefault();

            const input = document.getElementById('messageInput');
            const text = input.value.trim();
            if (!text) return;

            const chatArea = document.getElementById('chatArea');

            const row = document.createElement('div');
            row.className = 'message-row me';

            const bubble = document.createElement('div');
            bubble.className = 'bubble me';
            bubble.textContent = text;

            const time = document.createElement('div');
            time.className = 'time';

            const now = new Date();
            const hh = String(now.getHours()).padStart(2, '0');
            const mm = String(now.getMinutes()).padStart(2, '0');
            time.textContent = hh + ':' + mm;

            row.appendChild(bubble);
            row.appendChild(time);
            chatArea.appendChild(row);

            input.value = '';
            chatArea.scrollTop = chatArea.scrollHeight;
        }

        window.onload = function () {
            const chatArea = document.getElementById('chatArea');
            chatArea.scrollTop = chatArea.scrollHeight;
        };
    </script>
</body>
</html>