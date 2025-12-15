@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Shipping Companies</h6>
            <a href="{{ route('admin.shipping_companies.create') }}" class="btn btn-sm btn-primary">
                + Add Company
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th width="200">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($companies as $company)
                        <tr>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->code ?? '-' }}</td>

                            <td>
                                <span class="badge badge-{{ $company->is_active ? 'success' : 'secondary' }}">
                                    {{ $company->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td>{{ $company->created_at->format('Y-m-d') }}</td>

                            <td>
                                <a href="{{ route('admin.shipping_companies.show', $company) }}"
                                   class="btn btn-sm btn-info">View</a>

                                <a href="{{ route('admin.shipping_companies.edit', $company) }}"
                                   class="btn btn-sm btn-outline-primary">Edit</a>

                                <form action="{{ route('admin.shipping_companies.destroy', $company) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this company?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No companies found.</td></tr>
                    @endforelse
                    </tbody>
                </table>

            </div>

            <div class="mt-3">
                {{ $companies->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
