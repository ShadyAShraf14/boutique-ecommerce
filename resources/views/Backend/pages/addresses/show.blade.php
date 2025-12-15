@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                Address #{{ $address->id }}
            </h6>

            <div>
                <a href="{{ route('admin.addresses.edit', $address) }}"
                   class="btn btn-sm btn-outline-primary">Edit</a>
                <a href="{{ route('admin.addresses.index') }}"
                   class="btn btn-sm btn-secondary">Back</a>
            </div>
        </div>

        <div class="card-body">

            <h6 class="mb-3">User</h6>
            <p>
                <strong>{{ $address->user->name ?? '-' }}</strong><br>
                <small class="text-muted">{{ $address->user->email ?? '' }}</small>
            </p>

            <hr>

            <h6 class="mb-3">Location</h6>
            <p>
                <strong>Country:</strong> {{ $address->country->name ?? '-' }}<br>
                <strong>State:</strong> {{ $address->state->name ?? '-' }}<br>
                <strong>City:</strong> {{ $address->city->name ?? '-' }}
            </p>

            <hr>

            <h6 class="mb-3">Address</h6>
            <p>
                <strong>Name:</strong>
                {{ $address->first_name }} {{ $address->last_name }}<br>
                <strong>Line 1:</strong> {{ $address->address_line1 }}<br>
                @if($address->address_line2)
                    <strong>Line 2:</strong> {{ $address->address_line2 }}<br>
                @endif
                @if($address->postal_code)
                    <strong>Postal Code:</strong> {{ $address->postal_code }}<br>
                @endif
                @if($address->phone)
                    <strong>Phone:</strong> {{ $address->phone }}
                @endif
            </p>

            <hr>

            <h6 class="mb-3">Flags</h6>
            <p>
                <strong>Default Shipping:</strong>
                {{ $address->is_default_shipping ? 'Yes' : 'No' }}<br>
                <strong>Default Billing:</strong>
                {{ $address->is_default_billing ? 'Yes' : 'No' }}<br>
                <strong>Status:</strong>
                <span class="badge badge-{{ $address->is_active ? 'success' : 'secondary' }}">
                    {{ $address->is_active ? 'Active' : 'Inactive' }}
                </span>
            </p>

            <hr>

            <p>
                <strong>Created at:</strong> {{ $address->created_at->format('Y-m-d H:i') }}<br>
                <strong>Updated at:</strong> {{ $address->updated_at->format('Y-m-d H:i') }}
            </p>

            <form action="{{ route('admin.addresses.destroy', $address) }}"
                  method="POST"
                  onsubmit="return confirm('Delete this address?')">
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
