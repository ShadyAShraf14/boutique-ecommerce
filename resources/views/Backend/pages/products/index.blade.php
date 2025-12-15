@extends('Backend.inc.master')

@section('content')
    <div class="container-fluid mt-4">

        @include('Backend.inc.flash')

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Products</h6>
                <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">
                    + Add Product
                </a>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Created at</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        @php
                                            $img = $product->getFirstMediaUrl('image');
                                        @endphp
                                        @if ($img)
                                            <img src="{{ $img }}" style="height:40px;">
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? '-' }}</td>
                                    <td>{{ number_format($product->price, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $product->is_active ? 'success' : 'secondary' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $product->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.products.show', $product) }}"
                                            class="btn btn-sm btn-info">View</a>

                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="btn btn-sm btn-outline-primary">Edit</a>

                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this product?')">
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
                                    <td colspan="7" class="text-center text-muted">
                                        No products found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination + summary --}}
                <div class="mt-4 mb-5">
                    {{ $products->links() }}
                </div>

            </div>
        </div>

    </div>
@endsection
