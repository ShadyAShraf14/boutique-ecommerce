<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        // array of product IDs من السيشن
        $ids = session('wishlist', []);

        $products = Product::whereIn('id', $ids)
            ->where('is_active', true)
            ->get();

        return view('Frontend.pages.wishlist', compact('products'));
    }

    /**
     * Toggle (add/remove) product in wishlist
     */
    public function add(Request $request, Product $product)
    {
        $wishlist = session('wishlist', []);

        if (in_array($product->id, $wishlist)) {
            // لو المنتج موجود → نشيله
            $wishlist = array_values(array_diff($wishlist, [$product->id]));
            $message = 'Product removed from wishlist';
        } else {
            // لو مش موجود → نضيفه
            $wishlist[] = $product->id;
            $message = 'Product added to wishlist';
        }

        session(['wishlist' => $wishlist]);

        return back()->with('success', $message);
    }
}
