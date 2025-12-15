@extends('Backend.inc.master')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Shipping Methods</h5>
        <a href="{{ route('admin.shippingway.create') }}" class="btn btn-primary btn-sm">+ Add Method</a>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Code</th>
                <th>Country</th>
                <th>Price</th>
                <th>Status</th>
                <th width="140">Actions</th>
            </tr>
            </thead>

            <tbody>
            @forelse($methods as $method)
                <tr>
                    <td>{{ $method->id }}</td>
                    <td>{{ $method->name }}</td>
                    <td>{{ $method->code }}</td>
                    <td>{{ optional($method->country)->name ?? 'All' }}</td>
                    <td>{{ number_format($method->price, 2) }}$</td>

                    <td>
                        @if($method->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('admin.shippingway.edit', $method->id) }}"
                           class="btn btn-sm btn-outline-primary">Edit</a>

                        <form action="{{ route('admin.shippingway.destroy', $method->id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Delete this method?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">No shipping methods found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $methods->links() }}
    </div>
</div>

@endsection
