<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // الأساس الاحترافي
        $query = Product::with('category')->shop();

        // فلترة category via ?category=slug
        $activeCategory = null;
        if ($request->filled('category')) {
            $categorySlug = $request->query('category');

            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });

            $activeCategory = Category::where('slug', $categorySlug)->first();
        }

        // فلترة السعر: ?min_price=..&max_price=..
        if ($request->filled('min_price') || $request->filled('max_price')) {
            $min = (float) $request->get('min_price', 0);
            $max = (float) $request->get('max_price', 999999999);

            $query->whereBetween('price', [$min, $max]);
        }

        // متاح فقط: ?in_stock=1
        if ($request->boolean('in_stock')) {
            $query->inStock();
        }

        // السورت
        $sort = $request->get('sort', 'latest');

        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        // نعرض فقط الكاتيجوريز الفعالة
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('Frontend.pages.shop', [
            'products'       => $products,
            'categories'     => $categories,
            'activeCategory' => $activeCategory,
            'sort'           => $sort,
        ]);
    }

    public function show($slug)
    {
        // يخضع لنفس قواعد الـ shop
        $product = Product::with([
                'category',
                'tags',
                'reviews' => function ($q) {
                    $q->where('is_approved', true)->latest();
                },
                'reviews.user',
            ])
            ->shop()
            ->where('slug', $slug)
            ->firstOrFail();

        $mediaItems = $product->getMedia('image');
        $fallbackImage = asset('frontend-assets/img/default-product.jpg');
        $mainImage = $product->getFirstMediaUrl('image') ?: $fallbackImage;

        $relatedProducts = Product::shop()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $reviews      = $product->reviews;
        $avgRating    = $reviews->avg('rating') ?: 0;
        $reviewsCount = $reviews->count();

        return view('Frontend.pages.product-show', compact(
            'product',
            'mediaItems',
            'relatedProducts',
            'mainImage',
            'reviews',
            'avgRating',
            'reviewsCount'
        ));
    }
}
