{{-- resources/views/Frontend/pages/home.blade.php --}}
@extends('Frontend.inc.master')

@section('content')

@php
    $trendingProducts = $trendingProducts ?? collect();
    $wishlistIds      = session('wishlist', []);   // IDs of wishlisted products
@endphp

<style>
    /* ======= CATEGORIES GRID ======= */
    .categories-grid {
        max-width: 900px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-auto-rows: 260px;
        gap: 24px;
    }

    .category-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
    }

    .category-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .category-label {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        padding: 5px 18px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 11px;
    }

    .category-item.clothes     { grid-column: 1; grid-row: 1 / span 2; }
    .category-item.shoes       { grid-column: 2; grid-row: 1;          }
    .category-item.watches     { grid-column: 2; grid-row: 2;          }
    .category-item.electronics { grid-column: 3; grid-row: 1 / span 2; }

    /* ======= PRODUCT CARDS (TRENDING) ======= */
    .product-card {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #f0f0f0;
        transition: box-shadow .2s ease, transform .2s ease;
    }

    .product-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        transform: translateY(-3px);
    }

    .product-card .product-card-img-wrapper {
        position: relative;
        overflow: hidden;
    }

    .product-card .product-card-img-wrapper img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
        transition: all .25s ease;
    }

    .product-card:hover .product-card-img-wrapper img {
        opacity: 0.35;
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
        font-size: 11px;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 2px;
        font-weight: 600;
        letter-spacing: .05em;
    }

    .badge-flag.sale { background-color: #f7e0a3; color:#333; }
    .badge-flag.new  { background-color: #38cfd9; color:#fff; }
    .badge-flag.sold { background-color: #e74c3c; color:#fff; }

    /* Wishlist button state */
    .wishlist-btn.is-fav {
        background-color: #ffe6ec !important;
        border-color: #e63946 !important;
        color: #e63946 !important;
    }

    /* ======= SERVICE STRIP + NEWSLETTER SPACING ======= */
    .service-strip {
        background: #fafafa;
        border-top: 1px solid #eeeeee;
        border-bottom: 1px solid #eeeeee;
        padding: 2.5rem 0;
        margin-top: 1rem;
    }

    .service-strip h6 {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .service-strip p {
        margin-bottom: 0;
        font-size: 0.9rem;
        color: #777;
    }

    .newsletter-section {
        background: #ffffff;
        padding: 2.75rem 0;
        margin-top: -1px;
        margin-bottom: 3rem;
        box-shadow: 0 -6px 25px rgba(0,0,0,0.03);
        position: relative;
        z-index: 2;
    }

    .newsletter-section h5 {
        font-weight: 600;
    }

    .newsletter-section .newsletter-input .form-control {
        border-radius: 999px 0 0 999px;
    }

    .newsletter-section .newsletter-input .btn {
        border-radius: 0 999px 999px 0;
        padding-inline: 1.5rem;
    }

    @media (max-width: 767.98px) {
        .newsletter-section .newsletter-input {
            max-width: 100%;
        }
    }
</style>

{{-- HERO --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-md-6 mb-4 mb-md-0">
                <p class="text-uppercase small text-muted mb-1">New Inspiration 2025</p>
                <h1 class="h2 mb-3">20% OFF ON NEW SEASON</h1>
                <p class="mb-4">Discover our latest collection of fashion, electronics and more.</p>
                <a href="{{ route('frontend.products.index') }}" class="btn btn-dark">Browse collection</a>
            </div>

            <div class="col-md-6 text-center">
                <img src="{{ asset('static/img/hero-banner-alt.jpg') }}" class="img-fluid" alt="Hero banner">
            </div>

        </div>
    </div>
</section>

{{-- BROWSE CATEGORIES --}}
<section class="py-5">
    <div class="container text-center">

        <p class="text-uppercase small text-muted mb-1">
            Carefully curated collections
        </p>
        <h2 class="h4 mb-4">Browse Our Categories</h2>

        <div class="categories-grid">

            <a href="{{ route('frontend.products.index', ['category' => 'clothes']) }}"
               class="category-item clothes">
                <img src="{{ asset('static/img/cat-img-1.jpg') }}" alt="Clothes">
                <span class="category-label">Clothes</span>
            </a>

            <a href="{{ route('frontend.products.index', ['category' => 'shoes']) }}"
               class="category-item shoes">
                <img src="{{ asset('static/img/cat-img-2.jpg') }}" alt="Shoes">
                <span class="category-label">Shoes</span>
            </a>

            <a href="{{ route('frontend.products.index', ['category' => 'watches']) }}"
               class="category-item watches">
                <img src="{{ asset('static/img/product-4.jpg') }}" alt="Watches">
                <span class="category-label">Watches</span>
            </a>

            <a href="{{ route('frontend.products.index', ['category' => 'electronics']) }}"
               class="category-item electronics">
                <img src="{{ asset('static/img/cat-img-4.jpg') }}" alt="Electronics">
                <span class="category-label">Electronics</span>
            </a>

        </div>

    </div>
</section>

{{-- TOP TRENDING PRODUCTS --}}
<section class="py-5 bg-white">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <p class="text-uppercase text-muted small mb-0">This week's featured</p>
                <h3 class="h5 mb-0">Top Trending Products</h3>
            </div>
        </div>

        <div class="row g-4">
            @foreach($trendingProducts as $product)
                @php
                    $img = $product->getFirstMediaUrl('image')
                        ?: asset('frontend-assets/img/default-product.jpg');

                    $isFav = in_array($product->id, $wishlistIds);
                    $isSold = $product->isSoldOut();
                @endphp

                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100 product-card">

                        <div class="product-card-img-wrapper">
                            <a href="{{ route('frontend.products.show', $product->slug) }}">
                                <img src="{{ $img }}" alt="{{ $product->name }}">
                            </a>

                            @if($product->isSoldOut())
                                <span class="badge-flag sold">SOLD</span>
                            @elseif($product->isOnSale())
                                <span class="badge-flag sale">SALE</span>
                            @elseif($product->isNew(7))
                                <span class="badge-flag new">NEW</span>
                            @endif

                            <div class="position-absolute bottom-0 start-0 end-0 pb-3
                                        d-flex justify-content-center gap-2 overlay-actions">

                                {{-- Wishlist toggle --}}
                                @auth
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
                                @endauth

                                @guest
                                    <a href="{{ route('login') }}"
                                       class="btn btn-sm btn-light d-flex align-items-center justify-content-center"
                                       style="width:38px;height:38px;"
                                       title="Login first">
                                        <span style="font-size:16px;">♡</span>
                                    </a>
                                @endguest

                                {{-- Add to cart --}}
                                @auth
                                    <form action="{{ route('frontend.cart.add', $product->id) }}"
                                          method="POST"
                                          class="d-inline">
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

                                {{-- Quick View --}}
                                <button type="button"
                                        class="btn btn-sm btn-light d-flex align-items-center justify-content-center"
                                        style="width:38px;height:38px;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#quickViewModal"
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ $product->name }}"
                                        data-product-price="{{ $product->price }}"
                                        data-product-desc="{{ \Illuminate\Support\Str::limit($product->description, 150) }}"
                                        data-product-img="{{ $img }}">
                                    <span style="font-size:18px;">⤢</span>
                                </button>

                            </div>
                        </div>

                        <div class="card-body text-center">
                            <h6 class="mb-1" style="font-size:0.9rem;">
                                <a href="{{ route('frontend.products.show', $product->slug) }}"
                                   class="text-decoration-none text-dark">
                                    {{ $product->name }}
                                </a>
                            </h6>
                            <p class="text-muted small mb-1">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </p>

                            <p class="fw-bold mb-0">
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
            @endforeach
        </div>

    </div>
</section>

{{-- SERVICE STRIP --}}
<section class="service-strip">
    <div class="container">
        <div class="row text-center">

            <div class="col-md-4 mb-3 mb-md-0">
                <h6>Free shipping</h6>
                <p>Free shipping on all orders over $50</p>
            </div>

            <div class="col-md-4 mb-3 mb-md-0">
                <h6>24 x 7 service</h6>
                <p>We are here to help anytime</p>
            </div>

            <div class="col-md-4">
                <h6>Festival offers</h6>
                <p>Special discounts during the season</p>
            </div>

        </div>
    </div>
</section>

{{-- NEWSLETTER --}}
<section class="newsletter-section">
    <div class="container">

        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h5 class="mb-1" style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px;">
                    Let's be friends!
                </h5>
                <p class="mb-0" style="font-size: 0.85rem; color: #777;">
                    Get updates on sales, new arrivals and more.
                </p>
            </div>

            <div class="col-md-6 text-md-end">
                <form class="d-inline-block newsletter-input w-100 w-md-auto">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Enter your email address">
                        <button class="btn btn-dark" type="button">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</section>

{{-- QUICK VIEW MODAL --}}
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body p-4">
        <div class="row g-4">
          <div class="col-md-6">
            <img id="qvImage" src="" alt="" class="img-fluid">
          </div>
          <div class="col-md-6 position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0"
                    data-bs-dismiss="modal"></button>

            <p class="text-warning small mb-1">★★★★★</p>
            <h4 id="qvName" class="mb-1"></h4>
            <p class="h5 mb-3" id="qvPrice"></p>
            <p class="small text-muted" id="qvDesc"></p>

            {{-- Add to cart inside modal --}}
            @auth
                <form id="qvAddToCartForm" method="POST">
                    @csrf
                    <div class="d-flex align-items-center mb-3">
                        <span class="me-2 small text-uppercase text-muted">Quantity</span>
                        <input type="number" name="qty" min="1" value="1"
                               class="form-control form-control-sm" style="width:80px;">
                        <button class="btn btn-dark btn-sm ms-2" type="submit">
                            Add to cart
                        </button>
                    </div>
                </form>
            @endauth

            @guest
                <div class="d-flex align-items-center mb-3">
                    <span class="me-2 small text-uppercase text-muted">Quantity</span>
                    <input type="number" value="1" disabled
                           class="form-control form-control-sm" style="width:80px;">
                    <a class="btn btn-dark btn-sm ms-2" href="{{ route('login') }}">
                        Add to cart
                    </a>
                </div>
            @endguest

            {{-- Wishlist in modal (كان زرار وهمي) --}}
            @auth
                <form id="qvAddToWishlistForm" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 small">
                        ♥ Add to wishlist
                    </button>
                </form>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="btn btn-link p-0 small">
                    ♥ Add to wishlist
                </a>
            @endguest

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var qvModal = document.getElementById('quickViewModal');

    qvModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;

        var id    = button.getAttribute('data-product-id');
        var name  = button.getAttribute('data-product-name');
        var price = button.getAttribute('data-product-price');
        var desc  = button.getAttribute('data-product-desc');
        var img   = button.getAttribute('data-product-img');

        qvModal.querySelector('#qvName').textContent  = name;
        qvModal.querySelector('#qvPrice').textContent = '$' + parseFloat(price).toFixed(2);
        qvModal.querySelector('#qvDesc').textContent  = desc;
        qvModal.querySelector('#qvImage').setAttribute('src', img);

        // Action URLs
        @auth
            var form = qvModal.querySelector('#qvAddToCartForm');
            if (form) form.setAttribute('action', '{{ url('/cart/add') }}/' + id);

            var wForm = qvModal.querySelector('#qvAddToWishlistForm');
            if (wForm) wForm.setAttribute('action', '{{ url('/wishlist/add') }}/' + id);
        @endauth
    });
});
</script>

@endsection
