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
            padding: 20px;
        }

        .phone {
            width: 390px;
            height: 844px;
            background: url('{{ asset('pics/bg.png') }}') no-repeat center center;
            background-size: cover;
            border-radius: 22px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 12px 30px rgba(0,0,0,0.35);
            display: flex;
            flex-direction: column;
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

        .header {
            min-height: 70px;
            background: #c9defa;
            display: flex;
            align-items: center;
            padding: 0 12px;
            gap: 10px;
            position: relative;
            z-index: 2;
            flex-shrink: 0;
        }

        .back-btn {
            text-decoration: none;
            color: #4b79ff;
            font-size: 28px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .doctor-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .header-info {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
            min-width: 0;
            flex: 1;
        }

        .doctor-name {
            font-size: 17px;
            font-weight: 700;
            color: #3d4d63;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .online-status {
            font-size: 13px;
            color: #34c759;
            margin-top: 2px;
        }

        .menu-wrapper {
            position: relative;
            flex-shrink: 0;
        }

        .menu-btn {
            background: transparent;
            border: none;
            font-size: 24px;
            color: #3d4d63;
            cursor: pointer;
            line-height: 1;
            padding: 6px 8px;
            border-radius: 8px;
        }

        .menu-btn:hover {
            background: rgba(255,255,255,0.35);
        }

        .mute-menu {
            position: absolute;
            top: 42px;
            right: 0;
            width: 190px;
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            overflow: hidden;
            display: none;
            z-index: 50;
        }

        .mute-menu.show {
            display: block;
        }

        .mute-menu button {
            width: 100%;
            border: none;
            background: #ffffff;
            padding: 12px 14px;
            text-align: left;
            font-size: 14px;
            color: #3d4d63;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
        }

        .mute-menu button:last-child {
            border-bottom: none;
        }

        .mute-menu button:hover {
            background: #f5f9ff;
        }

        .chat-area {
            position: relative;
            z-index: 1;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 18px 14px 10px;
            scroll-behavior: smooth;
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
            line-height: 1.45;
            color: #6a7482;
            position: relative;
            word-wrap: break-word;
        }


          .bubble.me {
            background:  #c9efe9;
            border-bottom-right-radius: 8px;
        }
        .bubble.other {
            background:#cfe0ff;
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
            flex-shrink: 0;
        }

        .input-bar-wrap {
            min-height: 84px;
            background: #c9defa;
            display: flex;
            align-items: center;
            padding: 0 12px;
            gap: 10px;
            position: relative;
            z-index: 2;
            flex-shrink: 0;
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
            flex-shrink: 0;
        }

        .chat-area::-webkit-scrollbar {
            width: 6px;
        }

        .chat-area::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.14);
            border-radius: 10px;
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
    </style>
</head>
<body>
    @php
        $doctorName = $doctor['name'] ?? 'Doctor';
        $doctorImage = asset('pics/' . ($doctor['image'] ?? 'doctor3.png'));
    @endphp

    <div class="phone">
        <div class="bg-top"></div>
        <div class="bg-bottom-left"></div>
        <div class="bg-bottom-right"></div>

        <div class="header">
            <button class="back-btn" onclick="history.back()" type="button" aria-label="Back">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M15 5L8 12L15 19" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <img
                src="{{ $doctorImage }}"
                alt="Doctor"
                class="doctor-avatar"
            >

            <div class="header-info">
                <div class="doctor-name">{{ $doctorName }}</div>
                <div class="online-status" id="doctorStatus">• Online</div>
            </div>

            <div class="menu-wrapper">
                <button type="button" class="menu-btn" onclick="toggleMuteMenu()">⋮</button>

                <div class="mute-menu" id="muteMenu">
                    <button type="button" onclick="muteNotifications('1hour')">Mute for 1 hour</button>
                    <button type="button" onclick="muteNotifications('1day')">Mute for 1 day</button>
                    <button type="button" onclick="muteNotifications('1month')">Mute for 1 month</button>
                    <button type="button" onclick="muteNotifications('forever')">Mute forever</button>
                    <button type="button" onclick="unmuteNotifications()">Unmute</button>
                </div>
            </div>
        </div>

 <div class="chat-area" id="chatArea">
    @forelse(($messages ?? []) as $message)
        @php
            $isMe = $message->user_id == auth()->id();
        @endphp

        <div class="message-row {{ $isMe ? 'me' : 'other' }}">
            @if(($message->type ?? 'text') === 'text')
                <div class="bubble {{ $isMe ? 'me' : 'other' }}">
                    {{ $message->message }}
                </div>

            @elseif(($message->type ?? '') === 'image')
                <div class="bubble {{ $isMe ? 'me' : 'other' }}">
                    <img src="{{ asset('storage/' . $message->file_path) }}" style="max-width:140px; border-radius:12px;">
                </div>

            @elseif(($message->type ?? '') === 'file')
                <div class="bubble {{ $isMe ? 'me' : 'other' }}">
                    <a href="{{ asset('storage/' . $message->file_path) }}" target="_blank" style="color:inherit; text-decoration:underline;">
                        {{ basename($message->file_path) }}
                    </a>
                </div>
            @endif

            <div class="time">{{ $message->created_at?->format('H:i') }}</div>
        </div>
    @empty
        <div class="empty-chat" id="emptyChat" style="text-align:center; color:#777; margin-top:20px;">
            No messages yet. Start the conversation.
        </div>
    @endforelse
</div>

       <form class="input-bar-wrap" id="chatForm" enctype="multipart/form-data">
            <input
                type="file"
                id="fileInput"
                name="file"
                hidden
                accept="image/*,.pdf,.doc,.docx,.txt"
            >

            <button type="button" class="icon-btn" onclick="document.getElementById('fileInput').click()">📎</button>

            <input
                type="text"
                class="message-input"
                id="messageInput"
                placeholder="Write Here..."
                autocomplete="off"
            >

            <button type="button" class="icon-btn" id="voiceBtn">🎤</button>
            <button type="submit" class="send-btn">➤</button>
        </form>
    </div>

<script>
    let muteUntil = null;

    function toggleMuteMenu() {
        const menu = document.getElementById('muteMenu');
        menu.classList.toggle('show');
    }

    function muteNotifications(duration) {
        const now = new Date();

        if (duration === '1hour') {
            muteUntil = new Date(now.getTime() + 60 * 60 * 1000);
        } else if (duration === '1day') {
            muteUntil = new Date(now.getTime() + 24 * 60 * 60 * 1000);
        } else if (duration === '1month') {
            muteUntil = new Date(now);
            muteUntil.setMonth(muteUntil.getMonth() + 1);
        } else if (duration === 'forever') {
            muteUntil = 'forever';
        }

        localStorage.setItem(
            'parentDoctorMuteUntil',
            muteUntil === 'forever' ? 'forever' : muteUntil.toISOString()
        );

        document.getElementById('muteMenu').classList.remove('show');
        updateDoctorStatus();
    }

    function unmuteNotifications() {
        muteUntil = null;
        localStorage.removeItem('parentDoctorMuteUntil');
        document.getElementById('muteMenu').classList.remove('show');
        updateDoctorStatus();
    }

    function isMuted() {
        const savedMute = localStorage.getItem('parentDoctorMuteUntil');

        if (!savedMute) return false;
        if (savedMute === 'forever') return true;

        const muteDate = new Date(savedMute);
        return new Date() < muteDate;
    }

    function updateDoctorStatus() {
        const status = document.getElementById('doctorStatus');

        if (isMuted()) {
            status.textContent = '• Notifications muted';
            status.style.color = '#ff9500';
        } else {
            status.textContent = '• Online';
            status.style.color = '#34c759';
        }
    }

    function scrollChatToBottom() {
        const chatArea = document.getElementById('chatArea');
        chatArea.scrollTop = chatArea.scrollHeight;
    }

    function createTextMessage(text, timeText, isMe = true) {
        const chatArea = document.getElementById('chatArea');

        const row = document.createElement('div');
        row.className = 'message-row ' + (isMe ? 'me' : 'other');

        const bubble = document.createElement('div');
        bubble.className = 'bubble ' + (isMe ? 'me' : 'other');
        bubble.textContent = text;

        const time = document.createElement('div');
        time.className = 'time';
        time.textContent = timeText;

        row.appendChild(bubble);
        row.appendChild(time);
        chatArea.appendChild(row);
    }

    function createImageMessage(imageUrl, timeText, isMe = true) {
        const chatArea = document.getElementById('chatArea');

        const row = document.createElement('div');
        row.className = 'message-row ' + (isMe ? 'me' : 'other');

        const bubble = document.createElement('div');
        bubble.className = 'bubble ' + (isMe ? 'me' : 'other');

        const img = document.createElement('img');
        img.src = imageUrl;
        img.style.maxWidth = '140px';
        img.style.borderRadius = '12px';

        bubble.appendChild(img);

        const time = document.createElement('div');
        time.className = 'time';
        time.textContent = timeText;

        row.appendChild(bubble);
        row.appendChild(time);
        chatArea.appendChild(row);
    }

    function createFileMessage(fileUrl, fileName, timeText, isMe = true) {
        const chatArea = document.getElementById('chatArea');

        const row = document.createElement('div');
        row.className = 'message-row ' + (isMe ? 'me' : 'other');

        const bubble = document.createElement('div');
        bubble.className = 'bubble ' + (isMe ? 'me' : 'other');

        const link = document.createElement('a');
        link.href = fileUrl;
        link.target = '_blank';
        link.textContent = fileName || 'Open file';
        link.style.color = 'inherit';
        link.style.textDecoration = 'underline';

        bubble.appendChild(link);

        const time = document.createElement('div');
        time.className = 'time';
        time.textContent = timeText;

        row.appendChild(bubble);
        row.appendChild(time);
        chatArea.appendChild(row);
    }

    async function sendMessage(event) {
        event.preventDefault();

        const input = document.getElementById('messageInput');
        const fileInput = document.getElementById('fileInput');
        const text = input.value.trim();
        const file = fileInput.files[0];

        if (!text && !file) return;

        try {
            const formData = new FormData();
            formData.append('message', text);
            if (file) {
                formData.append('file', file);
            }

            const response = await fetch("{{ route('parents.chat.send', $doctor['id']) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to send message');
            }

            const emptyChat = document.getElementById('emptyChat');
            if (emptyChat) {
                emptyChat.remove();
            }

            if (data.type === 'image') {
                createImageMessage(data.file_url, data.time, true);
            } else if (data.type === 'file') {
                createFileMessage(data.file_url, data.file_name, data.time, true);
            } else {
                createTextMessage(data.message ?? text, data.time ?? '', true);
            }

            input.value = '';
            fileInput.value = '';
            scrollChatToBottom();
        } catch (error) {
            alert(error.message || 'Failed to send message.');
            console.error(error);
        }
    }

    document.getElementById('chatForm').addEventListener('submit', sendMessage);

    document.getElementById('voiceBtn').addEventListener('click', function () {
        alert('Voice messages will be added later.');
    });

    document.addEventListener('click', function (event) {
        const menu = document.getElementById('muteMenu');
        const button = document.querySelector('.menu-btn');

        if (!menu.contains(event.target) && !button.contains(event.target)) {
            menu.classList.remove('show');
        }
    });

    window.onload = function () {
        updateDoctorStatus();
        scrollChatToBottom();
    };
</script>
</body>
</html>