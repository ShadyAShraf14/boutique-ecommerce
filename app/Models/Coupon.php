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
}
