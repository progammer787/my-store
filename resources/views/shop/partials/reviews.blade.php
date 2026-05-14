{{--
    resources/views/shop/partials/reviews.blade.php
    Include in product.blade.php AFTER the product-tabs section:
    @include('shop.partials.reviews', ['product' => $product, 'reviews' => $reviews])
--}}

<style>
.reviews-section{padding:50px 0 60px}
.review-summary{display:flex;align-items:center;gap:40px;background:#fff;border-radius:14px;padding:28px 32px;box-shadow:0 2px 20px rgba(0,0,0,.06);margin-bottom:32px}
.avg-score{text-align:center}
.avg-number{font-size:56px;font-weight:800;color:var(--primary);line-height:1}
.avg-stars{font-size:24px;color:#f59e0b;letter-spacing:2px;margin:6px 0}
.avg-count{font-size:13px;color:var(--text-light)}
.rating-bars{flex:1}
.rating-bar-row{display:flex;align-items:center;gap:10px;margin-bottom:8px}
.rating-bar-label{font-size:13px;color:var(--text-light);width:40px;text-align:right;flex-shrink:0}
.rating-bar-track{flex:1;height:8px;background:#f0f0f0;border-radius:4px;overflow:hidden}
.rating-bar-fill{height:100%;background:#f59e0b;border-radius:4px;transition:.5s}
.rating-bar-count{font-size:12px;color:var(--text-light);width:28px}

.reviews-list{display:flex;flex-direction:column;gap:20px;margin-bottom:40px}
.review-card{background:#fff;border-radius:12px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,.05);border:1px solid var(--border)}
.review-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px}
.reviewer-name{font-weight:700;color:var(--primary);font-size:15px}
.review-date{font-size:12px;color:var(--text-light);margin-top:2px}
.review-stars{font-size:18px;color:#f59e0b;letter-spacing:1px}
.review-title{font-weight:600;color:var(--primary);margin-bottom:6px;font-size:14px}
.review-comment{font-size:14px;color:var(--text-light);line-height:1.8}
.verified-badge{display:inline-flex;align-items:center;gap:4px;font-size:11px;color:#2e7d32;background:#e8f5e9;padding:3px 8px;border-radius:10px;margin-top:8px}

/* FORM */
.review-form-wrap{background:#fff;border-radius:14px;padding:32px;box-shadow:0 2px 20px rgba(0,0,0,.06)}
.review-form-wrap h3{font-family:'Playfair Display',serif;font-size:22px;color:var(--primary);margin-bottom:6px}
.star-picker{display:flex;flex-direction:row-reverse;gap:4px;margin-bottom:4px}
.star-picker input{display:none}
.star-picker label{font-size:32px;color:#ddd;cursor:pointer;transition:.15s}
.star-picker input:checked ~ label,
.star-picker label:hover,
.star-picker label:hover ~ label{color:#f59e0b}
.rf-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.rf-group{margin-bottom:16px}
.rf-group label{display:block;font-size:12px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px}
.rf-group input,.rf-group textarea{width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:14px;font-family:inherit;outline:none;transition:.2s;color:var(--text)}
.rf-group input:focus,.rf-group textarea:focus{border-color:var(--accent)}
.btn-submit-review{background:var(--primary);color:#fff;border:none;padding:13px 32px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;transition:.2s;display:flex;align-items:center;gap:8px}
.btn-submit-review:hover{background:var(--accent)}
.no-reviews{text-align:center;padding:40px;color:var(--text-light)}
.no-reviews i{font-size:36px;display:block;margin-bottom:12px;opacity:.3}
.flash-review-success{background:#d4edda;color:#155724;border:1px solid #c3e6cb;padding:14px 18px;border-radius:8px;margin-bottom:20px;font-size:14px}
.flash-review-error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;padding:14px 18px;border-radius:8px;margin-bottom:20px;font-size:14px}
@media(max-width:640px){.review-summary{flex-direction:column;gap:20px}.rf-grid{grid-template-columns:1fr}}
</style>

<div class="reviews-section">
    <div class="section-header">
        <div>
            <div class="section-title">Customer Reviews</div>
            <div class="section-sub">
                @if($reviews->count())
                    {{ $product->avg_rating }} out of 5 &nbsp;·&nbsp; {{ $reviews->count() }} review{{ $reviews->count() !== 1 ? 's' : '' }}
                @else
                    Be the first to review this product
                @endif
            </div>
        </div>
    </div>

    {{-- SUMMARY BAR --}}
    @if($reviews->count())
    <div class="review-summary">
        <div class="avg-score">
            <div class="avg-number">{{ $product->avg_rating }}</div>
            <div class="avg-stars">
                @for($i=1;$i<=5;$i++)
                    <span style="color:{{ $i <= round($product->avg_rating) ? '#f59e0b' : '#ddd' }}">★</span>
                @endfor
            </div>
            <div class="avg-count">{{ $reviews->count() }} reviews</div>
        </div>
        <div class="rating-bars">
            @for($star=5; $star>=1; $star--)
                @php $count = $reviews->where('rating', $star)->count(); $pct = $reviews->count() ? round($count/$reviews->count()*100) : 0; @endphp
                <div class="rating-bar-row">
                    <div class="rating-bar-label">{{ $star }} ★</div>
                    <div class="rating-bar-track"><div class="rating-bar-fill" style="width:{{ $pct }}%"></div></div>
                    <div class="rating-bar-count">{{ $count }}</div>
                </div>
            @endfor
        </div>
    </div>
    @endif

    {{-- REVIEWS LIST --}}
    @if($reviews->count())
    <div class="reviews-list">
        @foreach($reviews as $review)
        <div class="review-card">
            <div class="review-header">
                <div>
                    <div class="reviewer-name">{{ $review->name }}</div>
                    <div class="review-date">{{ $review->created_at->format('d M Y') }}</div>
                </div>
                <div class="review-stars">
                    @for($i=1;$i<=5;$i++)<span style="color:{{ $i<=$review->rating?'#f59e0b':'#ddd' }}">★</span>@endfor
                </div>
            </div>
            @if($review->title)
                <div class="review-title">{{ $review->title }}</div>
            @endif
            <div class="review-comment">{{ $review->comment }}</div>
            <div class="verified-badge"><i class="fas fa-check-circle"></i> Verified Purchase</div>
        </div>
        @endforeach
    </div>
    @else
    <div class="no-reviews">
        <i class="fas fa-star"></i>
        <p>No reviews yet. Be the first!</p>
    </div>
    @endif

    {{-- SUBMIT FORM --}}
    <div class="review-form-wrap">
        <h3>Write a Review</h3>
        <p style="color:var(--text-light);font-size:14px;margin-bottom:24px">Share your experience with this product</p>

        @if(session('review_success'))
            <div class="flash-review-success"><i class="fas fa-check-circle"></i> {{ session('review_success') }}</div>
        @endif
        @if(session('review_error'))
            <div class="flash-review-error"><i class="fas fa-exclamation-circle"></i> {{ session('review_error') }}</div>
        @endif

        <form method="POST" action="{{ route('review.store', $product) }}">
            @csrf
            <div class="rf-group">
                <label>Your Rating *</label>
                <div class="star-picker">
                    @for($i=5;$i>=1;$i--)
                    <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" {{ old('rating')==$i?'checked':'' }} required>
                    <label for="star{{ $i }}" title="{{ $i }} star{{ $i>1?'s':'' }}">★</label>
                    @endfor
                </div>
                @error('rating')<span style="color:#e53935;font-size:12px">{{ $message }}</span>@enderror
            </div>

            <div class="rf-grid">
                <div class="rf-group">
                    <label>Your Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required>
                    @error('name')<span style="color:#e53935;font-size:12px">{{ $message }}</span>@enderror
                </div>
                <div class="rf-group">
                    <label>Email (optional)</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com">
                </div>
            </div>

            <div class="rf-group">
                <label>Review Title</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Summarize your experience">
            </div>

            <div class="rf-group">
                <label>Your Review *</label>
                <textarea name="comment" rows="4" placeholder="Tell others what you think about this product..." required>{{ old('comment') }}</textarea>
                @error('comment')<span style="color:#e53935;font-size:12px">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn-submit-review">
                <i class="fas fa-paper-plane"></i> Submit Review
            </button>
            <p style="font-size:12px;color:var(--text-light);margin-top:10px">Reviews are published after approval.</p>
        </form>
    </div>
</div>
