<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'image',
        'parent_id', 'sort_order', 'is_active', 'show_on_homepage'
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'show_on_homepage' => 'boolean',
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Scopes
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeHomepage($q)
    {
        return $q->where('show_on_homepage', true);
    }

    public function scopeRoots($q)
    {
        return $q->whereNull('parent_id');
    }

    // Auto slug
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function getImageUrlAttribute(): string
{
    return $this->image
        ? asset('storage/' . $this->image)
        : 'https://placehold.co/200x200/1a1a2e/c9a84c?text=' . urlencode($this->name);
}
}
