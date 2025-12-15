@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Countries</h6>

            <a href="{{ route('admin.countries.create') }}" class="btn btn-sm btn-primary">
                + Add Country
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>States</th>
                            <th>Cities</th>
                            <th>Addresses</th>
                            <th>Status</th>
                            <th>Created at</th>
                            <th width="200">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($countries as $country)
                        <tr>
                            <td>{{ $country->name }}</td>

                            <td>{{ $country->code ?? '-' }}</td>

                            <td>
                                <span class="badge badge-info">
                                    {{ $country->states_count }}
                                </span>
                            </td>

                            <td>
                                <span class="badge badge-secondary">
                                    {{ $country->cities_count }}
                                </span>
                            </td>

                            <td>
                                <span class="badge badge-dark">
                                    {{ $country->addresses_count }}
                                </span>
                            </td>

                            <td>
                                <span class="badge badge-{{ $country->is_active ? 'success' : 'secondary' }}">
                                    {{ $country->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td>{{ $country->created_at->format('Y-m-d') }}</td>

                            <td>

                                <a href="{{ route('admin.countries.show', $country) }}"
                                   class="btn btn-sm btn-info">
                                    View
                                </a>

                                <a href="{{ route('admin.countries.edit', $country) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Edit
                                </a>

                                <form action="{{ route('admin.countries.destroy', $country) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Delete this country?')">
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
                                No countries found.
                            </td>
                        </tr>

                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $countries->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
