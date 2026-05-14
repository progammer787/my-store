{{-- resources/views/admin/categories/form.blade.php --}}
@extends('admin.layouts.app')
@section('title', isset($category) ? 'Edit Category' : 'Add Category')
@section('page-title', isset($category) ? 'Edit Category' : 'Add Category')
@section('content')
<div class="card" style="max-width:640px">
    <form method="POST" action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}" enctype="multipart/form-data">
        @csrf @if(isset($category)) @method('PUT') @endif
        <div class="form-group">
            <label>Category Name *</label>
            <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required>
        </div>
        <div class="form-group">
            <label>Parent Category</label>
            <select name="parent_id">
                <option value="">None (Top Level)</option>
                @foreach($parents as $p)
                    <option value="{{ $p->id }}" {{ old('parent_id', $category->parent_id ?? '') == $p->id ? 'selected':'' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3" placeholder="Optional category description...">{{ old('description', $category->description ?? '') }}</textarea>
        </div>
        <div class="form-group">
            <label>Image</label>
            @if(isset($category) && $category->image)
                <div style="margin-bottom:8px"><img src="{{ $category->image_url }}" style="width:80px;height:80px;object-fit:cover;border-radius:8px"></div>
            @endif
            <input type="file" name="image" accept="image/*">
        </div>
        <div class="form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0">
        </div>
        <div style="display:flex;gap:20px;margin-bottom:24px">
            <label class="form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked':'' }}>
                <label>Active</label>
            </label>
            <label class="form-check">
                <input type="hidden" name="show_on_homepage" value="0">
                <input type="checkbox" name="show_on_homepage" value="1" {{ old('show_on_homepage', $category->show_on_homepage ?? false) ? 'checked':'' }}>
                <label>Show on Homepage</label>
            </label>
        </div>
        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection