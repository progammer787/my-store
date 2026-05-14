<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'role_id', 'is_active', 'avatar', 'last_login_at'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active'     => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function roleModel()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function hasAccess(string $permission): bool
    {
        if ($this->isSuperAdmin()) return true;
        if ($this->roleModel) return $this->roleModel->hasPermission($permission);
        if ($this->role === 'admin') return $permission !== 'users';
        return false;
    }

    public function hasAnyAccess(array $permissions): bool
    {
        foreach ($permissions as $p) {
            if ($this->hasAccess($p)) return true;
        }
        return false;
    }

    public function getRoleNameAttribute(): string
    {
        return $this->roleModel?->name ?? match($this->role) {
            'super_admin' => 'Super Admin',
            'admin'       => 'Admin',
            'staff'       => 'Staff',
            default       => ucfirst($this->role),
        };
    }

    public function getRoleBadgeColorAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'gold',
            'admin'       => 'info',
            default       => 'secondary',
        };
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=c8a96e&color=fff&size=64';
    }
}