{{-- resources/views/Customer/profile.blade.php --}}
@extends('Customer.layout')

@section('customer_page_title', 'Profile details')
@section('customer_breadcrumb', 'Profile')

@section('customer_content')

    <h5 class="mb-3">Profile details</h5>

    {{-- رسالة نجاح --}}
    @if(session('success'))
        <div class="alert alert-success py-2 small">
            {{ session('success') }}
        </div>
    @endif

    {{-- عرض أخطاء الفاليديشن --}}
    @if($errors->any())
        <div class="alert alert-danger py-2 small">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('customer.profile.update') }}" class="mt-3" style="max-width: 600px;">
        @csrf
        @method('PUT')

        {{-- NAME --}}
        <div class="mb-3">
            <label class="form-label small text-uppercase">Name</label>
            <input
                type="text"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}"
                required
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- EMAIL --}}
        <div class="mb-3">
            <label class="form-label small text-uppercase">Email</label>
            <input
                type="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}"
                required
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <hr class="my-4">

        <h6 class="small text-uppercase text-muted mb-2">Change password (optional)</h6>

        {{-- PASSWORD --}}
        <div class="mb-3">
            <label class="form-label small">New password</label>
            <input
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                autocomplete="new-password"
                placeholder="Leave empty if you don't want to change it"
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- PASSWORD CONFIRMATION --}}
        <div class="mb-4">
            <label class="form-label small">Confirm new password</label>
            <input
                type="password"
                name="password_confirmation"
                class="form-control"
                autocomplete="new-password"
            >
        </div>

        <button type="submit" class="btn btn-dark">
            Save changes
        </button>
    </form>

@endsection
