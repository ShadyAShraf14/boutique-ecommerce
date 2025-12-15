@extends('Backend.inc.master')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Add Shipping Method</h5>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger m-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.shippingway.store') }}" method="POST">
        @csrf

        <div class="card-body">

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name"
                       class="form-control"
                       value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label>Code (optional)</label>
                <input type="text" name="code"
                       class="form-control"
                       value="{{ old('code') }}">
            </div>

            <div class="mb-3">
                <label>Price</label>
                <input type="number" step="0.01" name="price"
                       class="form-control"
                       value="{{ old('price') }}" required>
            </div>

            <div class="mb-3">
                <label>Country (optional)</label>
                <select name="country_id" class="form-control">
                    <option value="">All Countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}"
                            {{ old('country_id') == $country->id ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_active"
                       {{ old('is_active', 1) ? 'checked' : '' }}>
                <label class="form-check-label">Active</label>
            </div>

        </div>

        <div class="card-footer">
            <button class="btn btn-primary">Save</button>
            <a href="{{ route('admin.shippingway.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
