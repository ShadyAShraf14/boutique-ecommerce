@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">User Addresses</h6>
            <a href="{{ route('admin.addresses.create') }}" class="btn btn-sm btn-primary">
                + Add Address
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>User</th>
                        <th>Country / State / City</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Defaults</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th width="220">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($addresses as $address)
                        <tr>
                            <td>
                                {{ $address->user->name ?? '-' }}<br>
                                <small class="text-muted">{{ $address->user->email ?? '' }}</small>
                            </td>
                            <td>
                                {{ $address->country->name ?? '-' }} /
                                {{ $address->state->name ?? '-' }} /
                                {{ $address->city->name ?? '-' }}
                            </td>
                            <td>
                                {{ $address->address_line1 }}<br>
                                @if($address->address_line2)
                                    <small class="text-muted">{{ $address->address_line2 }}</small><br>
                                @endif
                                @if($address->postal_code)
                                    <small class="text-muted">Postal: {{ $address->postal_code }}</small>
                                @endif
                            </td>
                            <td>{{ $address->phone }}</td>
                            <td>
                                @if($address->is_default_shipping)
                                    <span class="badge badge-info">Shipping</span>
                                @endif
                                @if($address->is_default_billing)
                                    <span class="badge badge-warning">Billing</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $address->is_active ? 'success' : 'secondary' }}">
                                    {{ $address->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $address->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.addresses.show', $address) }}"
                                   class="btn btn-sm btn-info">View</a>

                                <a href="{{ route('admin.addresses.edit', $address) }}"
                                   class="btn btn-sm btn-outline-primary">Edit</a>

                                <form action="{{ route('admin.addresses.destroy', $address) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this address?')">
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
                            <td colspan="8" class="text-center text-muted">
                                No addresses found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $addresses->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
