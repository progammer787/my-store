<div class="product-card">
    <a href="{{ route('product.show', $product->slug) }}">
        <div class="product-card-img">
            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" loading="lazy">
            @if($product->is_on_sale)
                <span class="badge badge-sale">-{{ $product->discount_percent }}%</span>
            @elseif(isset($badge))
                <span class="badge badge-new">{{ $badge }}</span>
            @elseif($product->is_new_arrival)
                <span class="badge badge-new">NEW</span>
            @endif
        </div>
        <div class="product-card-body">
            @if($product->category)
                <div class="product-card-cat">{{ $product->category->name }}</div>
            @endif
            <div class="product-card-name">{{ $product->name }}</div>
            <div class="product-card-price">
                <span class="price-current">{{ \App\Models\Setting::get('currency_symbol', 'Rs.') }} {{ number_format($product->current_price, 0) }}</span>
                @if($product->is_on_sale)
                    <span class="price-old">{{ \App\Models\Setting::get('currency_symbol', 'Rs.') }} {{ number_format($product->price, 0) }}</span>
                @endif
            </div>
        </div>
    </a>
    <div style="padding:0 16px 16px">
    @if($product->stock > 0)
        <form method="POST" action="{{ route('cart.add') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="btn-add-cart">Add to Cart</button>
        </form>
    @else
        <button class="btn-add-cart" disabled style="background:#ccc;cursor:not-allowed">Out of Stock</button>
    @endif
</div>
</div>