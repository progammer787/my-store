<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleAdminController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);
        $roles = Role::withCount('users')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);
        $allPermissions = Role::allPermissions();
        return view('admin.roles.form', compact('allPermissions'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        $request->validate([
            'name'        => 'required|string|max:100',
            'slug'        => 'required|string|unique:roles,slug',
            'description' => 'nullable|string|max:300',
            'permissions' => 'required|array',
        ]);

        Role::create([
            'name'        => $request->name,
            'slug'        => $request->slug,
            'description' => $request->description,
            'permissions' => $request->permissions,
            'is_system'   => false,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role created.');
    }

    public function edit(Role $role)
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);
        $allPermissions = Role::allPermissions();
        return view('admin.roles.form', compact('role', 'allPermissions'));
    }

    public function update(Request $request, Role $role)
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:300',
            'permissions' => 'required|array',
        ]);

        $role->update([
            'name'        => $request->name,
            'description' => $request->description,
            'permissions' => $request->permissions,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated.');
    }

    public function destroy(Role $role)
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);

        if ($role->is_system) {
            return back()->with('error', 'System roles cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role with assigned users. Reassign users first.');
        }

        $role->delete();
        return back()->with('success', 'Role deleted.');
    }
}