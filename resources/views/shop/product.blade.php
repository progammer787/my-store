@extends('shop.layouts.app')
@section('title', $product->name)

@push('styles')
<style>
.product-layout{display:grid;grid-template-columns:1fr 1fr;gap:48px;padding:40px 0}
.product-gallery .main-img{border-radius:12px;overflow:hidden;aspect-ratio:1;background:#f5f5f5;margin-bottom:12px}
.product-gallery .main-img img{width:100%;height:100%;object-fit:cover}
.thumb-row{display:flex;gap:10px;flex-wrap:wrap}
.thumb{width:72px;height:72px;border-radius:8px;overflow:hidden;cursor:pointer;border:2px solid transparent;transition:.2s}
.thumb:hover,.thumb.active{border-color:var(--accent)}
.thumb img{width:100%;height:100%;object-fit:cover}
.product-info{padding-top:8px}
.product-breadcrumb{font-size:13px;color:var(--text-light);margin-bottom:16px}
.product-breadcrumb a{color:var(--accent)}
.product-name{font-family:'Playfair Display',serif;font-size:32px;font-weight:700;color:var(--primary);margin-bottom:14px;line-height:1.3}
.product-price-row{display:flex;align-items:baseline;gap:14px;margin-bottom:20px}
.product-price-current{font-size:32px;font-weight:700;color:var(--primary)}
.product-price-old{font-size:18px;color:#999;text-decoration:line-through}
.product-discount{background:#e53935;color:#fff;font-size:12px;font-weight:700;padding:4px 10px;border-radius:20px}
.product-short-desc{font-size:15px;color:var(--text-light);line-height:1.8;margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid var(--border)}
.stock-badge{display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;padding:6px 14px;border-radius:20px;margin-bottom:20px}
.in-stock{background:#e8f5e9;color:#2e7d32}
.out-stock{background:#fce4ec;color:#c62828}
.qty-row{display:flex;align-items:center;gap:16px;margin-bottom:24px}
.qty-label{font-size:14px;font-weight:600;color:var(--primary)}
.qty-input{display:flex;align-items:center;border:1.5px solid var(--border);border-radius:8px;overflow:hidden}
.qty-input button{width:36px;height:40px;background:none;border:none;font-size:18px;cursor:pointer;color:var(--text)}
.qty-input button:hover{background:#f5f5f5}
.qty-input input{width:50px;height:40px;border:none;text-align:center;font-size:15px;font-weight:600}
.product-actions{display:flex;gap:14px;margin-bottom:28px}
.btn-wa{background:#25d366;color:#fff;padding:14px 28px;border-radius:var(--radius);font-size:14px;font-weight:600;display:flex;align-items:center;gap:10px;border:none;cursor:pointer;transition:.2s}
.btn-wa:hover{background:#20b858}
.product-tabs{margin-top:40px;border-top:1px solid var(--border);padding-top:30px}
.tab-btns{display:flex;gap:0;margin-bottom:24px;border-bottom:2px solid var(--border)}
.tab-btn{padding:12px 24px;font-size:14px;font-weight:600;color:var(--text-light);cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;transition:.2s}
.tab-btn.active{color:var(--primary);border-bottom-color:var(--accent)}
.tab-content{display:none;font-size:15px;line-height:1.9;color:var(--text-light)}
.tab-content.active{display:block}
.related-section{padding:60px 0 0}
@media(max-width:768px){.product-layout{grid-template-columns:1fr}.product-actions{flex-direction:column}}
</style>
@endpush

@section('content')
<div class="container">
    <div class="product-breadcrumb" style="padding-top:20px">
        <a href="{{ route('home') }}">Home</a> &rsaquo;
        <a href="{{ route('shop') }}">Shop</a> &rsaquo;
        @if($product->category)
            <a href="{{ route('shop.category', $product->category->slug) }}">{{ $product->category->name }}</a> &rsaquo;
        @endif
        {{ $product->name }}
    </div>

    <div class="product-layout">
        {{-- GALLERY --}}
        <div class="product-gallery">
            <div class="main-img">
                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" id="mainImg">
            </div>
            @if($product->images->count())
            <div class="thumb-row">
                <div class="thumb active" onclick="setImg('{{ $product->thumbnail_url }}', this)">
                    <img src="{{ $product->thumbnail_url }}" alt="">
                </div>
                @foreach($product->images as $img)
                <div class="thumb" onclick="setImg('{{ $img->url }}', this)">
                    <img src="{{ $img->url }}" alt="">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- INFO --}}
        <div class="product-info">
            @if($product->category)
                <div style="font-size:12px;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px">{{ $product->category->name }}</div>
            @endif
            <h1 class="product-name">{{ $product->name }}</h1>

            <div class="product-price-row">
                <span class="product-price-current">{{ \App\Models\Setting::get('currency_symbol','Rs.') }} {{ number_format($product->current_price, 0) }}</span>
                @if($product->is_on_sale)
                    <span class="product-price-old">{{ \App\Models\Setting::get('currency_symbol','Rs.') }} {{ number_format($product->price, 0) }}</span>
                    <span class="product-discount">{{ $product->discount_percent }}% OFF</span>
                @endif
            </div>

            @if($product->stock > 0)
                <div class="stock-badge in-stock"><i class="fas fa-check-circle"></i> In Stock ({{ $product->stock }} available)</div>
            @else
                <div class="stock-badge out-stock"><i class="fas fa-times-circle"></i> Out of Stock</div>
            @endif

            @if($product->short_description)
                <div class="product-short-desc">{{ $product->short_description }}</div>
            @endif

            @if($product->stock > 0)
            <form id="cartForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="qty-row">
                    <span class="qty-label">Quantity:</span>
                    <div class="qty-input">
                        <button type="button" onclick="changeQty(-1)">−</button>
                        <input type="number" id="qty" name="quantity" value="1" min="1" max="{{ $product->stock }}">
                        <button type="button" onclick="changeQty(1)">+</button>
                    </div>
                </div>
                <div class="product-actions">
                    <button type="button" class="btn btn-primary" style="flex:1;justify-content:center" onclick="addToCart()">
                        <i class="fas fa-shopping-bag"></i> Add to Cart
                    </button>
                    <a href="{{ $whatsappUrl ?? '#' }}" target="_blank" class="btn-wa">
                        <i class="fab fa-whatsapp"></i> Order via WhatsApp
                    </a>
                </div>
            </form>
            @endif

            <div style="display:flex;gap:20px;font-size:13px;color:var(--text-light)">
                <span><i class="fas fa-truck" style="color:var(--accent)"></i> Fast Delivery</span>
                <span><i class="fas fa-undo" style="color:var(--accent)"></i> Easy Returns</span>
                <span><i class="fas fa-shield-alt" style="color:var(--accent)"></i> Secure Order</span>
            </div>
        </div>
    </div>

    {{-- TABS --}}
    @if($product->description)
    <div class="product-tabs">
        <div class="tab-btns">
            <div class="tab-btn active" onclick="openTab('desc', this)">Description</div>
        </div>
        <div class="tab-content active" id="tab-desc">{!! nl2br(e($product->description)) !!}</div>
    </div>
    @endif

    {{-- RELATED --}}
    @if($related->count())
    <section class="related-section">
        <div class="section-header">
            <div>
                <div class="section-title">Related Products</div>
                <div class="section-sub">You might also like</div>
            </div>
        </div>
        <div class="products-grid">
            @foreach($related as $p)
                @include('shop.partials.product-card', ['product' => $p])
            @endforeach
        </div>
    </section>
    @endif
</div>

@push('scripts')
<script>
function setImg(url, el) {
    document.getElementById('mainImg').src = url;
    document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}
function changeQty(d) {
    const i = document.getElementById('qty');
    i.value = Math.max(1, Math.min(parseInt(i.value) + d, {{ $product->stock }}));
}
function addToCart() {
    const qty = document.getElementById('qty').value;
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},
        body: JSON.stringify({product_id: {{ $product->id }}, quantity: parseInt(qty)})
    }).then(r => r.json()).then(d => {
        document.getElementById('cart-count').textContent = d.count;
        alert('Added to cart!');
    });
}
function openTab(id, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('active');
    btn.classList.add('active');
}
</script>
@endpush
<div class="container">
    @include('shop.partials.reviews', ['product' => $product, 'reviews' => $reviews])
</div>
@endsection