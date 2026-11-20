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

        /* Storefront refresh */
        :root{--ink:#18211d;--muted:#707a73;--surface:#fff;--line:#e8ece8;--surface-soft:#f5f7f4;--shadow-soft:0 12px 34px rgba(20,38,28,.08)}
        body{color:var(--ink);background:#fbfcfa;line-height:1.55}
        .top-bar{background:#193d31;color:#edf4ed;font-size:11px;letter-spacing:.08em;text-transform:uppercase}
        header{box-shadow:0 4px 18px rgba(22,43,31,.05);border-color:var(--line)}
        .header-inner{max-width:1360px;height:78px}
        .header-inner>a:not(.logo){font-size:13px;font-weight:600;color:#3b4d42}
        .header-inner>a:not(.logo):hover{color:var(--accent)}
        .logo{font-family:'Inter',sans-serif;letter-spacing:-.04em}
        .search-bar input{background:var(--surface-soft);border-color:transparent;padding:12px 46px 12px 18px}
        .search-bar input:focus{background:#fff;box-shadow:0 0 0 4px color-mix(in srgb,var(--accent) 14%,transparent)}
        nav{background:#fff;border-bottom:1px solid var(--line)}
        .nav-inner{max-width:1360px}
        nav a{color:#546158;font-size:12px;font-weight:650;letter-spacing:.04em}
        nav a:hover,nav a.active{color:var(--accent)}
        .container{max-width:1360px}
        .section{padding:68px 0}
        .section-title{font-family:'Inter',sans-serif;letter-spacing:-.035em;font-size:clamp(25px,3vw,36px);color:#1c3026}
        .section-sub{color:var(--muted);margin:7px 0 0}
        .section-header{border-bottom:1px solid var(--line);margin-bottom:28px;padding-bottom:18px}
        .section-header .view-all{font-size:12px}
        .products-grid{grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:18px}
        .product-card{border:1px solid #edf0ec;border-radius:14px;box-shadow:0 3px 14px rgba(25,40,30,.035)}
        .product-card:hover{transform:translateY(-5px);box-shadow:var(--shadow-soft)}
        .product-card-img{background:#f3f5f1}
        .product-card-body{padding:16px 17px 18px}
        .product-card-name{color:#25372d;min-height:42px}
        .btn-add-cart{border-radius:8px;background:#214c3b;padding:12px}
        .btn-add-cart:hover{background:#173829}
        .btn{border-radius:9px}
        .flash{box-shadow:0 4px 14px rgba(20,30,20,.04)}
        footer{background:#142c22;color:#c2cec5;padding-top:52px}
        .footer-col h4{font-size:12px;letter-spacing:.1em}
        .footer-bottom{color:#9aaa9f}
        .whatsapp-float{box-shadow:0 8px 24px rgba(37,211,102,.3)}
        a:focus-visible,button:focus-visible,input:focus-visible,select:focus-visible,textarea:focus-visible{outline:3px solid color-mix(in srgb,var(--accent) 55%,white);outline-offset:3px}
        @media(max-width:768px){.header-inner{min-height:68px}.products-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.product-card-body{padding:12px}.section{padding:44px 0}}
    </style>
    @stack('styles')
    <style id="storefront-design-system">
        :root{
            --primary:#171717;--accent:#171717;--accent-dark:#383838;--bg:#fff;
            --white:#fff;--text:#1d1d1d;--text-light:#777;--border:#e9e9e9;
            --radius:5px;--shadow:0 5px 22px rgba(0,0,0,.055);
            --ink:#171717;--muted:#777;--surface:#fff;--line:#e9e9e9;
        }
        html{scroll-behavior:smooth}
        body{font-family:'Inter',Arial,sans-serif;color:#1d1d1d;background:#fff;line-height:1.55}
        a{transition:color .18s ease}
        button,input,select,textarea{font:inherit}
        .top-bar{background:#171717;color:#fff;font-size:10px;letter-spacing:.12em;text-transform:uppercase;padding:9px 16px}
        header{background:#fff;border-bottom:1px solid #ededed;box-shadow:none}
        .header-inner{max-width:1320px;height:76px;gap:28px}
        .logo{font-family:'Inter',Arial,sans-serif;font-size:25px;font-weight:800;letter-spacing:-.055em;color:#171717}
        .logo span{color:#9b8054}
        .search-bar{max-width:540px}
        .search-bar input{background:#f6f6f5;border:1px solid #f0f0ef;border-radius:3px;padding:12px 46px 12px 16px;color:#222}
        .search-bar input:focus{border-color:#aaa;box-shadow:none}
        .search-bar button{right:15px;color:#333}
        .header-inner>a:not(.logo){font-size:12px;font-weight:600;letter-spacing:.04em;color:#303030}
        .header-inner>a:not(.logo):hover,.header-actions a:hover{color:#9b8054}
        .header-actions{gap:22px}
        .header-actions a{color:#222;font-size:18px}
        .cart-badge{background:#171717;color:#fff}
        nav{background:#fff;border-bottom:1px solid #ededed}
        .nav-inner{max-width:1320px;min-height:44px;justify-content:center;gap:34px}
        nav a{color:#555;font-size:10px;font-weight:650;letter-spacing:.1em;padding:14px 2px;text-transform:uppercase}
        nav a:hover,nav a.active{color:#171717}
        .mobile-nav-link{display:none}
        .container{max-width:1320px;padding-left:28px;padding-right:28px}
        .section{padding:54px 0}
        .section-title{font-family:'Inter',Arial,sans-serif;color:#171717;font-size:25px;font-weight:700;letter-spacing:-.045em;margin:0}
        .section-sub{font-size:12px;color:#777;margin:6px 0 0}
        .section-header{border-bottom:1px solid #e9e9e9;padding:0 0 15px;margin-bottom:22px}
        .section-header .view-all{color:#171717;font-size:10px;font-weight:700;letter-spacing:.08em}
        .products-grid{grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}
        .product-card{background:#fff;border:0;border-radius:0;box-shadow:none;overflow:hidden;cursor:pointer}
        .product-card:hover{transform:none;box-shadow:none}
        .product-card-img{aspect-ratio:4/5;background:#f4f4f2}
        .product-card-img img{transition:transform .45s ease}
        .product-card:hover .product-card-img img{transform:scale(1.035)}
        .badge{top:10px;left:10px;border-radius:2px;padding:5px 8px;font-size:9px;letter-spacing:.08em}
        .badge-sale{background:#171717;color:#fff}
        .badge-new{background:#fff;color:#171717}
        .product-card-body{padding:12px 0 8px}
        .product-card-cat{font-size:9px;letter-spacing:.12em;color:#777;margin-bottom:4px}
        .product-card-name{font-size:12px;font-weight:550;color:#222;line-height:1.45;min-height:0;margin-bottom:5px}
        .product-card-price{gap:9px}
        .price-current{font-size:12px;font-weight:700;color:#171717}
        .price-old{font-size:11px;color:#999}
        .product-card form{padding:0 0 12px!important}
        .btn-add-cart{margin-top:6px;padding:10px 12px;border-radius:2px;background:#171717;font-size:10px;letter-spacing:.07em;text-transform:uppercase}
        .btn-add-cart:hover{background:#444}
        .btn{border-radius:2px;font-size:11px;letter-spacing:.04em}
        .btn-primary,.btn-accent{background:#171717;color:#fff}
        .btn-primary:hover,.btn-accent:hover{background:#414141}
        .btn-outline{border:1px solid #171717;color:#171717}
        .flash{border-radius:2px}
        footer{background:#f5f5f3;color:#555;padding:44px 0 0}
        .footer-brand .logo{color:#171717}
        .footer-col h4{color:#171717;font-size:10px;letter-spacing:.12em}
        .footer-col ul li a{color:#666;font-size:11px}
        .footer-col ul li a:hover{color:#171717}
        .footer-social a{background:#e9e9e7;color:#333}
        .footer-bottom{border-color:#e2e2df;color:#777}
        .whatsapp-float{width:48px;height:48px;right:22px;bottom:22px;font-size:22px}
        .hero{background:#f5f3ed}
        .hero-fallback{width:100%;background:#f6f0dc;overflow:hidden}
        .hero-fallback a{display:block;max-width:1600px;margin:0 auto}
        .hero-fallback img{width:100%;height:min(50vw,620px);min-height:300px;object-fit:cover;object-position:center 48%}
        .slide{height:480px}
        .slide img{opacity:1}
        .slide-content{padding-left:max(7vw,40px)}
        .slide-title{font-family:'Inter',Arial,sans-serif;letter-spacing:-.045em}
        .slide-badge{border-radius:2px}
        .slider-btn{width:40px;height:40px;border-radius:50%;background:rgba(0,0,0,.25)}
        .promo-row{gap:14px;padding-top:22px}
        .promo-card{height:auto;aspect-ratio:2.4/1;border-radius:0}
        .promo-card-content{padding:24px;background:linear-gradient(90deg,rgba(0,0,0,.48),transparent 75%)}
        .promo-card-badge{color:#fff;font-size:9px;letter-spacing:.14em}
        .promo-card-title{font-family:'Inter',Arial,sans-serif;font-size:19px}
        .promo-card-btn{background:#fff;color:#171717;border-radius:2px;font-size:10px;text-transform:uppercase;letter-spacing:.06em}
        .categories-grid{grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
        .cat-card{padding-bottom:4px}
        .cat-img-wrap{width:100%;height:auto;aspect-ratio:1.32/1;border-radius:0;background:#f3f3f1;border:0;margin:0 0 10px}
        .cat-card:hover .cat-img-wrap{border:0;transform:none}
        .cat-img-wrap img{transition:transform .45s ease}
        .cat-card:hover .cat-img-wrap img{transform:scale(1.035)}
        .cat-name{font-size:11px;letter-spacing:.03em;color:#222}
        .cat-icon-placeholder{display:grid;place-items:center;width:100%;height:100%;color:#333;font-size:38px;background:linear-gradient(135deg,#f7f7f5,#ecece9);transition:background .2s,color .2s}
        .cat-card:hover .cat-icon-placeholder{color:#9b8054;background:#efeee9}
        .cat-filter-label{display:flex;align-items:center;gap:9px}
        .cat-filter-label>i{width:15px;text-align:center;color:#777;font-size:12px}
        .cat-filter-item:hover .cat-filter-label>i,.cat-filter-item.active .cat-filter-label>i{color:#171717}
        .mid-banner{height:auto;min-height:180px;border-radius:0;background:#171717}
        .mid-banner img{position:absolute;inset:0}
        .mid-banner-content{padding:32px 5%;background:linear-gradient(90deg,rgba(0,0,0,.72),rgba(0,0,0,.08))}
        .mid-banner-text h3{font-family:'Inter',Arial,sans-serif;font-size:24px;font-weight:700}
        .shop-layout{grid-template-columns:205px minmax(0,1fr);gap:34px;padding:24px 0 54px}
        .sidebar{top:104px}
        .sidebar-card{background:#fff;border:1px solid #ececea;border-radius:0;box-shadow:none;padding:18px;margin-bottom:14px}
        .sidebar-card h3{color:#171717;font-size:10px;letter-spacing:.12em;border-bottom:1px solid #e9e9e9}
        .cat-filter-item{font-size:11px;color:#555;border-color:#f0f0f0}
        .cat-filter-item .count{background:#f3f3f1;color:#777;border-radius:2px}
        .price-inputs{flex-direction:row}
        .price-inputs input,.sort-select{border:1px solid #dededc;border-radius:2px;background:#fff;color:#333}
        .shop-header{border-bottom:1px solid #e9e9e9;padding-bottom:16px}
        .shop-header h1{font-family:'Inter',Arial,sans-serif;color:#171717;font-size:22px;letter-spacing:-.04em}
        .shop-header p{color:#777;font-size:11px}
        .pagination{gap:5px}
        .pagination a,.pagination span{border-color:#e3e3e1;border-radius:2px;color:#333}
        .pagination .active span{background:#171717;border-color:#171717}
        .product-layout{grid-template-columns:minmax(0,1.05fr) minmax(360px,.95fr);gap:56px;padding:24px 0 36px}
        .product-gallery .main-img{border-radius:0;background:#f4f4f2;margin-bottom:10px}
        .thumb{border-radius:0;border-color:transparent}
        .thumb:hover,.thumb.active{border-color:#171717}
        .product-breadcrumb{font-size:10px;color:#888}
        .product-breadcrumb a{color:#555}
        .product-name{font-family:'Inter',Arial,sans-serif;font-size:30px;letter-spacing:-.045em;color:#171717}
        .product-price-current{font-size:24px;color:#171717}
        .product-discount{border-radius:2px;background:#171717}
        .product-short-desc{font-size:13px;color:#666;line-height:1.8}
        .stock-badge{border-radius:2px;font-size:10px}
        .qty-input{border-radius:2px}
        .qty-input button{color:#222}
        .product-actions{gap:10px}
        .btn-wa{border-radius:2px;padding:14px 18px;font-size:10px;text-transform:uppercase;letter-spacing:.04em}
        .product-tabs{border-color:#e7e7e5}
        .tab-btn{font-size:11px;letter-spacing:.06em;text-transform:uppercase}
        .tab-btn.active{color:#171717;border-bottom-color:#171717}
        .tab-content{font-size:13px;color:#666}
        .related-section{padding:44px 0 0}
        .cart-layout,.checkout-layout{grid-template-columns:minmax(0,1fr) 340px;gap:24px;padding:24px 0 56px}
        .cart-table,.cart-summary,.checkout-form,.order-summary{border:1px solid #ececea;border-radius:0;box-shadow:none;background:#fff}
        .cart-table th{background:#f6f6f4;color:#555;font-size:9px;letter-spacing:.1em}
        .cart-table td{border-color:#ececea;font-size:12px}
        .cart-product img{border-radius:0;background:#f4f4f2}
        .cart-product-name{font-size:12px;color:#222}
        .cart-qty{border-radius:2px}
        .cart-summary,.order-summary{padding:22px;top:100px}
        .cart-summary h3,.order-summary h3{color:#171717!important;font-size:12px!important;letter-spacing:.08em;text-transform:uppercase}
        .summary-row{font-size:12px;color:#555}
        .summary-total{font-size:15px;color:#171717}
        .checkout-form{padding:26px}
        .form-group label{font-size:10px;letter-spacing:.06em;text-transform:uppercase;color:#333}
        .form-group input,.form-group textarea{border:1px solid #dededc;border-radius:2px;font-size:12px}
        .form-group input:focus,.form-group textarea:focus{border-color:#777}
        .order-item img{border-radius:0;background:#f4f4f2}
        .wa-notice{border-radius:2px}
        .empty-cart{padding:70px 0}
        .empty-cart h2{font-family:'Inter',Arial,sans-serif!important;color:#171717}
        .page-title{font-family:'Inter',Arial,sans-serif;font-size:25px;font-weight:700;letter-spacing:-.04em;color:#171717;margin:28px 0 8px}
        .about-hero,.contact-hero,.stats-section,.cta-section{background:#171717;color:#fff}
        .about-hero h1,.contact-hero h1,.story-content h2,.contact-form-card h2,.cta-section h2{font-family:'Inter',Arial,sans-serif;letter-spacing:-.04em}
        .about-hero,.contact-hero{padding:58px 0}
        .about-badge{border-radius:2px;background:#333;font-size:10px;letter-spacing:.1em;text-transform:uppercase}
        .story-section,.team-section{padding:56px 0}
        .story-img{border-radius:0}
        .stats-section{padding:44px 0}
        .stat-number{font-family:'Inter',Arial,sans-serif;color:#fff}
        .values-section{padding:56px 0}
        .values-grid,.team-grid{gap:16px;margin-top:28px}
        .value-card,.contact-card,.contact-form-card{border:1px solid #e9e9e9;border-radius:0;box-shadow:none}
        .value-card{padding:26px 22px}
        .value-icon,.contact-card-icon{border-radius:2px;background:#171717}
        .value-card h3,.team-name,.contact-card-body h4,.contact-form-card h2{color:#171717}
        .cta-section{padding:56px 0}
        .btn-white{border-radius:2px}
        .contact-section{padding:56px 0}
        .contact-grid{gap:28px}
        .contact-card{padding:20px;transition:border-color .2s ease}
        .contact-card:hover{transform:none;box-shadow:none;border-color:#999}
        .wa-big-btn{border-radius:2px}
        .contact-form-card{padding:28px}
        .form-field label,.rf-group label{color:#555;font-size:10px;letter-spacing:.1em}
        .form-field input,.form-field textarea,.form-field select,.rf-group input,.rf-group textarea{border:1px solid #dededc;border-radius:2px;background:#fff}
        .submit-btn,.btn-submit-review{background:#171717;border-radius:2px}
        .map-section{padding-bottom:56px}
        .map-wrap{border-radius:0}
        .reviews-section{padding:34px 0 44px}
        .review-summary,.review-card,.review-form-wrap{border:1px solid #ececea;border-radius:0;box-shadow:none}
        .review-form-wrap h3{font-family:'Inter',Arial,sans-serif;font-size:18px;color:#171717}
        .rf-group input:focus,.rf-group textarea:focus{border-color:#777}
        a:focus-visible,button:focus-visible,input:focus-visible,select:focus-visible,textarea:focus-visible{outline:2px solid #9b8054;outline-offset:3px}
        @media(max-width:1000px){
            .header-inner{gap:16px}.nav-inner{gap:22px}.products-grid{grid-template-columns:repeat(3,minmax(0,1fr))}
            .product-layout{gap:32px}.cart-layout,.checkout-layout{grid-template-columns:minmax(0,1fr) 300px}
        }
        @media(max-width:760px){
            .header-inner{height:auto;min-height:64px;padding:10px 16px}.container{padding-left:16px;padding-right:16px}
            .header-inner>a:not(.logo){display:none}.nav-inner{gap:18px;align-items:stretch}.nav-inner a{white-space:nowrap}
            .nav-inner .mobile-nav-link{display:block}
            .products-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.categories-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
            .section{padding:40px 0}.section-title{font-size:21px}.section-header{align-items:flex-end}
            .slide{height:300px}.slide-title{font-size:28px}.slide-sub{font-size:13px}.slide-content{padding:0 28px}
            .hero-fallback img{height:auto;min-height:0;object-fit:contain}
            .promo-row{grid-template-columns:1fr}.promo-card{aspect-ratio:2.5/1}
            .shop-layout{grid-template-columns:1fr;gap:18px;padding-top:14px}.sidebar{position:static;display:grid;grid-template-columns:1fr 1fr;gap:10px}
            .sidebar-card{margin:0;padding:14px}.shop-header{gap:10px}.shop-header h1{font-size:20px}
            .product-layout{grid-template-columns:1fr;gap:24px}.product-name{font-size:25px}.product-actions{flex-direction:row}
            .cart-layout,.checkout-layout{grid-template-columns:1fr;gap:16px}.cart-summary,.order-summary{position:static}
            .checkout-form{padding:20px 16px}.footer-grid{grid-template-columns:1fr 1fr;gap:24px}.footer-col:not(.footer-brand){display:block}
        }
        @media(max-width:480px){
            .top-bar{font-size:8px;letter-spacing:.08em}.header-inner{gap:10px}.logo{font-size:21px}.search-bar{min-width:0}
            .header-actions{gap:14px}.nav-inner{overflow-x:auto;justify-content:flex-start}.nav-inner a{font-size:9px;padding:12px 0}
            .product-card-name{font-size:11px}.product-card-body{padding-top:10px}.product-card form{padding-bottom:8px!important}
            .page-title{font-size:22px;margin-top:22px}
            .price-current{font-size:11px}.section-header{gap:8px}.section-header .view-all{white-space:nowrap}
            .sidebar{grid-template-columns:1fr}.product-actions{flex-direction:column}.btn-wa{justify-content:center}
            .cart-table th,.cart-table td{padding:10px 8px}.cart-product{gap:8px}.cart-product img{width:42px;height:42px}
            .footer-grid{grid-template-columns:1fr}.footer-col:not(.footer-brand){display:none}.footer-brand{text-align:center}.footer-social{justify-content:center}
        }
        @media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}*,*::before,*::after{transition-duration:.01ms!important;animation-duration:.01ms!important;animation-iteration-count:1!important}}
    </style>
    <style id="boutique-demo-theme">
        :root{--primary:#321b2a;--accent:#985d70;--accent-dark:#754355;--bg:#fff;--white:#fff;--text:#30272d;--text-light:#756970;--border:#eadfe3;--ink:#321b2a;--muted:#756970;--line:#eadfe3}
        .top-bar{background:#321b2a}
        .logo{color:#321b2a}
        .logo span,.header-inner>a:not(.logo):hover,.header-actions a:hover,nav a:hover,nav a.active{color:#985d70}
        .section-title,.shop-header h1,.product-name{color:#321b2a}
        .section-header .view-all,.product-card-cat{color:#985d70}
        .product-card-name,.price-current,.product-price-current{color:#321b2a}
        .btn-primary,.btn-accent,.btn-add-cart,.cart-badge{background:#321b2a;color:#fff}
        .btn-primary:hover,.btn-accent:hover,.btn-add-cart:hover{background:#754355;color:#fff}
        .btn-outline{border-color:#321b2a;color:#321b2a}
        .cat-icon-placeholder{color:#754355;background:linear-gradient(135deg,#f8f2ef,#ead8dd)}
        .mid-banner{background:#321b2a}
        .sidebar-card h3{color:#321b2a}
        .pagination .page-current{background:#321b2a;border-color:#321b2a}
        .boutique-demo-theme .product-card-img{background:#f4e8e9}
    </style>
</head>
<body>

<div class="top-bar">
    Boutique storefront demo &nbsp;|&nbsp; Sample collection for presentation only
</div>

<header>
    <div class="header-inner">
      <a href="{{ route('home') }}" class="logo">
    @if($siteLogo)
        <img src="{{ asset('storage/'.$siteLogo) }}"
             alt="{{ \App\Models\Setting::get('site_name') }}"
             style="height:44px;object-fit:contain;max-width:200px">
    @else
        {{ \App\Models\Setting::get('site_name', 'My Store') }}
    @endif
</a>
        <div class="search-bar">
            <form action="{{ route('shop.search') }}" method="GET">
                <input type="search" name="q" aria-label="Search products" placeholder="Search products..." value="{{ request('q') }}">
                <button type="submit" aria-label="Submit product search"><i class="fas fa-search"></i></button>
                
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
        <a href="{{ route('home') }}" class="mobile-nav-link">Home</a>
        <a href="{{ route('about') }}" class="mobile-nav-link">About</a>
        <a href="{{ route('contact') }}" class="mobile-nav-link">Contact</a>
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
                <div class="logo">{{ \App\Models\Setting::get('site_name', 'My Store') }}</div>
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
