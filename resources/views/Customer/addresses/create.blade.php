{{-- resources/views/Customer/addresses/create.blade.php --}}
@extends('Customer.layout')

@section('customer_page_title', 'Add new address')
@section('customer_breadcrumb', 'Add address')

@section('customer_content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-1">Add new address</h5>
    </div>

    <form action="{{ route('customer.addresses.store') }}" method="POST" class="card border-0 shadow-sm p-3">
        @csrf

        @include('Customer.addresses._form')

        <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('customer.addresses.index') }}" class="btn btn-link btn-sm">
                Cancel
            </a>
            <button type="submit" class="btn btn-dark btn-sm">
                Save address
            </button>
        </div>
    </form>

@endsection
