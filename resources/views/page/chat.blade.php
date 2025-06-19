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
        const input = document.getElementById('searchInput');
        const modal = document.getElementById('searchModal');
        const container = document.getElementById('resultSearch');
        const closeModalBtn = document.getElementById('closeModal');

        input?.addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                const keyword = input.value.trim();
                if (!keyword) return;

                axios.get('/users/search', {
                    params: {
                        keyword
                    }
                }).then(response => {
                    const users = response.data;
                    container.innerHTML = '';

                    if (!Array.isArray(users) || users.length === 0) {
                        container.innerHTML = '<div class="no-results">Không có kết quả nào</div>';
                    } else {
                        users.forEach(user => {
                            container.innerHTML += `
                        <div class="user-item" style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <strong>${user.name}</strong><br>
                            <button class="add-friend-btn" data-id="${user.id}">Kết bạn</button>
                        </div>`;
                        });
                    }

                    modal.classList.remove('hidden'); // 🔥 Show modal

                    document.querySelectorAll('.add-friend-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const friendId = this.getAttribute('data-id');
                            axios.post('/friends/request', {
                                friend_id: friendId
                            }).then(() => {
                                this.textContent = 'Đã gửi';
                                this.disabled = true;
                            }).catch(() => alert('Gửi lời mời kết bạn thất bại!'));
                        });
                    });
                }).catch(error => {
                    container.innerHTML = '<div class="no-results">Không có kết quả nào</div>';
                    modal.classList.remove('hidden');
                });
            }
        });

        // ❌ Đóng modal khi ấn nút X
        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // ❌ Đóng modal khi ấn ra ngoài nội dung
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });


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
                    <span class="message-sender-name">${isOwn ? 'Bạn' : selectedUser.name}</span>
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
                    // console.log('🎯 Nhận tin nhắn realtime:', e);
                    if (e.sender_id !== '{{ auth()->id() }}') {
                        addMessage(e.content, false, {
                            name: e.name,
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
                window.location.href = '/auth/logout';
                alert('Đã đăng xuất thành công!');
            }
            document.getElementById('profileDropdown').classList.remove('show');
        }

        // Group Functions
        function showCreateGroupModal() {
            alert('Tính năng tạo nhóm sẽ được triển khai sau!');
        }

        function showGroupInfo(conversationId) {
            const modal = document.getElementById('groupInfoModal');

            // Gọi API lấy thông tin nhóm
            axios.get(`/conversations/${conversationId}/info`)
                .then(response => {
                    const data = response.data;

                    // Cập nhật thông tin nhóm
                    modal.querySelector('.group-name').textContent = data.name || 'Nhóm chưa có tên';
                    modal.querySelector('.group-description').textContent = data.description || 'Chưa có mô tả';
                    modal.querySelector('.group-avatar').textContent = data.name?.charAt(0).toUpperCase() || 'G';

                    // Xử lý thành viên
                    const membersContainer = modal.querySelector('.members-section');
                    const memberItems = data.participants.map(member => `
    <div class="member-item">
        <div class="user-avatar" style="background: linear-gradient(45deg, #A8EDEA, #FED6E3);">
            ${member.name.charAt(0).toUpperCase()}
        </div>
        <div class="user-info">
            <div class="name">${member.id === data.current_user_id ? 'Bạn' : member.name}</div>
            
        </div>
        ${member.id !== data.current_user_id ? `
                        <button class="remove-member-btn" onclick="removeMember(this, '${member.id}')">Xóa</button>` : ''}
    </div>
`).join('');

                    // Tìm đúng phần member list để đổ
                    membersContainer.innerHTML = `
                <div class="section-title">
                    Thành viên <span class="user-count">(${data.participants.length})</span>
                    <button class="create-group-btn" style="font-size: 12px; padding: 5px 10px;"
                        onclick="showAddMember('${data.id}')">+ Thêm</button>
                </div>
                ${memberItems}
            `;

                    // Hiển thị modal
                    modal.classList.add('show');
                })
                .catch(error => {
                    console.error('Lỗi khi lấy thông tin nhóm:', error);
                    alert('Không thể tải thông tin nhóm!');
                });
        }


        function showAddMember() {
            document.getElementById('addMemberModal').classList.remove('hidden');
            document.getElementById('groupInfoModal').classList.remove('show');
            document.getElementById('chatMenu').classList.remove('show');
        }
        document.getElementById('closeAddMemberModal')?.addEventListener('click', () => {
            document.getElementById('addMemberModal').classList.add('hidden');
        });
        // Đóng khi click ra ngoài modal content
        window.addEventListener('click', (event) => {
            const modal = document.getElementById('addMemberModal');
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
        // Bắt sự kiện Enter trong input tìm kiếm
        document.getElementById('memberSearchInput')?.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                const keyword = this.value.trim();
                if (!keyword) return;

                axios.get('/users/search', {
                    params: {
                        keyword
                    }
                }).then(response => {
                    const users = response.data;
                    const resultDiv = document.getElementById('memberSearchResult');
                    resultDiv.innerHTML = '';

                    if (!Array.isArray(users) || users.length === 0) {
                        resultDiv.innerHTML =
                            '<div class="text-muted">Không tìm thấy người dùng nào.</div>';
                    } else {
                        users.forEach(user => {
                            resultDiv.innerHTML += `
                        <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                            <div>
                                <div><strong>${user.name}</strong></div>
                                <div class="text-muted small">${user.phone || 'Không có SĐT'}</div>
                            </div>
                            <button class="btn btn-sm btn-success add-btn" data-id="${user.id}">Thêm</button>
                        </div>
                    `;
                        });

                        document.querySelectorAll('.add-btn').forEach(button => {
                            button.addEventListener('click', function() {
                                const userId = this.getAttribute('data-id');
                                // Gọi API thêm user vào group
                                axios.post(`/conversations/${conversationId}/add-members`, {
                                    user_ids: [userId] // gửi đúng kiểu mảng
                                }).then(() => {
                                    this.textContent = 'Đã thêm';
                                    this.classList.remove('btn-success');
                                    this.classList.add('btn-secondary');
                                    this.disabled = true;
                                }).catch(() => {
                                    alert('Thêm thành viên thất bại.');
                                });
                            });
                        });
                    }
                }).catch(err => {
                    console.error(err);
                    alert('Lỗi tìm kiếm.');
                });
            }
        });


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

        function removeMember(button, userId) {
            // thay bằng biến thực tế bạn đang có

            if (!confirm('Bạn có chắc chắn muốn xóa thành viên này khỏi nhóm?')) return;

            axios.delete(`/conversations/${conversationId}/members/${userId}`)
                .then(response => {
                    alert(response.data.message || 'Đã xoá');
                    // Xoá phần tử khỏi DOM
                    button.closest('.member-item').remove();
                })
                .catch(error => {
                    console.error('Lỗi xoá thành viên:', error);
                    alert('Xóa thành viên thất bại!');
                });
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
