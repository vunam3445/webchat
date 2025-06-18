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