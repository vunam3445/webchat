@extends('layouts.master')
@section('title', 'Chat')
@section('content')
    <div class="chat-container">
        <x-partital.sidebar.sidebar :friends="$friends" />
        <?php

        if(isset($messages)){
        ?>
        <x-chats.chat-window :messages="$messages" :conversation="$conversation" />

        <?php
        }
        ?>
    </div>
@endsection
@section('script')
    <script>
        const messagesContainer = document.getElementById('messagesContainer');
        const typingIndicator = document.getElementById('typingIndicator');
        const onlineCount = document.getElementById('onlineCount');
        const sendButton = document.getElementById('sendButton');
        const messageInput = document.getElementById('messageInput');
        const conversationId = document.querySelector('.chat-main').dataset.conversationId;
        // Mảng các tên và avatar mẫu

        messageInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                console.log('hehe'); // ✅ Log này sẽ in ra nếu làm đúng
                sendMessage();
            }
        });
        // Mảng tin nhắn mẫu

        // Hàm tạo thời gian hiện tại
        function getCurrentTime() {
            const now = new Date();
            return now.getHours().toString().padStart(2, '0') + ':' +
                now.getMinutes().toString().padStart(2, '0');
        }

        // Hàm thêm tin nhắn
        function addMessage(text, isOwn = false, user = null) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isOwn ? 'own' : ''}`;

            const selectedUser = user || users[Math.floor(Math.random() * users.length)];
            const avatarStyle = isOwn ? 'background: linear-gradient(135deg, #667eea, #764ba2);' :
                `background: ${selectedUser.gradient};`;

            messageDiv.innerHTML = `
                <div class="message-avatar" style="${avatarStyle}">
                    ${isOwn ? 'B' : selectedUser.avatar}
                </div>
                <div class="message-content">
                    <div class="message-bubble">${text}</div>
                    <div class="message-time">${getCurrentTime()}</div>
                </div>
            `;

            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Hàm hiển thị typing indicator
        function showTypingIndicator() {
            const randomUser = users[Math.floor(Math.random() * users.length)];
            typingIndicator.querySelector('span').textContent = `${randomUser.name} đang gõ`;
            typingIndicator.style.display = 'flex';

            setTimeout(() => {
                typingIndicator.style.display = 'none';
                // Thêm tin nhắn sau khi typing
                const randomMessage = sampleMessages[Math.floor(Math.random() * sampleMessages.length)];
                addMessage(randomMessage, false, randomUser);
            }, 2000 + Math.random() * 2000);
        }

        // Xử lý gửi tin nhắn
        function sendMessage() {
            const text = messageInput.value.trim();
            if (!text) return;

            // Gửi lên server
            axios.post(`/conversations/${conversationId}/messages`, {
                    content: text,
                    type: 'text', // Nếu gửi ảnh/sound thì đổi lại
                    metadata: {} // Nếu có thêm thông tin (VD: ID ảnh, emoji...)
                })
                .then(response => {
                    const msg = response.data;
                    addMessage(msg.content, true, {
                        avatar: msg.avatar ? `<img src="/storage/${msg.avatar}">` : 'B',
                        gradient: 'linear-gradient(135deg, #667eea, #764ba2);'
                    });

                    messageInput.value = '';
                })
                .catch(error => {
                    console.error('Lỗi khi gửi tin nhắn:', error);
                });
        }

        // Event listeners
        function setupChatEvents() {
            const sendButton = document.getElementById('sendButton');
            const messageInput = document.getElementById('messageInput');

            if (!sendButton || !messageInput) {
                console.warn('Không tìm thấy input hoặc button trong DOM.');
                return;
            }

            sendButton.addEventListener('click', sendMessage);

            messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    console.log('hehe'); // ✅ Log này sẽ in ra nếu làm đúng
                    sendMessage();
                }
            });
        }


        try {
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: 'f8c6e4d4c713b39ebf47',
                cluster: 'ap1',
                forceTLS: false
            });

            Echo.channel('chat.' + conversationId)
                .listen('MessageSent', (e) => {
                    console.log('🎯 Nhận tin nhắn realtime:', e);
                    if (e.sender_id !== '{{ auth()->id() }}') {
                        addMessage(e.content, false, {
                            avatar: `<img src="/storage/${e.avatar}">`,
                            gradient: 'linear-gradient(45deg, #A8EDEA, #FED6E3)',
                        });
                    }
                });
        } catch (e) {
            console.error('Lỗi khi khởi tạo Echo:', e);
        }
    </script>

@endsection
