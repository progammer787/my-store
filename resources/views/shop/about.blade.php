@extends('shop.layouts.app')
@section('title', 'About this demo — ' . \App\Models\Setting::get('site_name', 'My Store'))

@push('styles')
<style>
.demo-about{padding:64px 0 80px}.demo-about-hero{text-align:center;max-width:760px;margin:0 auto 56px}.demo-about-hero .eyebrow{display:inline-flex;padding:8px 14px;border-radius:999px;background:#f8eaf0;color:#8b3656;font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.demo-about h1{font-size:clamp(34px,5vw,54px);line-height:1.08;margin:18px 0;color:var(--primary)}.demo-about-hero p,.demo-about-card p{color:var(--text-light);line-height:1.75}.demo-about-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:20px}.demo-about-card{border:1px solid var(--border);border-radius:16px;padding:26px;background:#fff}.demo-about-card .icon{width:46px;height:46px;border-radius:13px;display:grid;place-items:center;background:#f8eaf0;color:#8b3656;font-size:19px;margin-bottom:18px}.demo-about-card h2{font-size:18px;margin:0 0 8px;color:var(--primary)}.demo-about-note{margin-top:30px;padding:20px 24px;border-radius:14px;background:#fff8e7;border:1px solid #f0dfb2;color:#66511e;line-height:1.65}.demo-about-actions{display:flex;justify-content:center;gap:12px;flex-wrap:wrap;margin-top:32px}
@media(max-width:760px){.demo-about{padding:42px 0 56px}.demo-about-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')
<main class="container demo-about">
    <header class="demo-about-hero">
        <span class="eyebrow">Storefront preview</span>
        <h1>{{ \App\Models\Setting::get('site_name', 'Rang & Noor') }}</h1>
        <p>This boutique storefront is a working sample of how a local shop can present its collection online. The catalogue, prices, and checkout are demo content and can be replaced with your own products and business details.</p>
    </header>

    <section class="demo-about-grid" aria-label="Demo features">
        <article class="demo-about-card">
            <div class="icon"><i class="fas fa-shirt" aria-hidden="true"></i></div>
            <h2>Curated sample catalogue</h2>
            <p>Browse a small set of distinct sample styles across New Arrivals, Everyday Pret, and Occasion Wear.</p>
        </article>
        <article class="demo-about-card">
            <div class="icon"><i class="fas fa-mobile-screen-button" aria-hidden="true"></i></div>
            <h2>Built for every screen</h2>
            <p>Explore product pages, category browsing, and the cart on desktop or mobile.</p>
        </article>
        <article class="demo-about-card">
            <div class="icon"><i class="fas fa-sliders" aria-hidden="true"></i></div>
            <h2>Ready to tailor</h2>
            <p>Branding, real inventory, delivery details, payments, and contact channels can be configured for a shop.</p>
        </article>
    </section>

    <aside class="demo-about-note"><strong>Demo only:</strong> Sample products and prices are for presentation. Checkout does not take payment, reserve stock, or create a real fulfilment request.</aside>

    <div class="demo-about-actions">
        <a href="{{ route('shop') }}" class="btn btn-accent"><i class="fas fa-bag-shopping"></i> Browse the demo</a>
        <a href="{{ route('contact') }}" class="btn btn-outline">Contact</a>
    </div>
</main>
@endsection
