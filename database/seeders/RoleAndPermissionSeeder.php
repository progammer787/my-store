<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached roles and permissions to prevent conflicts
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Base Permissions
        $permissions = [
            'view dashboard',
            'manage users',
            'create products',
            'edit products',
            'delete products',
            'manage orders',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Create Roles & Assign Permissions
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $managerRole->syncPermissions(['view dashboard', 'create products', 'edit products', 'manage orders']);

        $supportRole = Role::firstOrCreate(['name' => 'support']);
        $supportRole->syncPermissions(['view dashboard', 'manage orders']);

        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);

        // 3. Create the Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@mystore.com'], 
            [
                'name' => 'Super Admin',
                'password' => Hash::make('securepassword123'),
            ]
        );
        
        // Ensure the super admin role is assigned
        if (!$superAdmin->hasRole('super-admin')) {
            $superAdmin->assignRole($superAdminRole);
        }
    }
}