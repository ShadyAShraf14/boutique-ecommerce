{{-- resources/views/Frontend/pages/wishlist.blade.php --}}
@extends('Frontend.inc.master')

@section('content')

    <style>
        /* ====== PRODUCT CARD STYLE (نفس شكل الشوب تقريبًا) ====== */
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
            height: 260px;      /* هنا بنظبط الارتفاع الثابت للصورة */
            object-fit: cover;  /* عشان ما تتمطش وتبقى مظبوطة */
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

        .wishlist-btn.is-fav {
            background-color: #ffe6ec !important;
            border-color: #e63946 !important;
            color: #e63946 !important;
        }
    </style>

    @php
        $wishlistIds = session('wishlist', []);
    @endphp

    <section class="py-5">
        <div class="container">

            {{-- هيدر بسيط --}}
            <div class="bg-light py-4 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h5 mb-0">My wishlist</h1>
                    <nav class="small text-muted">
                        <a href="{{ route('frontend.home') }}" class="text-muted text-decoration-none">Home</a>
                        <span class="mx-1">/</span>
                        <span class="text-dark">Wishlist</span>
                    </nav>
                </div>
            </div>

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

                                    {{-- Wishlist toggle (remove من الليست لو متعلم) --}}
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
                                <p class="fw-bold mb-0" style="font-size:0.9rem;">
                                    ${{ number_format($product->price, 2) }}
                                </p>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted mb-0">No products in your wishlist yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
