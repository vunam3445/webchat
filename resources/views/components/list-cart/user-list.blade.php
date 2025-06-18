@props(['users'])

<div class="users-list">
    <div id="onlineUsers">
        <h3>Người dùng online <span class="user-count">({{ count($users) }})</span></h3>

        @foreach ($users as $user)
            <x-carts.user-friend-cart :user="$user" />
        @endforeach

    </div>
</div>
