<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // 1) هات المنتجات اللي الأدمن علم عليها Trending
        $trendingProducts = Product::with('category')
            ->where('is_active', true)
            ->where('show_in_shop', true)
            ->where('is_trending', true)
            ->latest()
            ->take(8)
            ->get();

        // 2) fallback لو العدد أقل من 8 (عشان الصفحة متبقاش فاضية)
        if ($trendingProducts->count() < 8) {
            $more = Product::with('category')
                ->where('is_active', true)
                ->where('show_in_shop', true)
                ->whereNotIn('id', $trendingProducts->pluck('id'))
                ->latest()
                ->take(8 - $trendingProducts->count())
                ->get();

            $trendingProducts = $trendingProducts->concat($more);
        }

        return view('Frontend.pages.home', compact('trendingProducts'));
    }
}
