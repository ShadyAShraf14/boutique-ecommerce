@extends('Backend.inc.master')

@section('content')
<div class="container-fluid mt-4">

    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">{{ $product->name }}</h6>
        </div>
        <div class="card-body">
            @php $img = $product->getFirstMediaUrl('image'); @endphp
            @if($img)
                <img src="{{ $img }}" style="height:120px;" class="mb-3">
            @endif

            <p><strong>Category:</strong> {{ $product->category->name ?? '-' }}</p>
            <p><strong>Price:</strong> {{ number_format($product->price, 2) }}</p>
            <p><strong>Status:</strong> {{ $product->is_active ? 'Active' : 'Inactive' }}</p>
            <p><strong>Tags:</strong>
                @foreach($product->tags as $tag)
                    <span class="badge badge-info">{{ $tag->name }}</span>
                @endforeach
            </p>
            <p><strong>Description:</strong></p>
            <p>{{ $product->description }}</p>
        </div>
    </div>

</div>
@endsection
