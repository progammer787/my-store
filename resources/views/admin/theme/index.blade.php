@extends('admin.layouts.app')
@section('title', 'Theme & Branding')

@push('styles')
<style>
.theme-layout { display: grid; grid-template-columns: 1fr 380px; gap: 24px; align-items: start; }
.section-title { font-size: 13px; font-weight: 700; color: rgba(255,255,255,.4); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1px solid var(--card-border); }

/* Preset cards */
.presets-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 24px; }
.preset-card { border: 2px solid var(--card-border); border-radius: 10px; padding: 12px; cursor: pointer; transition: .2s; text-align: center; }
.preset-card:hover { border-color: var(--accent); }
.preset-card.active { border-color: var(--accent); background: var(--accent-glow); }
.preset-swatches { display: flex; gap: 4px; justify-content: center; margin-bottom: 8px; }
.preset-swatch { width: 18px; height: 18px; border-radius: 50%; border: 1px solid rgba(255,255,255,.1); }
.preset-label { font-size: 12px; font-weight: 600; color: #fff; }

/* Color pickers */
.color-row { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
.color-preview { width: 40px; height: 40px; border-radius: 8px; border: 1px solid var(--card-border); cursor: pointer; flex-shrink: 0; transition: .2s; }
.color-preview:hover { transform: scale(1.1); }
.color-info { flex: 1; }
.color-label { font-size: 12px; color: rgba(255,255,255,.5); margin-bottom: 4px; }
.color-hex { font-size: 13px; font-weight: 600; color: #fff; font-family: monospace; }
.color-input-wrap { position: relative; }
.color-input-wrap input[type=color] { position: absolute; opacity: 0; width: 40px; height: 40px; cursor: pointer; top: 0; left: 0; border: none; padding: 0; }

/* Font selector */
.font-option { padding: 10px 14px; border: 1px solid var(--card-border); border-radius: 8px; cursor: pointer; transition: .2s; margin-bottom: 8px; }
.font-option:hover, .font-option.selected { border-color: var(--accent); background: var(--accent-glow); }
.font-option span { font-size: 13px; color: #fff; }
.font-preview { font-size: 18px; color: rgba(255,255,255,.6); float: right; }

/* Slider */
.range-wrap { margin-bottom: 16px; }
.range-label { display: flex; justify-content: space-between; font-size: 12px; color: rgba(255,255,255,.5); margin-bottom: 6px; }
.range-label strong { color: #fff; }
input[type=range] { width: 100%; accent-color: var(--accent); height: 4px; cursor: pointer; }

/* Button style selector */
.btn-style-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; margin-bottom: 16px; }
.btn-style-opt { border: 2px solid var(--card-border); padding: 10px; text-align: center; cursor: pointer; transition: .2s; }
.btn-style-opt:hover, .btn-style-opt.selected { border-color: var(--accent); }
.btn-style-opt span { font-size: 12px; font-weight: 600; color: rgba(255,255,255,.6); display: block; margin-top: 6px; }
.demo-btn { display: inline-block; background: var(--accent); color: #000; padding: 6px 16px; font-size: 12px; font-weight: 600; }

/* Live preview */
.preview-wrap { background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid var(--card-border); }
.preview-header { padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #eee; }
.preview-logo { font-size: 18px; font-weight: 700; }
.preview-nav { display: flex; gap: 16px; }
.preview-nav a { font-size: 12px; color: #666; text-decoration: none; }
.preview-hero { padding: 40px 20px; text-align: center; }
.preview-hero h1 { font-size: 28px; font-weight: 700; margin-bottom: 10px; }
.preview-hero p { font-size: 14px; color: #666; margin-bottom: 20px; }
.preview-btn { display: inline-block; padding: 10px 24px; font-size: 13px; font-weight: 600; color: white; text-decoration: none; }
.preview-products { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; padding: 0 20px 20px; }
.preview-product { border: 1px solid #eee; border-radius: 8px; overflow: hidden; }
.preview-product-img { height: 60px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.preview-product-body { padding: 8px; }
.preview-product-name { font-size: 11px; font-weight: 600; margin-bottom: 4px; }
.preview-product-price { font-size: 13px; font-weight: 700; }

/* Logo upload */
.logo-upload-area { border: 2px dashed var(--card-border); border-radius: 10px; padding: 24px; text-align: center; cursor: pointer; transition: .2s; margin-bottom: 14px; }
.logo-upload-area:hover { border-color: var(--accent); background: var(--accent-glow); }
.logo-preview-img { max-width: 200px; max-height: 80px; object-fit: contain; margin: 0 auto 12px; display: block; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Theme & Branding</h1>
    <a href="{{ route('home') }}" target="_blank" class="btn btn-outline btn-sm">
        <i class="fas fa-eye"></i> View Store
    </a>
</div>

<form action="{{ route('admin.theme.save') }}" method="POST" enctype="multipart/form-data" id="themeForm">
@csrf

<div class="theme-layout">

    {{-- LEFT COLUMN --}}
    <div>

        {{-- PRESETS --}}
        <div class="card">
            <div class="section-title">Quick Presets</div>
            <div class="presets-grid">
                @foreach($presets as $key => $preset)
                <div class="preset-card {{ ($theme['theme_preset'] ?? 'luxury') === $key ? 'active' : '' }}"
                     onclick="applyPreset('{{ $key }}')" data-preset="{{ $key }}">
                    <div class="preset-swatches">
                        <div class="preset-swatch" style="background:{{ $preset['primary'] }}"></div>
                        <div class="preset-swatch" style="background:{{ $preset['accent'] }}"></div>
                        <div class="preset-swatch" style="background:{{ $preset['bg'] }}"></div>
                    </div>
                    <div class="preset-label">{{ $preset['label'] }}</div>
                </div>
                @endforeach
            </div>
            <input type="hidden" name="theme_preset" id="theme_preset" value="{{ $theme['theme_preset'] ?? 'luxury' }}">
        </div>

        {{-- COLORS --}}
        <div class="card">
            <div class="section-title">Colors</div>

            @php
            $colorFields = [
                ['key' => 'theme_primary_color', 'label' => 'Primary Color', 'desc' => 'Main buttons, text, nav'],
                ['key' => 'theme_accent_color',  'label' => 'Accent Color',  'desc' => 'Highlights, sale badges, links'],
                ['key' => 'theme_bg_color',      'label' => 'Background',    'desc' => 'Page background color'],
                ['key' => 'theme_text_color',    'label' => 'Text Color',    'desc' => 'Body text color'],
                ['key' => 'theme_border_color',  'label' => 'Border Color',  'desc' => 'Card borders, dividers'],
            ];
            @endphp

            @foreach($colorFields as $field)
            <div class="color-row">
                <div class="color-input-wrap">
                    <div class="color-preview" id="preview_{{ $field['key'] }}"
                         style="background: {{ $theme[$field['key']] ?? '#000000' }}"
                         onclick="document.getElementById('input_{{ $field['key'] }}').click()">
                    </div>
                    <input type="color" id="input_{{ $field['key'] }}"
                           name="{{ $field['key'] }}"
                           value="{{ $theme[$field['key']] ?? '#000000' }}"
                           oninput="syncColor('{{ $field['key'] }}', this.value)">
                </div>
                <div class="color-info">
                    <div class="color-label">{{ $field['label'] }} — <small>{{ $field['desc'] }}</small></div>
                    <div class="color-hex" id="hex_{{ $field['key'] }}">{{ $theme[$field['key']] ?? '#000000' }}</div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- TYPOGRAPHY --}}
        <div class="card">
            <div class="section-title">Typography</div>

            <div style="margin-bottom:16px">
                <div class="color-label" style="margin-bottom:8px">Body Font</div>
                <select name="theme_body_font" id="bodyFont" onchange="updateFontPreview()" style="width:100%;background:rgba(255,255,255,.05);border:1px solid var(--card-border);border-radius:8px;padding:10px 14px;color:#fff;font-size:14px;outline:none">
                    @foreach(['DM Sans','Inter','Poppins','Nunito','Roboto','Lato','Open Sans','Raleway','Montserrat'] as $font)
                        <option value="{{ $font }}" style="background:#1a1a2e" {{ ($theme['theme_body_font'] ?? 'DM Sans') === $font ? 'selected' : '' }}>{{ $font }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:16px">
                <div class="color-label" style="margin-bottom:8px">Heading Font</div>
                <select name="theme_heading_font" id="headingFont" onchange="updateFontPreview()" style="width:100%;background:rgba(255,255,255,.05);border:1px solid var(--card-border);border-radius:8px;padding:10px 14px;color:#fff;font-size:14px;outline:none">
                    @foreach(['Playfair Display','Cormorant Garamond','Merriweather','Lora','DM Serif Display','Syne','Space Grotesk','Bebas Neue'] as $font)
                        <option value="{{ $font }}" style="background:#1a1a2e" {{ ($theme['theme_heading_font'] ?? 'Playfair Display') === $font ? 'selected' : '' }}>{{ $font }}</option>
                    @endforeach
                </select>
            </div>

            <div class="range-wrap">
                <div class="range-label">
                    <span>Base Font Size</span>
                    <strong id="fontSizeVal">{{ $theme['theme_font_size'] ?? '15' }}px</strong>
                </div>
                <input type="range" name="theme_font_size" min="12" max="20" step="1"
                       value="{{ $theme['theme_font_size'] ?? '15' }}"
                       oninput="document.getElementById('fontSizeVal').textContent = this.value + 'px'">
            </div>
        </div>

        {{-- LAYOUT --}}
        <div class="card">
            <div class="section-title">Layout & Style</div>

            <div class="range-wrap">
                <div class="range-label">
                    <span>Border Radius</span>
                    <strong id="radiusVal">{{ $theme['theme_border_radius'] ?? '10' }}px</strong>
                </div>
                <input type="range" name="theme_border_radius" min="0" max="30" step="1"
                       value="{{ $theme['theme_border_radius'] ?? '10' }}"
                       oninput="document.getElementById('radiusVal').textContent = this.value + 'px'; updatePreviewRadius(this.value)">
            </div>

            <div class="range-wrap">
                <div class="range-label">
                    <span>Container Width</span>
                    <strong id="widthVal">{{ $theme['theme_layout_width'] ?? '1320' }}px</strong>
                </div>
                <input type="range" name="theme_layout_width" min="900" max="1800" step="60"
                       value="{{ $theme['theme_layout_width'] ?? '1320' }}"
                       oninput="document.getElementById('widthVal').textContent = this.value + 'px'">
            </div>

            {{-- Button Style --}}
            <div class="color-label" style="margin-bottom:10px">Button Style</div>
            <div class="btn-style-grid">
                @foreach(['rounded' => 'Rounded', 'pill' => 'Pill', 'square' => 'Square'] as $val => $label)
                <div class="btn-style-opt {{ ($theme['theme_button_style'] ?? 'rounded') === $val ? 'selected' : '' }}"
                     onclick="selectBtnStyle('{{ $val }}', this)"
                     style="border-radius: {{ $val === 'rounded' ? '8px' : ($val === 'pill' ? '50px' : '2px') }}">
                    <div class="demo-btn" style="border-radius: {{ $val === 'rounded' ? '8px' : ($val === 'pill' ? '50px' : '2px') }}">Button</div>
                    <span>{{ $label }}</span>
                </div>
                @endforeach
            </div>
            <input type="hidden" name="theme_button_style" id="btn_style" value="{{ $theme['theme_button_style'] ?? 'rounded' }}">

            {{-- Header Style --}}
            <div class="color-label" style="margin-bottom:10px;margin-top:16px">Header Style</div>
            <div class="btn-style-grid">
                @foreach(['light' => 'Light', 'dark' => 'Dark', 'transparent' => 'Glass'] as $val => $label)
                <div class="btn-style-opt {{ ($theme['theme_header_style'] ?? 'light') === $val ? 'selected' : '' }}"
                     onclick="selectHeaderStyle('{{ $val }}', this)">
                    <span>{{ $label }}</span>
                </div>
                @endforeach
            </div>
            <input type="hidden" name="theme_header_style" id="header_style" value="{{ $theme['theme_header_style'] ?? 'light' }}">
        </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div>

        {{-- LIVE PREVIEW --}}
        <div class="card" style="position:sticky;top:80px">
            <div class="section-title">Live Preview</div>

            <div class="preview-wrap" id="livePreview">
                <div class="preview-header" id="previewHeader" style="background:#fff">
                    <div class="preview-logo" id="previewLogo" style="font-family:'Playfair Display',serif">
                        @if(Setting::get('site_logo'))
                            <img src="{{ asset('storage/'.Setting::get('site_logo')) }}" style="height:32px;object-fit:contain">
                        @else
                            {{ Setting::get('site_name','My') }}<span id="previewLogoSpan" style="color:#c8a96e">Store</span>
                        @endif
                    </div>
                    <div class="preview-nav">
                        <a href="#">Shop</a>
                        <a href="#">About</a>
                    </div>
                </div>
                <div class="preview-hero" id="previewHero" style="background:#faf9f7">
                    <h1 id="previewH1" style="font-family:'Playfair Display',serif;color:#1a1a1a">Welcome to Our Store</h1>
                    <p style="color:#888">Discover amazing products at great prices</p>
                    <a href="#" class="preview-btn" id="previewBtn" style="background:#c8a96e;border-radius:10px">Shop Now</a>
                </div>
                <div class="preview-products">
                    @foreach(['👟','👜','⌚'] as $emoji)
                    <div class="preview-product" id="previewCard{{ $loop->index }}">
                        <div class="preview-product-img">{{ $emoji }}</div>
                        <div class="preview-product-body">
                            <div class="preview-product-name">Product Name</div>
                            <div class="preview-product-price" id="previewPrice{{ $loop->index }}" style="color:#1a1a1a">Rs. 2,500</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- LOGO UPLOAD --}}
            <div style="margin-top:20px">
                <div class="section-title">Logo & Favicon</div>

                {{-- Logo --}}
                <div class="color-label" style="margin-bottom:8px">Store Logo</div>
                @if(Setting::get('site_logo'))
                    <img src="{{ asset('storage/'.Setting::get('site_logo')) }}"
                         class="logo-preview-img" id="logoPreview">
                    <form action="{{ route('admin.theme.remove-logo') }}" method="POST" style="margin-bottom:10px">
                        @csrf <input type="hidden" name="type" value="logo">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Remove logo?')">
                            <i class="fas fa-trash"></i> Remove Logo
                        </button>
                    </form>
                @else
                    <div class="logo-upload-area" onclick="document.getElementById('logoInput').click()">
                        <i class="fas fa-cloud-upload-alt" style="font-size:24px;color:var(--accent);margin-bottom:8px"></i>
                        <div id="logoPreviewWrap" style="display:none;margin-bottom:10px">
                            <img id="logoPreview" style="max-width:180px;max-height:70px;object-fit:contain;margin:0 auto;display:block">
                        </div>
                        <p style="font-size:13px;color:rgba(255,255,255,.4)">Click to upload logo<br><small>PNG, SVG recommended — max 2MB</small></p>
                    </div>
                @endif
                <input type="file" name="site_logo" id="logoInput" accept="image/*" style="display:none"
                       onchange="previewFile(this, 'logoPreview', 'logoPreviewWrap')">

                <div class="range-wrap" style="margin-top:12px">
                    <div class="range-label">
                        <span>Logo Width</span>
                        <strong id="logoWidthVal">{{ $theme['logo_width'] ?? '160' }}px</strong>
                    </div>
                    <input type="range" name="logo_width" min="60" max="320" step="10"
                           value="{{ $theme['logo_width'] ?? '160' }}"
                           oninput="document.getElementById('logoWidthVal').textContent = this.value + 'px'">
                </div>

                {{-- Favicon --}}
                <div class="color-label" style="margin-bottom:8px;margin-top:16px">Favicon</div>
                @if(Setting::get('site_favicon'))
                    <img src="{{ asset('storage/'.Setting::get('site_favicon')) }}"
                         style="width:32px;height:32px;object-fit:contain;margin-bottom:8px">
                    <form action="{{ route('admin.theme.remove-logo') }}" method="POST" style="margin-bottom:10px">
                        @csrf <input type="hidden" name="type" value="favicon">
                        <button type="submit" class="btn btn-danger btn-sm">Remove Favicon</button>
                    </form>
                @else
                    <div class="logo-upload-area" onclick="document.getElementById('faviconInput').click()">
                        <i class="fas fa-image" style="font-size:20px;color:var(--accent);margin-bottom:6px"></i>
                        <p style="font-size:12px;color:rgba(255,255,255,.4)">Upload favicon (32×32 px)<br>ICO or PNG</p>
                    </div>
                @endif
                <input type="file" name="site_favicon" id="faviconInput" accept="image/*" style="display:none">
            </div>

            {{-- SAVE --}}
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:14px;margin-top:20px;font-size:15px">
                <i class="fas fa-save"></i> Save Theme
            </button>
        </div>

    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
// Sync color picker → preview
function syncColor(key, value) {
    document.getElementById('preview_' + key).style.background = value;
    document.getElementById('hex_' + key).textContent = value;
    updateLivePreview();
}

// Update live preview
function updateLivePreview() {
    const primary = document.querySelector('[name="theme_primary_color"]').value;
    const accent  = document.querySelector('[name="theme_accent_color"]').value;
    const bg      = document.querySelector('[name="theme_bg_color"]').value;
    const text    = document.querySelector('[name="theme_text_color"]').value;
    const radius  = document.querySelector('[name="theme_border_radius"]').value;
    const btnStyle= document.getElementById('btn_style').value;
    const heading = document.querySelector('[name="theme_heading_font"]').value;

    const btnRadius = btnStyle === 'pill' ? '50px' : btnStyle === 'square' ? '2px' : radius + 'px';

    document.getElementById('previewHero').style.background = bg;
    document.getElementById('previewH1').style.color = text;
    document.getElementById('previewH1').style.fontFamily = heading + ', serif';
    document.getElementById('previewBtn').style.background = accent;
    document.getElementById('previewBtn').style.borderRadius = btnRadius;
    document.getElementById('previewLogoSpan') && (document.getElementById('previewLogoSpan').style.color = accent);

    // Cards
    for (let i = 0; i < 3; i++) {
        const card = document.getElementById('previewCard' + i);
        if (card) card.style.borderRadius = (radius/2) + 'px';
        const price = document.getElementById('previewPrice' + i);
        if (price) price.style.color = primary;
    }
}

function updatePreviewRadius(val) { updateLivePreview(); }

// Button style selector
function selectBtnStyle(val, el) {
    document.getElementById('btn_style').value = val;
    document.querySelectorAll('.btn-style-opt').forEach(e => {
        if (e.parentElement === el.parentElement) e.classList.remove('selected');
    });
    el.classList.add('selected');
    updateLivePreview();
}

// Header style selector
function selectHeaderStyle(val, el) {
    document.getElementById('header_style').value = val;
    const siblings = el.parentElement.querySelectorAll('.btn-style-opt');
    siblings.forEach(e => e.classList.remove('selected'));
    el.classList.add('selected');
}

// Apply preset
function applyPreset(key) {
    const presets = @json($presets);
    const p = presets[key];
    if (!p) return;

    // Update color inputs + previews
    const map = {
        'theme_primary_color': p.primary,
        'theme_accent_color':  p.accent,
        'theme_bg_color':      p.bg,
        'theme_text_color':    p.text,
        'theme_border_color':  p.border,
    };

    for (const [name, val] of Object.entries(map)) {
        const inp = document.querySelector('[name="' + name + '"]');
        if (inp) { inp.value = val; syncColor(name, val); }
    }

    document.getElementById('theme_preset').value = key;
    document.querySelectorAll('.preset-card').forEach(c => c.classList.remove('active'));
    document.querySelector('[data-preset="' + key + '"]')?.classList.add('active');

    // Also save to server
    fetch('{{ route("admin.theme.preset") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ preset: key })
    });
}

// Font preview
function updateFontPreview() {
    const heading = document.querySelector('[name="theme_heading_font"]').value;
    const previewEl = document.getElementById('previewH1');
    if (previewEl) previewEl.style.fontFamily = heading + ', serif';
}

// File preview
function previewFile(input, imgId, wrapId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById(imgId);
            if (img) { img.src = e.target.result; img.style.display = 'block'; }
            const wrap = document.getElementById(wrapId);
            if (wrap) wrap.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Init
updateLivePreview();
</script>
@endpush
