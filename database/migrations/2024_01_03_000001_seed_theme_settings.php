<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        // Insert default theme settings into settings table
        $themes = [
            // Colors
            ['key' => 'theme_primary_color',   'value' => '#1a1a1a', 'group' => 'theme'],
            ['key' => 'theme_accent_color',    'value' => '#c8a96e', 'group' => 'theme'],
            ['key' => 'theme_bg_color',        'value' => '#faf9f7', 'group' => 'theme'],
            ['key' => 'theme_text_color',      'value' => '#1a1a1a', 'group' => 'theme'],
            ['key' => 'theme_border_color',    'value' => '#e8e4de', 'group' => 'theme'],
            // Typography
            ['key' => 'theme_body_font',       'value' => 'DM Sans',       'group' => 'theme'],
            ['key' => 'theme_heading_font',    'value' => 'Playfair Display', 'group' => 'theme'],
            ['key' => 'theme_font_size',       'value' => '15',            'group' => 'theme'],
            // Layout
            ['key' => 'theme_border_radius',   'value' => '10',            'group' => 'theme'],
            ['key' => 'theme_button_style',    'value' => 'rounded',       'group' => 'theme'], // rounded, pill, square
            ['key' => 'theme_header_style',    'value' => 'light',         'group' => 'theme'], // light, dark, transparent
            ['key' => 'theme_layout_width',    'value' => '1320',          'group' => 'theme'], // max container px
            // Preset
            ['key' => 'theme_preset',          'value' => 'luxury',        'group' => 'theme'], // luxury, minimal, bold, fresh
            // Logo
            ['key' => 'site_logo',             'value' => '',              'group' => 'theme'],
            ['key' => 'site_favicon',          'value' => '',              'group' => 'theme'],
            ['key' => 'logo_width',            'value' => '160',           'group' => 'theme'],
        ];

        foreach ($themes as $t) {
            \App\Models\Setting::updateOrCreate(['key' => $t['key']], $t);
        }
    }

    public function down(): void {}
};