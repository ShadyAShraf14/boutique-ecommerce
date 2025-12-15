@extends('Backend.inc.master')

@section('content')
<div class="container mt-4">
    @include('Backend.inc.flash')

    <div class="card shadow">
        <div class="card-header">
            <h6 class="font-weight-bold text-primary">Add Shipping Company</h6>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.shipping_companies.store') }}" method="POST">
                @csrf
                @include('Backend.pages.shipping_companies._form', ['button' => 'Create'])
            </form>
        </div>
    </div>
</div>
@endsection
