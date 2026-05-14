<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'short_description', 'description',
        'price', 'sale_price', 'stock', 'sku', 'thumbnail',
        'category_id', 'is_active', 'is_featured', 'is_new_arrival',
        'is_best_seller', 'attributes', 'sort_order'
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'is_featured'    => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_best_seller' => 'boolean',
        'attributes'     => 'array',
        'price'          => 'decimal:2',
        'sale_price'     => 'decimal:2',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }
    public function approvedReviews()
{
    return $this->hasMany(\App\Models\Review::class)->where('is_approved', true);
}

public function getAvgRatingAttribute(): float
{
    return round($this->approvedReviews()->avg('rating') ?? 0, 1);
}

public function getReviewCountAttribute(): int
{
    return $this->approvedReviews()->count();
}

    // Scopes
    public function scopeActive($q)    { return $q->where('is_active', true); }
    public function scopeFeatured($q)  { return $q->where('is_featured', true); }
    public function scopeNewArrival($q){ return $q->where('is_new_arrival', true); }
    public function scopeBestSeller($q){ return $q->where('is_best_seller', true); }

    // Accessors
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getIsOnSaleAttribute(): bool
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (!$this->is_on_sale) return 0;
        return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
    }

  public function getThumbnailUrlAttribute(): string
{
    return $this->thumbnail
        ? asset('storage/' . $this->thumbnail)
        : 'https://placehold.co/400x400/1a1a2e/c9a84c?text=No+Image';
}

    // Auto slug + SKU
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
            if (empty($model->sku)) {
                $model->sku = 'SKU-' . strtoupper(Str::random(8));
            }
        });
    }
}
