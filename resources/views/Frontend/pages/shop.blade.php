{{-- resources/views/Frontend/pages/shop.blade.php --}}
@extends('Frontend.inc.master')

@section('content')

@php
    $isPaginated = $products instanceof \Illuminate\Pagination\LengthAwarePaginator;
    $wishlistIds = session('wishlist', []);
@endphp

<style>
    .shop-page-hero { padding: 24px 0; border-bottom: 1px solid #eee; background: #fafafa; }
    .shop-title { font-size: 1.4rem; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; }
    .shop-breadcrumb a { color: #999; text-decoration: none; font-size: .85rem; }
    .shop-breadcrumb a:hover { color: #000; }
    .shop-sidebar { font-size: .9rem; }
    .shop-sidebar h6 { font-size: .85rem; text-transform: uppercase; letter-spacing: .12em; margin-bottom: .75rem; }
    .shop-sidebar .cat-link { display:block; padding:4px 0; color:#555; text-decoration:none; font-size:.9rem; }
    .shop-sidebar .cat-link.active, .shop-sidebar .cat-link:hover { color:#000; font-weight:600; }
    .filter-label { font-size:.8rem; text-transform:uppercase; letter-spacing:.12em; color:#999; margin-bottom:.25rem; }
    .shop-toolbar { font-size: .85rem; }
    .shop-toolbar .form-select { font-size: .85rem; padding: .25rem .75rem; height: 32px; }

    .product-card { border-radius:8px; overflow:hidden; border:1px solid #f0f0f0; transition: box-shadow .2s ease, transform .2s ease; background:#fff; }
    .product-card:hover { box-shadow:0 10px 25px rgba(0,0,0,0.06); transform: translateY(-3px); }
    .product-card-img-wrapper { position:relative; overflow:hidden; }
    .product-card-img-wrapper img { width:100%; height:230px; object-fit:cover; display:block; transition: all .25s ease; }
    .product-card:hover .product-card-img-wrapper img { opacity:.35; transform: scale(1.03); }
    .product-card .overlay-actions { opacity:0; visibility:hidden; transform: translateY(12px); transition: all .25s ease; }
    .product-card:hover .overlay-actions { opacity:1; visibility:visible; transform: translateY(0); }

    .badge-flag { position:absolute; top:12px; right:12px; font-size:10px; text-transform:uppercase; padding:3px 10px; border-radius:999px; font-weight:600; letter-spacing:.08em; }
    .badge-flag.sale { background:#f7e0a3; color:#333; }
    .badge-flag.new  { background:#38cfd9; color:#fff; }
    .badge-flag.sold { background:#e74c3c; color:#fff; }

    .wishlist-btn.is-fav { background-color:#ffe6ec !important; border-color:#e63946 !important; color:#e63946 !important; }
</style>

<section class="shop-page-hero">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <div class="shop-breadcrumb mb-1">
                    <a href="{{ url('/') }}">Home</a>
                    <span class="mx-1">/</span>
                    <span class="text-dark">Shop</span>
                </div>
                <h1 class="shop-title mb-0">Shop</h1>
            </div>

            <div class="text-muted small">
                @if($isPaginated)
                    Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} results
                @else
                    Showing {{ $products->count() }} results
                @endif
            </div>
        </div>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <div class="row g-4">

            {{-- SIDEBAR --}}
            <aside class="col-lg-3 shop-sidebar">

                {{-- Categories --}}
                <div class="mb-4">
                    <h6>Categories</h6>

                    {{-- All products --}}
                    <a href="{{ route('frontend.products.index', array_diff_key(request()->query(), ['category'=>1])) }}"
                       class="cat-link {{ request('category') ? '' : 'active' }}">
                        All products
                    </a>

                    @foreach($categories as $cat)
                        <a href="{{ route('frontend.products.index', array_merge(request()->query(), ['category'=>$cat->slug])) }}"
                           class="cat-link {{ request('category') == $cat->slug ? 'active' : '' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>

                {{-- Price filter (حقيقي) --}}
                <form method="GET" action="{{ route('frontend.products.index') }}" class="mb-4">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <input type="hidden" name="sort" value="{{ request('sort','latest') }}">
                    <input type="hidden" name="in_stock" value="{{ request('in_stock') }}">

                    <div class="filter-label">Price range</div>
                    <div class="d-flex gap-2">
                        <input type="number" name="min_price" class="form-control form-control-sm"
                               placeholder="Min" value="{{ request('min_price') }}">
                        <input type="number" name="max_price" class="form-control form-control-sm"
                               placeholder="Max" value="{{ request('max_price') }}">
                    </div>
                    <button class="btn btn-sm btn-dark mt-2 w-100" type="submit">Apply</button>
                </form>

                {{-- In stock (حقيقي) --}}
                <form method="GET" action="{{ route('frontend.products.index') }}" class="mb-4">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <input type="hidden" name="sort" value="{{ request('sort','latest') }}">
                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">

                    <div class="filter-label">Show only</div>
                    <div class="form-check small">
                        <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="inStock"
                               {{ request('in_stock') ? 'checked' : '' }}
                               onchange="this.form.submit()">
                        <label class="form-check-label" for="inStock">In stock</label>
                    </div>
                </form>

            </aside>

            {{-- PRODUCTS GRID --}}
            <div class="col-lg-9">

                {{-- Toolbar --}}
                <div class="d-flex justify-content-between align-items-center mb-3 shop-toolbar">
                    <div class="text-muted small">
                        @if($isPaginated)
                            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} results
                        @else
                            Showing {{ $products->count() }} results
                        @endif
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small me-1">Sort by</span>

                        {{-- Sort (حقيقي) --}}
                        <form method="GET" action="{{ route('frontend.products.index') }}">
                            <input type="hidden" name="category" value="{{ request('category') }}">
                            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                            <input type="hidden" name="in_stock" value="{{ request('in_stock') }}">

                            <select name="sort" class="form-select form-select-sm" style="max-width: 180px;"
                                    onchange="this.form.submit()">
                                <option value="latest"     @selected(request('sort','latest')=='latest')>Newest</option>
                                <option value="price_asc"  @selected(request('sort')=='price_asc')>Price: low to high</option>
                                <option value="price_desc" @selected(request('sort')=='price_desc')>Price: high to low</option>
                                <option value="name_asc"   @selected(request('sort')=='name_asc')>Name: A-Z</option>
                                <option value="name_desc"  @selected(request('sort')=='name_desc')>Name: Z-A</option>
                            </select>
                        </form>
                    </div>
                </div>

                {{-- Grid --}}
                <div class="row g-4">
                    @forelse($products as $product)
                        @php
                            $imageUrl = $product->getFirstMediaUrl('image')
                                ?: asset('frontend-assets/img/default-product.jpg');

                            $isFav = in_array($product->id, $wishlistIds);
                            $isSold = $product->isSoldOut();
                        @endphp

                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="product-card h-100">

                                <div class="product-card-img-wrapper">
                                    <a href="{{ route('frontend.products.show', $product->slug) }}">
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                                    </a>

                                    {{-- BADGE (حقيقي) --}}
                                    @if($product->isSoldOut())
                                        <span class="badge-flag sold">SOLD</span>
                                    @elseif($product->isOnSale())
                                        <span class="badge-flag sale">SALE</span>
                                    @elseif($product->isNew())
                                        <span class="badge-flag new">NEW</span>
                                    @endif

                                    <div class="position-absolute bottom-0 start-0 end-0 pb-3
                                                d-flex justify-content-center gap-2 overlay-actions">

                                        {{-- Add to cart --}}
                                        @auth
                                            <form action="{{ route('frontend.cart.add', $product->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="qty" value="1">
                                                <button type="submit" class="btn btn-sm btn-dark px-3"
                                                        {{ $isSold ? 'disabled' : '' }}>
                                                    Add to cart
                                                </button>
                                            </form>
                                        @endauth

                                        @guest
                                            <a href="{{ route('login') }}"
                                               class="btn btn-sm btn-dark px-3 {{ $isSold ? 'disabled' : '' }}"
                                               @if($isSold) aria-disabled="true" @endif>
                                                Add to cart
                                            </a>
                                        @endguest

                                        {{-- Wishlist toggle --}}
                                        @auth
                                            <form action="{{ route('frontend.wishlist.add', $product->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-light d-flex align-items-center justify-content-center wishlist-btn {{ $isFav ? 'is-fav' : '' }}"
                                                        style="width:38px;height:38px;">
                                                    <span style="font-size:16px;">
                                                        {!! $isFav ? '♥' : '♡' !!}
                                                    </span>
                                                </button>
                                            </form>
                                        @endauth

                                        @guest
                                            <a href="{{ route('login') }}"
                                               class="btn btn-sm btn-light d-flex align-items-center justify-content-center"
                                               style="width:38px;height:38px;"
                                               title="Login first">
                                                <span style="font-size:16px;">♡</span>
                                            </a>
                                        @endguest

                                    </div>
                                </div>

                                <div class="p-3 text-center">
                                    <h6 class="mb-1" style="font-size:0.9rem;">
                                        <a href="{{ route('frontend.products.show', $product->slug) }}"
                                           class="text-decoration-none text-dark">
                                            {{ $product->name }}
                                        </a>
                                    </h6>
                                    <p class="text-muted small mb-1">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </p>

                                    {{-- PRICE (SALE support) --}}
                                    <p class="fw-bold mb-0" style="font-size:0.9rem;">
                                        @if($product->isOnSale())
                                            <span class="text-muted text-decoration-line-through me-1">
                                                ${{ number_format($product->compare_price, 2) }}
                                            </span>
                                        @endif
                                        ${{ number_format($product->price, 2) }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted mb-0">No products found.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($isPaginated)
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</section>

@endsection
