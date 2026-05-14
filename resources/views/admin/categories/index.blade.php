{{-- resources/views/admin/categories/index.blade.php --}}
@extends('admin.layouts.app')
@section('title','Categories') @section('page-title','Categories')
@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-title">All Categories</span>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Category</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Category</th><th>Parent</th><th>Products</th><th>Homepage</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            @if($cat->image)<img src="{{ $cat->image_url }}" style="width:40px;height:40px;object-fit:cover;border-radius:6px">@endif
                            <span style="font-weight:600;color:var(--white)">{{ $cat->name }}</span>
                        </div>
                    </td>
                    <td style="color:var(--text-muted)">{{ $cat->parent?->name ?? '—' }}</td>
                    <td>{{ $cat->products_count }}</td>
                    <td>@if($cat->show_on_homepage)<span style="color:#4ade80"><i class="fas fa-check"></i></span>@else<span style="color:var(--text-muted)">—</span>@endif</td>
                    <td><span class="badge {{ $cat->is_active ? 'badge-active':'badge-inactive' }}">{{ $cat->is_active ? 'Active':'Inactive' }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:40px">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection