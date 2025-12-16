<!-- navbar-->
<header class="header bg-white">
    <div class="container px-lg-3">

        @php
            // CART
            $cart = session('cart', []);
            $cartCount = 0;
            if (is_array($cart)) {
                foreach ($cart as $item) {
                    if (is_array($item) && isset($item['qty'])) {
                        $cartCount += (int) $item['qty'];
                    }
                }
            }

            // WISHLIST
            $wishlistIds   = session('wishlist', []);
            $wishlistCount = is_array($wishlistIds) ? count($wishlistIds) : 0;

            // 🔔 Notifications (بس لو مسجل)
            $frontUser = auth()->guard('web')->user();
            $frontUnreadCount = 0;
            $frontNotifications = collect();

            if ($frontUser) {
                $frontUnreadCount = $frontUser->unreadNotifications()->count();
                $frontNotifications = $frontUser->notifications()->latest()->limit(12)->get();
            }
        @endphp

        <nav class="navbar navbar-expand-lg navbar-light py-3 px-lg-0">

            {{-- LOGO --}}
            <a class="navbar-brand" href="{{ route('frontend.home') }}">
                <span class="fw-bold text-uppercase text-dark">Boutique</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                {{-- LEFT LINKS --}}
                <ul class="navbar-nav me-auto">

                    {{-- HOME --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}"
                           href="{{ route('frontend.home') }}">
                            Home
                        </a>
                    </li>

                    {{-- SHOP --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('frontend.products.*') ? 'active' : '' }}"
                           href="{{ route('frontend.products.index') }}">
                            Shop
                        </a>
                    </li>

                    {{-- PAGES --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"
                           href="#" data-bs-toggle="dropdown">
                            Pages
                        </a>

                        <div class="dropdown-menu mt-3 shadow-sm">

                            <a class="dropdown-item"
                               href="{{ route('frontend.home') }}">
                                Homepage
                            </a>

                            <a class="dropdown-item"
                               href="{{ route('frontend.cart.index') }}">
                                Shopping cart
                            </a>

                            @auth
                                <a class="dropdown-item"
                                   href="{{ route('frontend.checkout.index') }}">
                                    Checkout
                                </a>
                            @endauth
                        </div>
                    </li>
                </ul>

                {{-- RIGHT LINKS --}}
                <ul class="navbar-nav ms-auto align-items-center">

                    {{-- CART --}}
                    <li class="nav-item me-3">
                        <a class="nav-link"
                           href="{{ route('frontend.cart.index') }}">
                            <i class="fa fa-shopping-cart"></i>
                            Cart (<span data-cart-count>{{ $cartCount }}</span>)
                        </a>
                    </li>

                    {{-- WISHLIST --}}
                    <li class="nav-item me-3">
                        <a class="nav-link"
                           href="{{ auth()->check() ? route('frontend.wishlist.index') : route('login') }}">
                            <i class="fa fa-heart"></i>
                            Wishlist (<span data-wishlist-count>{{ $wishlistCount }}</span>)
                        </a>
                    </li>

                    {{-- NOTIFICATIONS --}}
                    @if($frontUser)
                        <li class="nav-item me-3">
                            <a class="nav-link position-relative"
                               href="javascript:void(0)"
                               role="button"
                               data-bs-toggle="offcanvas"
                               data-bs-target="#frontNotificationsCanvas"
                               aria-controls="frontNotificationsCanvas">

                                <i class="fa fa-bell"></i>

                                @if($frontUnreadCount > 0)
                                    <span id="frontNotifBadge"
                                          class="badge bg-danger rounded-pill"
                                          style="position:absolute;top:-4px;right:-8px;font-size:10px;">
                                        {{ $frontUnreadCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endif

                    {{-- AUTH --}}
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle"
                               href="#" data-bs-toggle="dropdown">
                                <i class="fa fa-user"></i> {{ auth()->user()->name }}
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">
                                @role('customer')
                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('customer.dashboard') }}">
                                            Dashboard
                                        </a>
                                    </li>
                                @endrole

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </nav>
    </div>
</header>

{{-- ✅ Front Notifications Offcanvas --}}
@if($frontUser)
<div class="offcanvas offcanvas-end" tabindex="-1" id="frontNotificationsCanvas" aria-labelledby="frontNotificationsLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="frontNotificationsLabel">Notifications</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted small">
                You have {{ $frontUnreadCount }} unread
            </span>

            <form action="{{ route('front.notifications.readAll') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-outline-dark">
                    Mark all as read
                </button>
            </form>
        </div>

        @if($frontNotifications->count())
            <div class="list-group">
                @foreach($frontNotifications as $n)
                    @php
                        $data = (array) $n->data;

                        // مفاتيح شائعة للعنوان والرسالة
                        $title = $data['title'] ?? $data['subject'] ?? $data['name'] ?? null;
                        $msg   = $data['message'] ?? $data['body'] ?? $data['text'] ?? $data['content'] ?? null;

                        // لو مفيش: هات أول قيمة نصية من الداتا
                        if (!$title && !$msg) {
                            foreach ($data as $v) {
                                if (is_string($v) && trim($v) !== '') {
                                    $msg = $v;
                                    break;
                                }
                            }
                        }

                        // fallback أخير: نوع الإشعار
                        $fallbackText = class_basename($n->type);

                        // نص يظهر في السطر الأول
                        $line1 = $title
                            ?? ($msg ? \Illuminate\Support\Str::limit($msg, 60) : $fallbackText);

                        // سطر ثاني اختياري
                        $line2 = ($msg && $title)
                            ? \Illuminate\Support\Str::limit($msg, 90)
                            : null;
                    @endphp

                    <a href="{{ route('front.notifications.open', $n->id) }}"
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">

                        <div class="me-2">
                            <div class="small fw-semibold">
                                {{ $line1 }}
                            </div>

                            @if($line2)
                                <div class="text-muted" style="font-size:12px;">
                                    {{ $line2 }}
                                </div>
                            @endif

                            <div class="text-muted" style="font-size:12px;">
                                {{ $n->created_at->diffForHumans() }}
                            </div>
                        </div>

                        @if(is_null($n->read_at))
                            <span class="badge bg-danger rounded-pill" style="font-size:10px;">new</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-muted mb-0">No notifications.</p>
        @endif

    </div>
</div>
@endif
