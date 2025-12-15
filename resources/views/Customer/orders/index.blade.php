{{-- resources/views/Customer/orders/index.blade.php --}}
@extends('Customer.layout')

@section('customer_page_title', 'My orders')
@section('customer_breadcrumb', 'Orders')

@section('customer_content')

    <h5 class="mb-3">My orders</h5>
    <p class="text-muted small mb-4">
        Here you can find a summary of all your previous orders.
    </p>

    <style>
        .order-card {
            border-radius: 10px;
            border: 1px solid #eee;
            background: #fff;
            transition: box-shadow .18s ease, transform .18s ease;
        }
        .order-card:hover {
            box-shadow: 0 12px 30px rgba(0,0,0,0.05);
            transform: translateY(-2px);
        }
        .order-card-header {
            font-size: 0.95rem;
            font-weight: 600;
        }
        .order-meta {
            font-size: 0.8rem;
            color: #777;
        }
        .badge-soft {
            border-radius: 999px;
            padding: 0.20rem 0.6rem;
            font-size: 0.7rem;
            letter-spacing: .04em;
        }
        .badge-soft-status {
            background: #111;
            color: #fff;
        }
        .badge-soft-paid {
            background: #f4d887;
            color: #000;
        }
        .badge-soft-pending {
            background: #ffe9b5;
            color: #7a5a00;
        }
        .badge-soft-failed {
            background: #f8c7c5;
            color: #8b1510;
        }
        .order-amount {
            font-weight: 700;
            font-size: 1rem;
        }
        @media (max-width: 767.98px) {
            .order-card .text-end {
                text-align: left !important;
                margin-top: 0.5rem;
            }
        }
    </style>

    @forelse($orders as $order)
        @php
            $createdAt = $order->created_at?->format('Y-m-d H:i');
            $status    = strtoupper($order->status ?? 'N/A');
            $payment   = strtolower($order->payment_status ?? 'pending');

            $paymentLabel = strtoupper($order->payment_status ?? 'PENDING');

            $paymentClass = match ($payment) {
                'paid'    => 'badge-soft-paid',
                'failed'  => 'badge-soft-failed',
                default   => 'badge-soft-pending',
            };
        @endphp

        <div class="order-card mb-3 p-3">
            <div class="row align-items-center">

                {{-- اليسار: معلومات أساسية --}}
                <div class="col-md-6">
                    <div class="order-card-header mb-1">
                        <span class="me-1">🧾</span>
                        Order #{{ $order->id }}
                    </div>
                    <div class="order-meta">
                        {{ $createdAt }}
                    </div>

                    <div class="mt-2 d-flex flex-wrap gap-2">
                        <span class="badge badge-soft badge-soft-status">
                            {{ $status }}
                        </span>

                        <span class="badge badge-soft {{ $paymentClass }}">
                            {{ $paymentLabel }}
                        </span>
                    </div>
                </div>

                {{-- اليمين: المبلغ + زر التفاصيل --}}
                <div class="col-md-3 text-md-center mt-3 mt-md-0">
                    <div class="text-muted small mb-1 text-uppercase">
                        Total
                    </div>
                    <div class="order-amount">
                        ${{ number_format($order->total, 2) }}
                    </div>
                </div>

                <div class="col-md-3 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('customer.orders.show', $order->id) }}"
                       class="btn btn-dark btn-sm px-3">
                        View order
                    </a>
                </div>

            </div>
        </div>
    @empty
        <p class="text-muted">
            You don't have any orders yet.
        </p>
    @endforelse

    <div class="mt-3">
        {{ $orders->links() }}
    </div>
@endsection
