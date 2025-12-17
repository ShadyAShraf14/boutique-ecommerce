<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\UserAddress;
use App\Models\ShippingMethod;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /* ========== Helpers خاصة بالكارت ========== */

    protected function getCart(): array
    {
        return session('cart', []);
    }

    protected function getSubtotal(array $cart): float
    {
        return (float) collect($cart)->sum(function ($row) {
            return $row['price'] * $row['qty'];
        });
    }

    protected function calculateTax(float $amount): float
    {
        $taxBase = max(0, $amount);
        return round($taxBase * 0.15, 2);
    }

    /* ========== Helpers خاصة بالعناوين وطرق الشحن ========== */

    protected function getUserAddresses()
    {
        if (!auth()->check()) {
            return collect();
        }

        return UserAddress::with(['country', 'state', 'city'])
            ->where('user_id', auth()->id())
            ->where('is_active', true)
            ->orderByDesc('is_default_shipping')
            ->orderBy('id')
            ->get();
    }

    protected function getSelectedAddress($addresses): ?UserAddress
    {
        if ($addresses->isEmpty()) {
            return null;
        }

        $selectedId = session('checkout_address_id');

        if ($selectedId) {
            return $addresses->firstWhere('id', $selectedId) ?? $addresses->first();
        }

        $default = $addresses->firstWhere('is_default_shipping', true);

        return $default ?? $addresses->first();
    }

    protected function getShippingMethods(?UserAddress $address)
    {
        return ShippingMethod::where('is_active', true)
            ->orderBy('price')
            ->get();
    }

    protected function getSelectedShipping($shippingMethods)
    {
        if (!$shippingMethods || $shippingMethods->isEmpty()) {
            return null;
        }

        $selectedId = session('checkout_shipping_method_id');

        if ($selectedId) {
            return $shippingMethods->firstWhere('id', $selectedId);
        }

        return null;
    }

    /* ========== عرض صفحة الـ Checkout ========== */

    public function index()
    {
        $cart = $this->getCart();
        if (empty($cart)) {
            return redirect()->route('frontend.cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $addresses       = $this->getUserAddresses();
        $selectedAddress = $this->getSelectedAddress($addresses);

        $shippingMethods  = $this->getShippingMethods($selectedAddress);
        $selectedShipping = $this->getSelectedShipping($shippingMethods);

        $subtotal = $this->getSubtotal($cart);

        // ✅ Coupon (shared service)
        [$coupon, $discount] = $this->couponService->resolveFromSession($subtotal);

        $taxBase      = max(0, $subtotal - $discount);
        $tax          = $this->calculateTax($taxBase);
        $shippingCost = $selectedShipping?->price ?? 0;
        $total        = $taxBase + $tax + $shippingCost;

        $countries = Country::orderBy('name')->get();
        $states    = State::orderBy('name')->get();
        $cities    = City::orderBy('name')->get();

        $selectedPayment = session('checkout_payment_method', 'paypal');

        return view('Frontend.pages.checkout', compact(
            'cart',
            'addresses',
            'selectedAddress',
            'shippingMethods',
            'selectedShipping',
            'subtotal',
            'discount',
            'coupon',
            'tax',
            'shippingCost',
            'total',
            'countries',
            'states',
            'cities',
            'selectedPayment'
        ));
    }

    /* ========== كوبونات ========== */

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $cart = $this->getCart();

        if (empty($cart)) {
            return redirect()
                ->route('frontend.cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $subtotal = $this->getSubtotal($cart);

        $code   = strtoupper(trim($request->input('coupon_code')));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid coupon code.');
        }

        $error = $this->couponService->getCouponError($coupon, $subtotal);

        if ($error !== null) {
            return back()->with('error', $error);
        }

        session(['coupon_code' => $coupon->code]);

        return back()->with('success', 'Coupon applied successfully.');
    }

    public function removeCoupon()
    {
        session()->forget('coupon_code');

        return back()->with('success', 'Coupon removed.');
    }

    /* ========== اختيار العنوان ========== */

    public function selectAddress(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:user_addresses,id',
        ]);

        $address = UserAddress::where('user_id', auth()->id())
            ->where('is_active', true)
            ->findOrFail($request->address_id);

        session(['checkout_address_id' => $address->id]);
        session()->forget('checkout_shipping_method_id');

        return back();
    }

    public function storeAddress(Request $request)
    {
        $request->merge([
            'is_default_shipping' => true,
            'is_default_billing'  => true,
            'is_active'           => true,
        ]);

        $data = $request->validate([
            'first_name'          => 'required|string|max:255',
            'last_name'           => 'nullable|string|max:255',
            'phone'               => 'required|string|max:50',
            'country_id'          => 'required|exists:countries,id',
            'state_id'            => 'required|exists:states,id',
            'city_id'             => 'required|exists:cities,id',
            'address_line1'       => 'required|string|max:255',
            'address_line2'       => 'nullable|string|max:255',
            'postal_code'         => 'nullable|string|max:50',
            'is_default_shipping' => 'boolean',
            'is_default_billing'  => 'boolean',
            'is_active'           => 'boolean',
        ]);

        $data['user_id'] = auth()->id();

        $address = UserAddress::create($data);

        session(['checkout_address_id' => $address->id]);
        session()->forget('checkout_shipping_method_id');

        return redirect()
            ->route('frontend.checkout.index')
            ->with('success', 'Address added successfully.');
    }

    public function selectShipping(Request $request)
    {
        $request->validate([
            'shipping_method_id' => 'required|exists:shipping_methods,id',
        ]);

        $method = ShippingMethod::where('is_active', true)
            ->findOrFail($request->shipping_method_id);

        session(['checkout_shipping_method_id' => $method->id]);

        return back()->with('success', 'Shipping cost is applied successfully');
    }

    public function selectPayment(Request $request)
    {
        $data = $request->validate([
            'payment_method' => 'required|in:paypal,omnipay_paypal',
        ]);

        session(['checkout_payment_method' => $data['payment_method']]);

        return back()->with('success', 'Payment method selected successfully.');
    }

    public function placeOrder(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to place your order.');
        }

        $cart = $this->getCart();
        if (empty($cart)) {
            return redirect()->route('frontend.cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $addressId        = session('checkout_address_id');
        $shippingMethodId = session('checkout_shipping_method_id');
        $paymentMethod    = session('checkout_payment_method', 'paypal');

        if (!$addressId || !$shippingMethodId) {
            return back()->with('error', 'Please select shipping address and shipping way first.');
        }

        $addresses       = $this->getUserAddresses();
        $selectedAddress = $addresses->firstWhere('id', $addressId);

        $shippingMethods  = $this->getShippingMethods($selectedAddress);
        $selectedShipping = $shippingMethods->firstWhere('id', $shippingMethodId);

        $subtotal = $this->getSubtotal($cart);

        // ✅ Coupon (shared service)
        [$coupon, $discount] = $this->couponService->resolveFromSession($subtotal);

        $taxBase      = max(0, $subtotal - $discount);
        $tax          = $this->calculateTax($taxBase);
        $shippingCost = $selectedShipping?->price ?? 0;
        $total        = $taxBase + $tax + $shippingCost;

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id'            => auth()->id(),
                'address_id'         => $addressId,
                'shipping_method_id' => $shippingMethodId,

                'subtotal'      => $subtotal,
                'discount'      => $discount,
                'tax'           => $tax,
                'shipping_cost' => $shippingCost,
                'total'         => $total,

                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'status'         => 'pending',
            ]);

            foreach ($cart as $productId => $row) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $productId,
                    'product_name' => $row['name'] ?? '',
                    'price'        => $row['price'],
                    'quantity'     => $row['qty'],
                    'total'        => $row['price'] * $row['qty'],
                ]);
            }

            DB::commit();

            if ($paymentMethod === 'paypal') {
                return redirect()->route('payment.paypal.redirect', $order);
            }

            if ($paymentMethod === 'omnipay_paypal') {
                return redirect()->route('payment.omnipay.redirect', $order);
            }

            session()->forget([
                'cart',
                'coupon_code',
                'checkout_address_id',
                'checkout_shipping_method_id',
                'checkout_payment_method',
            ]);

            return redirect()
                ->route('customer.orders.show', $order->id)
                ->with('success', 'Order placed successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function thankYou($orderId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $order = Order::with(['items', 'shippingMethod', 'address'])
            ->where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('Frontend.pages.thankyou', compact('order'));
    }
}
