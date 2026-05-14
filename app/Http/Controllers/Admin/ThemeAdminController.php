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

        $request->validate([
            'theme_primary_color' => 'required|string|max:20',
            'theme_accent_color'  => 'required|string|max:20',
            'theme_bg_color'      => 'required|string|max:20',
            'theme_text_color'    => 'required|string|max:20',
            'theme_border_color'  => 'required|string|max:20',
            'theme_body_font'     => 'required|string|max:100',
            'theme_heading_font'  => 'required|string|max:100',
            'theme_font_size'     => 'required|integer|min:12|max:20',
            'theme_border_radius' => 'required|integer|min:0|max:30',
            'theme_button_style'  => 'required|in:rounded,pill,square',
            'theme_header_style'  => 'required|in:light,dark,transparent',
            'theme_layout_width'  => 'required|integer|min:900|max:1800',
            'theme_preset'        => 'nullable|string|max:50',
            'logo_width'          => 'nullable|integer|min:60|max:400',
            'site_logo'           => 'nullable|image|max:2048',
            'site_favicon'        => 'nullable|image|max:512',
        ]);

        $textKeys = [
            'theme_primary_color', 'theme_accent_color', 'theme_bg_color',
            'theme_text_color', 'theme_border_color', 'theme_body_font',
            'theme_heading_font', 'theme_font_size', 'theme_border_radius',
            'theme_button_style', 'theme_header_style', 'theme_layout_width',
            'theme_preset', 'logo_width',
        ];

        foreach ($textKeys as $key) {
            Setting::set($key, $request->input($key, ''), 'text', 'theme');
        }

        // Logo upload
        if ($request->hasFile('site_logo')) {
            $old = Setting::get('site_logo');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('site_logo')->store('brand', 'public');
            Setting::set('site_logo', $path, 'image', 'theme');
        }

        // Favicon upload
        if ($request->hasFile('site_favicon')) {
            $old = Setting::get('site_favicon');
            if ($old) Storage::disk('public')->delete($old);
            $path = $request->file('site_favicon')->store('brand', 'public');
            Setting::set('site_favicon', $path, 'image', 'theme');
        }

        ThemeService::clearCache();

        return back()->with('success', 'Theme updated successfully!');
    }

    public function applyPreset(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('settings'), 403);

        $request->validate(['preset' => 'required|string']);

        $presets = ThemeService::presets();
        $preset  = $presets[$request->preset] ?? null;

        if (!$preset) {
            return response()->json(['error' => 'Preset not found'], 404);
        }

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

        $type = $request->input('type', 'logo'); // logo or favicon
        $key  = $type === 'favicon' ? 'site_favicon' : 'site_logo';

        $old = Setting::get($key);
        if ($old) Storage::disk('public')->delete($old);
        Setting::set($key, '', 'image', 'theme');

        ThemeService::clearCache();

        return back()->with('success', ucfirst($type) . ' removed.');
    }
}