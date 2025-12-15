{{-- resources/views/Backend/pages/coupons/index.blade.php --}}

@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Coupons</h6>
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-sm btn-primary">
                + Add Coupon
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Min Order</th>
                        <th>Usage</th>
                        <th>Status</th>
                        <th>Valid From</th>
                        <th>Valid To</th>
                        <th width="200">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->code }}</td>
                            <td>{{ ucfirst($coupon->type) }}</td>
                            <td>
                                @if($coupon->type === 'percent')
                                    {{ $coupon->value }}%
                                @else
                                    {{ number_format($coupon->value, 2) }}
                                @endif
                            </td>
                            <td>
                                @if($coupon->min_order_total)
                                    {{ number_format($coupon->min_order_total, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                {{ $coupon->used_count }}
                                /
                                {{ $coupon->max_uses ?? '∞' }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $coupon->is_active ? 'success' : 'secondary' }}">
                                    {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $coupon->starts_at?->format('Y-m-d') ?? '-' }}</td>
                            <td>{{ $coupon->ends_at?->format('Y-m-d') ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.coupons.show', $coupon) }}"
                                   class="btn btn-sm btn-info">View</a>

                                <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                   class="btn btn-sm btn-outline-primary">Edit</a>

                                <form action="{{ route('admin.coupons.destroy', $coupon) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this coupon?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                No coupons found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $coupons->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
