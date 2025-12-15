@extends('Backend.inc.master')

@section('content')
<div class="container mt-4">
    @include('Backend.inc.flash')

    <div class="card shadow">
        <div class="card-header">
            <h6 class="font-weight-bold text-primary">Edit Shipping Company</h6>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.shipping_companies.update', $shipping_company) }}" method="POST">
                @csrf
                @method('PUT')
                @include('Backend.pages.shipping_companies._form', ['button' => 'Update'])
            </form>
        </div>
    </div>
</div>
@endsection
