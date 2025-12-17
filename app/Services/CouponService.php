<?php

namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    /**
     * Validate coupon against subtotal and return error message or null if valid.
     */
    public function getCouponError(Coupon $coupon, float $subtotal): ?string
    {
        if (! $coupon->is_active) {
            return 'This coupon is not active.';
        }

        if ($coupon->starts_at && now()->lt($coupon->starts_at)) {
            return 'This coupon is not valid yet.';
        }

        if ($coupon->ends_at && now()->gt($coupon->ends_at)) {
            return 'This coupon has expired.';
        }

        if (!is_null($coupon->max_uses) && $coupon->used_count >= $coupon->max_uses) {
            return 'This coupon has reached its usage limit.';
        }

        if (!is_null($coupon->min_order_total) && $subtotal < $coupon->min_order_total) {
            return 'Order total is too low for this coupon.';
        }

        return null;
    }

    /**
     * Calculate discount amount based on coupon type.
     */
    public function calculateDiscount(Coupon $coupon, float $subtotal): float
    {
        if ($subtotal <= 0) {
            return 0.0;
        }

        if ($coupon->type === 'fixed') {
            return (float) min($coupon->value, $subtotal);
        }

        // percent
        return (float) round($subtotal * ($coupon->value / 100), 2);
    }

    /**
     * Apply coupon code from session and return [coupon, discount]
     * - If invalid/expired -> remove it from session automatically.
     */
    public function resolveFromSession(float $subtotal): array
    {
        $couponCode = session('coupon_code');
        $coupon     = null;
        $discount   = 0.0;

        if (! $couponCode) {
            return [$coupon, $discount];
        }

        $coupon = Coupon::where('code', $couponCode)->first();

        if (! $coupon) {
            session()->forget('coupon_code');
            return [null, 0.0];
        }

        $error = $this->getCouponError($coupon, $subtotal);

        if ($error !== null) {
            session()->forget('coupon_code');
            return [null, 0.0];
        }

        $discount = $this->calculateDiscount($coupon, $subtotal);

        return [$coupon, $discount];
    }
}
