{{-- resources/views/Backend/pages/orders/show.blade.php --}}
@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Order #{{ $order->id }}</h5>

        <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm">
            ← Back to orders
        </a>
    </div>

    <div class="row g-3">

        {{-- تفاصيل عامة + تحديث الحالة --}}
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <strong>Order details</strong>
                </div>
                <div class="card-body">

                    {{-- صف عام --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Customer</small>
                            <strong>{{ optional($order->user)->name ?? 'Guest' }}</strong><br>
                            <small class="text-muted">{{ optional($order->user)->email }}</small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Date</small>
                            <strong>{{ $order->created_at->format('Y-m-d H:i') }}</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Order total</small>
                            <strong>${{ number_format($order->total, 2) }}</strong>
                        </div>
                    </div>

                    {{-- صف الدفع --}}
                    @php
                        $payClass = [
                            'pending'  => 'bg-warning text-dark',
                            'paid'     => 'bg-success',
                            'failed'   => 'bg-danger',
                            'refunded' => 'bg-secondary',
                        ][$order->payment_status] ?? 'bg-secondary';
                    @endphp

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Payment method</small>
                            <strong>{{ strtoupper($order->payment_method ?? 'N/A') }}</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Payment status</small>
                            <span class="badge {{ $payClass }}">
                                {{ ucfirst($order->payment_status ?? 'pending') }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Payment reference</small>
                            @if($order->payment_reference)
                                <code class="small d-block text-truncate" style="max-width: 100%;">
                                    {{ $order->payment_reference }}
                                </code>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </div>
                    </div>

                    {{-- تفاصيل إضافية لـ PayPal --}}
                    @if($order->payment_method === 'paypal')
                        <hr>
                        <h6 class="text-muted mb-2">PayPal details</h6>
                        <dl class="row small mb-0">

                            @if($order->paypal_order_id)
                                <dt class="col-sm-4">Order ID</dt>
                                <dd class="col-sm-8">
                                    <code class="text-truncate d-block">{{ $order->paypal_order_id }}</code>
                                </dd>
                            @endif

                            @if($order->paypal_capture_id)
                                <dt class="col-sm-4">Transaction / Capture ID</dt>
                                <dd class="col-sm-8">
                                    <code class="text-truncate d-block">{{ $order->paypal_capture_id }}</code>
                                </dd>
                            @endif

                            @if($order->paypal_payer_id)
                                <dt class="col-sm-4">Payer ID</dt>
                                <dd class="col-sm-8">
                                    <code class="text-truncate d-block">{{ $order->paypal_payer_id }}</code>
                                </dd>
                            @endif

                            @if($order->paypal_payer_email)
                                <dt class="col-sm-4">Payer email</dt>
                                <dd class="col-sm-8">
                                    <a href="mailto:{{ $order->paypal_payer_email }}">
                                        {{ $order->paypal_payer_email }}
                                    </a>
                                </dd>
                            @endif

                            @if(
                                !$order->paypal_order_id &&
                                !$order->paypal_capture_id &&
                                !$order->paypal_payer_id &&
                                !$order->paypal_payer_email
                            )
                                <dt class="col-sm-12">
                                    <span class="text-muted">No extra PayPal data stored for this order.</span>
                                </dt>
                            @endif
                        </dl>
                    @endif

                    {{-- فورم تحديث الحالة --}}
                    <form action="{{ route('admin.orders.update', $order) }}"
                          method="POST"
                          class="row g-3 align-items-end mt-3">
                        @csrf
                        @method('PATCH')

                        <div class="col-md-4">
                            <label class="form-label form-label-sm">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                @foreach (['pending','processing','completed','cancelled'] as $st)
                                    <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>
                                        {{ ucfirst($st) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label form-label-sm">Payment status</label>
                            <select name="payment_status" class="form-select form-select-sm">
                                @foreach (['pending','paid','failed','refunded'] as $pst)
                                    <option value="{{ $pst }}" {{ $order->payment_status === $pst ? 'selected' : '' }}>
                                        {{ ucfirst($pst) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <button class="btn btn-primary btn-sm">
                                Update order
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            {{-- العناصر --}}
            <div class="card shadow-sm">
                <div class="card-header">
                    <strong>Items</strong>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th width="80">Qty</th>
                                    <th width="120">Price</th>
                                    <th width="120">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        {{ $item->product_name }}
                                        @if($item->product)
                                            <br>
                                            <a href="{{ route('frontend.products.show', $item->product->slug) }}"
                                               target="_blank" class="small">
                                                View product
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>${{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- عنوان الشحن + ملخص --}}
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header">
                    <strong>Shipping address</strong>
                </div>
                <div class="card-body">
                    @if($order->address)
                        <strong>{{ $order->address->first_name }} {{ $order->address->last_name }}</strong><br>
                        {{ $order->address->address_line1 }}<br>
                        @if($order->address->address_line2)
                            {{ $order->address->address_line2 }}<br>
                        @endif
                        {{ optional($order->address->city)->name }},
                        {{ optional($order->address->state)->name }}<br>
                        {{ optional($order->address->country)->name }}<br>
                        Phone: {{ $order->address->phone }}
                    @else
                        <span class="text-muted">No address stored.</span>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <strong>Order summary</strong>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Discount</span>
                        <span>- ${{ number_format($order->discount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Tax</span>
                        <span>${{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Shipping</span>
                        <span>${{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
