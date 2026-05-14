<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'product_id', 'name', 'email', 'rating', 'title', 'comment', 'is_approved'
    ];

    protected $casts = ['is_approved' => 'boolean'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeApproved($q)
    {
        return $q->where('is_approved', true);
    }

    // Star string for display e.g. rating=4 → "★★★★☆"
    public function getStarsAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}