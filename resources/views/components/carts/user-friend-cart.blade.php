@props(['user'])

{{-- <div class="user-item"  onclick = 'loadPrivateChat("{{ $user['id'] }}")'> --}}
<div class="user-item" onclick='window.location.href="{{ route('conversations.getConversation', ['id' => $user['id']]) }}"'>

    <div class="user-avatar" style="background: linear-gradient(45deg, #FF9A9E, #FECFEF);"><img src="{{asset('storage/' . $user['avatar'])}}"></div>
    <div class="user-info">
        <div class="name">{{ $user['name'] }}</div>
        {{-- <div class="status">{{ $user['status'] }}</div> --}}
    </div>
</div>
