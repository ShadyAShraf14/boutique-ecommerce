<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected function getCart(): array
    {
        // شكل الكارت: [ product_id => [id, name, price, qty, image] ]
        return session()->get('cart', []);
    }

    protected function saveCart(array $cart): void
    {
        session(['cart' => $cart]);
    }

    /**
     * عرض صفحة الكارت
     */
    public function index()
    {
        $cart = $this->getCart();

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['qty'];
        });

        $total = $subtotal; // لحد ما نضيف شحن/خصم

        return view('Frontend.pages.cart', compact('cart', 'subtotal', 'total'));
    }

    /**
     * إضافة منتج للكارت
     */
    public function add(Request $request, Product $product)
    {
        $qty  = (int) $request->input('qty', 1);
        if ($qty < 1) {
            $qty = 1;
        }

        $cart = $this->getCart();

        if (isset($cart[$product->id])) {
            // المنتج موجود: زوّد الكمية
            $cart[$product->id]['qty'] += $qty;
        } else {
            // أول مرة يتضاف
            $cart[$product->id] = [
                'id'    => $product->id,
                'name'  => $product->name,
                'price' => $product->price,
                'qty'   => $qty,
                'image' => $product->getFirstMediaUrl('image')
                            ?: asset('frontend-assets/img/default-product.jpg'),
            ];
        }

        $this->saveCart($cart);

        return back()->with('success', 'Product added to cart');
    }

    /**
     * تعديل الكمية من صفحة الكارت
     */
    public function update(Request $request, Product $product)
    {
        $cart = $this->getCart();

        if (! isset($cart[$product->id])) {
            return redirect()->route('frontend.cart.index');
        }

        $qty = max(1, (int) $request->input('qty', 1));
        $cart[$product->id]['qty'] = $qty;

        $this->saveCart($cart);

        return redirect()->route('frontend.cart.index');
    }

    /**
     * حذف منتج من الكارت
     */
    public function remove(Product $product)
    {
        $cart = $this->getCart();

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            $this->saveCart($cart);
        }

        return redirect()->route('frontend.cart.index');
    }

    /**
     * تفريغ الكارت
     */
    public function clear()
    {
        $this->saveCart([]);

        return redirect()->route('frontend.cart.index');
    }
}
