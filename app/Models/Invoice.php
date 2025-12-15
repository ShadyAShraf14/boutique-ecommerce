<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'order_id',
        'invoice_number',
        'version',
        'pdf_path',
        'issued_at',
        'snapshot',
        'meta',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'snapshot'  => 'array',
        'meta'      => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
