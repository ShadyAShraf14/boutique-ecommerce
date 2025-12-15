{{-- resources/views/Backend/pages/supervisors/show.blade.php --}}

@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                Supervisor: {{ $supervisor->name }}
            </h6>

            <div>
                <a href="{{ route('admin.supervisors.edit', $supervisor) }}"
                   class="btn btn-sm btn-outline-primary">Edit</a>
                <a href="{{ route('admin.supervisors.index') }}"
                   class="btn btn-sm btn-secondary">Back</a>
            </div>
        </div>

        <div class="card-body">

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Name:</div>
                <div class="col-md-9">{{ $supervisor->name }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Email:</div>
                <div class="col-md-9">{{ $supervisor->email }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Status:</div>
                <div class="col-md-9">
                    <span class="badge badge-{{ $supervisor->is_active ? 'success' : 'secondary' }}">
                        {{ $supervisor->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <hr>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Created at:</div>
                <div class="col-md-9">
                    {{ $supervisor->created_at->format('Y-m-d H:i') }}
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
