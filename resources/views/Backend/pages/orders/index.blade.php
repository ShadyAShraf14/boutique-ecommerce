{{-- resources/views/Backend/pages/orders/index.blade.php --}}
@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Orders</h5>
    </div>

    {{-- إحصائيات سريعة --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body py-2">
                    <small class="text-muted d-block">Total orders</small>
                    <strong>{{ $stats['total'] }}</strong>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body py-2">
                    <small class="text-muted d-block">Pending</small>
                    <span class="badge bg-warning text-dark">{{ $stats['pending'] }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body py-2">
                    <small class="text-muted d-block">Processing</small>
                    <span class="badge bg-info text-dark">{{ $stats['processing'] }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body py-2">
                    <small class="text-muted d-block">Completed</small>
                    <span class="badge bg-success">{{ $stats['completed'] }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- فلتر أعلى الجدول --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form class="row g-2 align-items-end" method="GET">
                <div class="col-md-3">
                    <label class="form-label form-label-sm mb-1">Search (ID / customer)</label>
                    <input type="text" name="q" value="{{ request('q') }}"
                           class="form-control form-control-sm">
                </div>

                <div class="col-md-3">
                    <label class="form-label form-label-sm mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach (['pending','processing','completed','cancelled'] as $st)
                            <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                                {{ ucfirst($st) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label form-label-sm mb-1">Payment status</label>
                    <select name="payment_status" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach (['pending','paid','failed','refunded'] as $pst)
                            <option value="{{ $pst }}" {{ request('payment_status') === $pst ? 'selected' : '' }}>
                                {{ ucfirst($pst) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-primary btn-sm">
                        Filter
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Reference</th>
                            <th>Total</th>
                            <th width="80">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($orders as $order)
                        @php
                            $statusClass = [
                                'pending'    => 'bg-warning text-dark',
                                'processing' => 'bg-info text-dark',
                                'completed'  => 'bg-success',
                                'cancelled'  => 'bg-danger',
                            ][$order->status] ?? 'bg-secondary';

                            $payClass = [
                                'pending'  => 'bg-warning text-dark',
                                'paid'     => 'bg-success',
                                'failed'   => 'bg-danger',
                                'refunded' => 'bg-secondary',
                            ][$order->payment_status] ?? 'bg-secondary';
                        @endphp
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                {{ optional($order->user)->name ?? 'Guest' }}<br>
                                <small class="text-muted">{{ optional($order->user)->email }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $payClass }}">
                                    {{ strtoupper($order->payment_method) }} - {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                @if($order->payment_reference)
                                    <code class="small text-truncate d-block" style="max-width: 160px;">
                                        {{ $order->payment_reference }}
                                    </code>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>${{ number_format($order->total, 2) }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="btn btn-sm btn-outline-dark">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
