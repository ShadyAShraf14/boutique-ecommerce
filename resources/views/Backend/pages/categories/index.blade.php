{{-- resources/views/Backend/pages/categories/index.blade.php --}}
@extends('Backend.inc.master')

@section('content')
    <div class="container-fluid mt-4">

        @include('Backend.inc.flash')

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Product Categories</h6>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary">
                    + Add Category
                </a>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Parent</th>
                                <th>Status</th>
                                <th>Created at</th>
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>
@isset($category)
    @php $img = $category->getFirstMediaUrl('image'); @endphp
    @if($img)
        <div class="mt-2">
            <img src="{{ $img }}" style="height:50px;">
        </div>
    @endif
@endisset
                                    </td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->parent?->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $category->is_active ? 'success' : 'secondary' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $category->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="btn btn-sm btn-outline-primary">Edit</a>

                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this category?')">
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
                                    <td colspan="6" class="text-center text-muted">
                                        No categories found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
