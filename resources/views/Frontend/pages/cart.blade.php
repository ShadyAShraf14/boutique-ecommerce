@extends('Frontend.inc.master')

@section('content')
<section class="py-5">
    <div class="container">

        {{-- الهيدر الرمادي اللي مكتوب فيه CART --}}
        <div class="bg-light py-4 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h4 mb-0">CART</h1>
                <nav class="small text-muted">
                    <a href="{{ route('frontend.home') }}" class="text-muted text-decoration-none">Home</a>
                    <span class="mx-1">/</span>
                    <span class="text-dark">Cart</span>
                </nav>
            </div>
        </div>

        <div class="row">

            {{-- جدول الكارت --}}
            <div class="col-md-8 mb-4">
                <h5 class="mb-3">Shopping cart</h5>

                @if(empty($cart))
                    <p class="text-muted">Your cart is empty.</p>
                    <a href="{{ route('frontend.products.index') }}" class="btn btn-outline-dark btn-sm">
                        Continue shopping
                    </a>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="small text-uppercase text-muted">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th style="width: 150px;">Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $row)
                                    @php
                                        $product = \App\Models\Product::find($row['id']);
                                        $img = $product?->getFirstMediaUrl('image')
                                            ?: asset('frontend-assets/img/default-product.jpg');
                                        $lineTotal = $row['price'] * $row['qty'];
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ $product ? route('frontend.products.show', $product->slug) : '#' }}">
                                                    <img src="{{ $img }}" alt="{{ $row['name'] }}" width="60" class="me-2">
                                                </a>
                                                <span>{{ $row['name'] }}</span>
                                            </div>
                                        </td>
                                        <td>${{ number_format($row['price'], 2) }}</td>
                                        <td>
                                            <form action="{{ route('frontend.cart.update', $row['id']) }}"
                                                  method="POST"
                                                  class="d-flex align-items-center">
                                                @csrf
                                                <input type="number" name="qty"
                                                       min="1"
                                                       value="{{ $row['qty'] }}"
                                                       class="form-control form-control-sm"
                                                       style="width: 70px;">
                                                <button class="btn btn-sm btn-outline-secondary ms-2">
                                                    Update
                                                </button>
                                            </form>
                                        </td>
                                        <td>${{ number_format($lineTotal, 2) }}</td>
                                        <td class="text-end">
                                            <form action="{{ route('frontend.cart.remove', $row['id']) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Remove this item from cart?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-link text-danger" type="submit">
                                                    {{-- لو عندك Bootstrap Icons استخدم bi-trash، لو لأ استخدم FontAwesome --}}
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="{{ route('frontend.products.index') }}" class="btn btn-link text-muted">
                            ← Continue shopping
                        </a>

                        <form action="{{ route('frontend.cart.clear') }}" method="POST"
                              onsubmit="return confirm('Clear entire cart?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                Clear cart
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- إجمالي الكارت --}}
{{-- إجمالي الكارت --}}
<div class="col-md-4">
    <h5 class="mb-3">Cart total</h5>

    <div class="border p-3">

        {{-- Subtotal / Total (لسه بدون خصم في صفحة الكارت) --}}
        <div class="d-flex justify-content-between mb-2">
            <span>Subtotal</span>
            <strong>${{ number_format($subtotal, 2) }}</strong>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <span>Total</span>
            <strong>${{ number_format($subtotal, 2) }}</strong>
        </div>

        {{-- FORM: Apply coupon → يروح لـ CheckoutController@applyCoupon --}}
        <form action="{{ route('frontend.checkout.applyCoupon') }}"
              method="POST"
              class="mb-3">
            @csrf

            <div class="input-group input-group-sm">
                <input type="text"
                       name="coupon_code"
                       class="form-control"
                       placeholder="Enter coupon code"
                       value="{{ old('coupon_code', session('coupon_code')) }}">
                <button type="submit" class="btn btn-dark">
                    Apply coupon
                </button>
            </div>
        </form>

        {{-- زرار الذهاب لصفحة الـ Checkout --}}
        <a href="{{ route('frontend.checkout.index') }}"
           class="btn btn-outline-dark w-100">
            Proceed to checkout
        </a>

    </div>
</div>

        </div>

    </div>
</section>
@endsection
