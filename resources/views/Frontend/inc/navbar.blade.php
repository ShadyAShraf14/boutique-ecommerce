<!-- navbar-->
<header class="header bg-white">
    <div class="container px-lg-3">

        @php
            $cart = session('cart', []);
            $cartCount = 0;
            if (is_array($cart)) {
                foreach ($cart as $item) {
                    if (isset($item['qty'])) {
                        $cartCount += (int)$item['qty'];
                    }
                }
            }

            $wishlistIds   = session('wishlist', []);
            $wishlistCount = is_array($wishlistIds) ? count($wishlistIds) : 0;
        @endphp

        <nav class="navbar navbar-expand-lg navbar-light py-3 px-lg-0">

            <a class="navbar-brand" href="{{ route('frontend.home') }}">
                <strong>Boutique</strong>
            </a>

            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                {{-- LEFT --}}
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('frontend.products.index') }}">Shop</a>
                    </li>
                </ul>

                {{-- RIGHT --}}
                <ul class="navbar-nav ms-auto align-items-center">

                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('frontend.cart.index') }}">
                            <i class="fa fa-shopping-cart"></i>
                            Cart ({{ $cartCount }})
                        </a>
                    </li>

                    <li class="nav-item me-3">
                        <a class="nav-link"
                           href="{{ auth()->check() ? route('frontend.wishlist.index') : route('login') }}">
                            <i class="fa fa-heart"></i>
                            Wishlist ({{ $wishlistCount }})
                        </a>
                    </li>

                    {{-- CUSTOMER --}}
                    @auth
                    <li class="nav-item dropdown customer-dropdown">
                        <a class="nav-link dropdown-toggle"
                           href="#"
                           id="customerDropToggle"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <i class="fa fa-user"></i> {{ auth()->user()->name }}
                        </a>

                        <ul class="dropdown-menu shadow customer-menu"
                            aria-labelledby="customerDropToggle">
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endauth

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @endguest

                </ul>
            </div>
        </nav>
    </div>
</header>
