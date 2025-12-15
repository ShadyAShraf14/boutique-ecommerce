@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                Coupon: {{ $coupon->code }}
            </h6>

            <div>
                <a href="{{ route('admin.coupons.edit', $coupon) }}"
                   class="btn btn-sm btn-outline-primary">
                    Edit
                </a>

                <a href="{{ route('admin.coupons.index') }}"
                   class="btn btn-sm btn-secondary">
                    Back
                </a>
            </div>
        </div>

        <div class="card-body">

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Code:</div>
                <div class="col-md-9">{{ $coupon->code }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Type:</div>
                <div class="col-md-9">
                    {{ ucfirst($coupon->type) }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Value:</div>
                <div class="col-md-9">
                    @if($coupon->type === 'percent')
                        {{ $coupon->value }}%
                    @else
                        {{ number_format($coupon->value, 2) }}
                    @endif
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Min Order Total:</div>
                <div class="col-md-9">
                    @if($coupon->min_order_total)
                        {{ number_format($coupon->min_order_total, 2) }}
                    @else
                        -
                    @endif
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Usage:</div>
                <div class="col-md-9">
                    {{ $coupon->used_count }} /
                    {{ $coupon->max_uses ?? '∞' }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Status:</div>
                <div class="col-md-9">
                    <span class="badge badge-{{ $coupon->is_active ? 'success' : 'secondary' }}">
                        {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Valid From:</div>
                <div class="col-md-9">
                    {{ $coupon->starts_at?->format('Y-m-d H:i') ?? '-' }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Valid To:</div>
                <div class="col-md-9">
                    {{ $coupon->ends_at?->format('Y-m-d H:i') ?? '-' }}
                </div>
            </div>

            <hr>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Created at:</div>
                <div class="col-md-9">
                    {{ $coupon->created_at->format('Y-m-d H:i') }}
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 font-weight-bold">Updated at:</div>
                <div class="col-md-9">
                    {{ $coupon->updated_at->format('Y-m-d H:i') }}
                </div>
            </div>

            <form action="{{ route('admin.coupons.destroy', $coupon) }}"
                  method="POST"
                  onsubmit="return confirm('Delete this coupon?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">
                    Delete
                </button>
            </form>

        </div>
    </div>

</div>
@endsection
