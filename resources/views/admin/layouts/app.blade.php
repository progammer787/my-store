<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — {{ \App\Models\Setting::get('site_name', 'Store') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        :root{
            --sidebar-w:240px;
            --sidebar-bg:#0d0d1f;
            --sidebar-border:rgba(255,255,255,.07);
            --accent:#c9a84c;
            --accent-glow:rgba(201,168,76,.15);
            --text:#e8e8f0;
            --text-muted:rgba(255,255,255,.35);
            --card:#1a1a2e;
            --card-border:rgba(255,255,255,.08);
            --main-bg:#0f0f20;
            --white:#fff;
            --danger:#ef4444;
            --success:#22c55e;
            --warning:#f59e0b;
            --info:#3b82f6;
        }
        body{font-family:'Segoe UI',system-ui,sans-serif;background:var(--main-bg);color:var(--text);display:flex;min-height:100vh;font-size:14px}

        /* SIDEBAR */
        .sidebar{width:var(--sidebar-w);background:var(--sidebar-bg);border-right:1px solid var(--sidebar-border);display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:100;transition:.3s}
        .sidebar-logo{padding:24px 20px;border-bottom:1px solid var(--sidebar-border)}
        .sidebar-logo a{font-size:20px;font-weight:800;color:var(--white);text-decoration:none;letter-spacing:-0.5px}
        .sidebar-logo a span{color:var(--accent)}
        .sidebar-logo small{display:block;font-size:10px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1.5px;margin-top:2px}
        .sidebar-nav{flex:1;overflow-y:auto;padding:16px 0}
        .nav-section{font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:1.5px;padding:12px 20px 6px}
        .nav-item a{display:flex;align-items:center;gap:12px;padding:11px 20px;color:rgba(255,255,255,.55);font-size:13px;font-weight:500;text-decoration:none;transition:.2s;border-left:3px solid transparent;margin:1px 0}
        .nav-item a i{width:18px;text-align:center;font-size:14px;opacity:.7}
        .nav-item a:hover{color:var(--white);background:rgba(255,255,255,.05);border-left-color:rgba(201,168,76,.4)}
        .nav-item a.active{color:var(--accent);background:var(--accent-glow);border-left-color:var(--accent)}
        .nav-item a.active i{opacity:1}
        .sidebar-footer{padding:16px 20px;border-top:1px solid var(--sidebar-border)}
        .user-info{display:flex;align-items:center;gap:10px;margin-bottom:12px}
        .user-avatar{width:36px;height:36px;border-radius:50%;background:var(--accent-glow);border:1px solid var(--accent);display:flex;align-items:center;justify-content:center;font-size:14px;color:var(--accent);font-weight:700;flex-shrink:0}
        .user-name{font-size:13px;font-weight:600;color:var(--white)}
        .user-role{font-size:11px;color:var(--text-muted);text-transform:capitalize}
        .btn-logout{width:100%;padding:9px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);border-radius:8px;color:#f87171;font-size:12px;cursor:pointer;transition:.2s;text-align:center;display:flex;align-items:center;justify-content:center;gap:8px}
        .btn-logout:hover{background:rgba(239,68,68,.2)}

        /* MAIN */
        .main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}
        .topbar{background:var(--card);border-bottom:1px solid var(--card-border);padding:0 28px;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50}
        .topbar-title{font-size:18px;font-weight:700;color:var(--white)}
        .topbar-actions{display:flex;align-items:center;gap:12px}
        .page-content{padding:28px;flex:1}

        /* CARDS */
        .card{background:var(--card);border:1px solid var(--card-border);border-radius:12px;padding:24px;margin-bottom:24px}
        .card-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
        .card-title{font-size:15px;font-weight:700;color:var(--white)}

        /* STAT CARDS */
        .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:24px}
        .stat-card{background:var(--card);border:1px solid var(--card-border);border-radius:12px;padding:24px;position:relative;overflow:hidden}
        .stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--accent-color,var(--accent))}
        .stat-icon{width:44px;height:44px;border-radius:10px;background:var(--icon-bg,var(--accent-glow));display:flex;align-items:center;justify-content:center;font-size:18px;color:var(--accent-color,var(--accent));margin-bottom:14px}
        .stat-value{font-size:28px;font-weight:800;color:var(--white);margin-bottom:4px}
        .stat-label{font-size:12px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px}

        /* TABLE */
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse}
        th{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted);padding:12px 16px;text-align:left;border-bottom:1px solid var(--card-border)}
        td{padding:13px 16px;border-bottom:1px solid rgba(255,255,255,.04);color:var(--text);font-size:13px;vertical-align:middle}
        tr:last-child td{border-bottom:none}
        tr:hover td{background:rgba(255,255,255,.02)}

        /* BADGES */
        .badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;letter-spacing:.3px}
        .badge-pending{background:rgba(245,158,11,.15);color:#fbbf24}
        .badge-confirmed{background:rgba(59,130,246,.15);color:#60a5fa}
        .badge-processing{background:rgba(168,85,247,.15);color:#c084fc}
        .badge-shipped{background:rgba(20,184,166,.15);color:#2dd4bf}
        .badge-delivered{background:rgba(34,197,94,.15);color:#4ade80}
        .badge-cancelled{background:rgba(239,68,68,.15);color:#f87171}
        .badge-active{background:rgba(34,197,94,.15);color:#4ade80}
        .badge-inactive{background:rgba(239,68,68,.15);color:#f87171}

        /* BUTTONS */
        .btn{display:inline-flex;align-items:center;gap:8px;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:.2s;border:none;text-decoration:none}
        .btn-primary{background:var(--accent);color:#fff}
        .btn-primary:hover{background:#a8893a;color:#fff}
        .btn-sm{padding:6px 12px;font-size:12px}
        .btn-danger{background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.2)}
        .btn-danger:hover{background:rgba(239,68,68,.3)}
        .btn-secondary{background:rgba(255,255,255,.08);color:var(--text);border:1px solid var(--card-border)}
        .btn-secondary:hover{background:rgba(255,255,255,.12)}

        /* FORMS */
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
        .form-group{margin-bottom:20px}
        .form-group label{display:block;font-size:12px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px}
        .form-group input,.form-group select,.form-group textarea{width:100%;padding:11px 14px;background:rgba(255,255,255,.05);border:1px solid var(--card-border);border-radius:8px;color:var(--text);font-size:14px;font-family:inherit;outline:none;transition:.2s}
        .form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:var(--accent);background:rgba(201,168,76,.05)}
        .form-group input::placeholder,.form-group textarea::placeholder{color:var(--text-muted)}
        .form-group select option{background:#1a1a2e}
        .form-check{display:flex;align-items:center;gap:10px;cursor:pointer}
        .form-check input{accent-color:var(--accent);width:16px;height:16px}
        .form-check label{font-size:13px;color:var(--text);cursor:pointer;text-transform:none;letter-spacing:0}

        /* SEARCH */
        .search-wrap{position:relative}
        .search-wrap i{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px}
        .search-wrap input{padding-left:36px}

        /* IMG PREVIEW */
        .img-preview{width:80px;height:80px;border-radius:8px;object-fit:cover;border:1px solid var(--card-border)}

        /* FLASH */
        .alert{padding:14px 18px;border-radius:8px;margin-bottom:20px;font-size:13px;display:flex;align-items:center;gap:10px}
        .alert-success{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.2);color:#4ade80}
        .alert-error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);color:#f87171}

        @media(max-width:900px){
            .sidebar{transform:translateX(-100%)}
            .sidebar.open{transform:translateX(0)}
            .main{margin-left:0}
            .stats-grid{grid-template-columns:1fr 1fr}
        }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="{{ route('admin.dashboard') }}">{{ \App\Models\Setting::get('site_name','My') }}<span>Admin</span></a>
        <small>Management Panel</small>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
        </div>

        <div class="nav-section">Catalog</div>
        <div class="nav-item">
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> Products
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Categories
            </a>
        </div>

        <div class="nav-section">Sales</div>
        <div class="nav-item">
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i> Orders
            </a>
        </div>

        <div class="nav-item">
    <a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
        <i class="fas fa-star"></i> Reviews
    </a>
</div>

        <div class="nav-section">Design</div>
        <div class="nav-item">
            <a href="{{ route('admin.banners.index') }}" class="{{ request()->routeIs('admin.banners*') ? 'active' : '' }}">
                <i class="fas fa-image"></i> Banners
            </a>
        </div>

        <div class="nav-section">System</div>
        <div class="nav-item">
            <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('home') }}" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Store
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ auth()->user()->role }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-actions">
            <span style="font-size:12px;color:var(--text-muted)">{{ now()->format('D, d M Y') }}</span>
        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

@stack('scripts')
</body>
</html>