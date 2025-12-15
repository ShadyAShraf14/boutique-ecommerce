
@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Supervisors</h6>

            {{-- هنا زرار الإضافة --}}
            <a href="{{ route('admin.supervisors.create') }}"
               class="btn btn-sm btn-primary">
                + Add Supervisor
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
                        <th>Created at</th>
                        <th width="200">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($supervisors as $sup)
                        <tr>
                            <td>{{ $sup->name }}</td>
                            <td>{{ $sup->email }}</td>
                            <td>
                                <span class="badge badge-{{ $sup->is_active ? 'success' : 'secondary' }}">
                                    {{ $sup->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $sup->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.supervisors.show', $sup) }}"
                                   class="btn btn-sm btn-info">View</a>

                                <a href="{{ route('admin.supervisors.edit', $sup) }}"
                                   class="btn btn-sm btn-outline-primary">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No supervisors found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $supervisors->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
