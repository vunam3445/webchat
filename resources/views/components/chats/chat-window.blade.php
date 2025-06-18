

<div class="chat-main" data-conversation-id="{{ $conversation->id }}">
    <div class="chat-header">
        <div class="chat-header-left">
            <div>
                <h3>
                    {{ $conversation->name }}
                </h3>
                <div class="chat-info">
                    {{-- {{ count($conversation->participants) }} thành viên đang hoạt động --}}
                </div>
            </div>
        </div>
        <div class="chat-options">
            <button class="chat-menu-btn" onclick="toggleChatMenu()">⋮</button>
            <div class="chat-menu" id="chatMenu">
                <div class="dropdown-item" onclick="showGroupInfo()">👥 Thông tin nhóm</div>
                <div class="dropdown-item" onclick="showAddMember()">➕ Thêm thành viên</div>
                <div class="dropdown-item" onclick="showMediaFiles()">📁 File & Media</div>
                <div class="dropdown-item" onclick="muteGroup()">🔇 Tắt thông báo</div>
                <div class="dropdown-item" onclick="leaveGroup()" style="color: #f44336;">🚪 Rời nhóm</div>
            </div>
        </div>
    </div>

    <div class="messages-container" id="messagesContainer">
        @foreach ($messages as $message)
            <div class="message {{ $message->senderId === auth()->id() ? 'own' : '' }}">
                <div class="message-avatar" style="background: linear-gradient(45deg, #A8EDEA, #FED6E3);">
                    {{ strtoupper(substr($message->metadata['sender_name'] ?? 'U', 0, 1)) }}
                </div>
                <div class="message-content">
                    <div class="message-bubble">
                        {{ $message->content }}
                    </div>
                    <div class="message-time">
                        {{ $message->createdAt->format('H:i') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="typing-indicator" id="typingIndicator" style="display: none;">
        <span>Ai đó đang gõ</span>
        <div class="typing-dots">
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
        </div>
    </div>

    <div class="message-input-container">
        <input type="text" class="message-input" placeholder="Nhập tin nhắn của bạn..." id="messageInput">
        <button class="send-button" id="sendButton">➤</button>
    </div>
</div>

