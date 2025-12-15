@extends('Backend.inc.master')

@section('content')
<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header">
            <h6 class="font-weight-bold text-primary">{{ $shipping_company->name }}</h6>
        </div>

        <div class="card-body">

            <p><strong>Code:</strong> {{ $shipping_company->code ?? '-' }}</p>

            <p><strong>Tracking URL:</strong>
                @if($shipping_company->tracking_url)
                    <a href="{{ $shipping_company->tracking_url }}" target="_blank">
                        {{ $shipping_company->tracking_url }}
                    </a>
                @else
                    -
                @endif
            </p>

            <p><strong>Status:</strong>
                <span class="badge badge-{{ $shipping_company->is_active ? 'success' : 'secondary' }}">
                    {{ $shipping_company->is_active ? 'Active' : 'Inactive' }}
                </span>
            </p>

            <p><strong>Created at:</strong> {{ $shipping_company->created_at->format('Y-m-d') }}</p>

        </div>
    </div>

</div>
@endsection
