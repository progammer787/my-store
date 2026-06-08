<!DOCTYPE html>
<html lang="en">
<head>
@php
    use App\Services\ThemeService;
    $siteLogo = \App\Models\Setting::get('site_logo');
    $siteFav  = \App\Models\Setting::get('site_favicon');
@endphp

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', \App\Models\Setting::get('site_name', 'My Store'))</title>

@if($siteFav)
<link rel="icon" type="image/png" href="{{ asset('storage/'.$siteFav) }}">
@endif

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="{{ ThemeService::googleFontsUrl() }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
          :root{
        {{ ThemeService::cssVars() }}
        --white:#fff;
        --text-light:#666;
        --shadow:0 2px 20px rgba(0,0,0,0.08);
    }
        body{font-family:'Inter',sans-serif;color:var(--text);background:var(--bg);font-size:15px}
        a{text-decoration:none;color:inherit}
        img{max-width:100%;display:block}

        /* ── TOP BAR ── */
        .top-bar{background:var(--primary);color:#aaa;font-size:12px;padding:8px 0;text-align:center;letter-spacing:.5px}

        /* ── HEADER ── */
        header{background:var(--white);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:999;box-shadow:var(--shadow)}
        .header-inner{max-width:1280px;margin:0 auto;padding:0 24px;display:flex;align-items:center;gap:24px;height:70px}
        .logo{font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--primary);white-space:nowrap}
        .logo span{color:var(--accent)}
        .search-bar{flex:1;max-width:500px;position:relative}
        .search-bar input{width:100%;padding:10px 44px 10px 16px;border:1.5px solid var(--border);border-radius:25px;font-size:14px;outline:none;transition:.2s}
        .search-bar input:focus{border-color:var(--accent)}
        .search-bar button{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-light);cursor:pointer;font-size:15px}
        .header-actions{display:flex;align-items:center;gap:20px;margin-left:auto}
        .header-actions a{color:var(--text);font-size:20px;position:relative;transition:.2s}
        .header-actions a:hover{color:var(--accent)}
        .cart-badge{position:absolute;top:-8px;right:-8px;background:var(--accent);color:#fff;font-size:10px;font-weight:700;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center}

        /* ── NAV ── */
        nav{background:var(--primary)}
        .nav-inner{max-width:1280px;margin:0 auto;padding:0 24px;display:flex;align-items:center;gap:0}
        nav a{color:#ccc;font-size:13px;font-weight:500;padding:14px 18px;display:block;letter-spacing:.3px;transition:.2s;text-transform:uppercase}
        nav a:hover,nav a.active{color:var(--accent)}

        /* ── FLASH ── */
        .flash{padding:14px 24px;margin:16px auto;max-width:1280px;border-radius:var(--radius);font-size:14px;font-weight:500}
        .flash.success{background:#d4edda;color:#155724;border:1px solid #c3e6cb}
        .flash.error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb}

        /* ── CONTAINER ── */
        .container{max-width:1280px;margin:0 auto;padding:0 24px}
        .section{padding:60px 0}
        .section-title{font-family:'Playfair Display',serif;font-size:32px;font-weight:700;color:var(--primary);margin-bottom:8px}
        .section-sub{color:var(--text-light);font-size:14px;margin-bottom:36px}

        /* ── PRODUCT CARD ── */
        .products-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:24px}
        .product-card{background:var(--white);border-radius:12px;overflow:hidden;box-shadow:var(--shadow);transition:.3s;cursor:pointer}
        .product-card:hover{transform:translateY(-4px);box-shadow:0 8px 30px rgba(0,0,0,0.12)}
        .product-card-img{position:relative;aspect-ratio:1;overflow:hidden;background:#f5f5f5}
        .product-card-img img{width:100%;height:100%;object-fit:cover;transition:.4s}
        .product-card:hover .product-card-img img{transform:scale(1.06)}
        .badge{position:absolute;top:12px;left:12px;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;letter-spacing:.5px}
        .badge-sale{background:#e53935;color:#fff}
        .badge-new{background:var(--accent);color:#fff}
        .product-card-body{padding:16px}
        .product-card-cat{font-size:11px;color:var(--accent);font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px}
        .product-card-name{font-size:15px;font-weight:600;color:var(--primary);margin-bottom:10px;line-height:1.4}
        .product-card-price{display:flex;align-items:center;gap:10px}
        .price-current{font-size:18px;font-weight:700;color:var(--primary)}
        .price-old{font-size:13px;color:#999;text-decoration:line-through}
        .btn-add-cart{width:100%;margin-top:14px;padding:10px;background:var(--primary);color:#fff;border:none;border-radius:var(--radius);font-size:13px;font-weight:600;cursor:pointer;transition:.2s;letter-spacing:.3px}
        .btn-add-cart:hover{background:var(--accent)}

        /* ── BUTTONS ── */
        .btn{display:inline-flex;align-items:center;gap:8px;padding:12px 28px;border-radius:var(--radius);font-size:14px;font-weight:600;cursor:pointer;transition:.2s;border:none;text-align:center}
        .btn-primary{background:var(--primary);color:#fff}
        .btn-primary:hover{background:#2a2a4e}
        .btn-accent{background:var(--accent);color:#fff}
        .btn-accent:hover{background:var(--accent-dark)}
        .btn-outline{background:transparent;color:var(--primary);border:2px solid var(--primary)}
        .btn-outline:hover{background:var(--primary);color:#fff}

        /* ── FOOTER ── */
        footer{background:var(--primary);color:#aaa;padding:60px 0 0}
        .footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:40px;padding-bottom:40px}
        .footer-brand .logo{color:#fff;font-size:24px;margin-bottom:16px}
        .footer-brand p{font-size:13px;line-height:1.8;max-width:260px}
        .footer-social{display:flex;gap:12px;margin-top:20px}
        .footer-social a{width:36px;height:36px;background:rgba(255,255,255,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#ccc;transition:.2s;font-size:14px}
        .footer-social a:hover{background:var(--accent);color:#fff}
        .footer-col h4{color:#fff;font-size:14px;font-weight:600;margin-bottom:20px;text-transform:uppercase;letter-spacing:.5px}
        .footer-col ul{list-style:none}
        .footer-col ul li{margin-bottom:10px}
        .footer-col ul li a{font-size:13px;color:#999;transition:.2s}
        .footer-col ul li a:hover{color:var(--accent)}
        .footer-bottom{border-top:1px solid rgba(255,255,255,0.08);padding:20px 0;text-align:center;font-size:12px;color:#666}
        .whatsapp-float{position:fixed;bottom:28px;right:28px;background:#25d366;color:#fff;width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:26px;box-shadow:0 4px 20px rgba(37,211,102,.4);z-index:1000;transition:.2s}
        .whatsapp-float:hover{transform:scale(1.1)}
        @media(max-width:768px){
            .header-inner{flex-wrap:wrap;height:auto;padding:12px 16px;gap:12px}
            .search-bar{order:3;max-width:100%;flex:none;width:100%}
            .footer-grid{grid-template-columns:1fr 1fr}
            nav a{padding:10px 12px;font-size:12px}
        }

         
/* ── Tablet (max 1100px) ── */
@media (max-width: 1100px) {
    .product-grid { grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .footer-grid  { grid-template-columns: 1fr 1fr; gap: 32px; }
    .hero-title   { font-size: 42px; }
}
 
/* ── Small Tablet (max 900px) ── */
@media (max-width: 900px) {
    /* Header */
    .header-inner { flex-wrap: wrap; height: auto; padding: 12px 16px; gap: 10px; }
    .logo { font-size: 22px; }
    .search-bar { order: 3; max-width: 100%; width: 100%; flex: none; }
    .header-actions { margin-left: auto; }
 
    /* Nav */
    .nav-inner { padding: 0 12px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .nav-inner a { padding: 10px 12px; font-size: 13px; }
    .nav-inner::-webkit-scrollbar { display: none; }
 
    /* Products */
    .product-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
 
    /* Shop page layout */
    .shop-wrap { grid-template-columns: 1fr; }
    .shop-sidebar { display: none; }
 
    /* Product detail */
    .product-layout { grid-template-columns: 1fr; gap: 28px; }
 
    /* Cart */
    .cart-layout { grid-template-columns: 1fr; }
    .cart-table th:nth-child(2),
    .cart-table td:nth-child(2) { display: none; }
 
    /* Checkout */
    .checkout-layout { grid-template-columns: 1fr; }
    .form-grid { grid-template-columns: 1fr; }
 
    /* Hero */
    .hero-slide { height: 320px; }
    .hero-title { font-size: 30px; }
    .hero-subtitle { font-size: 15px; }
    .hero-slide-content { padding: 0 24px; }
    .hero-actions { flex-wrap: wrap; }
 
    /* Promo banners */
    .promo-row { grid-template-columns: 1fr; }
    .promo-banner { height: 180px; }
 
    /* Categories */
    .categories-grid { grid-template-columns: repeat(3, 1fr); }
 
    /* Footer */
    .footer-grid { grid-template-columns: 1fr 1fr; gap: 28px; }
    .footer-top { padding: 40px 16px 28px; }
 
    /* About page */
    .story-grid  { grid-template-columns: 1fr; }
    .stats-grid  { grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .values-grid { grid-template-columns: repeat(2, 1fr); }
    .team-grid   { grid-template-columns: repeat(2, 1fr); }
 
    /* Contact page */
    .contact-grid { grid-template-columns: 1fr; }
    .form-row     { grid-template-columns: 1fr; }
}
 
/* ── Mobile (max 768px) ── */
@media (max-width: 768px) {
    /* General spacing */
    .container { padding: 0 16px; }
    .section   { padding: 40px 0; }
 
    /* Header */
    .header-inner { padding: 10px 16px; gap: 8px; }
    .header-actions a { font-size: 16px; }
 
    /* Products */
    .product-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .product-name { font-size: 13px; }
    .price-current { font-size: 15px; }
    .product-card-actions { gap: 6px; }
    .product-card-actions .btn { padding: 8px 6px; font-size: 12px; }
 
    /* Section headers */
    .section-header { flex-direction: column; align-items: flex-start; gap: 8px; }
    .section-title  { font-size: 22px; }
 
    /* Hero */
    .hero-slide { height: 280px; }
    .hero-title { font-size: 24px; line-height: 1.2; }
    .hero-subtitle { font-size: 14px; display: none; }
    .hero-badge { font-size: 10px; padding: 4px 10px; }
    .hero-actions .btn:last-child { display: none; }
 
    /* Categories */
    .categories-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .category-img, .category-img-placeholder { width: 60px; height: 60px; }
    .category-name { font-size: 13px; }
 
    /* Cart table — show only essential columns */
    .cart-table th:nth-child(4),
    .cart-table td:nth-child(4) { display: none; }
 
    /* Cart product info */
    .cart-img  { width: 48px; height: 48px; }
    .cart-name { font-size: 13px; }
 
    /* Checkout */
    .checkout-form-card { padding: 20px 16px; }
    .order-summary-card { padding: 16px; }
 
    /* Product detail */
    .product-title { font-size: 22px; }
    .big-price     { font-size: 24px; }
    .gallery-thumbs { gap: 6px; }
    .gallery-thumb  { width: 56px; height: 56px; }
 
    /* Footer */
    .footer-grid { grid-template-columns: 1fr; gap: 24px; }
    .footer-col:not(.footer-brand) { display: none; }
    .footer-brand { text-align: center; }
    .footer-social { justify-content: center; }
 
    /* About page */
    .about-hero h1  { font-size: 28px; }
    .about-hero p   { font-size: 14px; }
    .values-grid    { grid-template-columns: 1fr; }
    .stats-grid     { grid-template-columns: repeat(2, 1fr); }
    .stat-number    { font-size: 32px; }
    .team-grid      { grid-template-columns: repeat(2, 1fr); }
    .cta-section h2 { font-size: 26px; }
 
    /* Contact page */
    .contact-hero h1   { font-size: 26px; }
    .contact-form-card { padding: 20px 16px; }
    .wa-big-btn { flex-direction: column; text-align: center; gap: 8px; }
 
    /* Pagination */
    .pagination-wrap { gap: 4px; }
    .page-link { width: 34px; height: 34px; font-size: 13px; }
 
    /* WhatsApp float */
    .whatsapp-float { width: 46px; height: 46px; font-size: 20px; bottom: 16px; right: 16px; }
}
 
/* ── Small Mobile (max 480px) ── */
@media (max-width: 480px) {
    /* Products — 2 column stays but smaller */
    .product-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .product-card-body { padding: 10px 10px 6px; }
    .product-card-actions { padding: 0 10px 10px; }
    .product-card-actions .btn { font-size: 11px; padding: 7px 4px; }
 
    /* Hero */
    .hero-slide { height: 220px; }
    .hero-title { font-size: 20px; }
    .hero-arrows { display: none; }
 
    /* Nav — hide some categories */
    .nav-inner a:nth-child(n+5) { display: none; }
 
    /* Buttons */
    .btn { padding: 10px 16px; font-size: 13px; }
 
    /* Stats */
    .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .stat-number { font-size: 28px; }
 
    /* Team */
    .team-avatar { width: 90px; height: 90px; font-size: 30px; }
 
    /* Form */
    .form-grid  { grid-template-columns: 1fr; }
    .form-row   { grid-template-columns: 1fr; }
}
 
/* ── Touch improvements ── */
@media (hover: none) {
    .product-card:hover { transform: none; }
    .btn:hover { transform: none; }
    .category-card:hover { transform: none; }
}
 
/* ── Mobile menu toggle (hamburger) ── */
.mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    font-size: 22px;
    color: var(--primary);
    cursor: pointer;
    padding: 6px;
    margin-left: auto;
}
.nav-mobile-open .nav-inner {
    flex-direction: column;
    align-items: flex-start;
}
@media (max-width: 600px) {
    .mobile-menu-btn { display: flex; align-items: center; }
    nav { display: none; }
    nav.open { display: block; }
    .nav-inner { flex-direction: column; padding: 8px 16px 16px; }
    .nav-inner a { padding: 12px 4px; border-bottom: 1px solid var(--border); width: 100%; border-left: none; }
    .nav-inner a:last-child { border-bottom: none; }
    .nav-inner a.active { border-left: 3px solid var(--accent); padding-left: 10px; }
}
    </style>
    @stack('styles')
</head>
<body>

<div class="top-bar">
    Free shipping on orders over Rs. 2,000 &nbsp;|&nbsp; WhatsApp: {{ \App\Models\Setting::get('site_phone', '') }}
</div>

<header>
    <div class="header-inner">
      <a href="{{ route('home') }}" class="logo">
    @if($siteLogo)
        <img src="{{ asset('storage/'.$siteLogo) }}"
             alt="{{ \App\Models\Setting::get('site_name') }}"
             style="height:44px;object-fit:contain;max-width:200px">
    @else
        {{ \App\Models\Setting::get('site_name', 'My') }}<span>Store</span>
    @endif
</a>
        <div class="search-bar">
            <form action="{{ route('shop.search') }}" method="GET">
                <input type="text" name="q" placeholder="Search products..." value="{{ request('q') }}">
                <button type="submit"><i class="fas fa-search"></i></button>
                
            </form>
            
        </div>
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('about') }}"   class="{{ request()->routeIs('about')   ? 'active' : '' }}">About</a>
        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
        
        <div class="header-actions">
            <a href="{{ route('shop') }}" title="Shop"><i class="fas fa-store"></i></a>
            <a href="{{ route('cart.index') }}" title="Cart" style="position:relative">
                <i class="fas fa-shopping-bag"></i>
                <span class="cart-badge" id="cart-count">0</span>
            </a>
        </div>
    </div>
</header>

<nav>
    <div class="nav-inner">
        
        <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'active' : '' }}">All Products</a>

        @foreach(\App\Models\Category::active()->roots()->orderBy('sort_order')->take(6)->get() as $cat)
            <a href="{{ route('shop.category', $cat->slug) }}" class="{{ request()->is('shop/category/'.$cat->slug) ? 'active' : '' }}">{{ $cat->name }}</a>
        @endforeach
    </div>
</nav>

<main>
    @if(session('success'))
        <div class="flash success container">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash error container">{{ session('error') }}</div>
    @endif

    @yield('content')
</main>

<footer>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="logo">{{ \App\Models\Setting::get('site_name', 'My') }}<span style="color:var(--accent)">Store</span></div>
                <p>{{ \App\Models\Setting::get('site_tagline', 'Shop the best products online.') }}</p>
                <div class="footer-social">
                    @if(\App\Models\Setting::get('facebook_url'))
                        <a href="{{ \App\Models\Setting::get('facebook_url') }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if(\App\Models\Setting::get('instagram_url'))
                        <a href="{{ \App\Models\Setting::get('instagram_url') }}" target="_blank"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if(\App\Models\Setting::get('whatsapp_number'))
                        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number') }}" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    @endif
                </div>
            </div>
            <div class="footer-col">
                <h4>Shop</h4>
                <ul>
                    <li><a href="{{ route('shop') }}">All Products</a></li>
                    @foreach(\App\Models\Category::active()->roots()->take(5)->get() as $cat)
                        <li><a href="{{ route('shop.category', $cat->slug) }}">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="footer-col">
                <h4>Help</h4>
                <ul>
                    <li><a href="#">How to Order</a></li>
                    <li><a href="#">Return Policy</a></li>
                    <li><a href="#">Delivery Info</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <ul>
                    <li><a href="mailto:{{ \App\Models\Setting::get('site_email') }}"><i class="fas fa-envelope" style="margin-right:6px"></i>{{ \App\Models\Setting::get('site_email', 'info@store.com') }}</a></li>
                    <li><a href="tel:{{ \App\Models\Setting::get('site_phone') }}"><i class="fas fa-phone" style="margin-right:6px"></i>{{ \App\Models\Setting::get('site_phone', '') }}</a></li>
                    <li style="font-size:13px;margin-top:8px">{{ \App\Models\Setting::get('site_address', '') }}</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            {{ \App\Models\Setting::get('footer_text', '© '.date('Y').' My Store. All rights reserved.') }}
        </div>
    </div>
</footer>

@if(\App\Models\Setting::get('whatsapp_number'))
<a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number') }}" target="_blank" class="whatsapp-float" title="Chat on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
@endif

<script>
// Update cart count on load
fetch('{{ route("cart.count") }}')
    .then(r => r.json())
    .then(d => { document.getElementById('cart-count').textContent = d.count || 0 });

// Add to cart AJAX
document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-add-cart')) {
        const btn = e.target.closest('.btn-add-cart');
        const productId = btn.dataset.id;
        if (!productId) return;

        btn.textContent = 'Adding...';
        btn.disabled = true;

        fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        })
        .then(r => r.json())
        .then(d => {
            document.getElementById('cart-count').textContent = d.count;
            btn.textContent = '✓ Added!';
            btn.style.background = '#2e7d32';
            setTimeout(() => {
                btn.textContent = 'Add to Cart';
                btn.style.background = '';
                btn.disabled = false;
            }, 1500);
        });
    }
});


// ── Mobile Nav Toggle ──────────────────────────────
(function() {
    const nav = document.querySelector('nav');
    const header = document.querySelector('header');
 
    if (!nav || !header) return;
 
    // Create hamburger button
    const btn = document.createElement('button');
    btn.className = 'mobile-menu-btn';
    btn.innerHTML = '<i class="fas fa-bars"></i>';
    btn.setAttribute('aria-label', 'Toggle menu');
 
    // Insert after header-actions
    const actions = header.querySelector('.header-actions');
    if (actions) actions.after(btn);
 
    btn.addEventListener('click', function() {
        nav.classList.toggle('open');
        const isOpen = nav.classList.contains('open');
        btn.innerHTML = isOpen
            ? '<i class="fas fa-times"></i>'
            : '<i class="fas fa-bars"></i>';
    });
 
    // Close on nav link click
    nav.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => {
            nav.classList.remove('open');
            btn.innerHTML = '<i class="fas fa-bars"></i>';
        });
    });
 
    // Close on outside click
    document.addEventListener('click', function(e) {
        if (!nav.contains(e.target) && !btn.contains(e.target)) {
            nav.classList.remove('open');
            btn.innerHTML = '<i class="fas fa-bars"></i>';
        }
    });
})();
</script>
@stack('scripts')
</body>
</html>