<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class ThemeService
{
    /**
     * Get all theme settings as array.
     */
    public static function get(): array
    {
        return Cache::remember('theme_settings', 3600, function () {
            return Setting::where('group', 'theme')->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get single theme value with fallback.
     */
    public static function value(string $key, string $default = ''): string
    {
        $theme = static::get();
        return $theme[$key] ?? $default;
    }

    /**
     * Generate CSS variables string for injection into <style> tag.
     */
    public static function cssVars(): string
    {
        $t = static::get();

        $radius = $t['theme_border_radius'] ?? '10';
        $btnRadius = match($t['theme_button_style'] ?? 'rounded') {
            'pill'   => '50px',
            'square' => '4px',
            default  => $radius . 'px',
        };

        return "
            --primary:        {$t['theme_primary_color']};
            --accent:         {$t['theme_accent_color']};
            --bg:             {$t['theme_bg_color']};
            --text:           {$t['theme_text_color']};
            --border:         {$t['theme_border_color']};
            --radius:         {$radius}px;
            --btn-radius:     {$btnRadius};
            --font-body:      '{$t['theme_body_font']}', sans-serif;
            --font-display:   '{$t['theme_heading_font']}', serif;
            --font-size:      {$t['theme_font_size']}px;
            --container-w:    {$t['theme_layout_width']}px;
        ";
    }

    /**
     * Get Google Fonts URL for selected fonts.
     */
    public static function googleFontsUrl(): string
    {
        $body    = static::value('theme_body_font', 'DM Sans');
        $heading = static::value('theme_heading_font', 'Playfair Display');

        $fonts = array_unique([$body, $heading]);
        $families = array_map(fn($f) => urlencode($f) . ':wght@300;400;500;600;700', $fonts);

        return 'https://fonts.googleapis.com/css2?' . implode('&', array_map(fn($f) => 'family=' . $f, $families)) . '&display=swap';
    }

    /**
     * Clear theme cache.
     */
    public static function clearCache(): void
    {
        Cache::forget('theme_settings');
    }

    /**
     * Get preset color palettes.
     */
    public static function presets(): array
    {
        return [
            'luxury' => [
                'label'   => 'Luxury Gold',
                'primary' => '#1a1a1a',
                'accent'  => '#c8a96e',
                'bg'      => '#faf9f7',
                'text'    => '#1a1a1a',
                'border'  => '#e8e4de',
            ],
            'minimal' => [
                'label'   => 'Clean Minimal',
                'primary' => '#111111',
                'accent'  => '#000000',
                'bg'      => '#ffffff',
                'text'    => '#111111',
                'border'  => '#eeeeee',
            ],
            'bold' => [
                'label'   => 'Bold Blue',
                'primary' => '#1e3a8a',
                'accent'  => '#3b82f6',
                'bg'      => '#f8faff',
                'text'    => '#1e293b',
                'border'  => '#dbeafe',
            ],
            'fresh' => [
                'label'   => 'Fresh Green',
                'primary' => '#14532d',
                'accent'  => '#22c55e',
                'bg'      => '#f0fdf4',
                'text'    => '#14532d',
                'border'  => '#bbf7d0',
            ],
            'rose' => [
                'label'   => 'Rose Pink',
                'primary' => '#881337',
                'accent'  => '#f43f5e',
                'bg'      => '#fff1f2',
                'text'    => '#1a1a1a',
                'border'  => '#fecdd3',
            ],
            'dark' => [
                'label'   => 'Dark Mode',
                'primary' => '#ffffff',
                'accent'  => '#a78bfa',
                'bg'      => '#0f0f0f',
                'text'    => '#e5e5e5',
                'border'  => '#2a2a2a',
            ],
        ];
    }
}