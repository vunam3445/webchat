@props(['item'])
<div class="notification-item">
    <div class="notification-avatar">T</div>
    <div class="notification-content">
        <div class="notification-text">{{$item['name']}} muốn kết bạn với bạn</div>
        <div class="notification-time">5 phút trước</div>
    </div>
    <div class="notification-actions">
        <button class="btn-accept" onclick="acceptFriend(this,'{{$item['user_id']}}')">Chấp nhận</button>
        <button class="btn-decline" onclick="declineFriend(this)">Từ chối</button>
    </div>
</div>
