@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                Country: {{ $country->name }}
            </h6>

            <div>
                <a href="{{ route('admin.countries.edit', $country) }}"
                   class="btn btn-sm btn-outline-primary">Edit</a>
                <a href="{{ route('admin.countries.index') }}"
                   class="btn btn-sm btn-secondary">Back</a>
            </div>
        </div>

        <div class="card-body">

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Name:</div>
                <div class="col-md-9">{{ $country->name }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Code:</div>
                <div class="col-md-9">{{ $country->code ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Status:</div>
                <div class="col-md-9">
                    <span class="badge badge-{{ $country->is_active ? 'success' : 'secondary' }}">
                        {{ $country->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <hr>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Created at:</div>
                <div class="col-md-9">{{ $country->created_at->format('Y-m-d H:i') }}</div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 font-weight-bold">Updated at:</div>
                <div class="col-md-9">{{ $country->updated_at->format('Y-m-d H:i') }}</div>
            </div>

            <form action="{{ route('admin.countries.destroy', $country) }}"
                  method="POST"
                  onsubmit="return confirm('Delete this country?')">
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
