<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        /* ================= CRUD COUNTS ================= */
        $products   = Product::count();
        $categories = Category::count();
        $tags       = Tag::count();
        $users      = User::count();

        /* ================= BUSINESS KPIs ================= */
        $revenueThisMonth = (float) Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('payment_status', 'paid')
            ->sum('total');

        $ordersThisMonth = (int) Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $pendingOrders = (int) Order::where('status', 'pending')->count();

        $newCustomers30 = (int) User::where('created_at', '>=', now()->subDays(30))->count();

        /* ================= LATEST ORDERS ================= */
        $latestOrders = Order::with('user:id,name')
            ->latest()
            ->take(5)
            ->get(['id','user_id','total','status','payment_status','created_at']);

        /* ================= CHART (LAST 6 MONTHS) ================= */
        $from = now()->subMonths(5)->startOfMonth();

        $rows = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as c')
            ->where('created_at', '>=', $from)
            ->groupBy('ym')
            ->orderBy('ym')
            ->get();

        $labels = [];
        $chartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $key = $d->format('Y-m');

            $labels[] = $d->format('M Y');
            $row = $rows->firstWhere('ym', $key);
            $chartData[] = (int) ($row->c ?? 0);
        }

        return view('Backend.dashboard', compact(
            'products',
            'categories',
            'tags',
            'users',
            'revenueThisMonth',
            'ordersThisMonth',
            'pendingOrders',
            'newCustomers30',
            'latestOrders',
            'labels',
            'chartData'
        ));
    }
}
