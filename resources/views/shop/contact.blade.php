@extends('shop.layouts.app')
@section('title', 'Contact Us — ' . \App\Models\Setting::get('site_name', 'My Store'))

@push('styles')
<style>
.contact-hero { background: linear-gradient(135deg, var(--primary) 0%, #2a2a3e 100%); padding: 60px 0; text-align: center; color: white; }
.contact-hero h1 { font-size: 42px; font-weight: 700; margin-bottom: 12px; }
.contact-hero p  { font-size: 16px; opacity: 0.8; }

.contact-section { padding: 80px 0; }
.contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: start; }

/* Info cards */
.contact-info { display: flex; flex-direction: column; gap: 20px; }
.contact-card { background: white; border: 1px solid var(--border); border-radius: 14px; padding: 24px; display: flex; align-items: flex-start; gap: 18px; transition: all 0.25s; }
.contact-card:hover { border-color: var(--accent); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
.contact-card-icon { width: 52px; height: 52px; border-radius: 12px; background: linear-gradient(135deg, var(--accent), #e8c97e); display: flex; align-items: center; justify-content: center; font-size: 22px; color: white; flex-shrink: 0; }
.contact-card-body h4 { font-size: 15px; font-weight: 700; color: var(--primary); margin-bottom: 6px; }
.contact-card-body p, .contact-card-body a { font-size: 14px; color: #666; line-height: 1.7; display: block; }
.contact-card-body a:hover { color: var(--accent); }

/* WhatsApp big button */
.wa-big-btn { background: #25d366; color: white; border-radius: 14px; padding: 20px 24px; display: flex; align-items: center; gap: 16px; transition: all 0.25s; }
.wa-big-btn:hover { background: #1ebe5b; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(37,211,102,0.3); color: white; }
.wa-big-btn i { font-size: 32px; }
.wa-big-btn-text strong { display: block; font-size: 16px; font-weight: 700; margin-bottom: 3px; }
.wa-big-btn-text span { font-size: 13px; opacity: 0.9; }

/* Form */
.contact-form-card { background: white; border: 1px solid var(--border); border-radius: 16px; padding: 36px; }
.contact-form-card h2 { font-size: 24px; font-weight: 700; color: var(--primary); margin-bottom: 6px; }
.contact-form-card p  { font-size: 14px; color: #888; margin-bottom: 28px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-field { margin-bottom: 18px; }
.form-field label { display: block; font-size: 12px; font-weight: 700; color: #888; margin-bottom: 7px; text-transform: uppercase; letter-spacing: 0.5px; }
.form-field input, .form-field textarea, .form-field select {
    width: 100%; border: 1.5px solid var(--border); border-radius: 10px;
    padding: 12px 16px; font-size: 14px; font-family: var(--font-body);
    outline: none; transition: all 0.2s; background: #fafafa;
}
.form-field input:focus, .form-field textarea:focus { border-color: var(--accent); background: white; box-shadow: 0 0 0 3px rgba(200,169,110,0.1); }
.form-field textarea { height: 130px; resize: vertical; }
.form-field.full { grid-column: 1 / -1; }
.submit-btn { width: 100%; padding: 14px; background: var(--primary); color: white; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; transition: all 0.25s; font-family: var(--font-body); display: flex; align-items: center; justify-content: center; gap: 10px; }
.submit-btn:hover { background: var(--accent); transform: translateY(-1px); }

/* Success message */
.contact-success { background: #edf7f2; border: 1px solid #bbf7d0; border-radius: 10px; padding: 16px 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; color: #166534; font-size: 14px; font-weight: 500; }

/* Map */
.map-section { padding: 0 0 80px; }
.map-wrap { border-radius: 16px; overflow: hidden; border: 1px solid var(--border); height: 400px; background: var(--border); display: flex; align-items: center; justify-content: center; }
.map-wrap iframe { width: 100%; height: 100%; border: none; }
.map-placeholder { text-align: center; color: #888; }
.map-placeholder i { font-size: 48px; margin-bottom: 12px; color: var(--accent); }

@media(max-width:900px) {
    .contact-grid { grid-template-columns: 1fr; }
    .form-row { grid-template-columns: 1fr; }
    .contact-hero h1 { font-size: 30px; }
}
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="contact-hero">
    <div class="container">
        <h1>Get In Touch</h1>
        <p>We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="contact-grid">

            {{-- LEFT — Contact Info --}}
            <div class="contact-info">

                {{-- WhatsApp Big Button --}}
                @if(\App\Models\Setting::get('whatsapp_number'))
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp_number')) }}"
                   target="_blank" class="wa-big-btn">
                    <i class="fab fa-whatsapp"></i>
                    <div class="wa-big-btn-text">
                        <strong>Chat on WhatsApp</strong>
                        <span>{{ \App\Models\Setting::get('whatsapp_number') }} — We reply instantly</span>
                    </div>
                </a>
                @endif

                {{-- Phone --}}
                @if(\App\Models\Setting::get('site_phone'))
                <div class="contact-card">
                    <div class="contact-card-icon"><i class="fas fa-phone"></i></div>
                    <div class="contact-card-body">
                        <h4>Phone</h4>
                        <a href="tel:{{ \App\Models\Setting::get('site_phone') }}">{{ \App\Models\Setting::get('site_phone') }}</a>
                        <p style="font-size:12px;margin-top:4px;color:#aaa">Mon–Sat, 9am to 8pm</p>
                    </div>
                </div>
                @endif

                {{-- Email --}}
                @if(\App\Models\Setting::get('site_email'))
                <div class="contact-card">
                    <div class="contact-card-icon"><i class="fas fa-envelope"></i></div>
                    <div class="contact-card-body">
                        <h4>Email</h4>
                        <a href="mailto:{{ \App\Models\Setting::get('site_email') }}">{{ \App\Models\Setting::get('site_email') }}</a>
                        <p style="font-size:12px;margin-top:4px;color:#aaa">We reply within 24 hours</p>
                    </div>
                </div>
                @endif

                {{-- Address --}}
                @if(\App\Models\Setting::get('site_address'))
                <div class="contact-card">
                    <div class="contact-card-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="contact-card-body">
                        <h4>Address</h4>
                        <p>{{ \App\Models\Setting::get('site_address') }}</p>
                    </div>
                </div>
                @endif

                {{-- Business Hours --}}
                <div class="contact-card">
                    <div class="contact-card-icon"><i class="fas fa-clock"></i></div>
                    <div class="contact-card-body">
                        <h4>Business Hours</h4>
                        <p>Monday – Saturday: 9:00 AM – 8:00 PM</p>
                        <p>Sunday: 11:00 AM – 5:00 PM</p>
                    </div>
                </div>

                {{-- Social --}}
                <div style="display:flex;gap:12px;padding:8px 0">
                    @if(\App\Models\Setting::get('facebook_url'))
                    <a href="{{ \App\Models\Setting::get('facebook_url') }}" target="_blank"
                       style="width:44px;height:44px;background:var(--primary);color:white;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;transition:all 0.25s"
                       onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--primary)'">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    @endif
                    @if(\App\Models\Setting::get('instagram_url'))
                    <a href="{{ \App\Models\Setting::get('instagram_url') }}" target="_blank"
                       style="width:44px;height:44px;background:var(--primary);color:white;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;transition:all 0.25s"
                       onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--primary)'">
                        <i class="fab fa-instagram"></i>
                    </a>
                    @endif
                    @if(\App\Models\Setting::get('twitter_url'))
                    <a href="{{ \App\Models\Setting::get('twitter_url') }}" target="_blank"
                       style="width:44px;height:44px;background:var(--primary);color:white;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;transition:all 0.25s"
                       onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--primary)'">
                        <i class="fab fa-twitter"></i>
                    </a>
                    @endif
                </div>
            </div>

            {{-- RIGHT — Contact Form --}}
            <div class="contact-form-card">
                <h2>Send Us a Message</h2>
                <p>Fill the form below and we'll get back to you shortly.</p>

                @if(session('success'))
                <div class="contact-success">
                    <i class="fas fa-check-circle" style="font-size:20px"></i>
                    {{ session('success') }}
                </div>
                @if(session('wa_url'))
                <a href="{{ session('wa_url') }}" target="_blank"
                   style="display:flex;align-items:center;gap:10px;background:#25d366;color:white;padding:12px 18px;border-radius:10px;font-weight:600;margin-bottom:20px;transition:all 0.25s">
                    <i class="fab fa-whatsapp" style="font-size:20px"></i> Open WhatsApp to send message
                </a>
                @endif
                @endif

                @if($errors->any())
                <div style="background:#fdf2f2;border:1px solid #fecaca;border-radius:10px;padding:14px 18px;margin-bottom:20px;color:#c62828;font-size:14px">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-field">
                            <label>Your Name *</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required>
                        </div>
                        <div class="form-field">
                            <label>Email Address *</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@email.com" required>
                        </div>
                        <div class="form-field">
                            <label>Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="03XX XXXXXXX">
                        </div>
                        <div class="form-field">
                            <label>Subject</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Order inquiry">
                        </div>
                        <div class="form-field full">
                            <label>Message *</label>
                            <textarea name="message" placeholder="Tell us how we can help you..." required>{{ old('message') }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- MAP --}}
@if(\App\Models\Setting::get('google_maps_embed'))
<section class="map-section">
    <div class="container">
        <h2 class="section-title" style="margin-bottom:24px">Find <span>Us</span></h2>
        <div class="map-wrap">
            {!! \App\Models\Setting::get('google_maps_embed') !!}
        </div>
    </div>
</section>
@endif

@endsection