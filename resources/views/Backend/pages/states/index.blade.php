@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">States</h6>
            <a href="{{ route('admin.states.create') }}" class="btn btn-sm btn-primary">
                + Add State
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Country</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th width="200">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($states as $state)
                        <tr>
                            <td>{{ $state->name }}</td>
                            <td>{{ $state->code ?? '-' }}</td>
                            <td>{{ $state->country->name ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $state->is_active ? 'success' : 'secondary' }}">
                                    {{ $state->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $state->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.states.show', $state) }}"
                                   class="btn btn-sm btn-info">View</a>

                                <a href="{{ route('admin.states.edit', $state) }}"
                                   class="btn btn-sm btn-outline-primary">Edit</a>

                                <form action="{{ route('admin.states.destroy', $state) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this state?')">
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
                            <td colspan="6" class="text-center text-muted">No states found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $states->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
