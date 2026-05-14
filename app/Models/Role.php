<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'permissions', 'is_system'];

    protected $casts = [
        'permissions' => 'array',
        'is_system'   => 'boolean',
    ];

    // All available permissions in the system
    public static function allPermissions(): array
    {
        return [
            'dashboard'  => 'View Dashboard',
            'products'   => 'Manage Products',
            'categories' => 'Manage Categories',
            'banners'    => 'Manage Banners',
            'orders'     => 'Manage Orders',
            'settings'   => 'Manage Settings',
            'users'      => 'Manage Users',
        ];
    }

    public function hasPermission(string $permission): bool
    {
        if (in_array('*', $this->permissions ?? [])) return true;
        return in_array($permission, $this->permissions ?? []);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}