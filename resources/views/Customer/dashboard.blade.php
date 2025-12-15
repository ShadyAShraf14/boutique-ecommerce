{{-- resources/views/Customer/dashboard.blade.php --}}
@extends('Customer.layout')

@section('customer_page_title', auth()->user()->name . ' Profile')
@section('customer_breadcrumb', 'Dashboard')

@section('customer_content')

    <h5 class="mb-3">General information</h5>
    <p class="text-muted small mb-4">
        Welcome back, {{ auth()->user()->name }}. Here you can see a quick overview of your account.
    </p>

    <div class="row g-3">

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted text-uppercase small mb-1">Email</p>
                    <p class="mb-0">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        {{-- تقدر بعدين تربط دول ببيانات حقيقية من الكنترولر --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted text-uppercase small mb-1">Total orders</p>
                    <p class="mb-0">
                        {{ $ordersCount ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <p class="text-muted text-uppercase small mb-1">Last order</p>
                    <p class="mb-0">
                        {{ $lastOrderCode ?? 'No orders yet' }}
                    </p>
                </div>
            </div>
        </div>

    </div>

@endsection
