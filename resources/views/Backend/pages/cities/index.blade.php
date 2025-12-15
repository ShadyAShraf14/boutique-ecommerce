@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Cities</h6>
            <a href="{{ route('admin.cities.create') }}" class="btn btn-sm btn-primary">
                + Add City
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>State</th>
                        <th>Country</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th width="200">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($cities as $city)
                        <tr>
                            <td>{{ $city->name }}</td>
                            <td>{{ $city->state->name ?? '-' }}</td>
                            <td>{{ $city->state->country->name ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $city->is_active ? 'success' : 'secondary' }}">
                                    {{ $city->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $city->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.cities.show', $city) }}"
                                   class="btn btn-sm btn-info">View</a>

                                <a href="{{ route('admin.cities.edit', $city) }}"
                                   class="btn btn-sm btn-outline-primary">Edit</a>

                                <form action="{{ route('admin.cities.destroy', $city) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this city?')">
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
                            <td colspan="6" class="text-center text-muted">No cities found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $cities->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
