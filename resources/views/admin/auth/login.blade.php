{{-- resources/views/admin/auth/login.blade.php --}}
@extends('Backend.inc.master')

@section('content')
    <div class="auth-wrapper d-flex align-items-center justify-content-center" style="min-height: 100vh; background:#f5f5f5;">
        <div class="card shadow-sm border-0" style="max-width: 420px; width:100%; border-radius: 1rem;">
            <div class="card-body p-4 p-md-5">

                {{-- Logo + Title --}}
                <div class="text-center mb-4">
                    <span class="d-inline-block fw-bold" style="letter-spacing: .3em; font-size: 0.9rem;">BOUTIQUE</span>
                    <h4 class="mt-3 mb-1" style="font-weight: 600;">Admin Login</h4>
                    <p class="text-muted mb-0" style="font-size: 0.9rem;">
                        Sign in to access the dashboard.
                    </p>
                </div>

                {{-- Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger py-2 mb-4">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}" class="auth-form">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label small text-uppercase text-muted mb-1">Email</label>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control form-control-lg @error('email') is-invalid @enderror"
                               required autofocus
                               placeholder="admin@example.com">
                        @error('email')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="password" class="form-label small text-uppercase text-muted mb-1">Password</label>
                        </div>
                        <input id="password"
                               type="password"
                               name="password"
                               class="form-control form-control-lg @error('password') is-invalid @enderror"
                               required
                               placeholder="••••••••">
                        @error('password')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label small text-muted" for="remember">
                            Keep me signed in
                        </label>
                    </div>

                    {{-- Submit --}}
                    <div class="d-grid mb-2">
                        <button type="submit" class="btn btn-dark btn-lg" style="border-radius: 999px;">
                            Login
                        </button>
                    </div>

                    <p class="text-center text-muted mb-0" style="font-size: 0.8rem;">
                        © {{ date('Y') }} BOUTIQUE Admin Panel
                    </p>
                </form>

            </div>
        </div>
    </div>
@endsection
