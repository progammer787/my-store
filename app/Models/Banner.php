<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'button_text', 'button_link',
        'image', 'badge_text', 'position', 'text_color',
        'bg_color', 'sort_order', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q) { return $q->where('is_active', true); }

    public function scopeHero($q)        { return $q->where('position', 'hero'); }
    public function scopePromoLeft($q)   { return $q->where('position', 'promo_left'); }
    public function scopePromoRight($q)  { return $q->where('position', 'promo_right'); }
    public function scopeMidBanner($q)   { return $q->where('position', 'mid_banner'); }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }
}
