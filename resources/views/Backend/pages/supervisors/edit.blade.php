{{-- resources/views/Backend/pages/supervisors/edit.blade.php --}}

@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Edit Supervisor</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.supervisors.update', $supervisor) }}" method="POST">
                @method('PUT')
                @include('Backend.pages.supervisors._form', ['button' => 'Update'])
            </form>
        </div>
    </div>

</div>
@endsection
