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