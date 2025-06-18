<div class="user-item"
     onclick='window.location.href="{{ $user['type'] === 'private'
        ? route('conversations.getConversation', ['id' => $user['id']])
        : route('conversations.getGroupConversation', ['id' => $user['id']]) }}"'>

    <div class="user-avatar">
        @if ($user['type'] === 'private')
            <img src="{{ asset('storage/' . $user['avatar']) }}" alt="avatar">
        @else
            <img src="{{ asset('images/group-icon.png') }}" alt="group" style="border-radius: 10px;">
        @endif
    </div>

    <div class="user-info">
        <div class="name">{{ $user['name'] }}</div>
    </div>
</div>
