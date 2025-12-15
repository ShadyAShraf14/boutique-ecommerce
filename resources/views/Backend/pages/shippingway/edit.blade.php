@extends('Backend.inc.master')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Edit Shipping Method</h5>
    </div>

    <form action="{{ route('admin.shippingway.update', $shippingway->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="mb-3">
                <label>Name</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ $shippingway->name }}"
                       required>
            </div>

            <div class="mb-3">
                <label>Code (optional)</label>
                <input type="text"
                       name="code"
                       class="form-control"
                       value="{{ $shippingway->code }}">
            </div>

            <div class="mb-3">
                <label>Price</label>
                <input type="number" step="0.01"
                       name="price"
                       class="form-control"
                       value="{{ $shippingway->price }}"
                       required>
            </div>

            <div class="mb-3">
                <label>Country (optional)</label>
                <select name="country_id" class="form-control">
                    <option value="">All Countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}"
                            {{ $shippingway->country_id == $country->id ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_active"
                       {{ $shippingway->is_active ? 'checked' : '' }}>
                <label class="form-check-label">Active</label>
            </div>

        </div>

        <div class="card-footer">
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('admin.shippingway.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
