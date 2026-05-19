<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('shop.contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:100',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        // Build WhatsApp message
        $waNum = Setting::get('whatsapp_number', '');
        $waNum = preg_replace('/[^0-9]/', '', $waNum);

        $msg = "📩 *New Contact Message*\n\n";
        $msg .= "👤 *Name:* {$request->name}\n";
        $msg .= "📧 *Email:* {$request->email}\n";
        if ($request->phone) $msg .= "📞 *Phone:* {$request->phone}\n";
        if ($request->subject) $msg .= "📌 *Subject:* {$request->subject}\n";
        $msg .= "\n💬 *Message:*\n{$request->message}";

        $waUrl = "https://wa.me/{$waNum}?text=" . urlencode($msg);

        return redirect()->route('contact')->with([
            'success' => 'Message sent! We will get back to you soon.',
            'wa_url'  => $waUrl,
        ]);
    }
}