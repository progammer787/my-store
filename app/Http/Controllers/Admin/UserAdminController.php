<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Role};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Storage};

class UserAdminController extends Controller
{
    public function index()
    {
        $this->authorize_permission('users');
        $users = User::with('roleModel')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize_permission('users');
        $roles = Role::all();
        return view('admin.users.form', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorize_permission('users');

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|string',
            'role_id'  => 'nullable|exists:roles,id',
            'is_active'=> 'boolean',
            'avatar'   => 'nullable|image|max:1024',
        ]);

        $data = $request->only(['name', 'email', 'role', 'role_id', 'is_active']);
        $data['password']  = Hash::make($request->password);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Super admin cannot be created via UI (only seeder)
        if ($data['role'] === 'super_admin') {
            $data['role'] = 'admin';
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $this->authorize_permission('users');

        // Prevent editing super admin unless you ARE super admin
        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403, 'Cannot edit Super Admin.');
        }

        $roles = Role::all();
        return view('admin.users.form', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize_permission('users');

        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|string',
            'role_id'  => 'nullable|exists:roles,id',
            'is_active'=> 'boolean',
            'avatar'   => 'nullable|image|max:1024',
        ]);

        $data = $request->only(['name', 'email', 'role', 'role_id']);
        $data['is_active'] = $request->boolean('is_active', true);

        // Cannot change own role
        if ($user->id === auth()->id()) {
            unset($data['role'], $data['role_id'], $data['is_active']);
        }

        // Prevent promoting to super_admin via UI
        if (isset($data['role']) && $data['role'] === 'super_admin' && !auth()->user()->isSuperAdmin()) {
            $data['role'] = 'admin';
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->authorize_permission('users');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete your own account.');
        }

        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Cannot delete Super Admin.');
        }

        if ($user->avatar) Storage::disk('public')->delete($user->avatar);
        $user->delete();

        return back()->with('success', 'User deleted.');
    }

    public function toggleStatus(User $user)
    {
        $this->authorize_permission('users');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated.');
    }

    private function authorize_permission(string $perm): void
    {
         if (!auth()->user()->load('roleModel')->hasAccess($perm)) abort(403);
    }
}