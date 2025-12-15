@auth
    @hasanyrole('admin|supervisor')
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard.index') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Dashboard</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard.index') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                E-Commerce
            </div>

            <!-- Categories ONLY -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                    href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-list"></i>
                    <span>Categories</span>
                </a>
            </li>



            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}"
                    href="{{ route('admin.tags.index') }}">
                    <i class="fas fa-tags"></i>
                    <span>Tags</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                    href="{{ route('admin.products.index') }}">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
            </li>


            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}"
                    href="{{ route('admin.coupons.index') }}">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Coupons</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
                    href="{{ route('admin.reviews.index') }}">
                    <i class="fas fa-star"></i>
                    <span>Reviews</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"
                    href="{{ route('admin.customers.index') }}">
                    <i class="fas fa-user-friends"></i>
                    <span>Customers</span>
                </a>
            </li>


            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.countries.*') ? 'active' : '' }}"
                    href="{{ route('admin.countries.index') }}">
                    <i class="fas fa-globe"></i>
                    <span>Countries</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.states.*') ? 'active' : '' }}"
                    href="{{ route('admin.states.index') }}">
                    <i class="fas fa-map-marked-alt"></i>
                    <span>States</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}"
                    href="{{ route('admin.cities.index') }}">
                    <i class="fas fa-city"></i>
                    <span>Cities</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.addresses.*') ? 'active' : '' }}"
                    href="{{ route('admin.addresses.index') }}">
                    <i class="fas fa-map-pin"></i>
                    <span>User Addresses</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.shipping_companies.*') ? 'active' : '' }}"
                    href="{{ route('admin.shipping_companies.index') }}">
                    <i class="fas fa-truck"></i>
                    <span>Shipping Companies</span>
                </a>
            </li>

<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.shippingway.index') }}">
        <i class="fas fa-shipping-fast"></i>
        <span>Shipping methods</span>
    </a>
</li>


<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
       href="{{ route('admin.orders.index') }}">
        <i class="fas fa-receipt"></i>
        <span>Orders</span>
    </a>
</li>








        </ul>
        <!-- End of Sidebar -->
    @endhasanyrole
@endauth
