<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Review;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'quantity',
        'is_active',
        'show_in_shop',
        'compare_price',
        'is_trending',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'show_in_shop'  => 'boolean',
        'is_trending'   => 'boolean',
        'price'         => 'decimal:2',
        'compare_price' => 'decimal:2',
        'quantity'      => 'integer',
    ];

    // Relations
    public function category() { return $this->belongsTo(Category::class); }
    public function tags() { return $this->belongsToMany(Tag::class); }
    public function reviews() { return $this->hasMany(Review::class); }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(300)->height(300);
    }

    // Scopes
    public function scopeShop($q)
    {
        return $q->where('is_active', true)
            ->where('show_in_shop', true)
            ->whereHas('category', fn ($c) => $c->where('is_active', true));
    }

    public function scopeInStock($q)
    {
        return $q->where('quantity', '>', 0);
    }

    public function scopeTrending($q)
    {
        return $q->where('is_trending', true);
    }

    // Badge helpers
    public function isSoldOut(): bool
    {
        return (int)($this->quantity ?? 0) <= 0;
    }

    public function isOnSale(): bool
    {
        $cp = $this->compare_price;
        return !is_null($cp) && (float)$cp > (float)$this->price;
    }

    public function isNew(int $days = 7): bool
    {
        return $this->created_at && $this->created_at->gt(now()->subDays($days));
    }
}
