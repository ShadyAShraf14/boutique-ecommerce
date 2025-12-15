<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Shop page (قائمة المنتجات مع الفلترة والسورت)
     */
    public function index(Request $request)
    {
        $query = Product::with('category')
            ->where('is_active', true);

        // فلترة بالكـاتيجوري عن طريق ?category=slug
        $activeCategory = null;

        if ($request->filled('category')) {
            $categorySlug = $request->query('category');

            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });

            $activeCategory = Category::where('slug', $categorySlug)->first();
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
            default: // latest
                $query->latest();
                break;
        }

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('Frontend.pages.shop', [
            'products'       => $products,
            'categories'     => $categories,
            'activeCategory' => $activeCategory,
            'sort'           => $sort,
        ]);
    }

    /**
     * Product details page
     */
public function show($slug)
{
    $product = Product::with([
            'category',
            'tags',
            'reviews' => function ($q) {
                $q->where('is_approved', true)->latest();
            },
            'reviews.user',
        ])
        ->where('slug', $slug)
        ->firstOrFail();

    // كل الميديا من الكولكشن الموحدة "image"
    $mediaItems = $product->getMedia('image');

    $fallbackImage = asset('frontend-assets/img/default-product.jpg');

    // صورة رئيسية
    $mainImage = $product->getFirstMediaUrl('image')
        ?: $fallbackImage;

    // Related products
    $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->take(4)
        ->get();

    // الريفيوز + المتوسط + العدد
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
