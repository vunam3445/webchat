<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Realtime</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar Styles */
        .navbar {
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar-logo {
            color: white;
            font-size: 20px;
            font-weight: bold;
        }

        .create-group-btn {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            color: white;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .create-group-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notification-icon {
            position: relative;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-icon:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .user-profile {
            position: relative;
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-avatar:hover {
            transform: scale(1.1);
        }

        /* Dropdown Menus */
        .dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            min-width: 200px;
            display: none;
            z-index: 1001;
            margin-top: 10px;
        }

        .dropdown.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            padding: 12px 16px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .notification-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .notification-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(45deg, #FF6B6B, #4ECDC4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        .notification-content {
            flex: 1;
        }

        .notification-text {
            color: white;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .notification-time {
            color: #B0B0B0;
            font-size: 12px;
        }

        .notification-actions {
            display: flex;
            gap: 8px;
        }

        .btn-accept,
        .btn-decline {
            padding: 4px 12px;
            border: none;
            border-radius: 15px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-accept {
            background: #4CAF50;
            color: white;
        }

        .btn-decline {
            background: #f44336;
            color: white;
        }

        .btn-accept:hover,
        .btn-decline:hover {
            transform: scale(1.05);
        }

        .chat-container {
            display: flex;
            flex: 1;
            width: 100%;
            max-width: none;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .sidebar {
            width: 300px;
            background: rgba(0, 0, 0, 0.2);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            color: white;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .user-status {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            background: #4CAF50;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .user-name {
            color: #E0E0E0;
            font-size: 14px;
        }

        .search-section {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .search-tabs {
            display: flex;
            gap: 5px;
            margin-bottom: 15px;
        }

        .search-tab {
            flex: 1;
            padding: 8px 12px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 20px;
            color: #B0B0B0;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-tab.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .search-tab:hover:not(.active) {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .search-container {
            position: relative;
            margin-bottom: 15px;
        }

        .search-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 10px 40px 10px 15px;
            color: white;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-input::placeholder {
            color: #B0B0B0;
        }

        .search-input:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #B0B0B0;
            font-size: 16px;
            pointer-events: none;
        }

        .users-list {
            flex: 1;
            padding: 20px;
            padding-top: 0;
            overflow-y: auto;
        }

        .users-list h3 {
            color: white;
            margin-bottom: 15px;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-count {
            font-size: 12px;
            color: #B0B0B0;
            font-weight: normal;
        }

        .user-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin-bottom: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .user-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(5px);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, #FF6B6B, #4ECDC4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        .user-info {
            flex: 1;
        }

        .user-info .name {
            color: white;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .user-info .status {
            color: #B0B0B0;
            font-size: 12px;
        }



        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.05);
        }

        .chat-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .chat-header h3 {
            color: white;
            font-size: 20px;
        }

        .chat-info {
            color: #B0B0B0;
            font-size: 14px;
        }

        .chat-options {
            position: relative;
        }

        .chat-menu-btn {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .chat-menu-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .chat-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            min-width: 200px;
            display: none;
            z-index: 1001;
            margin-top: 10px;
        }

        .chat-menu.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .messages-container {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message {
            display: flex;
            gap: 12px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.own {
            flex-direction: row-reverse;
        }

        .message-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }

        .message-content {
            max-width: 70%;
        }

        .message-bubble {
            background: rgba(255, 255, 255, 0.1);
            padding: 12px 16px;
            border-radius: 18px;
            color: white;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .message.own .message-bubble {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .message-time {
            font-size: 11px;
            color: #B0B0B0;
            margin-top: 4px;
            text-align: right;
        }

        .message.own .message-time {
            text-align: left;
        }

        .message-input-container {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .message-input {
            flex: 1;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 12px 20px;
            color: white;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        .message-input::placeholder {
            color: #B0B0B0;
        }

        .message-input:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .send-button {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 18px;
        }

        .send-button:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            color: #B0B0B0;
            font-size: 14px;
            font-style: italic;
        }

        .typing-dots {
            display: flex;
            gap: 4px;
        }

        .typing-dot {
            width: 6px;
            height: 6px;
            background: #B0B0B0;
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {

            0%,
            60%,
            100% {
                transform: translateY(0);
            }

            30% {
                transform: translateY(-10px);
            }
        }

        .online-count {
            background: rgba(76, 175, 80, 0.2);
            color: #4CAF50;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
        }

        /* Group Info Modal */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }

        .modal.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            color: white;
            font-size: 20px;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .modal-body {
            padding: 20px;
        }

        .group-avatar-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .group-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .group-name {
            color: white;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .group-description {
            color: #B0B0B0;
            font-size: 14px;
        }

        .members-section {
            margin-bottom: 20px;
        }

        .section-title {
            color: white;
            font-size: 18px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .member-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            margin-bottom: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .member-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .member-role {
            color: #4CAF50;
            font-size: 12px;
            background: rgba(76, 175, 80, 0.2);
            padding: 2px 8px;
            border-radius: 10px;
        }

        .remove-member-btn {
            background: #f44336;
            border: none;
            color: white;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remove-member-btn:hover {
            background: #d32f2f;
        }

        @media (max-width: 768px) {
            .chat-container {
                border-radius: 0;
            }

            .sidebar {
                width: 250px;
            }

            .message-content {
                max-width: 85%;
            }

            .navbar {
                padding: 8px 15px;
            }

            .navbar-logo {
                font-size: 16px;
            }

            .add-friend-btn {
                padding: 6px 12px;
                background: linear-gradient(135deg, #4CAF50, #45a049);
                border: none;
                border-radius: 15px;
                color: white;
                cursor: pointer;
                font-size: 12px;
                font-weight: 500;
                transition: all 0.3s ease;
                opacity: 0;
                transform: translateX(20px);
                position: absolute;
                right: 28px;
            }

            .user-item.active .add-friend-btn {
                opacity: 1;
                transform: translateX(0);
            }

            .add-friend-btn:hover {
                background: linear-gradient(135deg, #45a049, #4CAF50);
                transform: scale(1.05);
                box-shadow: 0 3px 10px rgba(76, 175, 80, 0.4);
            }

            .add-friend-btn:active {
                transform: scale(0.95);
            }

        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</head>

<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="navbar-left">
            <div class="navbar-logo">💬 ChatApp</div>
            <button class="create-group-btn" onclick="showCreateGroupModal()">
                ➕ Tạo nhóm
            </button>
        </div>
        <div class="navbar-right">
            <!-- Notifications -->
            <div class="notification-icon" onclick="toggleNotifications()">
                🔔
                <div class="notification-badge" id="notificationBadge">3</div>
                {{-- <div class="dropdown" id="notificationsDropdown">
                    <div class="notification-item">
                        <div class="notification-avatar">T</div>
                        <div class="notification-content">
                            <div class="notification-text">Tuấn Anh muốn kết bạn với bạn</div>
                            <div class="notification-time">5 phút trước</div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn-accept" onclick="acceptFriend(this)">Chấp nhận</button>
                            <button class="btn-decline" onclick="declineFriend(this)">Từ chối</button>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-avatar" style="background: linear-gradient(45deg, #FF9A9E, #FECFEF);">L
                        </div>
                        <div class="notification-content">
                            <div class="notification-text">Linh Chi muốn kết bạn với bạn</div>
                            <div class="notification-time">10 phút trước</div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn-accept" onclick="acceptFriend(this)">Chấp nhận</button>
                            <button class="btn-decline" onclick="declineFriend(this)">Từ chối</button>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-avatar" style="background: linear-gradient(45deg, #A8EDEA, #FED6E3);">M
                        </div>
                        <div class="notification-content">
                            <div class="notification-text">Minh Hoàng đã thêm bạn vào nhóm "Dự án ABC"</div>
                            <div class="notification-time">1 giờ trước</div>
                        </div>
                    </div>
                </div> --}}
                <x-list-cart.notification-list :items="$friendsPendingRequests" />
            </div>

            <!-- User Profile -->
            <div class="user-profile">
                <div class="profile-avatar" onclick="toggleProfileMenu()">B</div>
                <div class="dropdown" id="profileDropdown">
                    <div class="dropdown-item" onclick="showChangePassword()">🔑 Đổi mật khẩu</div>
                    <div class="dropdown-item" onclick="showForgotPassword()">❓ Quên mật khẩu</div>
                    <div class="dropdown-item" onclick="showProfile()">👤 Thông tin cá nhân</div>
                    <div class="dropdown-item" onclick="logout()">🚪 Đăng xuất</div>
                </div>
            </div>
        </div>
    </div>

    <div class="chat-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Chat Room</h2>
                <div class="user-status">
                    <div class="status-indicator"></div>
                    <span class="user-name">Bạn đang online</span>
                </div>
                <div class="online-count" style="margin-top: 10px;">
                    🟢 <span id="onlineCount">5</span> người online
                </div>
            </div>
            <div class="search-section">

                <div class="search-container">
                    <input type="text" class="search-input" id="searchInput" placeholder="Nhập tên để tìm kiếm...">
                    <div class="search-icon">🔍</div>
                </div>
            </div>

            <x-list-cart.user-list :users="$friends" />
            <div id="searchResults" class="users-list"></div>

        </div>

        <div class="chat-main">
            {{-- <div class="chat-header">
                <div class="chat-header-left">
                    <div>
                        <h3>Phòng chat chung</h3>
                        <div class="chat-info">5 thành viên đang hoạt động</div>
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
                <div class="message">
                    <div class="message-avatar">A</div>
                    <div class="message-content">
                        <div class="message-bubble">
                            Chào mọi người! Có ai ở đây không? 👋
                        </div>
                        <div class="message-time">14:30</div>
                    </div>
                </div>

                <div class="message own">
                    <div class="message-avatar">B</div>
                    <div class="message-content">
                        <div class="message-bubble">
                            Chào bạn! Mình đang ở đây nè 😊
                        </div>
                        <div class="message-time">14:31</div>
                    </div>
                </div>
                <div class="message">
                    <div class="message-avatar" style="background: linear-gradient(45deg, #FF9A9E, #FECFEF);">M</div>
                    <div class="message-content">
                        <div class="message-bubble">
                            Hôm nay thời tiết đẹp quá, mọi người có đi chơi không?
                        </div>
                        <div class="message-time">14:32</div>
                    </div>
                </div>

                <div class="message own">
                    <div class="message-avatar">B</div>
                    <div class="message-content">
                        <div class="message-bubble">
                            Mình định đi công viên chiều nay, có ai muốn đi cùng không? 🌳
                        </div>
                        <div class="message-time">14:33</div>
                    </div>
                </div>

                <div class="message">
                    <div class="message-avatar" style="background: linear-gradient(45deg, #A8EDEA, #FED6E3);">H</div>
                    <div class="message-content">
                        <div class="message-bubble">
                            Nghe hay đấy! Mình có thể tham gia được 🙋‍♂️
                        </div>
                        <div class="message-time">14:34</div>
                    </div>
                </div>
            </div>

            <div class="typing-indicator" id="typingIndicator" style="display: none;">
                <span>Anh Tuấn đang gõ</span>
                <div class="typing-dots">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>

            <div class="message-input-container">
                <input type="text" class="message-input" placeholder="Nhập tin nhắn của bạn..."
                    id="messageInput">
                <button class="send-button" id="sendButton">
                    ➤
                </button>
            </div> --}}
        </div>
    </div>

    <!-- Group Info Modal -->
    <div class="modal" id="groupInfoModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Thông tin nhóm</h3>
                <button class="close-btn" onclick="closeModal('groupInfoModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="group-avatar-section">
                    <div class="group-avatar">PC</div>
                    <div class="group-name">Phòng chat chung</div>
                    <div class="group-description">Nhóm trò chuyện dành cho mọi người</div>
                </div>

                <div class="members-section">
                    <div class="section-title">
                        Thành viên <span class="user-count">(5)</span>
                        <button class="create-group-btn" style="font-size: 12px; padding: 5px 10px;"
                            onclick="showAddMember()">+ Thêm</button>
                    </div>

                    <div class="member-item">
                        <div class="user-avatar">B</div>
                        <div class="user-info">
                            <div class="name">Bạn</div>
                            <div class="member-role">Quản trị viên</div>
                        </div>
                    </div>

                    <div class="member-item">
                        <div class="user-avatar">A</div>
                        <div class="user-info">
                            <div class="name">Anh Tuấn</div>
                            <div class="status">Online</div>
                        </div>
                        <button class="remove-member-btn" onclick="removeMember(this)">Xóa</button>
                    </div>

                    <div class="member-item">
                        <div class="user-avatar" style="background: linear-gradient(45deg, #FF9A9E, #FECFEF);">M</div>
                        <div class="user-info">
                            <div class="name">Mai Linh</div>
                            <div class="status">Online</div>
                        </div>
                        <button class="remove-member-btn" onclick="removeMember(this)">Xóa</button>
                    </div>

                    <div class="member-item">
                        <div class="user-avatar" style="background: linear-gradient(45deg, #A8EDEA, #FED6E3);">H</div>
                        <div class="user-info">
                            <div class="name">Hoàng Nam</div>
                            <div class="status">Vừa mới online</div>
                        </div>
                        <button class="remove-member-btn" onclick="removeMember(this)">Xóa</button>
                    </div>

                    <div class="member-item">
                        <div class="user-avatar" style="background: linear-gradient(45deg, #FFD93D, #FF6B9D);">L</div>
                        <div class="user-info">
                            <div class="name">Linh Chi</div>
                            <div class="status">Online</div>
                        </div>
                        <button class="remove-member-btn" onclick="removeMember(this)">Xóa</button>
                    </div>
                </div>

                <div class="members-section">
                    <div class="section-title">Tùy chọn nhóm</div>
                    <div class="dropdown-item" onclick="changeGroupName()">✏️ Đổi tên nhóm</div>
                    <div class="dropdown-item" onclick="changeGroupAvatar()">🖼️ Đổi ảnh nhóm</div>
                    <div class="dropdown-item" onclick="muteGroup()">🔇 Tắt thông báo</div>
                    <div class="dropdown-item" style="color: #f44336;" onclick="deleteGroup()">🗑️ Xóa nhóm</div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            axios.defaults.headers.common['X-CSRF-TOKEN'] =
                document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const lastId = localStorage.getItem('last_conversation');
            if (lastId) {
                loadPrivateChat(lastId); // Load lại cuộc trò chuyện gần nhất
            }

        });


        const messagesContainer = document.getElementById('messagesContainer');
        const typingIndicator = document.getElementById('typingIndicator');
        const onlineCount = document.getElementById('onlineCount');

        // Mảng các tên và avatar mẫu
        const users = [{
                name: 'Anh Tuấn',
                avatar: 'A',
                gradient: 'linear-gradient(45deg, #FF6B6B, #4ECDC4)'
            },
            {
                name: 'Mai Linh',
                avatar: 'M',
                gradient: 'linear-gradient(45deg, #FF9A9E, #FECFEF)'
            },
            {
                name: 'Hoàng Nam',
                avatar: 'H',
                gradient: 'linear-gradient(45deg, #A8EDEA, #FED6E3)'
            },
            {
                name: 'Linh Chi',
                avatar: 'L',
                gradient: 'linear-gradient(45deg, #FFD93D, #FF6B9D)'
            },
            {
                name: 'Đức Minh',
                avatar: 'D',
                gradient: 'linear-gradient(45deg, #74b9ff, #0984e3)'
            }
        ];

        // Mảng tin nhắn mẫu
        const sampleMessages = [
            'Hôm nay mọi người thế nào? 😊',
            'Có ai muốn đi ăn trưa không?',
            'Dự án mới tiến triển ra sao rồi?',
            'Cuối tuần này có kế hoạch gì không?',
            'Thời tiết hôm nay đẹp quá! ☀️',
            'Mình vừa xem bộ phim hay lắm!',
            'Ai có kinh nghiệm về chủ đề này không?',
            'Cảm ơn mọi người đã giúp đỡ! 🙏'
        ];

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
            axios.post(`/messages/${conversationId}`, {
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

        // Simulate hoạt động ngẫu nhiên
        function simulateActivity() {
            // Random typing indicator
            if (Math.random() < 0.3) {
                setTimeout(showTypingIndicator, Math.random() * 10000);
            }

            // Random online count update
            if (Math.random() < 0.2) {
                const newCount = 3 + Math.floor(Math.random() * 5);
                onlineCount.textContent = newCount;
            }
        }

        // Chạy simulation mỗi 15 giây
        setInterval(simulateActivity, 15000);

        // Auto scroll khi có tin nhắn mới
        const observer = new MutationObserver(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });

        observer.observe(messagesContainer, {
            childList: true
        });

        // Focus vào input khi trang load
        messageInput.focus();

        // search 
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

        let conversationId = null;

        function loadPrivateChat(friendId) {
            // 1. Xử lý UI (optional)



            // 2. Gửi request để lấy nội dung chat
            axios.get(`/conversations/${friendId}`)
                .then(response => {
                    const oldContainer = document.querySelector('.chat-main');

                    // Tạo 1 div tạm để chứa HTML từ server
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = response.data.html.trim();

                    // Lấy phần tử .chat-main mới từ HTML server trả về
                    const newContainer = tempDiv.querySelector('.chat-main');

                    if (!newContainer) {
                        console.error('Không tìm thấy .chat-main trong response');
                        return;
                    }

                    console.log('New chat container:', newContainer);
                    // Gán conversationId từ data-attribute
                    // conversationId = newContainer.dataset.conversationId;
                    // console.log('Conversation ID:', conversationId);

                    // Thay thế phần .chat-main cũ bằng phần mới
                    oldContainer.replaceWith(newContainer);

                    // Đăng ký lại sự kiện
                    setupChatEvents();

                })
                .catch(error => {
                    console.error(error);
                    document.querySelector('.chat-main').innerHTML = '<p>Lỗi khi tải hội thoại</p>';
                });
        }
    </script>
    @yield('script')
</body>

</html>
