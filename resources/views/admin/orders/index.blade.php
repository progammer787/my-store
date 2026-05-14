@extends('admin.layouts.app')
@section('title','Orders') @section('page-title','Orders')
@section('content')
<div class="card">
    <div class="card-header">
        <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap">
            <div class="search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" name="q" placeholder="Order #, name, phone..." value="{{ request('q') }}" style="width:220px">
            </div>
            <select name="status" style="padding:9px 14px;background:rgba(255,255,255,.05);border:1px solid var(--card-border);border-radius:8px;color:var(--text)">
                <option value="">All Status</option>
                @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i> Filter</button>
        </form>
        <span style="color:var(--text-muted);font-size:13px">{{ $orders->total() }} orders</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Order</th><th>Customer</th><th>Items</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr></thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong style="color:var(--accent)">{{ $order->order_number }}</strong></td>
                    <td>
                        <div style="font-weight:600;color:var(--white)">{{ $order->customer_name }}</div>
                        <div style="font-size:11px;color:var(--text-muted)">{{ $order->customer_phone }}</div>
                    </td>
                    <td style="color:var(--text-muted)">{{ $order->items->count() }} item(s)</td>
                    <td><strong>Rs. {{ number_format($order->total, 0) }}</strong></td>
                    <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    <td style="color:var(--text-muted);font-size:12px">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                    <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i> View</a></td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:40px">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:16px">{{ $orders->links() }}</div>
</div>
@endsection