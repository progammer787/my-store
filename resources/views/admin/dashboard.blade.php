@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="stats-grid">
    <div class="stat-card" style="--accent-color:#f59e0b;--icon-bg:rgba(245,158,11,.1)">
        <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
        <div class="stat-value">{{ $stats['total_orders'] }}</div>
        <div class="stat-label">Total Orders</div>
    </div>
    <div class="stat-card" style="--accent-color:#ef4444;--icon-bg:rgba(239,68,68,.1)">
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
        <div class="stat-value">{{ $stats['pending_orders'] }}</div>
        <div class="stat-label">Pending Orders</div>
    </div>
    <div class="stat-card" style="--accent-color:#22c55e;--icon-bg:rgba(34,197,94,.1)">
        <div class="stat-icon"><i class="fas fa-box"></i></div>
        <div class="stat-value">{{ $stats['total_products'] }}</div>
        <div class="stat-label">Total Products</div>
    </div>
    <div class="stat-card" style="--accent-color:#c9a84c;--icon-bg:rgba(201,168,76,.1)">
        <div class="stat-icon"><i class="fas fa-rupee-sign"></i></div>
        <div class="stat-value">{{ number_format($stats['total_revenue'], 0) }}</div>
        <div class="stat-label">Total Revenue (Rs.)</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Recent Orders</span>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary">View All</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td><strong style="color:var(--accent)">{{ $order->order_number }}</strong></td>
                        <td>
                            <div style="font-weight:600;color:var(--white)">{{ $order->customer_name }}</div>
                            <div style="font-size:11px;color:var(--text-muted)">{{ $order->customer_phone }}</div>
                        </td>
                        <td><strong>Rs. {{ number_format($order->total, 0) }}</strong></td>
                        <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                        <td style="color:var(--text-muted)">{{ $order->created_at->format('d M, h:i A') }}</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:40px">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <div class="card">
            <div class="card-header"><span class="card-title">Quick Stats</span></div>
            <div style="display:flex;flex-direction:column;gap:14px">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px;background:rgba(255,255,255,.03);border-radius:8px">
                    <span style="font-size:13px;color:var(--text-muted)">Categories</span>
                    <span style="font-weight:700;color:var(--white)">{{ $stats['total_categories'] }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px;background:rgba(245,158,11,.08);border-radius:8px">
                    <span style="font-size:13px;color:#fbbf24"><i class="fas fa-exclamation-triangle"></i> Low Stock</span>
                    <span style="font-weight:700;color:#fbbf24">{{ $stats['low_stock'] }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:12px;background:rgba(239,68,68,.08);border-radius:8px">
                    <span style="font-size:13px;color:#f87171"><i class="fas fa-times-circle"></i> Out of Stock</span>
                    <span style="font-weight:700;color:#f87171">{{ $stats['out_of_stock'] }}</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title" style="margin-bottom:16px">Quick Actions</div>
            <div style="display:flex;flex-direction:column;gap:10px">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary" style="justify-content:center"><i class="fas fa-plus"></i> Add Product</a>
                <a href="{{ route('admin.banners.create') }}" class="btn btn-secondary" style="justify-content:center"><i class="fas fa-image"></i> Add Banner</a>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-secondary" style="justify-content:center"><i class="fas fa-tag"></i> Add Category</a>
                <a href="{{ route('admin.orders.index') }}?status=pending" class="btn btn-secondary" style="justify-content:center;color:#fbbf24"><i class="fas fa-clock"></i> Pending Orders</a>
            </div>
        </div>
    </div>
</div>
@endsection