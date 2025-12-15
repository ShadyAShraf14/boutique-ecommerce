{{-- resources/views/Backend/pages/customers/show.blade.php --}}

@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                Customer: {{ $customer->name }}
            </h6>

            <div>
                <a href="{{ route('admin.customers.edit', $customer) }}"
                   class="btn btn-sm btn-outline-primary">Edit</a>
                <a href="{{ route('admin.customers.index') }}"
                   class="btn btn-sm btn-secondary">Back</a>
            </div>
        </div>

        <div class="card-body">

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Name:</div>
                <div class="col-md-9">{{ $customer->name }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Email:</div>
                <div class="col-md-9">{{ $customer->email }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Status:</div>
                <div class="col-md-9">
                    <span class="badge badge-{{ $customer->is_active ? 'success' : 'secondary' }}">
                        {{ $customer->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <hr>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Registered at:</div>
                <div class="col-md-9">
                    {{ $customer->created_at->format('Y-m-d H:i') }}
                </div>
            </div>

            <form action="{{ route('admin.customers.destroy', $customer) }}"
                  method="POST"
                  onsubmit="return confirm('Delete this customer?')">
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
