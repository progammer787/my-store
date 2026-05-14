@extends('shop.layouts.app')
@section('title', \App\Models\Setting::get('site_name', 'My Store') . ' - ' . \App\Models\Setting::get('site_tagline', 'Shop Online'))

@push('styles')
<style>
/* ── HERO SLIDER ── */
.hero{position:relative;overflow:hidden;background:var(--primary)}
.slider{display:flex;transition:transform .6s cubic-bezier(.25,.46,.45,.94)}
.slide{min-width:100%;position:relative;height:520px;overflow:hidden}
.slide img{width:100%;height:100%;object-fit:cover;opacity:.7}
.slide-content{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:center;padding:0 10%;max-width:700px}
.slide-badge{display:inline-block;background:var(--accent);color:#fff;font-size:12px;font-weight:700;letter-spacing:1px;padding:6px 16px;border-radius:20px;margin-bottom:16px;text-transform:uppercase}
.slide-title{font-family:'Playfair Display',serif;font-size:52px;font-weight:700;color:#fff;line-height:1.15;margin-bottom:14px}
.slide-sub{color:rgba(255,255,255,.8);font-size:16px;margin-bottom:28px;line-height:1.6}
.slider-btn{position:absolute;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:#fff;width:48px;height:48px;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:.2s;backdrop-filter:blur(4px);z-index:10}
.slider-btn:hover{background:rgba(255,255,255,.3)}
.slider-prev{left:20px}
.slider-next{right:20px}
.slider-dots{position:absolute;bottom:20px;left:50%;transform:translateX(-50%);display:flex;gap:8px}
.dot{width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.4);cursor:pointer;transition:.2s}
.dot.active{background:#fff;width:24px;border-radius:4px}

/* ── PROMO BANNERS ── */
.promo-row{display:grid;grid-template-columns:1fr 1fr;gap:20px;padding:30px 0 0}
.promo-card{border-radius:12px;overflow:hidden;position:relative;height:200px;cursor:pointer}
.promo-card img{width:100%;height:100%;object-fit:cover;transition:.4s}
.promo-card:hover img{transform:scale(1.04)}
.promo-card-content{position:absolute;inset:0;padding:28px;display:flex;flex-direction:column;justify-content:center;background:linear-gradient(90deg,rgba(0,0,0,.55) 0%,transparent 70%)}
.promo-card-badge{font-size:11px;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px}
.promo-card-title{font-family:'Playfair Display',serif;font-size:24px;font-weight:700;color:#fff;margin-bottom:10px}
.promo-card-btn{display:inline-block;padding:8px 18px;background:var(--accent);color:#fff;border-radius:20px;font-size:12px;font-weight:600;transition:.2s}
.promo-card-btn:hover{background:#fff;color:var(--primary)}

/* ── CATEGORIES ── */
.categories-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:16px}
.cat-card{text-align:center;cursor:pointer;group:true}
.cat-img-wrap{width:110px;height:110px;border-radius:50%;overflow:hidden;margin:0 auto 12px;background:#f0f0f0;border:3px solid transparent;transition:.3s}
.cat-card:hover .cat-img-wrap{border-color:var(--accent);transform:scale(1.05)}
.cat-img-wrap img{width:100%;height:100%;object-fit:cover}
.cat-name{font-size:13px;font-weight:600;color:var(--primary);transition:.2s}
.cat-card:hover .cat-name{color:var(--accent)}

/* ── SECTION HEADER ── */
.section-header{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:36px;border-bottom:2px solid var(--border);padding-bottom:16px}
.section-header .view-all{font-size:13px;font-weight:600;color:var(--accent);text-transform:uppercase;letter-spacing:.5px}

/* ── MID BANNER ── */
.mid-banner{border-radius:12px;overflow:hidden;position:relative;height:180px;margin:0}
.mid-banner img{width:100%;height:100%;object-fit:cover}
.mid-banner-content{position:absolute;inset:0;display:flex;align-items:center;justify-content:space-between;padding:0 60px;background:linear-gradient(90deg,rgba(0,0,0,.6),transparent)}
.mid-banner-text h3{font-family:'Playfair Display',serif;font-size:28px;color:#fff;margin-bottom:6px}
.mid-banner-text p{color:rgba(255,255,255,.8);font-size:14px}

@media(max-width:768px){
    .slide{height:320px}
    .slide-title{font-size:30px}
    .promo-row{grid-template-columns:1fr}
    .mid-banner{height:140px}
    .mid-banner-content{padding:0 24px}
    .mid-banner-text h3{font-size:20px}
}
</style>
@endpush

@section('content')

{{-- ── HERO SLIDER ── --}}
@if($heroBanners->count())
<section class="hero">
    <div class="slider" id="slider">
        @foreach($heroBanners as $banner)
        <div class="slide">
            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}">
            <div class="slide-content">
                @if($banner->badge_text)
                    <span class="slide-badge">{{ $banner->badge_text }}</span>
                @endif
                @if($banner->title)
                    <h1 class="slide-title" style="color:{{ $banner->text_color }}">{{ $banner->title }}</h1>
                @endif
                @if($banner->subtitle)
                    <p class="slide-sub" style="color:{{ $banner->text_color }}">{{ $banner->subtitle }}</p>
                @endif
                @if($banner->button_text && $banner->button_link)
                    <a href="{{ $banner->button_link }}" class="btn btn-accent">{{ $banner->button_text }} &rarr;</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @if($heroBanners->count() > 1)
        <button class="slider-btn slider-prev" onclick="slide(-1)"><i class="fas fa-chevron-left"></i></button>
        <button class="slider-btn slider-next" onclick="slide(1)"><i class="fas fa-chevron-right"></i></button>
        <div class="slider-dots" id="dots">
            @foreach($heroBanners as $i => $b)
                <div class="dot {{ $i===0?'active':'' }}" onclick="goTo({{ $i }})"></div>
            @endforeach
        </div>
    @endif
</section>
@endif

<div class="container">

    {{-- ── PROMO BANNERS ── --}}
    @if($promoBanners->count())
    <div class="promo-row">
        @foreach($promoBanners->take(2) as $banner)
        <a href="{{ $banner->button_link ?? '#' }}" class="promo-card">
            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}">
            <div class="promo-card-content">
                @if($banner->badge_text)<div class="promo-card-badge">{{ $banner->badge_text }}</div>@endif
                @if($banner->title)<div class="promo-card-title">{{ $banner->title }}</div>@endif
                @if($banner->button_text)<span class="promo-card-btn">{{ $banner->button_text }}</span>@endif
            </div>
        </a>
        @endforeach
    </div>
    @endif

    {{-- ── CATEGORIES ── --}}
    @if($homeCategories->count())
    <section class="section">
        <div class="section-header">
            <div>
                <div class="section-title">Shop by Category</div>
                <div class="section-sub">Find exactly what you're looking for</div>
            </div>
            <a href="{{ route('shop') }}" class="view-all">View All &rarr;</a>
        </div>
        <div class="categories-grid">
            @foreach($homeCategories as $cat)
            <a href="{{ route('shop.category', $cat->slug) }}" class="cat-card">
                <div class="cat-img-wrap">
                    <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}">
                </div>
                <div class="cat-name">{{ $cat->name }}</div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ── FEATURED PRODUCTS ── --}}
    @if($featuredProducts->count())
    <section class="section" style="padding-top:0">
        <div class="section-header">
            <div>
                <div class="section-title">Featured Products</div>
                <div class="section-sub">Handpicked just for you</div>
            </div>
            <a href="{{ route('shop') }}" class="view-all">View All &rarr;</a>
        </div>
        <div class="products-grid">
            @foreach($featuredProducts as $product)
                @include('shop.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>
    @endif

    {{-- ── MID BANNER ── --}}
    @if($midBanners->count())
    <section style="padding:20px 0 40px">
        @php $mb = $midBanners->first(); @endphp
        <a href="{{ $mb->button_link ?? '#' }}" class="mid-banner">
            <img src="{{ $mb->image_url }}" alt="{{ $mb->title }}">
            <div class="mid-banner-content">
                <div class="mid-banner-text">
                    <h3>{{ $mb->title }}</h3>
                    @if($mb->subtitle)<p>{{ $mb->subtitle }}</p>@endif
                </div>
                @if($mb->button_text)
                    <span class="btn btn-accent">{{ $mb->button_text }}</span>
                @endif
            </div>
        </a>
    </section>
    @endif

    {{-- ── NEW ARRIVALS ── --}}
    @if($newArrivals->count())
    <section class="section" style="padding-top:0">
        <div class="section-header">
            <div>
                <div class="section-title">New Arrivals</div>
                <div class="section-sub">Fresh styles, just landed</div>
            </div>
            <a href="{{ route('shop') }}" class="view-all">View All &rarr;</a>
        </div>
        <div class="products-grid">
            @foreach($newArrivals as $product)
                @include('shop.partials.product-card', ['product' => $product, 'badge' => 'NEW'])
            @endforeach
        </div>
    </section>
    @endif

    {{-- ── BEST SELLERS ── --}}
    @if($bestSellers->count())
    <section class="section" style="padding-top:0">
        <div class="section-header">
            <div>
                <div class="section-title">Best Sellers</div>
                <div class="section-sub">Most loved by our customers</div>
            </div>
            <a href="{{ route('shop') }}" class="view-all">View All &rarr;</a>
        </div>
        <div class="products-grid">
            @foreach($bestSellers as $product)
                @include('shop.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </section>
    @endif

</div>

@push('scripts')
<script>
let current = 0;
const slides = document.querySelectorAll('.slide');
const dots   = document.querySelectorAll('.dot');
function goTo(n) {
    current = (n + slides.length) % slides.length;
    document.getElementById('slider').style.transform = `translateX(-${current * 100}%)`;
    dots.forEach((d,i) => d.classList.toggle('active', i === current));
}
function slide(dir) { goTo(current + dir); }
if (slides.length > 1) setInterval(() => slide(1), 5000);
</script>
@endpush

@endsection