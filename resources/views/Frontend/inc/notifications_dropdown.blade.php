@php
    $user = auth()->guard('web')->user();
    $unreadCount = $user?->unreadNotifications()->count() ?? 0;

    $notifications = $user
        ? $user->notifications()->latest()->limit(8)->get()
        : collect();
@endphp

@if($user)
    {{-- Bell button (opens offcanvas) --}}
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

    {{-- Offcanvas panel --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="frontNotificationsCanvas"
         aria-labelledby="frontNotificationsCanvasLabel" style="width: 360px; max-width: 92vw;">
        <div class="offcanvas-header border-bottom">
            <h6 class="offcanvas-title" id="frontNotificationsCanvasLabel">Notifications</h6>

            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body p-0">
            @if($notifications->count() == 0)
                <div class="p-3 text-muted small text-center">No notifications yet.</div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($notifications as $n)
                        @php
                            $data = $n->data ?? [];
                            $isUnread = is_null($n->read_at);

                            $title = $data['message'] ?? ($data['type'] ?? 'Notification');

                            if (($data['type'] ?? null) === 'customer_order_paid') {
                                $title = 'Your order #'.($data['order_id'] ?? '').' is paid';
                            }
                        @endphp

                        <a href="{{ route('front.notifications.open', $n->id) }}"
                           class="list-group-item list-group-item-action d-flex gap-2 py-3 @if($isUnread) bg-light @endif">

                            <span style="width:10px;height:10px;border-radius:50%; margin-top:6px;
                                background: {{ $isUnread ? '#0d6efd' : '#c7c7c7' }}; flex:0 0 10px;">
                            </span>

                            <div class="flex-grow-1">
                                <div class="fw-semibold" style="line-height:1.3;">{{ $title }}</div>
                                <div class="text-muted small mt-1">{{ $n->created_at->diffForHumans() }}</div>
                            </div>

                            @if($isUnread)
                                <span class="badge bg-warning text-dark align-self-start mt-1">Unread</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="p-3 border-top text-center">
                <a class="btn btn-outline-dark btn-sm w-100" href="{{ route('front.notifications.index') }}">
                    View all
                </a>
            </div>
        </div>
    </div>

    {{-- Script: لما تفتح اللوحة نخلي العدد يختفي (Mark all as read via AJAX) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('frontNotificationsCanvas');
            const badge  = document.getElementById('frontNotifBadge');
            if (!canvas) return;

            canvas.addEventListener('shown.bs.offcanvas', function () {
                // لو مفيش badge يبقى أصلاً مفيش unread
                if (!badge) return;

                fetch("{{ route('front.notifications.readAll') }}", {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(r => r.json())
                .then(data => {
                    // اخفي الـ badge فوراً
                    badge.remove();
                })
                .catch(() => {
                    // حتى لو فشلنا، نخفيه عشان UX زي فيسبوك
                    badge.remove();
                });
            });
        });
    </script>
@endif
