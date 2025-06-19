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
    {{-- <div id="searchResults" class="users-list"></div> --}}
    <!-- Modal kết quả tìm kiếm -->


</div>
<!-- Modal Wrapper -->
<div id="searchModal" class="custom-modal hidden">
    <div class="custom-modal-content">
        <span class="close-modal" id="closeModal">&times;</span>
        <h3>Kết quả tìm kiếm</h3>
        <div id="resultSearch" class="result-search"></div>
    </div>
</div>

