{{-- resources/views/auth/login.blade.php --}}
@extends('Frontend.inc.master')

@section('content')
    <section class="py-5" style="min-height: 80vh; background:#f8f8f8;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-5">
                    <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
                        <div class="card-body p-4 p-md-5">

                            {{-- Logo + Title --}}
                            <div class="text-center mb-4">
                                <span class="d-inline-block fw-bold" style="letter-spacing: .3em; font-size: 0.9rem;">BOUTIQUE</span>
                                <h4 class="mt-3 mb-1" style="font-weight: 600;">Welcome back</h4>
                                <p class="text-muted mb-0" style="font-size: 0.9rem;">
                                    Login to continue shopping.
                                </p>
                            </div>

                            {{-- Errors --}}
                            @if ($errors->any())
                                <div class="alert alert-danger py-2 mb-4">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
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
                                           placeholder="you@example.com">
                                    @error('email')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Password --}}
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="password" class="form-label small text-uppercase text-muted mb-1">Password</label>
                                        @if (Route::has('password.request'))
                                            <a class="small text-decoration-none" href="{{ route('password.request') }}">
                                                Forgot password?
                                            </a>
                                        @endif
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
                                        Remember me
                                    </label>
                                </div>

                                {{-- Submit --}}
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-dark btn-lg" style="border-radius: 999px;">
                                        Login
                                    </button>
                                </div>

                                {{-- Register link --}}
                                @if (Route::has('register'))
                                    <p class="text-center text-muted mb-0" style="font-size: 0.9rem;">
                                        Don’t have an account?
                                        <a href="{{ route('register') }}" class="text-decoration-none">
                                            Create one
                                        </a>
                                    </p>
                                @endif
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
