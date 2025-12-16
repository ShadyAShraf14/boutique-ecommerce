{{-- resources/views/Frontend/pages/product-show.blade.php --}}
@extends('Frontend.inc.master')

@section('content')

<style>
    .product-hero { background: #fafafa; border-bottom: 1px solid #eee; }
    .product-hero-title { font-size: 1.5rem; font-weight: 600; }
    .breadcrumb-mini a { color: #999; text-decoration: none; }
    .breadcrumb-mini a:hover { color: #000; }

    .product-gallery-wrapper { display: grid; grid-template-columns: 90px 1fr; gap: 16px; align-items: flex-start; }
    .product-gallery-main { border-radius: 12px; overflow: hidden; border: 1px solid #eee; background: #fff; }
    .product-gallery-main img { width: 100%; height: auto; max-height: 520px; object-fit: contain; display: block; transition: transform .25s ease; }
    .product-gallery-main:hover img { transform: scale(1.03); }
    .product-thumbs { display: flex; flex-direction: column; gap: 10px; }
    .product-thumbs img { width: 80px; height: 80px; object-fit: cover; border-radius: 10px; border: 1px solid transparent; cursor: pointer; background: #fff; transition: all .2s ease; }
    .product-thumbs img:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.08); }
    .product-thumbs img.active { border-color: #000; }

    @media (max-width: 767.98px) {
        .product-gallery-wrapper { grid-template-columns: 1fr; }
        .product-thumbs { flex-direction: row; }
    }

    .rating-stars { color: #f9b233; font-size: 0.9rem; }
    .product-meta-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: .12em; color: #999; }
    .product-meta-value { font-size: 0.9rem; }

    .price-main { font-size: 1.6rem; font-weight: 600; }
    .price-old { text-decoration: line-through; color: #aaa; font-size: 0.95rem; }

    .pill-badge { display:inline-flex; align-items:center; padding:2px 10px; border-radius:999px; font-size:0.75rem; border:1px solid #eee; background:#fff; margin-right:6px; }
    .pill-badge.in-stock { border-color:#28a74533; color:#28a745; }
    .pill-badge.sold-out { border-color:#e74c3c33; color:#e74c3c; }
    .pill-badge.fast-ship { border-color:#007bff33; color:#007bff; }
    .pill-badge.new { border-color:#38cfd933; color:#38cfd9; }
    .pill-badge.sale { border-color:#f7e0a333; color:#9b7a00; }

    .qty-wrapper { display:inline-flex; align-items:center; border-radius:999px; border:1px solid #ddd; overflow:hidden; }
    .qty-wrapper input { width:60px; border:0; text-align:center; font-size:0.9rem; }
    .qty-btn { width:32px; height:32px; border:0; background:#f7f7f7; font-size:0.9rem; cursor:pointer; }
    .qty-btn:hover { background:#eee; }

    .nav-tabs .nav-link { text-transform: uppercase; font-size: 0.75rem; letter-spacing: .14em; }
    .feature-list { list-style:none; padding-left:0; font-size:0.9rem; }
    .feature-list li::before { content:"•"; color:#f9b233; display:inline-block; width:1em; margin-left:-1em; }

    .related-product-card img { width:100%; height:220px; object-fit:cover; border-radius:10px 10px 0 0; }
    .related-product-card { border-radius:12px; overflow:hidden; border:1px solid #f0f0f0; transition: box-shadow .2s ease, transform .2s ease; }
    .related-product-card:hover { box-shadow:0 12px 30px rgba(0,0,0,0.07); transform: translateY(-3px); }

    .badge-flag { position:absolute; top:12px; left:12px; font-size:11px; text-transform:uppercase; padding:4px 12px; border-radius:999px; font-weight:600; letter-spacing:.05em; box-shadow:0 6px 18px rgba(0,0,0,0.1); }
    .badge-flag.new { background:#38cfd9; color:#fff; }
    .badge-flag.sale { background:#f7e0a3; color:#333; }
    .badge-flag.sold { background:#e74c3c; color:#fff; }

    .wishlist-btn.is-fav { background-color:#ffe6ec !important; border-color:#e63946 !important; color:#e63946 !important; }
</style>

@php
    $mediaItems = isset($mediaItems)
        ? ($mediaItems instanceof \Illuminate\Support\Collection ? $mediaItems : collect($mediaItems))
        : collect();

    if ($mediaItems->isEmpty() && !empty($mainImage)) {
        $mediaItems = collect([(object) ['original_url' => $mainImage]]);
    }

    $wishlistIds = session('wishlist', []);
    $isFav = in_array($product->id, $wishlistIds);

    $isSold = $product->isSoldOut();
    $isSale = $product->isOnSale();
    $isNew  = $product->isNew();
@endphp

{{-- HERO / BREADCRUMB --}}
<section class="product-hero py-3 mb-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <div class="breadcrumb-mini small text-muted mb-1">
                    <a href="{{ route('frontend.products.index') }}">Shop</a>
                    @if ($product->category)
                        <span class="mx-1">/</span>
                        <a href="{{ route('frontend.products.index', ['category' => $product->category->slug]) }}">
                            {{ $product->category->name }}
                        </a>
                    @endif
                    <span class="mx-1">/</span>
                    <span class="text-dark">{{ $product->name }}</span>
                </div>
                <h1 class="product-hero-title mb-0">{{ $product->name }}</h1>
            </div>

            <div class="text-end small text-muted">
                <div>SKU: <strong>{{ $product->sku ?? $product->id }}</strong></div>
                <div>ID: #{{ $product->id }}</div>
            </div>
        </div>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <div class="row g-5">

            {{-- LEFT: GALLERY --}}
            <div class="col-lg-6">
                <div class="product-gallery-wrapper">

                    {{-- Thumbs --}}
                    <div class="product-thumbs">
                        @foreach ($mediaItems as $index => $media)
                            @php
                                if (method_exists($media, 'getUrl')) {
                                    $thumbUrl = $media->getUrl('thumb') ?: $media->getUrl();
                                    $fullUrl = $media->getUrl();
                                } else {
                                    $thumbUrl = $media->original_url ?? '';
                                    $fullUrl = $media->original_url ?? '';
                                }
                            @endphp

                            <img src="{{ $thumbUrl }}" data-full="{{ $fullUrl }}"
                                 alt="Thumb {{ $index + 1 }}" class="{{ $index === 0 ? 'active' : '' }}">
                        @endforeach
                    </div>

                    {{-- Main image --}}
                    <div class="product-gallery-main">
                        <img id="mainProductImage" src="{{ $mainImage }}" alt="{{ $product->name }}">
                    </div>

                </div>
            </div>

            {{-- RIGHT: INFO --}}
            <div class="col-lg-6">

                {{-- Rating --}}
                <div class="d-flex align-items-center mb-2">
                    <div class="rating-stars me-2">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($avgRating))
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                    <small class="text-muted">
                        {{ number_format($avgRating, 1) }} / 5
                        ({{ $reviewsCount }} {{ \Illuminate\Support\Str::plural('review', $reviewsCount) }})
                    </small>
                </div>

                {{-- Price --}}
                <div class="d-flex align-items-baseline gap-3 mb-2">
                    <div class="price-main">${{ number_format($product->price, 2) }}</div>
                    @if($isSale)
                        <div class="price-old">${{ number_format($product->compare_price, 2) }}</div>
                    @endif
                </div>

                {{-- Pills --}}
                <div class="mb-3">
                    @if($isSold)
                        <span class="pill-badge sold-out">Sold out</span>
                    @else
                        <span class="pill-badge in-stock">In stock</span>
                        <span class="pill-badge fast-ship">Ships in 24h</span>
                    @endif

                    @if($isSale)
                        <span class="pill-badge sale">Sale</span>
                    @elseif($isNew)
                        <span class="pill-badge new">New</span>
                    @endif
                </div>

                {{-- Short description --}}
                <p class="text-muted" style="font-size:0.9rem;">
                    {{ \Illuminate\Support\Str::limit($product->description ?? 'No description yet.', 260) }}
                </p>

                {{-- Quantity + actions --}}
                <div class="mb-3 d-flex flex-wrap align-items-center gap-3">

                    {{-- ADD TO CART --}}
                    @auth
                        <form action="{{ route('frontend.cart.add', $product->id) }}" method="POST"
                              class="d-flex align-items-center gap-2">
                            @csrf

                            <div>
                                <div class="product-meta-label mb-1">Quantity</div>
                                <div class="qty-wrapper">
                                    <button type="button" class="qty-btn" data-qty-change="-1" {{ $isSold ? 'disabled' : '' }}>–</button>
                                    <input type="number" name="qty" id="qtyInput" min="1" value="1" {{ $isSold ? 'disabled' : '' }}>
                                    <button type="button" class="qty-btn" data-qty-change="1" {{ $isSold ? 'disabled' : '' }}>+</button>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-dark btn-sm ms-2" {{ $isSold ? 'disabled' : '' }}>
                                Add to cart
                            </button>
                        </form>
                    @endauth

                    @guest
                        <div class="d-flex align-items-center gap-2">
                            <div>
                                <div class="product-meta-label mb-1">Quantity</div>
                                <div class="qty-wrapper">
                                    <button type="button" class="qty-btn" disabled>–</button>
                                    <input type="number" value="1" disabled>
                                    <button type="button" class="qty-btn" disabled>+</button>
                                </div>
                            </div>

                            <a href="{{ route('login') }}" class="btn btn-dark btn-sm ms-2">
                                Add to cart
                            </a>
                        </div>
                    @endguest

                    {{-- WISHLIST --}}
                    @auth
                        <form action="{{ route('frontend.wishlist.add', $product->id) }}" method="POST" class="d-inline">
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

                {{-- Meta info --}}
                <div class="row g-2 small mb-3">
                    <div class="col-sm-6">
                        <div class="product-meta-label">Category</div>
                        <div class="product-meta-value">
                            @if ($product->category)
                                <a href="{{ route('frontend.products.index', ['category' => $product->category->slug]) }}"
                                   class="text-decoration-none">
                                    {{ $product->category->name }}
                                </a>
                            @else
                                Uncategorized
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="product-meta-label">Tags</div>
                        <div class="product-meta-value">
                            @if (isset($product->tags) && $product->tags->count())
                                @foreach ($product->tags as $tag)
                                    <span class="badge bg-light text-dark border me-1 mb-1">{{ $tag->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">No tags</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <div class="product-meta-label">Highlights</div>
                        <ul class="feature-list mb-0">
                            <li>Premium quality materials with a modern finish.</li>
                            <li>Perfect for everyday use, work, or travel.</li>
                            <li>Easy to style and pair with your wardrobe.</li>
                        </ul>
                    </div>
                </div>

            </div>

            {{-- TABS --}}
            <div class="mt-5">
                <ul class="nav nav-tabs" id="productTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab">
                            Description
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-pane" type="button" role="tab">
                            Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-pane" type="button" role="tab">
                            Reviews
                        </button>
                    </li>
                </ul>

                <div class="tab-content border border-top-0 p-4 small bg-white" id="productTabContent">
                    <div class="tab-pane fade show active" id="desc-pane" role="tabpanel">
                        {!! nl2br(e($product->description ?? 'No description available.')) !!}
                    </div>

                    <div class="tab-pane fade" id="details-pane" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>SKU:</strong> {{ $product->sku ?? $product->id }}</p>
                                <p class="mb-1"><strong>Category:</strong> {{ $product->category->name ?? 'Uncategorized' }}</p>
                                <p class="mb-1"><strong>Price:</strong>
                                    @if($isSale)
                                        <span class="text-muted text-decoration-line-through me-1">
                                            ${{ number_format($product->compare_price, 2) }}
                                        </span>
                                    @endif
                                    ${{ number_format($product->price, 2) }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Care:</strong> Hand wash recommended.</p>
                                <p class="mb-1"><strong>Origin:</strong> Imported.</p>
                                <p class="mb-1"><strong>Warranty:</strong> 1 year limited warranty.</p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="reviews-pane" role="tabpanel">
                        @if (session('success'))
                            <div class="alert alert-success small">{{ session('success') }}</div>
                        @endif

                        @if ($reviewsCount > 0)
                            <div class="mb-3">
                                <h6 class="mb-1">Customer reviews</h6>
                                <div class="d-flex align-items-center">
                                    <div class="rating-stars me-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($avgRating)) ★ @else ☆ @endif
                                        @endfor
                                    </div>
                                    <small class="text-muted">
                                        {{ number_format($avgRating, 1) }} out of 5
                                        ({{ $reviewsCount }} {{ \Illuminate\Support\Str::plural('review', $reviewsCount) }})
                                    </small>
                                </div>
                            </div>

                            @foreach ($reviews as $review)
                                <div class="border rounded-3 p-3 mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <strong>{{ $review->user->name ?? 'Guest user' }}</strong>
                                        <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                                    </div>

                                    <div class="rating-stars mb-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating) ★ @else ☆ @endif
                                        @endfor
                                    </div>

                                    @if ($review->title)
                                        <div class="fw-semibold mb-1" style="font-size:0.9rem;">{{ $review->title }}</div>
                                    @endif

                                    @if ($review->comment)
                                        <p class="mb-0" style="font-size:0.9rem;">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted small mb-3">
                                No reviews yet. Be the first to review this product.
                            </p>
                        @endif

                        @auth
                            <hr>
                            <h6 class="mb-2">Write a review</h6>

                            <form method="POST" action="{{ route('frontend.products.reviews.store', $product) }}" class="small">
                                @csrf

                                <div class="mb-2">
                                    <label class="form-label mb-1">Rating</label>
                                    <select name="rating" class="form-select form-select-sm @error('rating') is-invalid @enderror"
                                            style="max-width: 180px;" required>
                                        <option value="">Select rating</option>
                                        @for ($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                                {{ $i }} / 5
                                            </option>
                                        @endfor
                                    </select>
                                    @error('rating')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label class="form-label mb-1">Title (optional)</label>
                                    <input type="text" name="title"
                                           class="form-control form-control-sm @error('title') is-invalid @enderror"
                                           value="{{ old('title') }}" maxlength="255">
                                    @error('title')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label mb-1">Comment (optional)</label>
                                    <textarea name="comment" rows="3"
                                              class="form-control form-control-sm @error('comment') is-invalid @enderror"
                                              maxlength="2000">{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-dark btn-sm">Submit review</button>
                            </form>
                        @else
                            <hr>
                            <p class="small mb-0"><a href="{{ route('login') }}">Login</a> to write a review.</p>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- RELATED PRODUCTS --}}
            @if ($relatedProducts->count())
                <div class="mt-5">
                    <h4 class="h5 mb-4">You may also like</h4>

                    <div class="row g-4">
                        @foreach ($relatedProducts as $related)
                            @php
                                $relatedImg =
                                    $related->getFirstMediaUrl('image')
                                    ?: asset('frontend-assets/img/default-product.jpg');
                            @endphp

                            <div class="col-6 col-md-3">
                                <div class="related-product-card h-100 position-relative">
                                    <a href="{{ route('frontend.products.show', $related->slug) }}">
                                        <img src="{{ $relatedImg }}" alt="{{ $related->name }}">

                                        {{-- BADGE (حقيقي) --}}
                                        @if($related->isSoldOut())
                                            <span class="badge-flag sold">SOLD</span>
                                        @elseif($related->isOnSale())
                                            <span class="badge-flag sale">SALE</span>
                                        @elseif($related->isNew())
                                            <span class="badge-flag new">NEW</span>
                                        @endif
                                    </a>

                                    <div class="p-3 text-center">
                                        <h6 class="mb-1" style="font-size: 0.9rem;">
                                            <a href="{{ route('frontend.products.show', $related->slug) }}"
                                               class="text-decoration-none text-dark">
                                                {{ $related->name }}
                                            </a>
                                        </h6>
                                        <p class="text-muted small mb-1">
                                            {{ $related->category->name ?? 'Uncategorized' }}
                                        </p>

                                        {{-- PRICE --}}
                                        <p class="fw-bold mb-0" style="font-size: 0.9rem;">
                                            @if($related->isOnSale())
                                                <span class="text-muted text-decoration-line-through me-1">
                                                    ${{ number_format($related->compare_price, 2) }}
                                                </span>
                                            @endif
                                            ${{ number_format($related->price, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endif

        </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mainImg = document.getElementById('mainProductImage');
    const thumbs = document.querySelectorAll('.product-thumbs img');

    thumbs.forEach(function(thumb) {
        thumb.addEventListener('click', function() {
            const full = this.getAttribute('data-full');
            if (full) mainImg.setAttribute('src', full);

            thumbs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Quantity buttons (موجودة للـ auth فقط، بس الكود آمن حتى لو guest)
    const qtyInput = document.getElementById('qtyInput');
    const qtyBtns = document.querySelectorAll('[data-qty-change]');

    qtyBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (!qtyInput) return;

            const change = parseInt(this.getAttribute('data-qty-change'), 10);
            let current = parseInt(qtyInput.value || '1', 10);
            current = current + change;
            if (current < 1) current = 1;
            qtyInput.value = current;
        });
    });
});
</script>

@endsection
