<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ===== 1) الـ navbar في الـ Backend (الكود القديم بتاعك) =====
        View::composer('Backend.inc.navbar', function ($view) {
            $query = User::supervisors()->orderBy('name');

            $view->with('supervisorsCount', $query->count());
            $view->with('supervisorsTop',   $query->take(5)->get());
        });

            Paginator::useBootstrapFive();


        // ===== 2) عداد الكارت والويش ليست لكل الـ views في الفرونت =====
        View::composer('*', function ($view) {
            // cart من السيشن
            $cart = session('cart', []);
            if (! is_array($cart)) {
                $cart = [];
            }

            $cartCount = collect($cart)->sum(function ($item) {
                return isset($item['qty']) ? (int) $item['qty'] : 0;
            });

            // wishlist من السيشن
            $wishlistIds = session('wishlist', []);
            if (! is_array($wishlistIds)) {
                $wishlistIds = [];
            }

            $wishlistCount = count($wishlistIds);

            $view->with([
                'cartCount'     => $cartCount,
                'wishlistCount' => $wishlistCount,
            ]);
        });
    }
}
