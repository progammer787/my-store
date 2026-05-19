@extends('shop.layouts.app')
@section('title', 'About Us — ' . \App\Models\Setting::get('site_name', 'My Store'))

@push('styles')
<style>
/* HERO */
.about-hero {
    background: linear-gradient(135deg, var(--primary) 0%, #2a2a3e 100%);
    padding: 80px 0;
    text-align: center;
    color: white;
    position: relative;
    overflow: hidden;
}
.about-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.about-hero-content { position: relative; z-index: 2; }
.about-hero h1 { font-size: 48px; font-weight: 700; margin-bottom: 16px; }
.about-hero p  { font-size: 18px; opacity: 0.8; max-width: 600px; margin: 0 auto; }
.about-badge   { display: inline-block; background: var(--accent); color: white; padding: 6px 18px; border-radius: 30px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }

/* STORY */
.story-section { padding: 80px 0; }
.story-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
.story-img { border-radius: 16px; overflow: hidden; aspect-ratio: 4/3; background: var(--border); }
.story-img img { width: 100%; height: 100%; object-fit: cover; }
.story-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary), var(--accent)); font-size: 80px; }
.story-content h2 { font-size: 36px; font-weight: 700; margin-bottom: 16px; color: var(--primary); }
.story-content p  { font-size: 15px; color: #555; line-height: 1.9; margin-bottom: 16px; }
.story-content .highlight { color: var(--accent); font-weight: 600; }

/* STATS */
.stats-section { background: var(--primary); padding: 60px 0; }
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px; text-align: center; }
.stat-item { color: white; }
.stat-number { font-size: 44px; font-weight: 800; color: var(--accent); font-family: var(--font-display); }
.stat-label  { font-size: 14px; opacity: 0.7; margin-top: 6px; text-transform: uppercase; letter-spacing: 1px; }

/* VALUES */
.values-section { padding: 80px 0; background: var(--bg); }
.values-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; margin-top: 48px; }
.value-card { background: white; border-radius: 14px; padding: 32px 28px; border: 1px solid var(--border); text-align: center; transition: all 0.3s; }
.value-card:hover { transform: translateY(-6px); box-shadow: 0 12px 40px rgba(0,0,0,0.1); border-color: var(--accent); }
.value-icon { width: 64px; height: 64px; background: linear-gradient(135deg, var(--accent), #e8c97e); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 20px; }
.value-card h3 { font-size: 20px; font-weight: 700; color: var(--primary); margin-bottom: 12px; }
.value-card p  { font-size: 14px; color: #666; line-height: 1.8; }

/* TEAM */
.team-section { padding: 80px 0; }
.team-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-top: 48px; }
.team-card { text-align: center; }
.team-avatar { width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 16px; overflow: hidden; border: 4px solid var(--border); background: linear-gradient(135deg, var(--primary), var(--accent)); display: flex; align-items: center; justify-content: center; font-size: 40px; color: white; }
.team-avatar img { width: 100%; height: 100%; object-fit: cover; }
.team-name { font-size: 16px; font-weight: 700; color: var(--primary); margin-bottom: 4px; }
.team-role { font-size: 13px; color: var(--accent); font-weight: 500; }

/* CTA */
.cta-section { padding: 80px 0; background: linear-gradient(135deg, var(--accent), #e8c97e); text-align: center; }
.cta-section h2 { font-size: 36px; font-weight: 700; color: white; margin-bottom: 14px; }
.cta-section p  { font-size: 16px; color: rgba(255,255,255,0.85); margin-bottom: 32px; }
.btn-white { background: white; color: var(--primary); padding: 14px 32px; border-radius: var(--btn-radius); font-weight: 700; font-size: 15px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.25s; }
.btn-white:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.2); }

@media(max-width:900px) {
    .story-grid  { grid-template-columns: 1fr; }
    .stats-grid  { grid-template-columns: repeat(2,1fr); }
    .values-grid { grid-template-columns: repeat(2,1fr); }
    .team-grid   { grid-template-columns: repeat(2,1fr); }
    .about-hero h1 { font-size: 32px; }
}
@media(max-width:600px) {
    .values-grid { grid-template-columns: 1fr; }
    .team-grid   { grid-template-columns: repeat(2,1fr); }
}
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="about-hero">
    <div class="about-hero-content">
        <div class="about-badge">Our Story</div>
        <h1>About {{ \App\Models\Setting::get('site_name', 'Our Store') }}</h1>
        <p>{{ \App\Models\Setting::get('about_tagline', 'We are passionate about bringing you the best products at the best prices.') }}</p>
    </div>
</section>

{{-- STORY --}}
<section class="story-section">
    <div class="container">
        <div class="story-grid">
            <div class="story-img">
                @if(\App\Models\Setting::get('about_image'))
                    <img src="{{ asset('storage/'.\App\Models\Setting::get('about_image')) }}" alt="Our Story">
                @else
                    <div class="story-placeholder">🛍️</div>
                @endif
            </div>
            <div class="story-content">
                <h2>{{ \App\Models\Setting::get('about_title', 'Our Story') }}</h2>
                <p>{!! nl2br(e(\App\Models\Setting::get('about_story', 'We started with a simple mission: to make quality products accessible to everyone. What began as a small passion project has grown into a trusted online store serving thousands of happy customers across Pakistan.'))) !!}</p>
                <p>{{ \App\Models\Setting::get('about_story_2', 'We believe shopping should be easy, enjoyable and trustworthy. Every product we carry is carefully selected to meet our high standards of quality and value.') }}</p>
                <div style="display:flex;gap:16px;margin-top:28px">
                    <a href="{{ route('shop') }}" class="btn btn-accent">Shop Now <i class="fas fa-arrow-right"></i></a>
                    <a href="{{ route('contact') }}" class="btn btn-outline">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">{{ \App\Models\Setting::get('about_stat_customers', '5,000') }}+</div>
                <div class="stat-label">Happy Customers</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ \App\Models\Setting::get('about_stat_products', '500') }}+</div>
                <div class="stat-label">Products</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ \App\Models\Setting::get('about_stat_years', '3') }}+</div>
                <div class="stat-label">Years Experience</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ \App\Models\Setting::get('about_stat_cities', '50') }}+</div>
                <div class="stat-label">Cities Served</div>
            </div>
        </div>
    </div>
</section>

{{-- VALUES --}}
<section class="values-section">
    <div class="container">
        <div style="text-align:center">
            <h2 class="section-title">Our <span>Values</span></h2>
            <p class="section-sub">What drives us every single day</p>
        </div>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">✨</div>
                <h3>Quality First</h3>
                <p>Every product is carefully selected and quality checked before reaching your hands.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🚀</div>
                <h3>Fast Delivery</h3>
                <p>We ensure quick and safe delivery to your doorstep, wherever you are in Pakistan.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">💰</div>
                <h3>Best Prices</h3>
                <p>We work directly with suppliers to offer you the most competitive prices in the market.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🤝</div>
                <h3>Trust & Honesty</h3>
                <p>What you see is what you get. No hidden charges, no fake reviews.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">💬</div>
                <h3>24/7 Support</h3>
                <p>Our team is always ready to help you on WhatsApp, any time of day or night.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🔄</div>
                <h3>Easy Returns</h3>
                <p>Not satisfied? We offer a hassle-free return and exchange policy.</p>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="cta-section">
    <h2>Ready to Start Shopping?</h2>
    <p>Join thousands of happy customers and discover amazing products today.</p>
    <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap">
        <a href="{{ route('shop') }}" class="btn-white">
            <i class="fas fa-shopping-bag"></i> Browse Products
        </a>
        @if(\App\Models\Setting::get('whatsapp_number'))
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp_number')) }}"
           target="_blank"
           style="background:rgba(255,255,255,0.2);color:white;padding:14px 32px;border-radius:var(--btn-radius);font-weight:700;font-size:15px;display:inline-flex;align-items:center;gap:8px;transition:all 0.25s;border:2px solid rgba(255,255,255,0.4)">
            <i class="fab fa-whatsapp"></i> Chat With Us
        </a>
        @endif
    </div>
</section>

@endsection