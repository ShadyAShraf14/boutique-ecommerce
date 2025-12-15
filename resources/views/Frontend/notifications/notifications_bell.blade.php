@php
    $user = auth()->guard('web')->user();
    $unreadCount = $user?->unreadNotifications()->count() ?? 0;
@endphp

@if($user)
<li class="nav-item me-3">
    <a class="nav-link position-relative d-flex align-items-center"
       href="#"
       role="button"
       data-bs-toggle="offcanvas"
       data-bs-target="#frontNotificationsCanvas"
       aria-controls="frontNotificationsCanvas"
       title="Notifications">

        <i class="fa fa-bell"></i>

        @if($unreadCount > 0)
            <span id="frontNotifBadge"
                  class="badge rounded-pill bg-danger"
                  style="position:absolute; top:-2px; right:-10px; font-size:10px; padding:3px 6px;">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </a>
</li>
@endif
