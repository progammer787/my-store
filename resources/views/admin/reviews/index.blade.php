{{-- resources/views/admin/reviews/index.blade.php --}}
@extends('admin.layouts.app')
@section('title','Reviews') @section('page-title','Customer Reviews')
@section('content')
<div class="card">
    <div class="card-header">
        <form method="GET" style="display:flex;gap:10px">
            <select name="status" style="padding:9px 14px;background:rgba(255,255,255,.05);border:1px solid var(--card-border);border-radius:8px;color:var(--text)" onchange="this.form.submit()">
                <option value="">All Reviews</option>
                <option value="pending"  {{ request('status')==='pending'  ?'selected':'' }}>Pending Approval</option>
                <option value="approved" {{ request('status')==='approved' ?'selected':'' }}>Approved</option>
            </select>
        </form>
        <span style="color:var(--text-muted);font-size:13px">{{ $reviews->total() }} reviews</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Product</th><th>Customer</th><th>Rating</th><th>Review</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td style="font-weight:600;color:var(--white);max-width:140px">{{ $review->product?->name ?? '—' }}</td>
                    <td>
                        <div style="font-weight:600;color:var(--white)">{{ $review->name }}</div>
                        @if($review->email)<div style="font-size:11px;color:var(--text-muted)">{{ $review->email }}</div>@endif
                    </td>
                    <td style="color:#f59e0b;font-size:16px;letter-spacing:1px">
                        {{ str_repeat('★', $review->rating) }}<span style="color:rgba(255,255,255,.15)">{{ str_repeat('★', 5-$review->rating) }}</span>
                    </td>
                    <td style="max-width:240px">
                        @if($review->title)<div style="font-weight:600;color:var(--white);font-size:13px;margin-bottom:4px">{{ $review->title }}</div>@endif
                        <div style="font-size:12px;color:var(--text-muted);line-height:1.5">{{ Str::limit($review->comment, 100) }}</div>
                    </td>
                    <td>
                        @if($review->is_approved)
                            <span class="badge badge-active">Approved</span>
                        @else
                            <span class="badge badge-pending">Pending</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);font-size:12px">{{ $review->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            @if(!$review->is_approved)
                            <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                @csrf
                                <button class="btn btn-sm" style="background:rgba(34,197,94,.15);color:#4ade80;border:1px solid rgba(34,197,94,.2)">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete review?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:40px">No reviews yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:16px">{{ $reviews->links() }}</div>
</div>
@endsection
