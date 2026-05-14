{{-- resources/views/shop/checkout.blade.php --}}
@extends('shop.layouts.app')
@section('title', 'Checkout')
@push('styles')
<style>
.checkout-layout{display:grid;grid-template-columns:1fr 380px;gap:32px;padding:40px 0}
.checkout-form{background:var(--white);border-radius:12px;box-shadow:var(--shadow);padding:32px}
.form-group{margin-bottom:20px}
.form-group label{display:block;font-size:13px;font-weight:600;color:var(--primary);margin-bottom:8px}
.form-group input,.form-group textarea{width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:var(--radius);font-size:14px;font-family:inherit;transition:.2s}
.form-group input:focus,.form-group textarea:focus{outline:none;border-color:var(--accent)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.order-summary{background:var(--white);border-radius:12px;box-shadow:var(--shadow);padding:28px;align-self:start;position:sticky;top:90px}
.order-item{display:flex;gap:12px;padding:12px 0;border-bottom:1px solid var(--border)}
.order-item img{width:56px;height:56px;object-fit:cover;border-radius:8px;flex-shrink:0}
.wa-notice{background:#e8f5e9;border:1px solid #a5d6a7;border-radius:var(--radius);padding:16px;margin-bottom:24px;font-size:13px;color:#2e7d32;display:flex;gap:10px;align-items:flex-start}
@media(max-width:768px){.checkout-layout{grid-template-columns:1fr}.form-row{grid-template-columns:1fr}.order-summary{position:static}}
</style>
@endpush
@section('content')
<div class="container">
    <h1 style="font-family:'Playfair Display',serif;font-size:30px;padding:30px 0 20px;color:var(--primary)">Checkout</h1>
    <div class="checkout-layout">
        <div class="checkout-form">
            <div class="wa-notice">
                <i class="fab fa-whatsapp" style="font-size:20px;flex-shrink:0"></i>
                <div>After placing your order, you'll be redirected to <strong>WhatsApp</strong> to confirm with us. Your order will be saved automatically.</div>
            </div>
            <h3 style="font-size:16px;font-weight:700;margin-bottom:20px;color:var(--primary)">Your Information</h3>
            <form method="POST" action="{{ route('checkout.place') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Your name">
                        @error('name')<span style="color:#e53935;font-size:12px">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Phone Number *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="03xx-xxxxxxx">
                        @error('phone')<span style="color:#e53935;font-size:12px">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label>Email (optional)</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com">
                </div>
                <div class="form-group">
                    <label>Delivery Address</label>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Street address">
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="Karachi, Lahore...">
                </div>
                <div class="form-group">
                    <label>Order Notes (optional)</label>
                    <textarea name="notes" rows="3" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                </div>
                <button type="submit" class="btn btn-accent" style="width:100%;justify-content:center;font-size:16px;padding:16px">
                    <i class="fab fa-whatsapp"></i> Place Order via WhatsApp
                </button>
            </form>
        </div>

        <div class="order-summary">
            <h3 style="font-size:16px;font-weight:700;margin-bottom:20px;color:var(--primary)">Your Order ({{ count($items) }} items)</h3>
            @foreach($items as $item)
            <div class="order-item">
                <img src="{{ $item['thumbnail'] }}" alt="{{ $item['name'] }}">
                <div style="flex:1">
                    <div style="font-size:14px;font-weight:600">{{ $item['name'] }}</div>
                    <div style="font-size:13px;color:var(--text-light)">Qty: {{ $item['quantity'] }}</div>
                    <div style="font-size:14px;font-weight:700;color:var(--primary)">{{ \App\Models\Setting::get('currency_symbol','Rs.') }} {{ number_format($item['price'] * $item['quantity'], 0) }}</div>
                </div>
            </div>
            @endforeach
            <div style="display:flex;justify-content:space-between;padding:16px 0;font-size:18px;font-weight:700;color:var(--primary)">
                <span>Total</span>
                <span>{{ \App\Models\Setting::get('currency_symbol','Rs.') }} {{ number_format($subtotal, 0) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection