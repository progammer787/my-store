{{-- resources/views/admin/products/index.blade.php --}}
@extends('admin.layouts.app')
@section('title','Products')
@section('page-title','Products')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
            <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap">
                <div class="search-wrap">
                    <i class="fas fa-search"></i>
                    <input type="text" name="q" placeholder="Search products..." value="{{ request('q') }}" style="width:220px">
                </div>
                <select name="category" style="padding:9px 14px;background:rgba(255,255,255,.05);border:1px solid var(--card-border);border-radius:8px;color:var(--text)">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="status" style="padding:9px 14px;background:rgba(255,255,255,.05);border:1px solid var(--card-border);border-radius:8px;color:var(--text)">
                    <option value="">All Status</option>
                    <option value="active"   {{ request('status')==='active'   ?'selected':'' }}>Active</option>
                    <option value="inactive" {{ request('status')==='inactive' ?'selected':'' }}>Inactive</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i> Filter</button>
            </form>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Product</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Flags</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px">
                            <img src="{{ $product->thumbnail_url }}" class="img-preview" style="width:48px;height:48px">
                            <div>
                                <div style="font-weight:600;color:var(--white)">{{ $product->name }}</div>
                                <div style="font-size:11px;color:var(--text-muted)">SKU: {{ $product->sku }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--text-muted)">{{ $product->category?->name ?? '—' }}</td>
                    <td>
                        <div style="font-weight:700;color:var(--white)">Rs. {{ number_format($product->current_price, 0) }}</div>
                        @if($product->is_on_sale)
                            <div style="font-size:11px;text-decoration:line-through;color:var(--text-muted)">Rs. {{ number_format($product->price, 0) }}</div>
                        @endif
                    </td>
                    <td>
                        @if($product->stock == 0)
                            <span style="color:#f87171;font-weight:600">Out of Stock</span>
                        @elseif($product->stock <= 5)
                            <span style="color:#fbbf24;font-weight:600">{{ $product->stock }} (Low)</span>
                        @else
                            <span style="color:#4ade80">{{ $product->stock }}</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.products.toggle', $product) }}">
                            @csrf
                            <button type="submit" class="badge {{ $product->is_active ? 'badge-active' : 'badge-inactive' }}" style="cursor:pointer;border:none">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td style="font-size:12px">
                        @if($product->is_featured)<span style="color:#c9a84c" title="Featured"><i class="fas fa-star"></i></span>@endif
                        @if($product->is_new_arrival)<span style="color:#3b82f6;margin-left:4px" title="New"><i class="fas fa-tag"></i></span>@endif
                        @if($product->is_best_seller)<span style="color:#22c55e;margin-left:4px" title="Best Seller"><i class="fas fa-fire"></i></span>@endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:40px">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:16px">{{ $products->links() }}</div>
</div>
@endsection