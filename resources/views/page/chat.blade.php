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


        // Navbar Functions
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationsDropdown');
            const profileDropdown = document.getElementById('profileDropdown');
            const chatMenu = document.getElementById('chatMenu');

            // Close other dropdowns
            profileDropdown.classList.remove('show');
            chatMenu.classList.remove('show');

            dropdown.classList.toggle('show');
        }

        function toggleProfileMenu() {
            const dropdown = document.getElementById('profileDropdown');
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            const chatMenu = document.getElementById('chatMenu');

            // Close other dropdowns
            notificationsDropdown.classList.remove('show');
            chatMenu.classList.remove('show');

            dropdown.classList.toggle('show');
        }

        function toggleChatMenu() {
            const dropdown = document.getElementById('chatMenu');
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            const profileDropdown = document.getElementById('profileDropdown');

            // Close other dropdowns
            notificationsDropdown.classList.remove('show');
            profileDropdown.classList.remove('show');

            dropdown.classList.toggle('show');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.notification-icon') && !e.target.closest('.user-profile') && !e.target.closest(
                    '.chat-options')) {
                document.getElementById('notificationsDropdown').classList.remove('show');
                document.getElementById('profileDropdown').classList.remove('show');
                document.getElementById('chatMenu').classList.remove('show');
            }
        });

        // Notification Functions
        function acceptFriend(button, userId) {
            axios.put('/friends/accept', {
                    user_id: userId
                })
                .then(response => {
                    // Làm hiệu ứng mờ dần rồi xoá phần tử
                    const notificationItem = button.closest('.notification-item');
                    notificationItem.style.opacity = '0.5';
                    setTimeout(() => {
                        notificationItem.remove();
                        updateNotificationBadge();
                    }, 300);
                })
                .catch(error => {
                    console.error('Lỗi khi chấp nhận lời mời kết bạn:', error);
                    alert('Không thể chấp nhận lời mời. Vui lòng thử lại!');
                });
        }


        function declineFriend(button) {
            const notificationItem = button.closest('.notification-item');
            notificationItem.style.opacity = '0.5';
            setTimeout(() => {
                notificationItem.remove();
                updateNotificationBadge();
            }, 300);
        }

        function updateNotificationBadge() {
            const notifications = document.querySelectorAll('.notification-item');
            const badge = document.getElementById('notificationBadge');
            const count = notifications.length;

            if (count === 0) {
                badge.style.display = 'none';
            } else {
                badge.textContent = count;
                badge.style.display = 'flex';
            }
        }

        // Profile Functions
        function showChangePassword() {
            alert('Tính năng đổi mật khẩu sẽ được triển khai sau!');
            document.getElementById('profileDropdown').classList.remove('show');
        }

        function showForgotPassword() {
            alert('Tính năng quên mật khẩu sẽ được triển khai sau!');
            document.getElementById('profileDropdown').classList.remove('show');
        }

        function showProfile() {
            alert('Tính năng thông tin cá nhân sẽ được triển khai sau!');
            document.getElementById('profileDropdown').classList.remove('show');
        }

        function logout() {
            if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                alert('Đã đăng xuất thành công!');
            }
            document.getElementById('profileDropdown').classList.remove('show');
        }

        // Group Functions
        function showCreateGroupModal() {
            alert('Tính năng tạo nhóm sẽ được triển khai sau!');
        }

        function showGroupInfo() {
            document.getElementById('groupInfoModal').classList.add('show');
            document.getElementById('chatMenu').classList.remove('show');
        }

        function showAddMember() {
            alert('Tính năng thêm thành viên sẽ được triển khai sau!');
            document.getElementById('chatMenu').classList.remove('show');
        }

        function showMediaFiles() {
            alert('Tính năng xem file & media sẽ được triển khai sau!');
            document.getElementById('chatMenu').classList.remove('show');
        }

        function muteGroup() {
            alert('Đã tắt thông báo cho nhóm này!');
            document.getElementById('chatMenu').classList.remove('show');
        }

        function leaveGroup() {
            if (confirm('Bạn có chắc chắn muốn rời khỏi nhóm này?')) {
                alert('Đã rời nhóm thành công!');
            }
            document.getElementById('chatMenu').classList.remove('show');
        }

        // Modal Functions
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        function removeMember(button) {
            const memberItem = button.closest('.member-item');
            const memberName = memberItem.querySelector('.name').textContent;

            if (confirm(`Bạn có chắc chắn muốn xóa ${memberName} khỏi nhóm?`)) {
                memberItem.style.opacity = '0';
                setTimeout(() => {
                    memberItem.remove();
                }, 300);
            }
        }

        function changeGroupName() {
            const newName = prompt('Nhập tên mới cho nhóm:', 'Phòng chat chung');
            if (newName && newName.trim()) {
                document.querySelector('.group-name').textContent = newName.trim();
                document.querySelector('.chat-header h3').textContent = newName.trim();
                alert('Đã đổi tên nhóm thành công!');
            }
        }

        function changeGroupAvatar() {
            alert('Tính năng đổi ảnh nhóm sẽ được triển khai sau!');
        }

        function deleteGroup() {
            if (confirm('Bạn có chắc chắn muốn xóa nhóm này? Hành động này không thể hoàn tác!')) {
                alert('Nhóm đã được xóa!');
                closeModal('groupInfoModal');
            }
        }

        // Close modal when clicking outside
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                e.target.classList.remove('show');
            }
        });


        const observer = new MutationObserver(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });

        observer.observe(messagesContainer, {
            childList: true
        });

        // Focus vào input khi trang load
        messageInput.focus();


        document.getElementById('searchInput').addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                const keyword = event.target.value;
                if (keyword.trim() === '') {

                    return;
                }
                axios.get('/users/search', {
                        params: {
                            keyword
                        }
                    })
                    .then(response => {
                        const users = response.data;
                        const container = document.getElementById('users-list');
                        container.innerHTML = ''; // Clear old results
                        console.log('users:', users);
                        console.log('Array.isArray(users):', Array.isArray(users));
                        console.log('users.length:', users.length);
                        if (!Array.isArray(users) || users.length === 0) {
                            container.innerHTML = '<div class="no-results">Không có kết quả nào</div>';
                            return;
                        }

                        users.forEach(user => {
                            const userItem = `
            <div class="user-item">
                <div class="user-avatar" style="background: ${user.avatar_color || '#ccc'}">
                    ${user.name.charAt(0).toUpperCase()}
                </div>
                <div class="user-info">
                    <div class="name">${user.name}</div>
                    <div class="status">${user.status || 'Đang hoạt động'}</div>
                </div>
<button class = 'add-friend-btn'data-id="${user.id}" style="
            padding: 6px 12px;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            border: none;
            border-radius: 15px;
            color: white;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
            opacity: 1;
            transform: translateX(20px);
            position: absolute;
            right: 28px;
        ">Kết bạn</button>            </div>
        `;
                            container.innerHTML += userItem;
                        });

                        // ✅ Gán sự kiện sau khi các nút đã được thêm vào DOM
                        document.querySelectorAll('.add-friend-btn').forEach(btn => {
                            btn.addEventListener('click', function(e) {
                                e.stopPropagation(); // Ngăn sự kiện click lan ra ngoài

                                const friendId = this.getAttribute('data-id');
                                const userName = this.parentElement.querySelector('.name')
                                    .textContent;

                                // Gọi API gửi lời mời kết bạn
                                axios.post('/friends/request', {
                                        friend_id: friendId
                                    })
                                    .then(() => {
                                        this.textContent = 'Đã gửi';
                                        this.style.background =
                                            'linear-gradient(135deg, #666, #888)';
                                        this.disabled = true;

                                        console.log(
                                            `Đã gửi lời mời kết bạn tới ${userName}`);
                                    })
                                    .catch(error => {
                                        console.error('Gửi lời mời kết bạn thất bại:',
                                            error);
                                        alert('Gửi lời mời kết bạn thất bại!');
                                    });
                            });
                        });
                    })
                    .catch(error => {
                        const container = document.getElementById('searchResults');
                        container.innerHTML = '<div class="no-results">Không có kết quả nào</div>';
                    });

            }

        });


        // Xử lý click vào user item
        document.querySelectorAll('.user-item').forEach(item => {
            item.addEventListener('click', function(e) {
                // Nếu click vào nút kết bạn thì không xử lý
                if (e.target.classList.contains('add-friend-btn')) {
                    return;
                }

                // Xóa active class khỏi tất cả user items
                document.querySelectorAll('.user-item').forEach(el => {
                    el.classList.remove('active');
                });

                // Thêm active class cho item được click
                this.classList.add('active');
            });
        });

        // Xử lý click nút kết bạn


        // Click ra ngoài để ẩn nút
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-item')) {
                document.querySelectorAll('.user-item').forEach(el => {
                    el.classList.remove('active');
                });
            }
        });



        document.getElementById('createGroupForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const groupName = document.getElementById('groupName').value;
            // const memberSelect = document.getElementById('groupMembers');
            // const userIds = Array.from(memberSelect.selectedOptions).map(option => option.value);

            axios.post('/conversations/group', {
                    name: groupName,
                    // userIds: userIds
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => {
                    alert('✅ Nhóm đã được tạo!');
                    location.reload(); // Hoặc bạn có thể render giao diện nhóm mới mà không cần reload
                })
                .catch(error => {
                    console.error(error);
                    alert('❌ Tạo nhóm thất bại!');
                });
        });
    </script>

@endsection
