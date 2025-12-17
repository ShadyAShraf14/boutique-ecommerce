<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CouponService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    protected function getCart(): array
    {
        return session()->get('cart', []);
    }

    protected function saveCart(array $cart): void
    {
        session(['cart' => $cart]);
    }

    public function index(CouponService $couponService)
    {
        $cart = $this->getCart();

        $subtotal = (float) collect($cart)->sum(function ($item) {
            return $item['price'] * $item['qty'];
        });

        // ✅ apply coupon (if exists) using shared service
        [$coupon, $discount] = $couponService->resolveFromSession($subtotal);

        $total = max(0, $subtotal - $discount);

        return view('Frontend.pages.cart', compact(
            'cart',
            'subtotal',
            'coupon',
            'discount',
            'total'
        ));
    }

    public function add(Request $request, Product $product)
    {
        $qty  = (int) $request->input('qty', 1);
        if ($qty < 1) {
            $qty = 1;
        }

        $cart = $this->getCart();

        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty'] += $qty;
        } else {
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

    public function remove(Product $product)
    {
        $cart = $this->getCart();

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            $this->saveCart($cart);
        }

        return redirect()->route('frontend.cart.index');
    }

    public function clear()
    {
        $this->saveCart([]);

        // لو فضّيت الكارت، شيل الكوبون كمان عشان يبقى منطقي
        session()->forget('coupon_code');

        return redirect()->route('frontend.cart.index');
    }
}
