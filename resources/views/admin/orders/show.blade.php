{{-- resources/views/admin/orders/show.blade.php --}}
@extends('admin.layouts.app')
@section('title','Order '.$order->order_number)
@section('page-title','Order Details')
@section('content')
<div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start">
    <div>
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">{{ $order->order_number }}</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:4px">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                </div>
                <span class="badge badge-{{ $order->status }}" style="font-size:13px;padding:6px 14px">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Total</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px">
                                    @if($item->product_image)
                                        <img src="{{ $item->product_image }}" style="width:48px;height:48px;object-fit:cover;border-radius:6px">
                                    @endif
                                    <div style="font-weight:600;color:var(--white)">{{ $item->product_name }}</div>
                                </div>
                            </td>
                            <td>Rs. {{ number_format($item->price, 0) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td><strong>Rs. {{ number_format($item->total, 0) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:16px 16px 0;border-top:1px solid var(--card-border);display:flex;justify-content:flex-end">
                <div style="text-align:right">
                    <div style="font-size:13px;color:var(--text-muted);margin-bottom:6px">Subtotal: Rs. {{ number_format($order->subtotal, 0) }}</div>
                    <div style="font-size:18px;font-weight:800;color:var(--white)">Total: Rs. {{ number_format($order->total, 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="card">
            <div class="card-title" style="margin-bottom:16px">Customer Info</div>
            <div style="display:flex;flex-direction:column;gap:12px;font-size:13px">
                <div><span style="color:var(--text-muted)">Name:</span> <strong style="color:var(--white)">{{ $order->customer_name }}</strong></div>
                <div><span style="color:var(--text-muted)">Phone:</span>
                    <a href="tel:{{ $order->customer_phone }}" style="color:var(--accent)">{{ $order->customer_phone }}</a>
                </div>
                @if($order->customer_email)
                <div><span style="color:var(--text-muted)">Email:</span> {{ $order->customer_email }}</div>
                @endif
                @if($order->customer_address)
                <div><span style="color:var(--text-muted)">Address:</span> {{ $order->customer_address }}{{ $order->city ? ', '.$order->city : '' }}</div>
                @endif
                @if($order->notes)
                <div><span style="color:var(--text-muted)">Notes:</span> {{ $order->notes }}</div>
                @endif
            </div>
            @if(\App\Models\Setting::get('whatsapp_number'))
            <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number') }}?text=Hello+{{ urlencode($order->customer_name) }}%2C+regarding+your+order+{{ $order->order_number }}"
               target="_blank" class="btn btn-sm" style="margin-top:16px;background:#25d366;color:#fff;width:100%;justify-content:center">
                <i class="fab fa-whatsapp"></i> Message Customer
            </a>
            @endif
        </div>

        <div class="card">
            <div class="card-title" style="margin-bottom:16px">Update Status</div>
            <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                @csrf @method('PATCH')
                <div class="form-group">
                    <select name="status">
                        @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center"><i class="fas fa-save"></i> Update Status</button>
            </form>
        </div>

        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary" style="width:100%;justify-content:center"><i class="fas fa-arrow-left"></i> Back to Orders</a>
    </div>
</div>
@endsection
