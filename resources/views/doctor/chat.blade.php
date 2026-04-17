<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Chat</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            background: #f8f8f8;
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

        .parent-avatar {
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

        .parent-name {
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
            height: calc(100% - 62px - 84px);
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
            word-wrap: break-word;
        }

        .bubble.me {
            background: #c9efe9;
            border-bottom-right-radius: 8px;
        }

        .bubble.other {
            background: #cfe0ff;
            border-bottom-left-radius: 8px;
        }

        .time {
            font-size: 12px;
            color: #7f7f7f;
            margin-top: 6px;
        }

        .empty-state {
            text-align: center;
            color: #666;
            margin-top: 40px;
            font-size: 14px;
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

@php
    $parentName = $parent['name'] ?? 'Parent';
    $parentImage = asset('images/' . ($parent['image'] ?? 'p1.png'));
@endphp

<body>
    <div class="phone">
        <div class="bg-top"></div>
        <div class="bg-bottom-left"></div>
        <div class="bg-bottom-right"></div>

        <div class="header">
            <a href="{{ route('doctor.parents') }}" class="back-btn">‹</a>

            <img src="{{ $parentImage }}" alt="Parent" class="parent-avatar">

            <div class="header-info">
                <div class="parent-name">{{ $parentName }}</div>
                <div class="online-status">• Online</div>
            </div>
        </div>

        <div class="chat-area" id="chatArea">
            <div class="empty-state" id="emptyState">Loading messages...</div>
        </div>

       <form class="input-bar-wrap" id="chatForm">
            <button type="button" class="icon-btn">📎</button>

            <input
                type="text"
                name="message"
                class="message-input"
                id="messageInput"
                placeholder="Write Here..."
            >

            <button type="button" class="icon-btn" id="voiceBtn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M12 15a3 3 0 0 0 3-3V7a3 3 0 1 0-6 0v5a3 3 0 0 0 3 3Z" stroke="currentColor" stroke-width="1.8"/>
                <path d="M19 11a7 7 0 0 1-14 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M12 18v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M9 21h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </button>
            <button type="submit" class="send-btn">➤</button>
        </form>
    </div>

    <script>
        const chatArea = document.getElementById('chatArea');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const emptyState = document.getElementById('emptyState');

        const parentId = @json($parent['id']);
        const messagesUrl = @json(route('doctor.chat.messages', ['parentId' => $parent['id']]));
        const sendUrl = @json(route('doctor.chat.send', ['parentId' => $parent['id']]));
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function renderMessage(message) {
            const row = document.createElement('div');
            row.className = 'message-row ' + (message.is_me ? 'me' : 'other');

            if (message.type === 'text') {
                const bubble = document.createElement('div');
                bubble.className = 'bubble ' + (message.is_me ? 'me' : 'other');
                bubble.textContent = message.message;
                row.appendChild(bubble);
            }

            const time = document.createElement('div');
            time.className = 'time';
            time.textContent = message.time ?? '';
            row.appendChild(time);

            chatArea.appendChild(row);
        }

        function scrollToBottom() {
            chatArea.scrollTop = chatArea.scrollHeight;
        }

        async function loadMessages() {
            try {
                const response = await fetch(messagesUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                chatArea.innerHTML = '';

                if (!data.messages || data.messages.length === 0) {
                    chatArea.innerHTML = '<div class="empty-state">No messages yet. Start the conversation.</div>';
                    return;
                }

                data.messages.forEach(renderMessage);
                scrollToBottom();
            } catch (error) {
                chatArea.innerHTML = '<div class="empty-state">Failed to load messages.</div>';
            }
        }

        chatForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const text = messageInput.value.trim();
            if (!text) return;

            try {
                const response = await fetch(sendUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: text
                    })
                });

                const data = await response.json();

                if (data.message) {
                    const currentEmpty = document.getElementById('emptyState');
                    if (currentEmpty) currentEmpty.remove();

                    renderMessage(data.message);
                    messageInput.value = '';
                    scrollToBottom();
                }
            } catch (error) {
                alert('Failed to send message.');
            }
        });

        loadMessages();
        
    </script>
</body>
</html>