<div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="createGroupForm">
        <div class="modal-header">
          <h5 class="modal-title" id="createGroupModalLabel">Tạo nhóm chat mới</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="groupName" class="form-label">Tên nhóm</label>
            <input type="text" class="form-control" id="groupName" name="groupName" required>
          </div>
          <div class="mb-3">
            <label for="groupMembers" class="form-label">Thành viên</label>
            <select multiple class="form-select" id="groupMembers" name="groupMembers[]">
              <!-- Dữ liệu này sẽ được render từ backend (Laravel hoặc JS) -->
              <option value="1">Nguyễn Văn A</option>
              <option value="2">Trần Thị B</option>
              <option value="3">Lê Văn C</option>
            </select>
            <div class="form-text">Giữ Ctrl (Windows) hoặc Cmd (Mac) để chọn nhiều thành viên.</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Tạo nhóm</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        </div>
      </form>
    </div>
  </div>
</div>
