{{-- resources/views/shop/checkout-success.blade.php --}}
@extends('shop.layouts.app')
@section('title', 'Order Placed!')
@section('content')
<div class="container" style="text-align:center;padding:80px 0">
    <div style="width:80px;height:80px;background:#e8f5e9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:36px;color:#2e7d32">
        <i class="fas fa-check"></i>
    </div>
    <h1 style="font-family:'Playfair Display',serif;font-size:36px;color:var(--primary);margin-bottom:10px">Order Placed!</h1>
    <p style="color:var(--text-light);font-size:16px;margin-bottom:6px">Order Number: <strong>{{ $order->order_number }}</strong></p>
    <p style="color:var(--text-light);margin-bottom:32px">Click below to confirm your order on WhatsApp. We'll process it right away!</p>
    @if(session('wa_url'))
        <a href="{{ session('wa_url') }}" target="_blank" class="btn btn-accent" style="font-size:16px;padding:16px 36px">
            <i class="fab fa-whatsapp"></i> Confirm on WhatsApp
        </a>
    @endif
    <br><br>
    <a href="{{ route('home') }}" class="btn btn-outline" style="margin-top:16px">Back to Home</a>
</div>
@endsection