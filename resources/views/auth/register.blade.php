{{-- resources/views/auth/register.blade.php --}}
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
                                <span class="d-inline-block fw-bold" style="letter-spacing: .3em; font-size: 0.9rem;">
                                    BOUTIQUE
                                </span>
                                <h4 class="mt-3 mb-1" style="font-weight: 600;">Create Account</h4>
                                <p class="text-muted mb-0" style="font-size: 0.9rem;">
                                    Join us and enjoy seamless shopping.
                                </p>
                            </div>

                            {{-- Errors --}}
                            @if ($errors->any())
                                <div class="alert alert-danger py-2 mb-4 small">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                {{-- Name --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label small text-uppercase text-muted mb-1">Full Name</label>
                                    <input id="name"
                                           type="text"
                                           name="name"
                                           class="form-control form-control-lg @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}"
                                           required autofocus
                                           placeholder="Your full name">
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="email" class="form-label small text-uppercase text-muted mb-1">E-mail Address</label>
                                    <input id="email"
                                           type="email"
                                           name="email"
                                           class="form-control form-control-lg @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}"
                                           required
                                           placeholder="you@example.com">
                                </div>

                                {{-- Password --}}
                                <div class="mb-3">
                                    <label for="password" class="form-label small text-uppercase text-muted mb-1">Password</label>
                                    <input id="password"
                                           type="password"
                                           name="password"
                                           class="form-control form-control-lg @error('password') is-invalid @enderror"
                                           required
                                           placeholder="••••••••">
                                </div>

                                {{-- Confirm Password --}}
                                <div class="mb-4">
                                    <label for="password-confirm" class="form-label small text-uppercase text-muted mb-1">
                                        Confirm Password
                                    </label>
                                    <input id="password-confirm"
                                           type="password"
                                           name="password_confirmation"
                                           class="form-control form-control-lg"
                                           required
                                           placeholder="Re-type your password">
                                </div>

                                {{-- Submit --}}
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-dark btn-lg" style="border-radius: 999px;">
                                        Register
                                    </button>
                                </div>

                                {{-- Login link --}}
                                <p class="text-center text-muted mb-0" style="font-size: 0.9rem;">
                                    Already have an account?
                                    <a href="{{ route('login') }}" class="text-decoration-none">
                                        Login here
                                    </a>
                                </p>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
