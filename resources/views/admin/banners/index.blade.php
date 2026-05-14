{{-- resources/views/admin/banners/index.blade.php --}}
@extends('admin.layouts.app')
@section('title','Banners') @section('page-title','Banner Manager')
@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-title">All Banners</span>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Banner</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Banner</th><th>Title</th><th>Position</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($banners as $banner)
                <tr>
                    <td><img src="{{ $banner->image_url }}" style="width:80px;height:48px;object-fit:cover;border-radius:6px"></td>
                    <td>
                        <div style="font-weight:600;color:var(--white)">{{ $banner->title ?? '(No title)' }}</div>
                        @if($banner->badge_text)<span style="font-size:11px;color:var(--accent)">{{ $banner->badge_text }}</span>@endif
                    </td>
                    <td><span class="badge badge-confirmed" style="text-transform:uppercase">{{ str_replace('_',' ', $banner->position) }}</span></td>
                    <td><span class="badge {{ $banner->is_active ? 'badge-active':'badge-inactive' }}">{{ $banner->is_active ? 'Active':'Inactive' }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:40px">No banners yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection