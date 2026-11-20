@extends('shop.layouts.app')
@section('title', isset($category) ? $category->name : 'Shop All Products')

@push('styles')
<style>
.shop-layout{display:grid;grid-template-columns:240px 1fr;gap:32px;padding:40px 0}
.sidebar{position:sticky;top:90px;align-self:start}
.sidebar-card{background:var(--white);border-radius:12px;padding:24px;box-shadow:var(--shadow);margin-bottom:20px}
.sidebar-card h3{font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--primary);margin-bottom:16px;padding-bottom:10px;border-bottom:2px solid var(--border)}
.cat-filter-item{display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f0f0f0;font-size:14px;color:var(--text);transition:.2s}
.cat-filter-item:hover,.cat-filter-item.active{color:var(--accent)}
.cat-filter-item .count{font-size:12px;color:#999;background:#f5f5f5;padding:2px 8px;border-radius:10px}
.price-inputs{display:flex;gap:8px;margin-top:10px;flex-direction: column;}
.price-inputs input{flex:1;padding:8px;border:1px solid var(--border);border-radius:6px;font-size:13px}
.shop-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px}
.shop-header h1{font-family:'Playfair Display',serif;font-size:26px;color:var(--primary)}
.shop-header p{font-size:13px;color:var(--text-light);margin-top:4px}
.sort-select{padding:8px 16px;border:1px solid var(--border);border-radius:6px;font-size:13px;color:var(--text);cursor:pointer}
.no-products{text-align:center;padding:80px 0;color:var(--text-light)}
.no-products i{font-size:48px;display:block;margin-bottom:16px;opacity:.3}
.pagination{display:flex;flex-wrap:wrap;align-items:center;gap:8px;justify-content:center;padding:32px 0 0}
.pagination a,.pagination span{display:inline-flex;align-items:center;justify-content:center;min-width:38px;min-height:38px;padding:8px 12px;border:1px solid #e3e3e1;border-radius:2px;font-size:13px;color:#333;line-height:1.2;transition:.2s}
.pagination a:hover{border-color:#171717;color:#171717}
.pagination .page-current{background:#171717;color:#fff;border-color:#171717}
.pagination .page-disabled{color:#aaa;background:#fafafa}
.pagination-summary{text-align:center;color:#777;font-size:12px;padding:10px 0 0}
@media(max-width:768px){.shop-layout{grid-template-columns:1fr}.sidebar{position:static}}
</style>
@endpush

@section('content')
<div class="container">
    <div style="padding:16px 0;font-size:13px;color:var(--text-light)">
        <a href="{{ route('home') }}">Home</a> &rsaquo;
        @if(isset($category))
            <a href="{{ route('shop') }}">Shop</a> &rsaquo; <span>{{ $category->name }}</span>
        @else
            <span>Shop</span>
        @endif
    </div>

    <div class="shop-layout">
        {{-- SIDEBAR --}}
        <aside class="sidebar">
            <div class="sidebar-card">
                <h3>Categories</h3>
                <a href="{{ route('shop') }}" class="cat-filter-item {{ !isset($category) ? 'active' : '' }}" style="display:flex">
                    <span class="cat-filter-label"><i class="fas fa-grip" aria-hidden="true"></i> All Products</span>
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('shop.category', $cat->slug) }}" class="cat-filter-item {{ isset($category) && $category->id === $cat->id ? 'active' : '' }}" style="display:flex">
                    <span class="cat-filter-label">@include('shop.partials.category-icon', ['category' => $cat]) {{ $cat->name }}</span>
                    <span class="count">{{ $cat->products_count }}</span>
                </a>
                @endforeach
            </div>

            <div class="sidebar-card">
                <h3>Price Range</h3>
                <form method="GET" action="{{ request()->url() }}">
                    <div class="price-inputs">
                        <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
                        <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
                    </div>
                    @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                    <button type="submit" class="btn btn-primary" style="width:100%;margin-top:12px;justify-content:center">Apply</button>
                </form>
            </div>
        </aside>

        {{-- PRODUCTS --}}
        <div>
            <div class="shop-header">
                <div>
                    <h1>{{ isset($category) ? $category->name : 'All Products' }}</h1>
                    <p>{{ $products->total() }} products found</p>
                </div>
                <form method="GET">
                    @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
                    <select class="sort-select" name="sort" onchange="this.form.submit()">
                        <option value="">Default</option>
                        <option value="newest"     {{ request('sort') === 'newest'     ? 'selected':'' }}>Newest</option>
                        <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected':'' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected':'' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>

            @if($products->count())
                <div class="products-grid">
                    @foreach($products as $product)
                        @include('shop.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
                @if($products->hasPages())
                    <nav class="pagination" aria-label="Product pages">
                        @if($products->onFirstPage())
                            <span class="page-disabled" aria-disabled="true">Previous</span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" rel="prev">Previous</a>
                        @endif
                        @for($page = max(1, $products->currentPage() - 1); $page <= min($products->lastPage(), $products->currentPage() + 1); $page++)
                            @if($page === $products->currentPage())
                                <span class="page-current" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $products->url($page) }}">{{ $page }}</a>
                            @endif
                        @endfor
                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" rel="next">Next</a>
                        @else
                            <span class="page-disabled" aria-disabled="true">Next</span>
                        @endif
                    </nav>
                    <p class="pagination-summary">Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products</p>
                @endif
            @else
                <div class="no-products">
                    <i class="fas fa-box-open"></i>
                    <p>No products found.</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary" style="margin-top:16px;display:inline-flex">Browse All Products</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
