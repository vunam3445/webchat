@props(['user'])

<div class="user-item">
    <div class="user-avatar" style="background: linear-gradient(45deg, #FF9A9E, #FECFEF);"><img src="{{asset('storage/' . $user['avatar'])}}"></div>
    <div class="user-info">
        <div class="name">{{ $user['name'] }}</div>
        {{-- <div class="status">{{ $user['status'] }}</div> --}}
    </div>
    <button class="add-friend-btn">Kết bạn</button>
</div>
