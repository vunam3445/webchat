<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
<!-- PusherJS CDN -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<!-- Laravel Echo -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.1/echo.iife.js"></script>

</head>

<body>
    @include('components.partital.narbar.narbar')

    @yield('content')

    @include('components.modals.groupInfo')
    @yield('script')
</body>

</html>
