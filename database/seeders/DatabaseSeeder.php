<?php

namespace Database\Seeders;

use App\Models\{User, Role, Setting, Category, Product};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        // Users
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

        // Settings
        foreach ([
            ['key'=>'site_name','value'=>'My Store','group'=>'general'],
            ['key'=>'site_tagline','value'=>'Shop the best','group'=>'general'],
            ['key'=>'whatsapp_number','value'=>'923001234567','group'=>'general'],
            ['key'=>'site_email','value'=>'info@store.com','group'=>'general'],
            ['key'=>'site_phone','value'=>'+92 300 1234567','group'=>'general'],
            ['key'=>'currency_symbol','value'=>'Rs.','group'=>'general'],
            ['key'=>'footer_text','value'=>'© 2025 My Store. All rights reserved.','group'=>'general'],
        ] as $s) { Setting::updateOrCreate(['key'=>$s['key']], $s); }

        // Categories
        foreach ([
            ['name'=>'Electronics','show_on_homepage'=>true,'sort_order'=>1],
            ['name'=>'Fashion','show_on_homepage'=>true,'sort_order'=>2],
            ['name'=>'Furniture','show_on_homepage'=>true,'sort_order'=>3],
            ['name'=>'Accessories','show_on_homepage'=>true,'sort_order'=>4],
        ] as $cat) { Category::updateOrCreate(['name'=>$cat['name']], array_merge($cat,['is_active'=>true])); }

        // Products
        $elecId = Category::where('name','Electronics')->first()?->id;
        $fashId = Category::where('name','Fashion')->first()?->id;
        for ($i=1;$i<=12;$i++) {
            Product::updateOrCreate(['name'=>"Sample Product {$i}"], [
                'short_description'=>'Quality product.','price'=>rand(500,5000),
                'sale_price'=>$i%3===0?rand(300,499):null,'stock'=>rand(5,50),
                'category_id'=>$i%2===0?$fashId:$elecId,'is_active'=>true,
                'is_featured'=>$i<=4,'is_new_arrival'=>$i>=9,'is_best_seller'=>in_array($i,[2,5,8]),'sort_order'=>$i,
            ]);
        }
    }
}