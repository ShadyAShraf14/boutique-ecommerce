{{-- resources/views/Backend/pages/supervisors/create.blade.php --}}

@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Add Supervisor</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.supervisors.store') }}" method="POST">
                @include('Backend.pages.supervisors._form', ['button' => 'Create'])
            </form>
        </div>
    </div>

</div>
@endsection
