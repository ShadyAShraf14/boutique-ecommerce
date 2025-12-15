@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Tags</h6>
            <a href="{{ route('admin.tags.create') }}" class="btn btn-sm btn-primary">
                + Add Tag
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th width="180">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($tags as $tag)
                        <tr>
                            <td>
                                @php
                                    $img = $tag->getFirstMediaUrl('image'); // نستخدم الأصل مش thumb
                                @endphp
                                @if($img)
                                    <img src="{{ $img }}" style="height:40px;">
                                @endif
                            </td>
                            <td>{{ $tag->name }}</td>
                            <td>
                                <span class="badge badge-{{ $tag->is_active ? 'success' : 'secondary' }}">
                                    {{ $tag->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $tag->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.tags.edit', $tag) }}"
                                   class="btn btn-sm btn-outline-primary">Edit</a>

                                <form action="{{ route('admin.tags.destroy', $tag) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this tag?')">
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
                            <td colspan="5" class="text-center text-muted">
                                No tags found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $tags->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
