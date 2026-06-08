<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class ThemeService
{
    public static function get(): array
    {
        return Cache::remember('theme_settings', 3600, function () {
            return Setting::where('group', 'theme')->pluck('value', 'key')->toArray();
        });
    }

    public static function value(string $key, string $default = ''): string
    {
        return static::get()[$key] ?? $default;
    }

    public static function cssVars(): string
    {
        $t = static::get();

        $primary  = $t['theme_primary_color']  ?? '#1a1a1a';
        $accent   = $t['theme_accent_color']   ?? '#c8a96e';
        $bg       = $t['theme_bg_color']       ?? '#faf9f7';
        $text     = $t['theme_text_color']     ?? '#1a1a1a';
        $border   = $t['theme_border_color']   ?? '#e8e4de';
        $bodyFont = $t['theme_body_font']      ?? 'DM Sans';
        $headFont = $t['theme_heading_font']   ?? 'Playfair Display';
        $fontSize = $t['theme_font_size']      ?? '15';
        $radius   = $t['theme_border_radius']  ?? '10';
        $btnStyle = $t['theme_button_style']   ?? 'rounded';
        $width    = $t['theme_layout_width']   ?? '1320';

        $btnRadius = match($btnStyle) {
            'pill'   => '50px',
            'square' => '4px',
            default  => $radius . 'px',
        };

        return "
            --primary:      {$primary};
            --accent:       {$accent};
            --bg:           {$bg};
            --text:         {$text};
            --border:       {$border};
            --radius:       {$radius}px;
            --btn-radius:   {$btnRadius};
            --font-body:    '{$bodyFont}', sans-serif;
            --font-display: '{$headFont}', serif;
            --font-size:    {$fontSize}px;
            --container-w:  {$width}px;
        ";
    }

    public static function googleFontsUrl(): string
    {
        $body    = static::value('theme_body_font', 'DM Sans');
        $heading = static::value('theme_heading_font', 'Playfair Display');
        $fonts   = array_unique([$body, $heading]);
        $families = array_map(
            fn($f) => 'family=' . urlencode($f) . ':wght@300;400;500;600;700',
            $fonts
        );
        return 'https://fonts.googleapis.com/css2?' . implode('&', $families) . '&display=swap';
    }

    public static function clearCache(): void
    {
        Cache::forget('theme_settings');
        Cache::flush();
    }

    public static function presets(): array
    {
        return [
            'luxury'  => ['label' => 'Luxury Gold',   'primary' => '#1a1a1a', 'accent' => '#c8a96e', 'bg' => '#faf9f7', 'text' => '#1a1a1a', 'border' => '#e8e4de'],
            'minimal' => ['label' => 'Clean Minimal',  'primary' => '#111111', 'accent' => '#000000', 'bg' => '#ffffff', 'text' => '#111111', 'border' => '#eeeeee'],
            'bold'    => ['label' => 'Bold Blue',       'primary' => '#1e3a8a', 'accent' => '#3b82f6', 'bg' => '#f8faff', 'text' => '#1e293b', 'border' => '#dbeafe'],
            'fresh'   => ['label' => 'Fresh Green',     'primary' => '#14532d', 'accent' => '#22c55e', 'bg' => '#f0fdf4', 'text' => '#14532d', 'border' => '#bbf7d0'],
            'rose'    => ['label' => 'Rose Pink',       'primary' => '#881337', 'accent' => '#f43f5e', 'bg' => '#fff1f2', 'text' => '#1a1a1a', 'border' => '#fecdd3'],
            'dark'    => ['label' => 'Dark Mode',       'primary' => '#ffffff', 'accent' => '#a78bfa', 'bg' => '#0f0f0f', 'text' => '#e5e5e5', 'border' => '#2a2a2a'],
            'ocean' => ['label'   => 'Ocean Teal',      'primary' => '#1F3A5A',  'accent'  => '#359B99','bg'=> '#FFFFFF','text'  => '#1F3A5A','border'  => '#D0EAEA',],
        ];
    }
}