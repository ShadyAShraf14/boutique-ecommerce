<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'tracking_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
