<?php

namespace Database\Seeders;

use App\Models\{User, Role, Setting, Category, Product};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $superAdminRole = Role::updateOrCreate(['slug' => 'super_admin'], [
            'name' => 'Super Admin', 'description' => 'Full access', 'permissions' => ['*'], 'is_system' => true,
        ]);
        $managerRole = Role::updateOrCreate(['slug' => 'manager'], [
            'name' => 'Manager', 'description' => 'Products, categories, banners, orders',
            'permissions' => ['dashboard','products','categories','banners','orders'], 'is_system' => true,
        ]);
        $staffRole = Role::updateOrCreate(['slug' => 'staff'], [
            'name' => 'Staff', 'description' => 'Orders only',
            'permissions' => ['dashboard','orders'], 'is_system' => true,
        ]);
        Role::updateOrCreate(['slug' => 'editor'], [
            'name' => 'Editor', 'description' => 'Products and categories',
            'permissions' => ['dashboard','products','categories'], 'is_system' => false,
        ]);

        // Production gets exactly one admin from private host environment variables.
        // Never create the known development accounts on a public deployment.
        if (app()->environment('production')) {
            $adminEmail = trim((string) env('INITIAL_ADMIN_EMAIL'));
            $adminPassword = (string) env('INITIAL_ADMIN_PASSWORD');
            if ($adminEmail === '' || !filter_var($adminEmail, FILTER_VALIDATE_EMAIL)
                || strlen($adminPassword) < 16) {
                throw new \RuntimeException('Set INITIAL_ADMIN_EMAIL and a 16+ character INITIAL_ADMIN_PASSWORD before deploying.');
            }

            User::firstOrCreate(['email' => $adminEmail], [
                'name' => trim((string) env('INITIAL_ADMIN_NAME', 'Store Admin')) ?: 'Store Admin',
                'password' => Hash::make($adminPassword),
                'role' => 'super_admin', 'role_id' => $superAdminRole->id, 'is_active' => true,
            ]);
        } else {
            User::updateOrCreate(['email' => 'admin@store.com'], [
                'name' => 'Super Admin', 'password' => Hash::make('admin123'),
                'role' => 'super_admin', 'role_id' => $superAdminRole->id, 'is_active' => true,
            ]);
            User::updateOrCreate(['email' => 'manager@store.com'], [
                'name' => 'Store Manager', 'password' => Hash::make('manager123'),
                'role' => 'staff', 'role_id' => $managerRole->id, 'is_active' => true,
            ]);
            User::updateOrCreate(['email' => 'staff@store.com'], [
                'name' => 'Order Staff', 'password' => Hash::make('staff123'),
                'role' => 'staff', 'role_id' => $staffRole->id, 'is_active' => true,
            ]);
        }

        // The container can restart at any time. Seed the demo only once so an
        // admin's later catalogue edits aren't overwritten during a restart.
        if (app()->environment('production') && Setting::where('key', 'production_demo_seeded')->exists()) {
            return;
        }

        // Settings
        foreach ([
            ['key'=>'site_name','value'=>'Rang & Noor — Boutique Demo','group'=>'general'],
            ['key'=>'site_tagline','value'=>'Everyday elegance, made for you','group'=>'general'],
            ['key'=>'whatsapp_number','value'=>'','group'=>'general'],
            ['key'=>'site_email','value'=>'','group'=>'general'],
            ['key'=>'site_phone','value'=>'','group'=>'general'],
            ['key'=>'currency_symbol','value'=>'Rs.','group'=>'general'],
            ['key'=>'footer_text','value'=>'Boutique storefront demo — replace sample products and details before launch.','group'=>'general'],
        ] as $s) { Setting::updateOrCreate(['key'=>$s['key']], $s); }

        $categoryDefinitions = [
            ['legacy' => 'home-kitchen', 'slug' => 'new-arrivals', 'name' => 'New Arrivals', 'description' => 'Fresh looks, just added to the boutique.', 'image' => null],
            ['legacy' => 'laundry-care', 'slug' => 'everyday-pret', 'name' => 'Everyday Pret', 'description' => 'Easy pieces for your everyday wardrobe.', 'image' => null],
            ['legacy' => 'motorcycle-accessories', 'slug' => 'occasion-wear', 'name' => 'Occasion Wear', 'description' => 'Elegant looks for celebrations and special days.', 'image' => null],
        ];
        $boutiqueCategories = [];
        foreach ($categoryDefinitions as $index => $definition) {
            $category = Category::where('slug', $definition['slug'])->first()
                ?? Category::where('slug', $definition['legacy'])->first()
                ?? new Category();
            $category->fill([
                'name' => $definition['name'], 'slug' => $definition['slug'],
                'description' => $definition['description'], 'image' => $definition['image'],
                'show_on_homepage' => true, 'is_active' => true, 'sort_order' => $index + 1,
            ])->save();
            $boutiqueCategories[] = $category;
        }

        // Hide the earlier general-store sample inventory; it is not boutique stock.
        Product::where('sku', 'like', 'DEMO-%')->update(['is_active' => false]);

        $boutiqueProducts = [
            ['name' => 'Sage Garden Embroidered Kurta', 'category' => 0, 'price' => 3890, 'description' => 'A soft sage kurta with delicate floral embroidery, designed for graceful everyday wear.', 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['Sage Green', 'Ivory'], 'fabric' => 'Lawn'],
            ['name' => 'Rosewood Chiffon Dupatta Set', 'category' => 2, 'price' => 7490, 'description' => 'A three-piece formal look with a flowing chiffon dupatta and subtle tonal detailing.', 'sizes' => ['S', 'M', 'L'], 'colors' => ['Rosewood'], 'fabric' => 'Chiffon'],
            ['name' => 'Ivory Dobby Daily Kurta', 'category' => 1, 'price' => 3290, 'description' => 'A versatile ivory dobby kurta with a clean silhouette for work and relaxed weekends.', 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['Ivory', 'Sand'], 'fabric' => 'Dobby'],
            ['name' => 'Midnight Blue Zari Suit', 'category' => 2, 'price' => 8990, 'description' => 'A deep-blue occasion suit finished with fine zari accents and a matching dupatta.', 'sizes' => ['S', 'M', 'L'], 'colors' => ['Midnight Blue'], 'fabric' => 'Organza blend'],
            ['name' => 'Terracotta Printed Co-ord', 'category' => 0, 'price' => 4590, 'description' => 'A relaxed two-piece co-ord with an all-over botanical print in warm terracotta tones.', 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['Terracotta'], 'fabric' => 'Cotton lawn'],
            ['name' => 'Pearl White Straight Shirt', 'category' => 1, 'price' => 2790, 'description' => 'An easy straight-cut shirt with a refined neckline and lightweight feel.', 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['Pearl White', 'Powder Blue'], 'fabric' => 'Cotton'],
            ['name' => 'Marigold Festive Pishwas', 'category' => 2, 'price' => 10990, 'description' => 'A statement festive pishwas with gathered volume and delicate hand-finished details.', 'sizes' => ['S', 'M', 'L'], 'colors' => ['Marigold'], 'fabric' => 'Raw silk blend'],
            ['name' => 'Sky Blue Printed Lawn Suit', 'category' => 1, 'price' => 4290, 'description' => 'A fresh printed lawn suit with a light dupatta for bright, comfortable daywear.', 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['Sky Blue'], 'fabric' => 'Lawn'],
            ['name' => 'Plum Velvet Evening Set', 'category' => 2, 'price' => 11990, 'description' => 'A rich plum evening set with a soft velvet finish and understated embellishment.', 'sizes' => ['S', 'M', 'L'], 'colors' => ['Plum'], 'fabric' => 'Velvet'],
            ['name' => 'Olive Everyday Tunic', 'category' => 1, 'price' => 2990, 'description' => 'A comfortable olive tunic with side slits and an easy fit for daily styling.', 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['Olive', 'Black'], 'fabric' => 'Cotton'],
            ['name' => 'Blush Mirror-Work Kurta Set', 'category' => 0, 'price' => 6890, 'description' => 'A blush kurta set with small mirror-work accents for dinners and family gatherings.', 'sizes' => ['S', 'M', 'L'], 'colors' => ['Blush Pink'], 'fabric' => 'Lawn blend'],
            ['name' => 'Classic Black Embroidered Shirt', 'category' => 0, 'price' => 5190, 'description' => 'A polished black shirt with restrained embroidery that transitions from day to evening.', 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['Black'], 'fabric' => 'Cotton'],
        ];

        foreach ($boutiqueProducts as $index => $sample) {
            $sku = 'BOUTIQUE-' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT);
            Product::updateOrCreate(['sku' => $sku], [
                'name' => $sample['name'], 'slug' => Str::slug($sample['name']),
                'short_description' => $sample['description'],
                'description' => "Fabric: {$sample['fabric']}\nAvailable sizes: " . implode(', ', $sample['sizes']) . "\nAvailable colours: " . implode(', ', $sample['colors']) . "\n\nDEMO ITEM: Replace this sample listing with the boutique's confirmed product, pricing, availability and original photographs before launch.",
                'price' => $sample['price'], 'sale_price' => null, 'stock' => 8,
                'category_id' => $boutiqueCategories[$sample['category']]->id,
                'thumbnail' => 'images/boutique-products/style-' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) . '.svg',
                'attributes' => ['sizes' => $sample['sizes'], 'colors' => $sample['colors'], 'fabric' => $sample['fabric'], 'demo_only' => true],
                'is_active' => true, 'is_featured' => $index < 4,
                'is_new_arrival' => $index < 5, 'is_best_seller' => in_array($index, [1, 6, 8], true),
                'sort_order' => $index + 1,
            ]);
        }

        Category::whereIn('name', ['Electronics', 'Fashion', 'Furniture', 'Accessories'])
            ->whereDoesntHave('products')
            ->delete();

        if (app()->environment('production')) {
            Setting::updateOrCreate(['key' => 'production_demo_seeded'], [
                'value' => '1', 'type' => 'boolean', 'group' => 'deployment',
            ]);
        }
    }
}
