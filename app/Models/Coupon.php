<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_total',
        'max_uses',
        'used_count',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'value'           => 'decimal:2',
        'min_order_total' => 'decimal:2',
        'starts_at'       => 'datetime',
        'ends_at'         => 'datetime',
        'is_active'       => 'boolean',
        'max_uses'        => 'integer',
        'used_count'      => 'integer',
    ];

    /**
     * Normalize coupon code on save (trim + uppercase).
     */
    protected static function booted()
    {
        static::saving(function (self $coupon) {
            if (isset($coupon->code)) {
                $coupon->code = strtoupper(trim($coupon->code));
            }

            // normalize type just in case
            if (isset($coupon->type)) {
                $coupon->type = strtolower(trim($coupon->type));
            }

            // ensure numeric sanity (optional safe guards)
            if (isset($coupon->used_count) && $coupon->used_count < 0) {
                $coupon->used_count = 0;
            }
        });
    }

    /**
     * Dashboard-friendly status label.
     */
    public function getStatusLabelAttribute(): string
    {
        if (! $this->is_active) {
            return 'Inactive';
        }

        if ($this->starts_at && now()->lt($this->starts_at)) {
            return 'Scheduled';
        }

        if ($this->ends_at && now()->gt($this->ends_at)) {
            return 'Expired';
        }

        if (!is_null($this->max_uses) && $this->used_count >= $this->max_uses) {
            return 'Limit reached';
        }

        return 'Active';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status_label) {
            'Active'        => 'success',
            'Scheduled'     => 'info',
            'Expired'       => 'danger',
            'Limit reached' => 'warning',
            default         => 'secondary',
        };
    }

    /**
     * Convenience for quick checks.
     */
    public function isCurrentlyValid(float $subtotal = null): bool
    {
        if (! $this->is_active) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->ends_at && now()->gt($this->ends_at)) return false;
        if (!is_null($this->max_uses) && $this->used_count >= $this->max_uses) return false;
        if (!is_null($subtotal) && !is_null($this->min_order_total) && $subtotal < (float)$this->min_order_total) return false;

        return true;
    }
}
