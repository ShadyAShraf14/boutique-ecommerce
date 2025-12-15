@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                Review #{{ $review->id }}
            </h6>

            <div>
                <a href="{{ route('admin.reviews.edit', $review) }}"
                   class="btn btn-sm btn-outline-primary">
                    Edit
                </a>

                <a href="{{ route('admin.reviews.index') }}"
                   class="btn btn-sm btn-secondary">
                    Back
                </a>
            </div>
        </div>

        <div class="card-body">

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Product:</div>
                <div class="col-md-9">
                    {{ $review->product->name ?? '-' }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">User:</div>
                <div class="col-md-9">
                    @if($review->user)
                        {{ $review->user->name }} <br>
                        <small class="text-muted">{{ $review->user->email }}</small>
                    @else
                        <span class="text-muted">Guest</span>
                    @endif
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Rating:</div>
                <div class="col-md-9">
                    {{ $review->rating }} / 5
                </div>
            </div>

            @if($review->title)
            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Title:</div>
                <div class="col-md-9">
                    {{ $review->title }}
                </div>
            </div>
            @endif

            @if($review->comment)
            <div class="row mb-3">
                <div class="col-md-3 font-weight-bold">Comment:</div>
                <div class="col-md-9">
                    {{ $review->comment }}
                </div>
            </div>
            @endif

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Status:</div>
                <div class="col-md-9">
                    <span class="badge badge-{{ $review->is_approved ? 'success' : 'secondary' }}">
                        {{ $review->is_approved ? 'Approved' : 'Pending' }}
                    </span>
                </div>
            </div>

            <hr>

            <div class="row mb-2">
                <div class="col-md-3 font-weight-bold">Created at:</div>
                <div class="col-md-9">
                    {{ $review->created_at->format('Y-m-d H:i') }}
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3 font-weight-bold">Updated at:</div>
                <div class="col-md-9">
                    {{ $review->updated_at->format('Y-m-d H:i') }}
                </div>
            </div>

            <form action="{{ route('admin.reviews.destroy', $review) }}"
                  method="POST"
                  onsubmit="return confirm('Delete this review?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">
                    Delete
                </button>
            </form>

        </div>
    </div>

</div>
@endsection
