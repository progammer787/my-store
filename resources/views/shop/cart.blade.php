{{-- resources/views/shop/cart.blade.php --}}
@extends('shop.layouts.app')
@section('title', 'Your Cart')

@push('styles')
<style>
.cart-layout{display:grid;grid-template-columns:1fr 360px;gap:32px;padding:40px 0}
.cart-table{background:var(--white);border-radius:12px;box-shadow:var(--shadow);overflow:hidden}
.cart-table table{width:100%;border-collapse:collapse}
.cart-table th{background:#f8f8f8;padding:14px 20px;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--text-light);text-align:left;font-weight:600}
.cart-table td{padding:16px 20px;border-top:1px solid var(--border);vertical-align:middle}
.cart-product{display:flex;align-items:center;gap:14px}
.cart-product img{width:64px;height:64px;object-fit:cover;border-radius:8px;flex-shrink:0}
.cart-product-name{font-size:14px;font-weight:600;color:var(--primary)}
.cart-qty{display:flex;align-items:center;border:1px solid var(--border);border-radius:6px;overflow:hidden;width:fit-content}
.cart-qty button{width:32px;height:32px;background:none;border:none;cursor:pointer;font-size:16px}
.cart-qty input{width:44px;height:32px;border:none;text-align:center;font-size:14px;font-weight:600}
.cart-summary{background:var(--white);border-radius:12px;box-shadow:var(--shadow);padding:28px;align-self:start;position:sticky;top:90px}
.summary-row{display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border);font-size:14px}
.summary-total{display:flex;justify-content:space-between;padding:16px 0;font-size:18px;font-weight:700;color:var(--primary)}
.empty-cart{text-align:center;padding:80px 0}
.empty-cart i{font-size:64px;color:#ddd;display:block;margin-bottom:20px}
@media(max-width:768px){.cart-layout{grid-template-columns:1fr}.cart-summary{position:static}}
</style>
@endpush

@section('content')
<div class="container">
    <h1 style="font-family:'Playfair Display',serif;font-size:30px;padding:30px 0 0;color:var(--primary)">Your Cart</h1>

    @if(count($items))
    <div class="cart-layout">
        <div class="cart-table">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $key => $item)
                    <tr>
                        <td>
                            <div class="cart-product">
                                <img src="{{ $item['thumbnail'] }}" alt="{{ $item['name'] }}">
                                <div class="cart-product-name">{{ $item['name'] }}</div>
                            </div>
                        </td>
                        <td>{{ \App\Models\Setting::get('currency_symbol','Rs.') }} {{ number_format($item['price'], 0) }}</td>
                        <td>
                            <form method="POST" action="{{ route('cart.update') }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="key" value="{{ $key }}">
                                <div class="cart-qty">
                                    <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}">−</button>
                                    <input type="number" value="{{ $item['quantity'] }}" readonly>
                                    <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}">+</button>
                                </div>
                            </form>
                        </td>
                        <td><strong>{{ \App\Models\Setting::get('currency_symbol','Rs.') }} {{ number_format($item['price'] * $item['quantity'], 0) }}</strong></td>
                        <td>
                            <form method="POST" action="{{ route('cart.remove') }}">
                                @csrf @method('DELETE')
                                <input type="hidden" name="key" value="{{ $key }}">
                                <button type="submit" style="background:none;border:none;color:#e53935;cursor:pointer;font-size:16px"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="cart-summary">
            <h3 style="font-size:16px;font-weight:700;margin-bottom:20px;color:var(--primary)">Order Summary</h3>
            <div class="summary-row"><span>Subtotal</span><span>{{ \App\Models\Setting::get('currency_symbol','Rs.') }} {{ number_format($subtotal, 0) }}</span></div>
            <div class="summary-row"><span>Shipping</span><span style="color:#2e7d32">Free</span></div>
            <div class="summary-total"><span>Total</span><span>{{ \App\Models\Setting::get('currency_symbol','Rs.') }} {{ number_format($subtotal, 0) }}</span></div>
            <a href="{{ route('checkout.index') }}" class="btn btn-accent" style="width:100%;justify-content:center;margin-top:20px;display:flex">
                <i class="fab fa-whatsapp"></i> Proceed to Checkout
            </a>
            <a href="{{ route('shop') }}" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:10px;display:flex">Continue Shopping</a>
        </div>
    </div>
    @else
    <div class="empty-cart">
        <i class="fas fa-shopping-bag"></i>
        <h2 style="font-family:'Playfair Display',serif;margin-bottom:10px">Your cart is empty</h2>
        <p style="color:var(--text-light);margin-bottom:24px">Add some products to your cart to get started.</p>
        <a href="{{ route('shop') }}" class="btn btn-primary">Start Shopping</a>
    </div>
    @endif
</div>
@endsection