@php
    $routeIs = fn($name) => request()->routeIs($name);
@endphp

<div class="account-nav-card p-3 shadow-sm">
    <h6 class="text-uppercase small text-muted mb-3">
        Navigation
    </h6>

    <nav class="nav flex-column">

        <a href="{{ route('customer.dashboard') }}"
           class="nav-link {{ $routeIs('customer.dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <a href="{{ route('customer.profile') }}"
           class="nav-link {{ $routeIs('customer.profile') ? 'active' : '' }}">
            Profile
        </a>

        <a href="{{ route('customer.addresses.index') }}"
           class="nav-link {{ $routeIs('customer.addresses.*') ? 'active' : '' }}">
            Addresses
        </a>

        <a href="{{ route('customer.orders.index') }}"
           class="nav-link {{ $routeIs('customer.orders.*') ? 'active' : '' }}">
            Orders
        </a>

        <hr class="my-2">

        <form action="{{ route('logout') }}" method="POST" class="mt-1">
            @csrf
            <button type="submit" class="btn btn-link p-0 nav-link text-start" style="color:#c49d2a;">
                Logout
            </button>
        </form>
    </nav>
</div>
