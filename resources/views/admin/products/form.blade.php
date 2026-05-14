@extends('admin.layouts.app')
@section('title', isset($product) ? 'Edit Product' : 'Add Product')
@section('page-title', isset($product) ? 'Edit Product' : 'Add Product')

@section('content')
<form method="POST" action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    @if(isset($product)) @method('PUT') @endif

    <div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start">

        {{-- LEFT --}}
        <div>
            <div class="card">
                <div class="card-title" style="margin-bottom:20px">Product Information</div>
                <div class="form-group">
                    <label>Product Name *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required placeholder="Enter product name">
                    @error('name')<span style="color:#f87171;font-size:12px">{{ $message }}</span>@enderror
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Price (Rs.) *</label>
                        <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" step="0.01" min="0" required placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label>Sale Price (Rs.)</label>
                        <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price ?? '') }}" step="0.01" min="0" placeholder="Leave empty for no sale">
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Stock Quantity *</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" placeholder="Auto-generated if empty">
                    </div>
                </div>
                <div class="form-group">
                    <label>Short Description</label>
                    <textarea name="short_description" rows="2" placeholder="Brief summary shown in listings...">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Full Description</label>
                    <textarea name="description" rows="6" placeholder="Detailed product description...">{{ old('description', $product->description ?? '') }}</textarea>
                </div>
            </div>

            <div class="card">
                <div class="card-title" style="margin-bottom:20px">Product Images</div>
                <div class="form-group">
                    <label>Thumbnail Image</label>
                    @if(isset($product) && $product->thumbnail)
                        <div style="margin-bottom:10px"><img src="{{ $product->thumbnail_url }}" class="img-preview" style="width:100px;height:100px"></div>
                    @endif
                    <input type="file" name="thumbnail" accept="image/*" onchange="previewImg(this,'thumb-preview')">
                    <img id="thumb-preview" style="display:none;width:100px;height:100px;object-fit:cover;border-radius:8px;margin-top:8px">
                </div>
                <div class="form-group">
                    <label>Gallery Images (multiple)</label>
                    @if(isset($product) && $product->images->count())
                        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:10px">
                            @foreach($product->images as $img)
                            <div style="position:relative">
                                <img src="{{ $img->url }}" style="width:72px;height:72px;object-fit:cover;border-radius:6px">
                                <input type="checkbox" name="remove_images[]" value="{{ $img->id }}" style="position:absolute;top:2px;right:2px;accent-color:#ef4444" title="Check to remove">
                            </div>
                            @endforeach
                        </div>
                        <small style="color:var(--text-muted)">Check images to remove them</small>
                    @endif
                    <input type="file" name="gallery[]" accept="image/*" multiple style="margin-top:8px">
                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <div>
            <div class="card">
                <div class="card-title" style="margin-bottom:20px">Organization</div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id">
                        <option value="">No Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected':'' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $product->sort_order ?? 0) }}" min="0">
                </div>
            </div>

            <div class="card">
                <div class="card-title" style="margin-bottom:16px">Visibility & Flags</div>
                <div style="display:flex;flex-direction:column;gap:14px">
                    <label class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked':'' }}>
                        <label>Active (visible in store)</label>
                    </label>
                    <label class="form-check">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked':'' }}>
                        <label>Featured Product</label>
                    </label>
                    <label class="form-check">
                        <input type="hidden" name="is_new_arrival" value="0">
                        <input type="checkbox" name="is_new_arrival" value="1" {{ old('is_new_arrival', $product->is_new_arrival ?? false) ? 'checked':'' }}>
                        <label>New Arrival</label>
                    </label>
                    <label class="form-check">
                        <input type="hidden" name="is_best_seller" value="0">
                        <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller', $product->is_best_seller ?? false) ? 'checked':'' }}>
                        <label>Best Seller</label>
                    </label>
                </div>
            </div>

            <div style="display:flex;gap:10px">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                    <i class="fas fa-save"></i> {{ isset($product) ? 'Update' : 'Create' }} Product
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function previewImg(input, id) {
    const preview = document.getElementById(id);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection