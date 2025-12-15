<!-- navbar-->
<header class="header bg-white">
    <div class="container px-lg-3">

        @php
            // الكارت: بنخزنها في السيشن بالشكل:
            // [ product_id => [id, name, price, qty, image] ]
            $cart = session('cart', []);

            $cartCount = 0;
            if (is_array($cart)) {
                foreach ($cart as $item) {
                    if (is_array($item) && isset($item['qty'])) {
                        $cartCount += (int) $item['qty'];
                    }
                }
            }

            // الـ wishlist: array of product IDs
            $wishlistIds   = session('wishlist', []);
            $wishlistCount = is_array($wishlistIds) ? count($wishlistIds) : 0;

            // منتج تجريبي لصفحة Product detail في الـ dropdown
            $sampleProduct = \App\Models\Product::where('is_active', true)
                ->latest('id')
                ->first();

            // 🔔 Notifications (Front)
            $frontUser = auth()->guard('web')->user();
            $frontUnreadCount = $frontUser?->unreadNotifications()->count() ?? 0;
            $frontNotifications = $frontUser
                ? $frontUser->notifications()->latest()->limit(12)->get()
                : collect();
        @endphp

        <nav class="navbar navbar-expand-lg navbar-light py-3 px-lg-0">
            {{-- لوجو بيرجع عالهوم --}}
            <a class="navbar-brand" href="{{ route('frontend.home') }}">
                <span class="fw-bold text-uppercase text-dark">Boutique</span>
            </a>

            <button class="navbar-toggler navbar-toggler-end" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                {{-- الروابط اللي على الشمال --}}
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

                    {{-- PAGES DROPDOWN --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="pagesDropdown"
                           href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Pages
                        </a>

                        <div class="dropdown-menu mt-3 shadow-sm" aria-labelledby="pagesDropdown">
                            {{-- Product detail: يفتح صفحة تفاصيل منتج حقيقي --}}
                            @if($sampleProduct)
                                <a class="dropdown-item border-0 transition-link"
                                   href="{{ route('frontend.products.show', $sampleProduct->slug) }}">
                                    Product detail
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif

                            <a class="dropdown-item border-0 transition-link"
                               href="{{ route('frontend.home') }}">
                                Homepage
                            </a>

                            <a class="dropdown-item border-0 transition-link"
                               href="{{ route('frontend.products.index') }}">
                                Category
                            </a>

                            <a class="dropdown-item border-0 transition-link"
                               href="{{ route('frontend.cart.index') }}">
                                Shopping cart
                            </a>

                            @auth
                                <a class="dropdown-item border-0 transition-link"
                                   href="{{ route('frontend.checkout.index') }}">
                                    Checkout
                                </a>
                            @endauth
                        </div>
                    </li>
                </ul>

                {{-- الروابط اللي على اليمين --}}
                <ul class="navbar-nav ms-auto d-flex align-items-center">

                    {{-- CART --}}
                    <li class="nav-item me-3">
                        <a class="nav-link d-flex align-items-center"
                           href="{{ route('frontend.cart.index') }}">
                            <i class="fa fa-shopping-cart me-1"></i>
                            <span>
                                Cart (
                                <span data-cart-count>{{ $cartCount }}</span>
                                )
                            </span>
                        </a>
                    </li>

                    {{-- WISHLIST --}}
                    <li class="nav-item me-3">
                        <a class="nav-link d-flex align-items-center"
                           href="{{ route('frontend.wishlist.index') }}">
                            <i class="fa fa-heart me-1"></i>
                            <span>
                                Wishlist (
                                <span data-wishlist-count>{{ $wishlistCount }}</span>
                                )
                            </span>
                        </a>
                    </li>

                    {{-- 🔔 NOTIFICATIONS (OFFCANVAS like Facebook) --}}
                    @if($frontUser)
                        <li class="nav-item me-3">
                            <a class="nav-link position-relative d-flex align-items-center"
                               href="#"
                               role="button"
                               data-bs-toggle="offcanvas"
                               data-bs-target="#frontNotificationsCanvas"
                               aria-controls="frontNotificationsCanvas"
                               title="Notifications">

                                <i class="fa fa-bell"></i>

                                @if($frontUnreadCount > 0)
                                    <span id="frontNotifBadge"
                                          class="badge rounded-pill bg-danger"
                                          style="position:absolute; top:-2px; right:-10px; font-size:10px; padding:3px 6px;">
                                        {{ $frontUnreadCount > 99 ? '99+' : $frontUnreadCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endif

                    {{-- لو مش عامل لوجين: Login / Register --}}
                    @guest
                        <li class="nav-item me-2">
                            <a class="nav-link" href="{{ route('login') }}">
                                Login
                            </a>
                        </li>

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    Register
                                </a>
                            </li>
                        @endif
                    @endguest

                    {{-- لو عامل لوجين: Dropdown للحساب --}}
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center"
                               href="#" id="accountDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">

                                <i class="fa fa-user me-1"></i>

                                <span class="small">
                                    {{ auth()->user()->name }}
                                </span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                aria-labelledby="accountDropdown">

                                @role('customer')
                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('customer.dashboard') }}">
                                            Dashboard
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('customer.profile') }}">
                                            Profile
                                        </a>
                                    </li>

                                    @if (Route::has('customer.orders.index'))
                                        <li>
                                            <a class="dropdown-item"
                                               href="{{ route('customer.orders.index') }}">
                                                My orders
                                            </a>
                                        </li>
                                    @endif

                                    <li><hr class="dropdown-divider"></li>
                                @endrole

                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth

                </ul>
            </div>
        </nav>
    </div>

    {{-- ✅ OFFCANVAS لازم يكون خارج nav / خارج ul --}}
    @if($frontUser)
        <div class="offcanvas offcanvas-end" tabindex="-1" id="frontNotificationsCanvas"
             aria-labelledby="frontNotificationsCanvasLabel" style="width:360px; max-width:92vw;">
            <div class="offcanvas-header border-bottom">
                <h6 class="offcanvas-title" id="frontNotificationsCanvasLabel">Notifications</h6>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
            </div>

            <div class="offcanvas-body p-0">
                @if($frontNotifications->count() == 0)
                    <div class="p-3 text-muted small text-center">No notifications yet.</div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($frontNotifications as $n)
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

        {{-- ✅ زي فيسبوك: أول ما تفتح اللوحة، شيل الرقم واعتبرها Read --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const canvas = document.getElementById('frontNotificationsCanvas');
                const badge  = document.getElementById('frontNotifBadge');
                if (!canvas) return;

                canvas.addEventListener('shown.bs.offcanvas', function () {
                    if (!badge) return;

                    fetch("{{ route('front.notifications.readAll') }}", {
                        method: "POST",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    })
                    .then(() => badge.remove())
                    .catch(() => badge.remove());
                });
            });
        </script>
    @endif

</header>
