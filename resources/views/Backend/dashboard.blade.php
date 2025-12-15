@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    <h3 class="mb-4">Admin Dashboard</h3>

    {{-- ================= CRUD CARDS ================= --}}
    <div class="row">

        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Products
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $products }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 py-2 border-left-success">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Categories
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 py-2 border-left-info">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        Tags
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tags }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 py-2 border-left-warning">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Users
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users }}</div>
                </div>
            </div>
        </div>

    </div>

    {{-- ================= BUSINESS KPIs ================= --}}
    <div class="row mt-2">

        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 py-2 border-left-danger">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                        Revenue This Month
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($revenueThisMonth, 2) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 py-2 border-left-secondary">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                        Orders This Month
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ordersThisMonth }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 py-2 border-left-dark">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                        Pending Orders
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingOrders }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        New Customers (30 Days)
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $newCustomers30 }}</div>
                </div>
            </div>
        </div>

    </div>

    {{-- ================= CHART + LATEST ORDERS ================= --}}
    <div class="row mt-2">

        <div class="col-lg-8 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-3">
                        Orders Overview (Last 6 Months)
                    </div>
                    <canvas id="ordersChart" height="110"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-3">
                        Latest Orders
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($latestOrders as $o)
                                @php
                                    $statusClass = match($o->status) {
                                        'pending'    => 'badge-warning',
                                        'processing' => 'badge-info',
                                        'completed'  => 'badge-success',
                                        'cancelled'  => 'badge-danger',
                                        default      => 'badge-secondary',
                                    };
                                @endphp
                                <tr>
                                    <td>#{{ $o->id }}</td>
                                    <td>{{ $o->user->name ?? '—' }}</td>
                                    <td>{{ number_format($o->total, 2) }}</td>
                                    <td><span class="badge {{ $statusClass }}">{{ $o->status }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        No orders
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>

{{-- ================= CHART JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('ordersChart'), {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Orders',
                data: @json($chartData),
                tension: 0.35,
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
