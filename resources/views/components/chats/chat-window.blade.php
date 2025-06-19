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
                <div class="dropdown-item" onclick="showGroupInfo('{{ $conversation->id }}')">👥 Thông tin nhóm</div>
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
                    <img src="{{ asset('storage/' . $message->avatar) }}" alt="avatar">
                </div>
                <div class="message-content">
                    <span class="message-sender-name">
                        {{ $message->senderId === Auth::id() ? 'Bạn' : $message->name }}
                    </span>
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

<!-- Modal -->
<div id="addMemberModal" class="custom-modal hidden">
    <div class="custom-modal-content">
        <span class="custom-modal-close" id="closeAddMemberModal">&times;</span>
        <h2>Thêm thành viên vào nhóm</h2>
        <input type="text" id="memberSearchInput" placeholder="Nhập tên hoặc số điện thoại">
        <div id="memberSearchResult" class="search-results"></div>
    </div>
</div>
