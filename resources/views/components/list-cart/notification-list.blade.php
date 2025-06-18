@props(['items'])
<div class="dropdown" id="notificationsDropdown">
    @foreach ($items as $item)
        <x-carts.notification-cart :item="$item" />
    @endforeach
</div>
