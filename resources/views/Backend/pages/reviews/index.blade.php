{{-- resources/views/Backend/pages/reviews/index.blade.php --}}

@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">

        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Reviews</h6>

            <form method="GET" class="form-inline">
                <select name="status" class="form-control form-control-sm mr-2">
                    @php $status = request('status'); @endphp
                    <option value="">All</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending"  {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
                <button class="btn btn-sm btn-outline-primary">Filter</button>
            </form>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>User</th>
                        <th>Rating</th>
                        <th>Approved</th>
                        <th>Created at</th>
                        <th width="200">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>{{ $review->product->name ?? '-' }}</td>
                            <td>
                                @if($review->user)
                                    {{ $review->user->name }} <br>
                                    <small class="text-muted">{{ $review->user->email }}</small>
                                @else
                                    <span class="text-muted">Guest</span>
                                @endif
                            </td>
                            <td>{{ $review->rating }} / 5</td>
                            <td>
                                <span class="badge badge-{{ $review->is_approved ? 'success' : 'secondary' }}">
                                    {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                </span>
                            </td>
                            <td>{{ $review->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.reviews.show', $review) }}"
                                   class="btn btn-sm btn-info">View</a>

                                <a href="{{ route('admin.reviews.edit', $review) }}"
                                   class="btn btn-sm btn-outline-primary">Edit</a>

                                <form action="{{ route('admin.reviews.destroy', $review) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this review?')">
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
                                No reviews found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
