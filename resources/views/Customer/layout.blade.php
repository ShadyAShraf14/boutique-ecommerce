{{-- resources/views/Customer/layout.blade.php --}}
@extends('Frontend.inc.master')

@section('content')

@php
    $user = auth()->user();
@endphp
<style>
    .account-hero {
        background: #f8f9fb;
        border-bottom: 1px solid #eee;
    }
    .account-nav-card {
        border-radius: 8px;
        border: 1px solid #f0f0f0;
        background: #ffffff;
    }
    .account-nav-card .nav-link {
        font-size: 0.9rem;
        padding: 0.4rem 0;
        color: #555;
    }
    .account-nav-card .nav-link.active {
        font-weight: 600;
        background: #111;
        color: #f9c86a !important; /* لون دهبي قريب من ثيمك */
        border-radius: 4px;
        padding-left: 0.4rem;
    }
    .account-nav-card .nav-link:hover {
        color: #000;
    }
</style>

{{-- الهيرو اللي فوق --}}
<section class="py-4 account-hero">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-md-6">
                <p class="text-uppercase small text-muted mb-1">My account</p>
                <h1 class="h4 mb-0">
                    @yield('customer_page_title', ($user?->name ?? 'Customer') . ' Profile')
                </h1>
            </div>

            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-md-end mb-0 small">
                        <li class="breadcrumb-item">
                            <a href="{{ route('frontend.home') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            @yield('customer_breadcrumb', 'Profile')
                        </li>
                    </ol>
                </nav>
            </div>

        </div>
    </div>
</section>

{{-- محتوى الحساب --}}
<section class="py-5">
    <div class="container">
        <div class="row">

            {{-- الـ Navigation Box --}}
            <div class="col-md-3 mb-4 mb-md-0">
                @include('Customer.inc.sidebar')
            </div>

            {{-- المحتوى اللي بيتغيّر --}}
            <div class="col-md-9">
                @yield('customer_content')
            </div>

        </div>
    </div>
</section>
@endsection
