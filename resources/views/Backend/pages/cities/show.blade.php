@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                City: {{ $city->name }}
            </h6>

            <div>
                <a href="{{ route('admin.cities.edit', $city) }}"
                   class="btn btn-sm btn-outline-primary">Edit</a>
                <a href="{{ route('admin.cities.index') }}"
                   class="btn btn-sm btn-secondary">Back</a>
            </div>
        </div>

        <div class="card-body">

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Name:</div>
                <div class="col-md-9">{{ $city->name }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">State:</div>
                <div class="col-md-9">{{ $city->state->name ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Country:</div>
                <div class="col-md-9">{{ $city->state->country->name ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Status:</div>
                <div class="col-md-9">
                    <span class="badge badge-{{ $city->is_active ? 'success' : 'secondary' }}">
                        {{ $city->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <hr>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Created at:</div>
                <div class="col-md-9">{{ $city->created_at->format('Y-m-d H:i') }}</div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 font-weight-bold">Updated at:</div>
                <div class="col-md-9">{{ $city->updated_at->format('Y-m-d H:i') }}</div>
            </div>

            <form action="{{ route('admin.cities.destroy', $city) }}"
                  method="POST"
                  onsubmit="return confirm('Delete this city?')">
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
