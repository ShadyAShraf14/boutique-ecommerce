
{{-- resources/views/Backend/pages/categories/edit.blade.php --}}
@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    @include('Backend.inc.flash')

    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Edit Category</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category) }}"
                  method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('Backend.pages.categories._form', ['button' => 'Update'])
            </form>
        </div>
    </div>

</div>
@endsection
