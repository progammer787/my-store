{{-- resources/views/admin/banners/form.blade.php --}}
@extends('admin.layouts.app')
@section('title', isset($banner) ? 'Edit Banner' : 'Add Banner')
@section('page-title', isset($banner) ? 'Edit Banner' : 'Add Banner')
@section('content')
<div class="card" style="max-width:700px">
    <form method="POST" action="{{ isset($banner) ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" enctype="multipart/form-data">
        @csrf @if(isset($banner)) @method('PUT') @endif
        <div class="form-group">
            <label>Position *</label>
            <select name="position" required>
                <option value="hero"        {{ old('position', $banner->position ?? '') === 'hero'        ? 'selected':'' }}>Hero Slider (main banner)</option>
                <option value="promo_left"  {{ old('position', $banner->position ?? '') === 'promo_left'  ? 'selected':'' }}>Promo Left (under hero)</option>
                <option value="promo_right" {{ old('position', $banner->position ?? '') === 'promo_right' ? 'selected':'' }}>Promo Right (under hero)</option>
                <option value="mid_banner"  {{ old('position', $banner->position ?? '') === 'mid_banner'  ? 'selected':'' }}>Mid Page Banner</option>
            </select>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}" placeholder="Banner headline">
            </div>
            <div class="form-group">
                <label>Badge Text (e.g. "50% OFF")</label>
                <input type="text" name="badge_text" value="{{ old('badge_text', $banner->badge_text ?? '') }}" placeholder="Optional badge">
            </div>
        </div>
        <div class="form-group">
            <label>Subtitle</label>
            <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle ?? '') }}" placeholder="Sub-headline text">
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label>Button Text</label>
                <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text ?? '') }}" placeholder="e.g. Shop Now">
            </div>
            <div class="form-group">
                <label>Button Link</label>
                <input type="text" name="button_link" value="{{ old('button_link', $banner->button_link ?? '') }}" placeholder="/shop or https://...">
            </div>
        </div>
        <div class="form-group">
            <label>Banner Image *</label>
            @if(isset($banner) && $banner->image)
                <div style="margin-bottom:8px"><img src="{{ $banner->image_url }}" style="max-width:300px;height:120px;object-fit:cover;border-radius:8px"></div>
            @endif
            <input type="file" name="image" accept="image/*" {{ !isset($banner) ? 'required' : '' }}>
            <small style="color:var(--text-muted)">Recommended: Hero = 1920×700px | Promo = 800×300px</small>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label>Text Color</label>
                <input type="color" name="text_color" value="{{ old('text_color', $banner->text_color ?? '#ffffff') }}" style="height:42px;padding:4px">
            </div>
            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" min="0">
            </div>
        </div>
        <label class="form-check" style="margin-bottom:24px">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active ?? true) ? 'checked':'' }}>
            <label>Active</label>
        </label>
        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Banner</button>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection