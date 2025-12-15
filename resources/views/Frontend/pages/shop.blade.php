{{-- resources/views/Frontend/pages/products.blade.php --}}
@extends('Frontend.inc.master')

@section('content')

@php
    // نتأكد إن عندنا كولكشن أو Paginator
    $isPaginated = $products instanceof \Illuminate\Pagination\LengthAwarePaginator;
    $wishlistIds = session('wishlist', []);
@endphp

<style>
    .shop-page-hero {
        padding: 24px 0;
        border-bottom: 1px solid #eee;
        background: #fafafa;
    }
    .shop-title {
        font-size: 1.4rem;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
    }
    .shop-breadcrumb a {
        color: #999;
        text-decoration: none;
        font-size: .85rem;
    }
    .shop-breadcrumb a:hover { color: #000; }

    /* Sidebar */
    .shop-sidebar {
        font-size: .9rem;
    }
    .shop-sidebar h6 {
        font-size: .85rem;
        text-transform: uppercase;
        letter-spacing: .12em;
        margin-bottom: .75rem;
    }
    .shop-sidebar .cat-link {
        display: block;
        padding: 4px 0;
        color: #555;
        text-decoration: none;
        font-size: .9rem;
    }
    .shop-sidebar .cat-link.active,
    .shop-sidebar .cat-link:hover {
        color: #000;
        font-weight: 600;
    }

    .filter-label {
        font-size: .8rem;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: #999;
        margin-bottom: .25rem;
    }

    /* Products grid */
    .shop-toolbar {
        font-size: .85rem;
    }
    .shop-toolbar .form-select {
        font-size: .85rem;
        padding: .25rem .75rem;
        height: 32px;
    }

    .product-card {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #f0f0f0;
        transition: box-shadow .2s ease, transform .2s ease;
        background: #fff;
    }
    .product-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        transform: translateY(-3px);
    }
    .product-card-img-wrapper {
        position: relative;
        overflow: hidden;
    }
    .product-card-img-wrapper img {
        width: 100%;
        height: 230px;
        object-fit: cover;
        display: block;
        transition: all .25s ease;
    }
    .product-card:hover .product-card-img-wrapper img {
        opacity: .35;
        transform: scale(1.03);
    }
    .product-card .overlay-actions {
        opacity: 0;
        visibility: hidden;
        transform: translateY(12px);
        transition: all .25s ease;
    }
    .product-card:hover .overlay-actions {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    .badge-flag {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 10px;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 999px;
        font-weight: 600;
        letter-spacing: .08em;
    }
    .badge-flag.sale { background:#f7e0a3; color:#333; }
    .badge-flag.new  { background:#38cfd9; color:#fff; }
    .badge-flag.sold { background:#e74c3c; color:#fff; }

    .wishlist-btn.is-fav {
        background-color: #ffe6ec !important;
        border-color: #e63946 !important;
        color: #e63946 !important;
    }
</style>

{{-- ===== HERO / BREADCRUMB ===== --}}
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

            @if($isPaginated)
                <div class="text-muted small">
                    Showing
                    {{ $products->firstItem() }}–{{ $products->lastItem() }}
                    of {{ $products->total() }} results
                </div>
            @else
                <div class="text-muted small">
                    Showing {{ $products->count() }} results
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ===== MAIN CONTENT ===== --}}
<section class="py-4">
    <div class="container">
        <div class="row g-4">

            {{-- SIDEBAR --}}
            <aside class="col-lg-3 shop-sidebar">

                {{-- Categories (لو عندك كاتيجوريز حقيقية استخدميها) --}}
                <div class="mb-4">
                    <h6>Categories</h6>
                    @php
                        $categories = $categories ?? collect();
                    @endphp

                    @if($categories->count())
                        @foreach($categories as $cat)
                            <a href="{{ route('frontend.products.index', ['category' => $cat->slug]) }}"
                               class="cat-link">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    @else
                        <a href="{{ route('frontend.products.index') }}" class="cat-link active">All products</a>
                        <a href="#" class="cat-link">Fashion &amp; Acc</a>
                        <a href="#" class="cat-link">Health &amp; Beauty</a>
                        <a href="#" class="cat-link">Electronics</a>
                    @endif
                </div>

                {{-- Price filter (ديكور فقط حالياً) --}}
                <div class="mb-4">
                    <div class="filter-label">Price range</div>
                    <input type="range" class="form-range" min="0" max="1000">
                    <div class="d-flex justify-content-between small text-muted">
                        <span>$0</span>
                        <span>$1000</span>
                    </div>
                </div>

                {{-- Checkboxes demo --}}
                <div class="mb-4">
                    <div class="filter-label">Show only</div>
                    <div class="form-check small">
                        <input class="form-check-input" type="checkbox" id="filter1">
                        <label class="form-check-label" for="filter1">Returns accepted</label>
                    </div>
                    <div class="form-check small">
                        <input class="form-check-input" type="checkbox" id="filter2">
                        <label class="form-check-label" for="filter2">Free shipping</label>
                    </div>
                </div>

            </aside>

            {{-- PRODUCTS GRID --}}
            <div class="col-lg-9">

                {{-- Toolbar --}}
                <div class="d-flex justify-content-between align-items-center mb-3 shop-toolbar">
                    <div class="text-muted small">
                        {{ $isPaginated
                            ? "Showing {$products->firstItem()}–{$products->lastItem()} of {$products->total()} results"
                            : "Showing {$products->count()} results" }}
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small me-1">Sort by</span>
                        <select class="form-select form-select-sm" style="max-width: 180px;">
                            <option>Default</option>
                            <option>Price: low to high</option>
                            <option>Price: high to low</option>
                            <option>Newest</option>
                        </select>
                    </div>
                </div>

                {{-- Grid --}}
                <div class="row g-4">
                    @forelse($products as $product)
                        @php
                            $imageUrl = $product->getFirstMediaUrl('image')
                                ?: asset('frontend-assets/img/default-product.jpg');

                            $badges    = ['new', 'sale', 'sold'];
                            $badgeType = $badges[$loop->index % 3];

                            $isFav = in_array($product->id, $wishlistIds);
                        @endphp

                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="product-card h-100">

                                <div class="product-card-img-wrapper">
                                    <a href="{{ route('frontend.products.show', $product->slug) }}">
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                                    </a>

                                    <span class="badge-flag {{ $badgeType }}">
                                        {{ strtoupper($badgeType) }}
                                    </span>

                                    <div class="position-absolute bottom-0 start-0 end-0 pb-3
                                                d-flex justify-content-center gap-2 overlay-actions">

                                        {{-- Add to cart --}}
                                        <form action="{{ route('frontend.cart.add', $product->id) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            <input type="hidden" name="qty" value="1">
                                            <button type="submit" class="btn btn-sm btn-dark px-3">
                                                Add to cart
                                            </button>
                                        </form>

                                        {{-- Wishlist toggle --}}
                                        <form action="{{ route('frontend.wishlist.add', $product->id) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-light d-flex align-items-center justify-content-center wishlist-btn {{ $isFav ? 'is-fav' : '' }}"
                                                    style="width:38px;height:38px;">
                                                <span style="font-size:16px;">
                                                    {!! $isFav ? '♥' : '♡' !!}
                                                </span>
                                            </button>
                                        </form>
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
                                    <p class="fw-bold mb-0" style="font-size:0.9rem;">
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
