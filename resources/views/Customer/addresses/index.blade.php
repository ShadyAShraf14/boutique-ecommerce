{{-- resources/views/Customer/addresses/index.blade.php --}}
@extends('Customer.layout')




@section('customer_page_title', 'My addresses')
@section('customer_breadcrumb', 'Addresses')

@section('customer_content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-1">My addresses</h5>
            <p class="text-muted small mb-0">
                Manage your saved shipping addresses.
            </p>
        </div>

        <a href="{{ route('customer.addresses.create') }}" class="btn btn-dark btn-sm">
            + Add new address
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 small">
            {{ session('success') }}
        </div>
    @endif



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


    @if($addresses->isEmpty())
        <p class="text-muted mt-3">
            You don't have any saved addresses yet.
        </p>
    @else
        <div class="row g-3">
            @foreach($addresses as $address)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="mb-1">
                                <i class="fa fa-map-marker-alt me-1"></i>
                                {{ $address->name ?: 'Address #'.$loop->iteration }}
                            </h6>

                            <p class="small mb-2 text-muted">
                                {{ $address->address_line1 ?? $address->address ?? 'No address text saved yet.' }}
                            </p>

                            <p class="small mb-3">
                                Phone: {{ $address->phone }}
                            </p>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('customer.addresses.edit', $address->id) }}"
                                   class="btn btn-outline-secondary btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('customer.addresses.destroy', $address->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this address?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection
