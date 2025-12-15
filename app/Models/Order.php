<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'shipping_method_id',
        'subtotal',
        'discount',
        'tax',
        'shipping_cost',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'payment_reference',
        'paypal_order_id',
        'paypal_capture_id',
        'paypal_payer_id',
        'paypal_payer_email',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // ✅ آخر فاتورة (أحدث Version)
    public function latestInvoice(): HasOne
    {
        return $this->hasOne(Invoice::class)->latestOfMany('version');
    }
}
