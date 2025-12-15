{{-- resources/views/Backend/pages/customers/index.blade.php --}}

@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Customers</h6>

            <a href="{{ route('admin.customers.create') }}"
               class="btn btn-sm btn-primary">
                + Add Customer
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Registered at</th>
                        <th width="200">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>
                                <span class="badge badge-{{ $customer->is_active ? 'success' : 'secondary' }}">
                                    {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $customer->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.customers.show', $customer) }}"
                                   class="btn btn-sm btn-info">View</a>

                                <a href="{{ route('admin.customers.edit', $customer) }}"
                                   class="btn btn-sm btn-outline-primary">Edit</a>

                                <form action="{{ route('admin.customers.destroy', $customer) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this customer?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No customers found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $customers->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
