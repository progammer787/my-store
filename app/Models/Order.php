<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'customer_name', 'customer_phone', 'customer_email',
        'customer_address', 'city', 'subtotal', 'discount', 'total',
        'status', 'payment_method', 'notes', 'confirmed_at'
    ];

    protected $casts = [
        'subtotal'     => 'decimal:2',
        'discount'     => 'decimal:2',
        'total'        => 'decimal:2',
        'confirmed_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Status badge colors for admin UI
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'amber',
            'confirmed'  => 'blue',
            'processing' => 'purple',
            'shipped'    => 'teal',
            'delivered'  => 'green',
            'cancelled'  => 'red',
            default      => 'gray',
        };
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->order_number)) {
                $model->order_number = 'ORD-' . strtoupper(Str::random(8));
            }
        });
    }
}
