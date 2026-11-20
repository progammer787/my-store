{{-- resources/views/shop/checkout-success.blade.php --}}
@extends('shop.layouts.app')
@section('title', 'Demo Order Complete')
@section('content')
<div class="container" style="text-align:center;padding:80px 0">
    <div style="width:80px;height:80px;background:#e8f5e9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;font-size:36px;color:#2e7d32">
        <i class="fas fa-check"></i>
    </div>
    <h1 class="page-title">Demo order complete</h1>
    <p style="color:var(--text-light);font-size:16px;margin-bottom:6px">Reference: <strong>{{ $order->order_number }}</strong></p>
    <p style="color:var(--text-light);margin-bottom:32px">This was a demo order. It is not a purchase and will not be fulfilled.</p>
    <br><br>
    <a href="{{ route('home') }}" class="btn btn-outline" style="margin-top:16px">Back to Home</a>
</div>
@endsection
