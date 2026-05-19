<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ThemeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThemeAdminController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->hasAccess('settings'), 403);
        $theme   = ThemeService::get();
        $presets = ThemeService::presets();
        return view('admin.theme.index', compact('theme', 'presets'));
    }

    public function save(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('settings'), 403);

        $keys = [
            'theme_primary_color', 'theme_accent_color', 'theme_bg_color',
            'theme_text_color', 'theme_border_color', 'theme_body_font',
            'theme_heading_font', 'theme_font_size', 'theme_border_radius',
            'theme_button_style', 'theme_header_style', 'theme_layout_width',
            'theme_preset', 'logo_width',
        ];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key, ''), 'text', 'theme');
        }

        if ($request->hasFile('site_logo')) {
            $old = Setting::get('site_logo');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('site_logo')->store('brand', 'public');
            Setting::set('site_logo', $path, 'image', 'theme');
        }

        if ($request->hasFile('site_favicon')) {
            $old = Setting::get('site_favicon');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('site_favicon')->store('brand', 'public');
            Setting::set('site_favicon', $path, 'image', 'theme');
        }

        ThemeService::clearCache();

        \Illuminate\Support\Facades\Artisan::call('view:clear');
         return back()->with('success', 'Theme saved successfully!');
    }

    public function applyPreset(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('settings'), 403);

        $presets = ThemeService::presets();
        $preset  = $presets[$request->preset] ?? null;
        if (!$preset) return response()->json(['error' => 'Not found'], 404);

        Setting::set('theme_primary_color', $preset['primary'], 'text', 'theme');
        Setting::set('theme_accent_color',  $preset['accent'],  'text', 'theme');
        Setting::set('theme_bg_color',      $preset['bg'],      'text', 'theme');
        Setting::set('theme_text_color',    $preset['text'],    'text', 'theme');
        Setting::set('theme_border_color',  $preset['border'],  'text', 'theme');
        Setting::set('theme_preset',        $request->preset,   'text', 'theme');

        ThemeService::clearCache();

        return response()->json(['success' => true, 'preset' => $preset]);
    }

    public function removeLogo(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('settings'), 403);

        $key = $request->input('type') === 'favicon' ? 'site_favicon' : 'site_logo';
        $old = Setting::get($key);
        if ($old) Storage::disk('public')->delete($old);
        Setting::set($key, '', 'image', 'theme');
        ThemeService::clearCache();

        return back()->with('success', 'Removed successfully.');
    }
}