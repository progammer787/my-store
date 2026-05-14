<?php

namespace Database\Seeders;

use App\Models\{User, Setting, Category, Product, Banner};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@store.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'is_active'=> true,
            ]
        );

        // Default settings
        $settings = [
            ['key' => 'site_name',        'value' => 'My Store',         'group' => 'general'],
            ['key' => 'site_tagline',      'value' => 'Shop the best',    'group' => 'general'],
            ['key' => 'whatsapp_number',   'value' => '923001234567',     'group' => 'general'],
            ['key' => 'site_email',        'value' => 'info@store.com',   'group' => 'general'],
            ['key' => 'site_phone',        'value' => '+92 300 1234567',  'group' => 'general'],
            ['key' => 'currency_symbol',   'value' => 'Rs.',              'group' => 'general'],
            ['key' => 'footer_text',       'value' => '© 2025 My Store. All rights reserved.', 'group' => 'general'],
            ['key' => 'meta_description',  'value' => 'Shop the best products online.', 'group' => 'seo'],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }

        // Sample categories
        $cats = [
            ['name' => 'Electronics',  'show_on_homepage' => true,  'sort_order' => 1],
            ['name' => 'Fashion',      'show_on_homepage' => true,  'sort_order' => 2],
            ['name' => 'Furniture',    'show_on_homepage' => true,  'sort_order' => 3],
            ['name' => 'Accessories',  'show_on_homepage' => true,  'sort_order' => 4],
            ['name' => 'Home & Living','show_on_homepage' => false, 'sort_order' => 5],
        ];

        foreach ($cats as $cat) {
            Category::updateOrCreate(['name' => $cat['name']], array_merge($cat, ['is_active' => true]));
        }

        // Sample products
        $electronicsId = Category::where('name', 'Electronics')->first()?->id;
        $fashionId     = Category::where('name', 'Fashion')->first()?->id;

        for ($i = 1; $i <= 12; $i++) {
            Product::updateOrCreate(
                ['name' => "Sample Product {$i}"],
                [
                    'short_description' => 'High quality product for everyday use.',
                    'price'             => rand(500, 5000),
                    'sale_price'        => $i % 3 === 0 ? rand(300, 499) : null,
                    'stock'             => rand(5, 50),
                    'category_id'       => $i % 2 === 0 ? $fashionId : $electronicsId,
                    'is_active'         => true,
                    'is_featured'       => $i <= 4,
                    'is_new_arrival'    => $i >= 9,
                    'is_best_seller'    => in_array($i, [2, 5, 8]),
                    'sort_order'        => $i,
                ]
            );
        }
    }
}
