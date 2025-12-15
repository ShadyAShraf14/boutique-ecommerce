{{-- resources/views/Customer/orders/show.blade.php --}}
@extends('Customer.layout')

@section('customer_page_title', 'Order #'.$order->id)
@section('customer_breadcrumb', 'Order #'.$order->id)

@section('customer_content')

@php
    $invoice = $order->latestInvoice; // علاقة latestInvoice من Order Model
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">Order #{{ $order->id }}</h4>

        @if($invoice)
            <div class="small text-muted">
                Invoice: <strong>{{ $invoice->invoice_number }}</strong>
            </div>
        @else
            <div class="small text-muted">
                Invoice: NO
            </div>
        @endif
    </div>

    @if($invoice)
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm"
               target="_blank"
               href="{{ route('customer.invoices.view', $invoice->id) }}">
                View Invoice
            </a>

            <a class="btn btn-outline-dark btn-sm"
               href="{{ route('customer.invoices.download', $invoice->id) }}">
                Download PDF
            </a>
        </div>
    @endif
</div>

<h5 class="mb-3">Order #{{ $order->id }}</h5>

@if(session('success'))
    <div class="alert alert-success py-2 small">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger py-2 small">
        {{ session('error') }}
    </div>
@endif

@php
    $createdAt = $order->created_at?->format('Y-m-d H:i');
    $status    = strtoupper($order->status ?? 'N/A');
    $payment   = strtolower($order->payment_status ?? 'pending');

    $paymentLabel = strtoupper($order->payment_status ?? 'PENDING');

    $paymentClass = match ($payment) {
        'paid'    => 'bg-warning text-dark',
        'failed'  => 'bg-danger text-white',
        default   => 'bg-light text-dark',
    };

    $itemsCount = $order->items?->sum('quantity') ?? $order->items?->count() ?? 0;
@endphp

<style>
    .order-summary-card {
        border-radius: 10px;
        border: 1px solid #eee;
        background: #fff;
    }
    .order-summary-card h6 {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #777;
    }
    .order-summary-card p {
        font-weight: 600;
        margin-bottom: 0;
    }
    .address-box {
        border-radius: 10px;
        border: 1px solid #eee;
        background: #fff;
    }
    .order-items-table th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #999;
    }
    .order-items-table td {
        vertical-align: middle;
    }
</style>

{{-- Summary --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="order-summary-card p-3 h-100">
            <h6>Placed on</h6>
            <p>{{ $createdAt }}</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="order-summary-card p-3 h-100">
            <h6>Total amount</h6>
            <p>${{ number_format($order->total, 2) }}</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="order-summary-card p-3 h-100">
            <h6>Items</h6>
            <p>{{ $itemsCount }}</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="order-summary-card p-3 h-100">
            <h6>Status</h6>
            <p class="mb-1">
                <span class="badge bg-dark">{{ $status }}</span>
            </p>
            <span class="badge {{ $paymentClass }} rounded-pill">
                {{ $paymentLabel }}
            </span>

            {{-- Cancel button --}}
            @if($canCancel ?? false)
                <form action="{{ route('customer.orders.cancel', $order->id) }}"
                      method="POST"
                      class="mt-2"
                      onsubmit="return confirm('Are you sure you want to cancel this order?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        Cancel order
                    </button>
                    <small class="d-block text-muted mt-1" style="font-size: 0.7rem;">
                        You can cancel your order within 5 days from placing it.
                    </small>
                </form>
            @endif
        </div>
    </div>
</div>

{{-- Address + Shipping --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="address-box p-3 h-100">
            <h6 class="text-uppercase small text-muted mb-2">Shipping address</h6>

            @if($order->address)
                <p class="mb-1 fw-semibold">{{ $order->address->name ?? $order->address->recipient_name ?? '' }}</p>
                <p class="mb-1">
                    {{ $order->address->address ?? '' }}<br>
                    {{ $order->address->city->name ?? '' }}
                    @if($order->address->state) , {{ $order->address->state->name }} @endif
                    @if($order->address->country) , {{ $order->address->country->name }} @endif
                </p>
                <p class="mb-0 text-muted small">
                    Phone: {{ $order->address->phone ?? '-' }}
                </p>
            @else
                <p class="text-muted mb-0">No address information.</p>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="address-box p-3 h-100">
            <h6 class="text-uppercase small text-muted mb-2">Shipping & payment</h6>

            <p class="mb-1">
                <span class="text-muted small d-block">Shipping method</span>
                <span class="fw-semibold">{{ $order->shippingMethod->name ?? 'N/A' }}</span>
            </p>

            <p class="mb-1">
                <span class="text-muted small d-block">Payment method</span>
                <span class="fw-semibold">{{ $order->payment_method ?? 'N/A' }}</span>
            </p>

            <p class="mb-0">
                <span class="text-muted small d-block">Payment reference</span>
                @if($order->payment_reference)
                    <code class="small d-block text-truncate" style="max-width: 100%;">
                        {{ $order->payment_reference }}
                    </code>
                @else
                    <span class="text-muted small">—</span>
                @endif
            </p>
        </div>
    </div>
</div>

{{-- Items --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <h6 class="text-uppercase small text-muted mb-3">Order items</h6>

        <div class="table-responsive">
            <table class="table align-middle order-items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        @php
                            $product = $item->product;
                            $name    = $product->name ?? 'Deleted product';
                            $price   = $item->price;
                            $qty     = $item->quantity;
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $name }}</div>
                                @if($product)
                                    <div class="small text-muted">#{{ $product->id }}</div>
                                @endif
                            </td>
                            <td class="text-center">{{ $qty }}</td>
                            <td class="text-end">${{ number_format($price, 2) }}</td>
                            <td class="text-end">${{ number_format($price * $qty, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-end mt-3">
            <span class="text-muted me-2">Grand total:</span>
            <span class="fw-bold">${{ number_format($order->total, 2) }}</span>
        </div>
    </div>
</div>

<a href="{{ route('customer.orders.index') }}" class="btn btn-outline-dark btn-sm">
    ← Back to orders
</a>

@endsection
