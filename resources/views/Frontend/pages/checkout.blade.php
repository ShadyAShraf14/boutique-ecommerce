@extends('Frontend.inc.master')

@section('content')
    <style>
        .checkout-hero {
            background: #fafafa;
            padding: 40px 0;
            border-bottom: 1px solid #eee;
        }

        .address-card {
            border: 1px solid #e5e5e5;
            border-radius: 6px;
            padding: 12px 14px;
            cursor: pointer;
            background: #fff;
            transition: .2s;
        }

        .address-card.active {
            border-color: #111;
            box-shadow: 0 0 0 1px #111;
        }

        .address-label {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .address-meta {
            font-size: 0.8rem;
            color: #777;
            line-height: 1.35rem;
        }

        .order-summary-card {
            border: 1px solid #eee;
            border-radius: 6px;
            padding: 20px;
            background: #fff;
        }
    </style>

    {{-- HERO --}}
    <section class="checkout-hero">
        <div class="container">
            <h1 class="h4 mb-0">Checkout</h1>
        </div>
    </section>

    {{-- رسائل الفلاش --}}
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">

                {{-- LEFT COLUMN --}}
                <div class="col-lg-8">

                    {{-- SHIPPING ADDRESSES --}}
                    <h5 class="mb-3">Shipping addresses</h5>

                    @guest
                        <div class="alert alert-warning small">
                            Please <a href="{{ route('login') }}">login</a> to continue checkout.
                        </div>
                    @else
                        {{-- لو مفيش عناوين --}}
                        @if ($addresses->isEmpty())
                            <div class="alert alert-info small">
                                You have no saved addresses. Please add one:
                            </div>

                            {{-- FORM ADD ADDRESS (أول مرة بس) --}}
                            <form action="{{ route('frontend.checkout.storeAddress') }}" method="POST"
                                  class="border rounded p-3 mb-4">
                                @csrf

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label small">First name</label>
                                        <input type="text" name="first_name" class="form-control form-control-sm" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small">Last name</label>
                                        <input type="text" name="last_name" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small">Phone</label>
                                        <input type="text" name="phone" class="form-control form-control-sm" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small">Country</label>
                                        <select name="country_id" class="form-select form-select-sm" required>
                                            @foreach ($countries as $c)
                                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small">State</label>
                                        <select name="state_id" class="form-select form-select-sm" required>
                                            @foreach ($states as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small">City</label>
                                        <select name="city_id" class="form-select form-select-sm" required>
                                            @foreach ($cities as $ct)
                                                <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label small">Address line 1</label>
                                        <input type="text" name="address_line1" class="form-control form-control-sm"
                                               required>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label small">Address line 2</label>
                                        <input type="text" name="address_line2" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label small">Postal code</label>
                                        <input type="text" name="postal_code" class="form-control form-control-sm">
                                    </div>

                                    <input type="hidden" name="is_default_shipping" value="1">
                                    <input type="hidden" name="is_default_billing" value="1">
                                    <input type="hidden" name="is_active" value="1">

                                    <div class="col-12 mt-2">
                                        <button class="btn btn-dark btn-sm" type="submit">Save address</button>
                                    </div>

                                </div>
                            </form>
                        @else
                            {{-- لو فيه عناوين جاهزة --}}
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Select one of your saved addresses.</small>
                                <a href="#" id="toggle-new-address" class="small text-decoration-underline">
                                    + Add new address
                                </a>
                            </div>

                            {{-- FORM ADD ADDRESS (إضافة جديدة من نفس الصفحة) --}}
                            <div id="new-address-wrapper" class="border rounded p-3 mb-4" style="display: none;">
                                <form action="{{ route('frontend.checkout.storeAddress') }}" method="POST">
                                    @csrf

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small">First name</label>
                                            <input type="text" name="first_name" class="form-control form-control-sm"
                                                   required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small">Last name</label>
                                            <input type="text" name="last_name" class="form-control form-control-sm">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small">Phone</label>
                                            <input type="text" name="phone" class="form-control form-control-sm" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small">Country</label>
                                            <select name="country_id" class="form-select form-select-sm" required>
                                                @foreach ($countries as $c)
                                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small">State</label>
                                            <select name="state_id" class="form-select form-select-sm" required>
                                                @foreach ($states as $s)
                                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label small">City</label>
                                            <select name="city_id" class="form-select form-select-sm" required>
                                                @foreach ($cities as $ct)
                                                    <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label small">Address line 1</label>
                                            <input type="text" name="address_line1" class="form-control form-control-sm"
                                                   required>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label small">Address line 2</label>
                                            <input type="text" name="address_line2" class="form-control form-control-sm">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label small">Postal code</label>
                                            <input type="text" name="postal_code" class="form-control form-control-sm">
                                        </div>

                                        <input type="hidden" name="is_default_shipping" value="1">
                                        <input type="hidden" name="is_default_billing" value="1">
                                        <input type="hidden" name="is_active" value="1">

                                        <div class="col-12 mt-2">
                                            <button class="btn btn-dark btn-sm" type="submit">
                                                Save address
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{-- كروت اختيار العنوان --}}
                            <form action="{{ route('frontend.checkout.selectAddress') }}" id="address-form" method="POST">
                                @csrf
                                <div class="row g-3">
                                    @foreach ($addresses as $address)
                                        <div class="col-md-6">
                                            <label
                                                class="address-card {{ isset($selectedAddress) && $address->id == $selectedAddress->id ? 'active' : '' }}">
                                                <input type="radio" name="address_id" value="{{ $address->id }}"
                                                       class="d-none js-address-radio"
                                                       {{ isset($selectedAddress) && $address->id == $selectedAddress->id ? 'checked' : '' }}>

                                                <div class="address-label">
                                                    {{ $address->first_name }} {{ $address->last_name }}
                                                </div>

                                                <div class="address-meta">
                                                    {{ $address->address_line1 }}<br>
                                                    @if ($address->address_line2)
                                                        {{ $address->address_line2 }}<br>
                                                    @endif
                                                    {{ optional($address->city)->name }},
                                                    {{ optional($address->state)->name }},
                                                    {{ optional($address->country)->name }}
                                                    <br>
                                                    Phone: {{ $address->phone }}
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        @endif
                    @endguest

                    {{-- SHIPPING METHODS --}}
                    <h6 class="mt-4">Shipping way</h6>

                    @if ($shippingMethods && $shippingMethods->count())
                        <form action="{{ route('frontend.checkout.selectShipping') }}" method="POST"
                              id="shipping-form">
                            @csrf

                            <div class="border rounded p-3">
                                @foreach ($shippingMethods as $method)
                                    <div class="form-check mb-2">
                                        <input type="radio" name="shipping_method_id" value="{{ $method->id }}"
                                               class="form-check-input js-shipping-radio"
                                               {{ isset($selectedShipping) && $selectedShipping->id == $method->id ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ $method->name }} – (${{ number_format($method->price, 2) }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </form>
                    @else
                        <div class="alert alert-light small">
                            No shipping methods available.
                        </div>
                    @endif

                    {{-- PAYMENT METHODS --}}
                    <h6 class="mt-4">Payment way</h6>

                    <form action="{{ route('frontend.checkout.selectPayment') }}" method="POST" id="payment-form">
                        @csrf

                        <div class="border rounded p-3">

                            {{-- PayPal (SDK) --}}
                            <div class="form-check mb-2">
                                <input type="radio" name="payment_method" value="paypal"
                                       class="form-check-input js-payment-radio"
                                       {{ isset($selectedPayment) && $selectedPayment === 'paypal' ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    PayPal
                                </label>
                            </div>

                            {{-- Omnipay (PayPal_Rest كمثال) --}}
                            <div class="form-check mb-2">
                                <input type="radio" name="payment_method" value="omnipay_paypal"
                                       class="form-check-input js-payment-radio"
                                       {{ isset($selectedPayment) && $selectedPayment === 'omnipay_paypal' ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    PayPal (via Omnipay)
                                </label>
                            </div>

                        </div>
                    </form>

                </div>

                {{-- RIGHT COLUMN --}}
                <div class="col-lg-4">
                    <div class="order-summary-card">

                        <h5>Your order</h5>

                        @if ($selectedAddress)
                            <small class="text-muted">
                                <strong>Shipping to:</strong><br>
                                {{ $selectedAddress->address_line1 }}<br>
                                @if ($selectedAddress->address_line2)
                                    {{ $selectedAddress->address_line2 }}<br>
                                @endif
                                {{ optional($selectedAddress->city)->name }},
                                {{ optional($selectedAddress->state)->name }},
                                {{ optional($selectedAddress->country)->name }}
                            </small>
                            <hr>
                        @endif

                        <div class="d-flex justify-content-between">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>Tax</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>Shipping</span>
                            <span>${{ number_format($shippingCost, 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between fw-bold border-top pt-2 mt-2">
                            <span>TOTAL</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>

                        <form action="{{ route('frontend.checkout.placeOrder') }}" method="POST" class="mt-3">
                            @csrf
                            <button class="btn btn-dark w-100">
                                Place order
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // تغيير العنوان المختار
            document.querySelectorAll('.js-address-radio').forEach(function(r) {
                r.addEventListener('change', function() {
                    document.getElementById('address-form').submit();
                });
            });

            // تغيير وسيلة الشحن المختارة
            document.querySelectorAll('.js-shipping-radio').forEach(function(r) {
                r.addEventListener('change', function() {
                    document.getElementById('shipping-form').submit();
                });
            });

            // تغيير وسيلة الدفع المختارة
            document.querySelectorAll('.js-payment-radio').forEach(function(r) {
                r.addEventListener('change', function() {
                    document.getElementById('payment-form').submit();
                });
            });

            // إظهار/إخفاء فورم إضافة عنوان جديد
            const toggleBtn = document.getElementById('toggle-new-address');
            const wrapper = document.getElementById('new-address-wrapper');

            if (toggleBtn && wrapper) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    wrapper.style.display =
                        (wrapper.style.display === 'none' || wrapper.style.display === '') ?
                            'block' :
                            'none';
                });
            }
        });
    </script>

@endsection
