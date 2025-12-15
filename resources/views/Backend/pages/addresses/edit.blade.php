@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Edit Address</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.addresses.update', $address) }}" method="POST">
                @method('PUT')
                @include('Backend.pages.addresses._form', ['button' => 'Update'])
            </form>
        </div>
    </div>

</div>
@endsection
