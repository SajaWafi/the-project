<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Chat</title>

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
            position: absolute;
            left: 0;
            background: transparent;
            border: none;
            cursor: pointer;
            color: #2f80ed;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .back-btn svg {
            width: 26px;
            height: 26px;
        }

        .parent-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            margin-left: 26px;
        }

        .header-info {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
            min-width: 0;
            flex: 1;
        }

        .parent-name {
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

        .chat-area::-webkit-scrollbar {
            width: 6px;
        }

        .chat-area::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.14);
            border-radius: 10px;
        }

        .message-row {
            position: relative;
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

        .image-message {
            max-width: 170px;
        }

        .image-message.me {
            align-self: flex-end;
        }

        .image-message.other {
            align-self: flex-start;
        }

        .chat-image {
            width: 100%;
            max-width: 170px;
            border-radius: 14px;
            display: block;
            object-fit: cover;
            box-shadow: 0 3px 10px rgba(0,0,0,0.10);
            cursor: pointer;
        }

        .message-action-menu {
            position: absolute;
            top: -6px;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
            padding: 6px;
            display: none;
            z-index: 20;
        }

        .message-action-menu.show {
            display: block;
        }

        .message-action-btn {
            border: none;
            background: transparent;
            color: #ff5c5c;
            font-size: 13px;
            padding: 6px 10px;
            cursor: pointer;
            border-radius: 8px;
        }

        .message-action-btn:hover {
            background: #fff1f1;
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

        .input-preview-wrap {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            min-width: 0;
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
            width: 100%;
        }

        .message-input.has-file {
            color: transparent;
            caret-color: transparent;
        }

        .message-input.has-file::placeholder {
            color: transparent;
        }

        .selected-file-preview {
            position: absolute;
            left: 18px;
            right: 42px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 13px;
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            pointer-events: none;
            display: none;
        }

        .clear-file-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 22px;
            height: 22px;
            border: none;
            border-radius: 50%;
            background: #ff6b6b;
            color: white;
            font-size: 14px;
            line-height: 1;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
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

        .image-modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.82);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        }

        .image-modal.show {
            display: flex;
        }

        .image-modal img {
            max-width: 90vw;
            max-height: 85vh;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.35);
        }

        .image-modal-close {
            position: absolute;
            top: 18px;
            right: 22px;
            background: rgba(255,255,255,0.15);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 22px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    @if ($errors->any())
        <div style="color:red; font-size:13px; padding:8px;">
            {{ $errors->first() }}
        </div>
    @endif

    @php
        $parentName = $parent['name'] ?? 'Parent';
        $parentImage = !empty($parent['image'])
            ? asset('storage/' . $parent['image'])
            : asset('images/default-user.png');
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

            <img src="{{ $parentImage }}" alt="Parent" class="parent-avatar">

            <div class="header-info">
                <div class="parent-name">{{ $parentName }}</div>
                <div class="online-status">• Online</div>
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

                <div class="message-row {{ $isMe ? 'me' : 'other' }}" data-message-id="{{ $message->id }}">
                    @if(($message->type ?? 'text') === 'text')
                        <div class="bubble {{ $isMe ? 'me' : 'other' }}"
                            @if($isMe)
                                oncontextmenu="openMessageMenu(event, this)"
                                ontouchstart="startPress(event, this)"
                                ontouchend="cancelPress()"
                            @endif
                        >
                            {{ $message->message }}
                        </div>

                    @elseif(($message->type ?? '') === 'image')
                        <div class="image-message {{ $isMe ? 'me' : 'other' }}"
                            @if($isMe)
                                oncontextmenu="openMessageMenu(event, this)"
                                ontouchstart="startPress(event, this)"
                                ontouchend="cancelPress()"
                            @endif
                        >
                            <img
                                src="{{ asset('storage/' . $message->file_path) }}"
                                alt="image"
                                class="chat-image"
                                onclick="openImageModal(this.src)"
                            >
                        </div>

                    @elseif(($message->type ?? '') === 'file')
                        <div class="bubble {{ $isMe ? 'me' : 'other' }}"
                            @if($isMe)
                                oncontextmenu="openMessageMenu(event, this)"
                                ontouchstart="startPress(event, this)"
                                ontouchend="cancelPress()"
                            @endif
                        >
                            <a
                                href="{{ asset('storage/' . $message->file_path) }}"
                                target="_blank"
                                style="color:inherit; text-decoration:underline; word-break: break-all;"
                            >
                                {{ basename($message->file_path) }}
                            </a>
                        </div>
                    @endif

                    @if($isMe)
                        <div class="message-action-menu">
                            <button type="button" class="message-action-btn" onclick="deleteMessage({{ $message->id }}, this)">
                                Delete
                            </button>
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

        <form
            class="input-bar-wrap"
            id="chatForm"
            method="POST"
            action="{{ route('doctor.chat.send', ['parentId' => $parent['id']]) }}"
            enctype="multipart/form-data"
        >
            @csrf

            <input
                type="file"
                id="fileInput"
                name="file"
                hidden
                accept="image/*,.pdf,.doc,.docx,.txt"
            >

            <button
                type="button"
                class="icon-btn"
                onclick="document.getElementById('fileInput').click()"
            >
                📎
            </button>

            <div class="input-preview-wrap">
                <input
                    type="text"
                    name="message"
                    class="message-input"
                    id="messageInput"
                    placeholder="Write Here..."
                    autocomplete="off"
                >
                <div id="selectedFilePreview" class="selected-file-preview"></div>
                <button type="button" id="clearSelectedFile" class="clear-file-btn">×</button>
            </div>

            <button type="button" class="icon-btn" id="voiceBtn">🎤</button>
            <button type="submit" class="send-btn">➤</button>
        </form>
    </div>

    <div class="image-modal" id="imageModal">
        <button class="image-modal-close" type="button" onclick="closeImageModal()">×</button>
        <img id="imageModalPreview" src="" alt="Preview">
    </div>

    <script>
        let muteUntil = null;
        let pressTimer = null;

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
                'doctorParentMuteUntil',
                muteUntil === 'forever' ? 'forever' : muteUntil.toISOString()
            );

            document.getElementById('muteMenu').classList.remove('show');
            updateParentStatus();
        }

        function unmuteNotifications() {
            muteUntil = null;
            localStorage.removeItem('doctorParentMuteUntil');
            document.getElementById('muteMenu').classList.remove('show');
            updateParentStatus();
        }

        function isMuted() {
            const savedMute = localStorage.getItem('doctorParentMuteUntil');

            if (!savedMute) return false;
            if (savedMute === 'forever') return true;

            const muteDate = new Date(savedMute);
            return new Date() < muteDate;
        }

        function updateParentStatus() {
            const status = document.querySelector('.online-status');
            if (!status) return;

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
            if (chatArea) {
                chatArea.scrollTop = chatArea.scrollHeight;
            }
        }

        function openImageModal(src) {
            const modal = document.getElementById('imageModal');
            const preview = document.getElementById('imageModalPreview');
            preview.src = src;
            modal.classList.add('show');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            const preview = document.getElementById('imageModalPreview');
            modal.classList.remove('show');
            preview.src = '';
        }

        function clearSelectedFile() {
            const input = document.getElementById('messageInput');
            const fileInput = document.getElementById('fileInput');
            const filePreview = document.getElementById('selectedFilePreview');
            const clearBtn = document.getElementById('clearSelectedFile');

            fileInput.value = '';
            filePreview.textContent = '';
            filePreview.style.display = 'none';
            clearBtn.style.display = 'none';
            input.classList.remove('has-file');
        }

        function closeAllMessageMenus() {
            document.querySelectorAll('.message-action-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }

        function openMessageMenu(event, element) {
            event.preventDefault();
            closeAllMessageMenus();

            const row = element.closest('.message-row');
            if (!row) return;

            const menu = row.querySelector('.message-action-menu');
            if (menu) {
                menu.classList.add('show');
            }
        }

        function startPress(event, element) {
            pressTimer = setTimeout(() => {
                openMessageMenu(event, element);
            }, 500);
        }

        function cancelPress() {
            clearTimeout(pressTimer);
        }

      function createTextMessage(text, timeText, messageId = null) {
    const chatArea = document.getElementById('chatArea');

    const row = document.createElement('div');
    row.className = 'message-row me';
    if (messageId) row.setAttribute('data-message-id', messageId);

    const bubble = document.createElement('div');
    bubble.className = 'bubble me';
    bubble.textContent = text;

    if (messageId) {
        bubble.setAttribute('oncontextmenu', 'openMessageMenu(event, this)');
        bubble.setAttribute('ontouchstart', 'startPress(event, this)');
        bubble.setAttribute('ontouchend', 'cancelPress()');
    }

    row.appendChild(bubble);

    if (messageId) {
        const menu = document.createElement('div');
        menu.className = 'message-action-menu';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'message-action-btn';
        btn.textContent = 'Delete';
        btn.onclick = function () {
            deleteMessage(messageId, btn);
        };

        menu.appendChild(btn);
        row.appendChild(menu);
    }

    const time = document.createElement('div');
    time.className = 'time';
    time.textContent = timeText;

    row.appendChild(time);
    chatArea.appendChild(row);
}

        function createImageMessage(imageUrl, timeText, messageId = null) {
    const chatArea = document.getElementById('chatArea');

    const row = document.createElement('div');
    row.className = 'message-row me';
    if (messageId) row.setAttribute('data-message-id', messageId);

    const imageWrap = document.createElement('div');
    imageWrap.className = 'image-message me';

    if (messageId) {
        imageWrap.setAttribute('oncontextmenu', 'openMessageMenu(event, this)');
        imageWrap.setAttribute('ontouchstart', 'startPress(event, this)');
        imageWrap.setAttribute('ontouchend', 'cancelPress()');
    }

    const img = document.createElement('img');
    img.src = imageUrl;
    img.className = 'chat-image';
    img.onclick = function () {
        openImageModal(imageUrl);
    };

    imageWrap.appendChild(img);
    row.appendChild(imageWrap);

    if (messageId) {
        const menu = document.createElement('div');
        menu.className = 'message-action-menu';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'message-action-btn';
        btn.textContent = 'Delete';
        btn.onclick = function () {
            deleteMessage(messageId, btn);
        };

        menu.appendChild(btn);
        row.appendChild(menu);
    }

    const time = document.createElement('div');
    time.className = 'time';
    time.textContent = timeText;

    row.appendChild(time);
    chatArea.appendChild(row);
}

    function createFileMessage(fileUrl, fileName, timeText, messageId = null) {
    const chatArea = document.getElementById('chatArea');

    const row = document.createElement('div');
    row.className = 'message-row me';
    if (messageId) row.setAttribute('data-message-id', messageId);

    const bubble = document.createElement('div');
    bubble.className = 'bubble me';

    if (messageId) {
        bubble.setAttribute('oncontextmenu', 'openMessageMenu(event, this)');
        bubble.setAttribute('ontouchstart', 'startPress(event, this)');
        bubble.setAttribute('ontouchend', 'cancelPress()');
    }

    const link = document.createElement('a');
    link.href = fileUrl;
    link.target = '_blank';
    link.textContent = fileName || 'Open file';
    link.style.color = 'inherit';
    link.style.textDecoration = 'underline';
    link.style.wordBreak = 'break-all';

    bubble.appendChild(link);
    row.appendChild(bubble);

    if (messageId) {
        const menu = document.createElement('div');
        menu.className = 'message-action-menu';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'message-action-btn';
        btn.textContent = 'Delete';
        btn.onclick = function () {
            deleteMessage(messageId, btn);
        };

        menu.appendChild(btn);
        row.appendChild(menu);
    }

    const time = document.createElement('div');
    time.className = 'time';
    time.textContent = timeText;

    row.appendChild(time);
    chatArea.appendChild(row);
}

        async function sendMessage(event) {
            event.preventDefault();

            const chatForm = document.getElementById('chatForm');
            const input = document.getElementById('messageInput');
            const fileInput = document.getElementById('fileInput');

            const text = input.value.trim();
            const file = fileInput.files[0];

            if (!text && !file) return;

            try {
                const formData = new FormData(chatForm);

                const response = await fetch(chatForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const raw = await response.text();
                let data;

                try {
                    data = JSON.parse(raw);
                } catch {
                    throw new Error(raw);
                }

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to send message');
                }

                const emptyChat = document.getElementById('emptyChat');
                if (emptyChat) {
                    emptyChat.remove();
                }

                if (data.type === 'image') {
                    createImageMessage(data.file_url, data.time);
                } else if (data.type === 'file') {
                    createFileMessage(data.file_url, data.file_name, data.time);
                } else {
                    createTextMessage(data.message ?? text, data.time ?? '');
                }

                input.value = '';
                clearSelectedFile();
                scrollChatToBottom();
            } catch (error) {
                alert(error.message || 'Failed to send message.');
                console.error(error);
            }
        }

        async function deleteMessage(messageId, button) {
            try {
                const url = "{{ route('doctor.chat.message.delete', ['messageId' => '__ID__']) }}".replace('__ID__', messageId);

                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to delete message');
                }

                const row = button.closest('.message-row');
                if (row) row.remove();

                closeAllMessageMenus();
            } catch (error) {
                alert(error.message || 'Failed to delete message.');
                console.error(error);
            }
        }

        document.getElementById('chatForm').addEventListener('submit', sendMessage);

        document.getElementById('fileInput').addEventListener('change', function () {
            const input = document.getElementById('messageInput');
            const filePreview = document.getElementById('selectedFilePreview');
            const clearBtn = document.getElementById('clearSelectedFile');

            if (this.files[0]) {
                input.value = '';
                filePreview.textContent = this.files[0].name;
                filePreview.style.display = 'block';
                clearBtn.style.display = 'flex';
                input.classList.add('has-file');
            } else {
                clearSelectedFile();
            }
        });

        document.getElementById('clearSelectedFile').addEventListener('click', clearSelectedFile);

        document.getElementById('voiceBtn').addEventListener('click', function () {
            alert('Voice messages will be added later.');
        });

        document.getElementById('imageModal').addEventListener('click', function (e) {
            if (e.target.id === 'imageModal') {
                closeImageModal();
            }
        });

        document.addEventListener('click', function (event) {
            const muteMenu = document.getElementById('muteMenu');
            const muteBtn = document.querySelector('.menu-btn');

            if (muteMenu && muteBtn && !muteMenu.contains(event.target) && !muteBtn.contains(event.target)) {
                muteMenu.classList.remove('show');
            }

            if (!event.target.closest('.message-action-menu') &&
                !event.target.closest('.bubble') &&
                !event.target.closest('.image-message')) {
                closeAllMessageMenus();
            }
        });

        window.onload = function () {
            updateParentStatus();
            scrollChatToBottom();
        };
    </script>
</body>
</html>