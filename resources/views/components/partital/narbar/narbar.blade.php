 <div class="navbar">
     <div class="navbar-left">
         <div class="navbar-logo">💬 ChatApp</div>
         <button class="create-group-btn" data-bs-toggle="modal" data-bs-target="#createGroupModal">
             ➕ Tạo nhóm
         </button>
     </div>
     <div class="navbar-right">
         <!-- Notifications -->
         <div class="notification-icon" onclick="toggleNotifications()">
             🔔
             {{-- <div class="notification-badge" id="notificationBadge">3</div> --}}
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

 <div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModalLabel"
     aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <form id="createGroupForm">
                 @csrf
                 <div class="modal-header">
                     <h5 class="modal-title" id="createGroupModalLabel">Tạo nhóm chat mới</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                 </div>
                 <div class="modal-body">
                     <div class="mb-3">
                         <label for="groupName" class="form-label">Tên nhóm</label>
                         <input type="text" class="form-control" id="groupName" name="groupName" required>
                     </div>
                     {{-- <div class="mb-3">
                         <label for="groupMembers" class="form-label">Thành viên</label>
                         <select multiple class="form-select" id="groupMembers" name="groupMembers[]">
                             <!-- Dữ liệu này sẽ được render từ backend (Laravel hoặc JS) -->
                             <option value="1">Nguyễn Văn A</option>
                             <option value="2">Trần Thị B</option>
                             <option value="3">Lê Văn C</option>
                         </select>
                         <div class="form-text">Giữ Ctrl (Windows) hoặc Cmd (Mac) để chọn nhiều thành viên.</div>
                     </div> --}}
                 </div>
                 <div class="modal-footer">
                     <button type="submit" class="btn btn-primary">Tạo nhóm</button>
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                 </div>
             </form>
         </div>
     </div>
 </div>
 
