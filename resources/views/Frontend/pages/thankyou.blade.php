@extends('Frontend.inc.master')

@section('content')
    <section class="py-5">
        <div class="container">

            {{-- رسالة الشكر --}}
            <div class="text-center mb-5">
                <h1 class="h3 mb-2">Thank you for your order! 🎉</h1>
                <p class="text-muted mb-0">
                    Your order has been placed successfully and is now <strong>pending</strong> payment/processing.
                </p>
            </div>

            <div class="row g-4">

                {{-- تفاصيل الطلب --}}
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Order details</h5>
                        </div>
                        <div class="card-body">

                            <p class="mb-1">
                                <strong>Order ID:</strong> #{{ $order->id }}
                            </p>
                            <p class="mb-1">
                                <strong>Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}
                            </p>
                            <p class="mb-1">
                                <strong>Status:</strong> {{ ucfirst($order->status) }}
                            </p>
                            <p class="mb-3">
                                <strong>Payment method:</strong> {{ strtoupper($order->payment_method) }}
                                ({{ $order->payment_status }})
                            </p>

                            <hr>

                            {{-- قائمة المنتجات --}}
                            <h6>Items</h6>

                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-0">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product_name }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                            <td class="text-end">${{ number_format($item->total, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-muted text-center small">
                                                No items found for this order.
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- الملخص + عنوان الشحن --}}
                <div class="col-lg-4">

                    {{-- عنوان الشحن --}}
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Shipping address</h6>
                        </div>
                        <div class="card-body small text-muted">
                            @if($order->address)
                                {{ $order->address->first_name }} {{ $order->address->last_name }}<br>
                                {{ $order->address->address_line1 }}<br>
                                @if($order->address->address_line2)
                                    {{ $order->address->address_line2 }}<br>
                                @endif
                                {{ optional($order->address->city)->name }},
                                {{ optional($order->address->state)->name }},
                                {{ optional($order->address->country)->name }}<br>
                                Phone: {{ $order->address->phone }}
                            @else
                                <span class="text-danger">No address attached to this order.</span>
                            @endif
                        </div>
                    </div>

                    {{-- ملخص الأسعار --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Order summary</h6>
                        </div>
                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-1">
                                <span>Subtotal</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-1">
                                <span>Discount</span>
                                <span>- ${{ number_format($order->discount, 2) }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-1">
                                <span>Tax</span>
                                <span>${{ number_format($order->tax, 2) }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-1">
                                <span>Shipping</span>
                                <span>${{ number_format($order->shipping_cost, 2) }}</span>
                            </div>

                            <hr class="my-2">

                            <div class="d-flex justify-content-between fw-bold mb-3">
                                <span>Total</span>
                                <span>${{ number_format($order->total, 2) }}</span>
                            </div>

                            <a href="{{ route('frontend.products.index') }}" class="btn btn-outline-dark w-100 mb-2">
                                Continue shopping
                            </a>

                            {{-- لما نعمل صفحة My Orders هنخلي اللينك ده صح --}}
                            {{-- <a href="{{ route('customer.orders.index') }}" class="btn btn-link w-100 p-0">
                                View my orders
                            </a> --}}
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection
